<?php
include_once(dirname(__FILE__)."/../site_config.php");
include_once(dirname(__FILE__)."/../utils.php");
require('fpdf.php');


if(function_exists('mcrypt_encrypt'))
{
	function RC4($key, $data)
	{
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM, '');
	}
}
else
{
	function RC4($key, $data)
	{
		static $last_key, $last_state;

		if($key != $last_key)
		{
			$k = str_repeat($key, 256/strlen($key)+1);
			$state = range(0, 255);
			$j = 0;
			for ($i=0; $i<256; $i++){
				$t = $state[$i];
				$j = ($j + $t + ord($k[$i])) % 256;
				$state[$i] = $state[$j];
				$state[$j] = $t;
			}
			$last_key = $key;
			$last_state = $state;
		}
		else
			$state = $last_state;

		$len = strlen($data);
		$a = 0;
		$b = 0;
		$out = '';
		for ($i=0; $i<$len; $i++){
			$a = ($a+1) % 256;
			$t = $state[$a];
			$b = ($b+$t) % 256;
			$state[$a] = $state[$b];
			$state[$b] = $t;
			$k = $state[($state[$a]+$state[$b]) % 256];
			$out .= chr(ord($data[$i]) ^ $k);
		}
		return $out;
	}
}

//Stream handler to read from global variables
class VariableStream
{
	var $varname;
	var $position;

	function stream_open($path, $mode, $options, &$opened_path)
	{
		$url = parse_url($path);
		$this->varname = $url['host'];
		if(!isset($GLOBALS[$this->varname]))
		{
			trigger_error('Global variable '.$this->varname.' does not exist', E_USER_WARNING);
			return false;
		}
		$this->position = 0;
		return true;
	}

	function stream_read($count)
	{
		$ret = substr($GLOBALS[$this->varname], $this->position, $count);
		$this->position += strlen($ret);
		return $ret;
	}

	function stream_eof()
	{
		return $this->position >= strlen($GLOBALS[$this->varname]);
	}

	function stream_tell()
	{
		return $this->position;
	}

	function stream_seek($offset, $whence)
	{
		if($whence==SEEK_SET)
		{
			$this->position = $offset;
			return true;
		}
		return false;
	}
	
	function stream_stat()
	{
		return array();
	}
}

class PDF_MemImage extends FPDF
{
	
	var $encrypted = false;  //whether document is protected
	var $Uvalue;             //U entry in pdf document
	var $Ovalue;             //O entry in pdf document
	var $Pvalue;             //P entry in pdf document
	var $enc_obj_id;         //encryption object id
	
	var $candidate_name;
	var $candidate_email;
	var $assessment_date;
	var $time_taken;
	var $bShowWaterMark;
	var $org_logo;
	var $widths;
	var $aligns;
	
	/**
	 * Function to set permissions as well as user and owner passwords
	 *
	 * - permissions is an array with values taken from the following list:
	 *   copy, print, modify, annot-forms
	 *   If a value is present it means that the permission is granted
	 * - If a user password is set, user will be prompted before document is opened
	 * - If an owner password is set, document can be opened in privilege mode with no
	 *   restriction if that password is entered
	 */
	function SetProtection($permissions=array(), $user_pass='', $owner_pass=null)
	{
		$options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32 );
		$protection = 192;
		foreach($permissions as $permission)
		{
			if (!isset($options[$permission]))
				$this->Error('Incorrect permission: '.$permission);
			$protection += $options[$permission];
		}
		if ($owner_pass === null)
			$owner_pass = uniqid(rand());
		$this->encrypted = true;
		$this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08".
				"\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
		$this->_generateencryptionkey($user_pass, $owner_pass, $protection);
	}
	
	/****************************************************************************
	 *                                                                           *
	*                              Private methods                              *
	*                                                                           *
	****************************************************************************/
	
	function _putstream($s)
	{
		if ($this->encrypted) {
			$s = RC4($this->_objectkey($this->n), $s);
		}
		parent::_putstream($s);
	}
	
	function _textstring($s)
	{
		if ($this->encrypted) {
			$s = RC4($this->_objectkey($this->n), $s);
		}
		return parent::_textstring($s);
	}
	
	/**
	 * Compute key depending on object number where the encrypted data is stored
	 */
	function _objectkey($n)
	{
		return substr($this->_md5_16($this->encryption_key.pack('VXxx',$n)),0,10);
	}
	
	function _putresources()
	{
		parent::_putresources();
		if ($this->encrypted) {
			$this->_newobj();
			$this->enc_obj_id = $this->n;
			$this->_out('<<');
			$this->_putencryption();
			$this->_out('>>');
			$this->_out('endobj');
		}
	}
	
	function _putencryption()
	{
		$this->_out('/Filter /Standard');
		$this->_out('/V 1');
		$this->_out('/R 2');
		$this->_out('/O ('.$this->_escape($this->Ovalue).')');
		$this->_out('/U ('.$this->_escape($this->Uvalue).')');
		$this->_out('/P '.$this->Pvalue);
	}
	
	function _puttrailer()
	{
		parent::_puttrailer();
		if ($this->encrypted) {
			$this->_out('/Encrypt '.$this->enc_obj_id.' 0 R');
			$this->_out('/ID [()()]');
		}
	}
	
	/**
	 * Get MD5 as binary string
	 */
	function _md5_16($string)
	{
		return pack('H*',md5($string));
	}
	
	/**
	 * Compute O value
	 */
	function _Ovalue($user_pass, $owner_pass)
	{
		$tmp = $this->_md5_16($owner_pass);
		$owner_RC4_key = substr($tmp,0,5);
		return RC4($owner_RC4_key, $user_pass);
	}
	
	/**
	 * Compute U value
	 */
	function _Uvalue()
	{
		return RC4($this->encryption_key, $this->padding);
	}
	
	/**
	 * Compute encryption key
	 */
	function _generateencryptionkey($user_pass, $owner_pass, $protection)
	{
		// Pad passwords
		$user_pass = substr($user_pass.$this->padding,0,32);
		$owner_pass = substr($owner_pass.$this->padding,0,32);
		// Compute O value
		$this->Ovalue = $this->_Ovalue($user_pass,$owner_pass);
		// Compute encyption key
		$tmp = $this->_md5_16($user_pass.$this->Ovalue.chr($protection)."\xFF\xFF\xFF");
		$this->encryption_key = substr($tmp,0,5);
		// Compute U value
		$this->Uvalue = $this->_Uvalue();
		// Compute P value
		$this->Pvalue = -(($protection^255)+1);
	}
	
	function Header()
	{
		$this->SetFont('Times','',8);
		$this->Cell(30,5,'Powered By:',0,0,'C');
		
		$this->SetLeftMargin(125);
		$this->Cell(250,5,'Candidate Name: '.$this->candidate_name);
		$this->SetLeftMargin(16);
		$this->Ln(5);
		
		if(CUtils::getMimeType(base64_decode($this->org_logo)) != "application/octet-stream")
		{
			$this->GDImage(base64_decode($this->org_logo),16,15,25, 8);
		}
		else
		{
			$this->SetFont('Times','B',14);
			$this->Cell(30,5,$this->org_logo);
			$this->SetFont('Times','',8);
		}
		$this->SetLeftMargin(125);
		$this->Cell(250,5,'Candidate Email: '.$this->candidate_email);
		$this->Ln(5);
		$this->Cell(250,5,'Assessment Date: '.$this->assessment_date);
		$this->Ln(5);
		$this->Cell(250,5,'Time Taken: '.$this->time_taken);
		$this->SetLeftMargin(10);
		$this->Ln(5);
		$this->Line(10,30,190,30);
		$this->Ln(5);
		
		if($this->bShowWaterMark)
		{
			$this->SetFont('Arial', 'B', 50);
			$this->SetTextColor(232, 232, 232);
			$this->RotatedText(30, 190, 'E Z e e A s s e s s . c o m', 45);
		}
	}
	
	function Footer()
	{
		$this->SetFont('Times','',8);
		$this->SetY(-20);
		$this->Line(10,280,190,280);
		$this->Cell(30,10,'Assessment Partner:',0,0,'C');
		$this->Cell(250,10,CSiteConfig::ROOT_URL,0,0,'C', false, CSiteConfig::ROOT_URL);
		$this->Image(dirname(__FILE__)."/../../images/ezeeassess_logo.png", 13, 285, 25, 8);
		$this->Ln(5);
		$this->Cell(180,10,'(Page '.$this->PageNo().')',0,0,'C');
	}
	
	function RotatedText($x, $y, $txt, $angle)
	{
		//Text rotated around its origin
		$this->Rotate($angle, $x, $y);
		$this->Text($x, $y, $txt);
		$this->Rotate(0);
	}
	
	function Rotate($angle,$x=-1,$y=-1)
	{
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0)
		{
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}
	
	function _endpage()
	{
		if($this->angle!=0)
		{
			$this->angle=0;
			$this->_out('Q');
		}
		parent::_endpage();
	}
	
	function PDF_MemImage($orientation='P', $unit='mm', $format='A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken, $bShowWaterMark = true)
	{
		$this->candidate_name 	= $candidate_name;
		$this->candidate_email 	= $candidate_email;
		$this->assessment_date 	= $assessment_date;
		$this->time_taken		= $time_taken;
		$this->bShowWaterMark	= $bShowWaterMark;
		$this->org_logo			= $org_logo;
		
		$this->FPDF($orientation, $unit, $format);
		//Register var stream protocol
		stream_wrapper_register('var', 'VariableStream');
	}

	function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
	{
		//Display the image contained in $data
		$v = 'img'.md5($data);
		$GLOBALS[$v] = $data;
		$a = getimagesize('var://'.$v);
		if(!$a)
			$this->Error('Invalid image data');
		$type = substr(strstr($a['mime'],'/'),1);
		$this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
		unset($GLOBALS[$v]);
	}

	function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
	{
		//Display the GD image associated to $im
		ob_start();
		imagepng($im);
		echo $im;
		$data = ob_get_clean();
		$this->MemImage($data, $x, $y, $w, $h, $link);
	}
	
	function MultiCellBltArray($w, $h, $blt_array, $border=0, $align='J', $fill=false)
	{
		if (!is_array($blt_array))
		{
			die('MultiCellBltArray requires an array with the following keys: bullet,margin,text,indent,spacer');
			exit;
		}
	
		//Save x
		$bak_x = $this->x;
	
		for ($i=0; $i<sizeof($blt_array['text']); $i++)
		{
			//Get bullet width including margin
			$blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin'])+$this->cMargin*2;
						
			// SetX
			$this->SetX($bak_x);
						
			//Output indent
			if ($blt_array['indent'] > 0)
				$this->Cell($blt_array['indent']);
				
			//Output bullet
			$this->SetFont('Times','B',12);
			$this->Cell($blt_width,$h,$blt_array['bullet'] . $blt_array['margin'],0,'',$fill);
			$this->SetFont('Times','',12);
				
			//Output text
			$this->MultiCell($w-$blt_width,$h,$blt_array['text'][$i],$border,$align,$fill);
				
			//Insert a spacer between items if not the last item
			if ($i != sizeof($blt_array['text'])-1)
				$this->Ln($blt_array['spacer']);
					
			//Increment bullet if it's a number
			if (is_numeric($blt_array['bullet']))
					$blt_array['bullet']++;
		}
	
				//Restore x
				$this->x = $bak_x;
	}
	
	function SetCol($col)
	{
		// Set position at a given column
		$this->col = $col;
		$x = 10+$col*100;
		if($this->indent == 1)
		{
			$x = $x - 5;
		}
		$this->SetLeftMargin($x);
		$this->SetX($x);
	}
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data, $colHeight, $fill = false, $drawMultiCellBorder = 0)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h=$colHeight*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x, $y, $w, $h);
			//Print the text
			$this->MultiCell($w, $colHeight, $data[$i], $drawMultiCellBorder, $a, $fill);
			//Put the position to the right of the cell
			$this->SetXY($x+$w, $y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function CheckPageBreak($h, $ln_cond = false)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
		{
			$this->AddPage($this->CurOrientation);
			if($ln_cond)
			{
				$this->Ln(15);
			}
		}
	}
	
	function NbLines($w, $txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r", '', $txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}

?>