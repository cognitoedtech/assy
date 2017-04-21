<?php 
include_once(dirname(__FILE__)."/site_config.php");
include_once(dirname(__FILE__)."/../3rd_party/fpdf/mem_image.php");

class COTFATTPIN extends PDF_MemImage
{
	private $nameOfTest;
	private $tschd_id;
	
	function COTFATTPIN($orientation='P', $unit='mm', $format='A4', $test_name = "EZeeAssess", $tschd_id = 500)
	{
		$this->nameOfTest = $test_name;
		$this->tschd_id = $tschd_id;
		$this->FPDF($orientation, $unit, $format);
		//Register var stream protocol
		stream_wrapper_register('var', 'VariableStream');
	}
	
	function Header()
	{
		$this->SetFont('Times','B',14);
		$this->SetTextColor(0, 0, 0);
		$this->Cell(190, 8,"Test:- ".$this->nameOfTest, 0, 0, 'R');
		$this->Ln();
		$this->SetFont('Times','UB',10);
		$this->SetTextColor(0, 0, 255);
		$this->Cell(190, 8,"Registration Link:- ".CSiteConfig::ROOT_URL."/reg-tpin/".$this->tschd_id, 0, 0, 'R');
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(115, 115, 115);
		$this->SetLineWidth(0.3);
		$this->Line(10,30,200,30);
		$this->SetLineWidth(0.2);
		$this->SetDrawColor(0,0,0);
		$this->SetTextColor(0,0,0);
		$this->SetLeftMargin(10);
		$this->Ln(25);
	}
	
	function Footer()
	{
		
	}
	
	function GenerateTPINByRequiredCount($TPINAry)
	{
		$this->AddPage();
		$y = 44;
		$this->SetTextColor(0, 0, 0);
		for($i = 0; $i < count($TPINAry); $i++)
		{
			$this->SetFont('Times','B',11);
			if($i%3 == 0)
			{
				$this->Cell(75,5,($i+1).").  ".$TPINAry[$i], 0, 0);
				$this->Rect(47, $y, 3, 3);
			}
			else if($i%3 == 1)
			{
				$this->Cell(75,5,($i+1).").  ".$TPINAry[$i], 0, 0);
				$this->Rect(122, $y, 3, 3);
			}
			else if($i%3 == 2)
			{
				$this->Cell(64,5,($i+1).").  ".$TPINAry[$i], 0, 0);
				$this->Rect(197, $y, 3, 3);
				$this->Ln(7);
				$y += 7;
			}
			if(($i+1)%99 == 0)
			{
				$y = 44;
			}
		}
	}
	
	function GenerateTPINWithCandInfo($TPINAry, $fname_ary, $lname_ary, $fathers_name_ary, $dob_ary)
	{
		$TPINUserInfoAry = array();
		$this->AddPage();
		$this->SetDrawColor(0,0,0);
		$this->SetFillColor(50,50,50);
		$this->SetTextColor(255,255,255);
		$this->SetWidths(array(20, 30, 50, 50, 30));
		$this->SetAligns(array('L', 'L', 'L', 'L', 'R'));
		$this->SetFont('Arial','B',9);
		$this->Row(array("Serial No.", "TPIN", "Name", "Father's Name", "Date of Birth"), 8, true);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','B',9);
		
		for($fname_index = 0; $fname_index < count($lname_ary); $fname_index++)
		{	
			$TPINUserInfoAry[$TPINAry[$fname_index]]["fname"] 	= $fname_ary[$fname_index];
			$TPINUserInfoAry[$TPINAry[$fname_index]]["lname"]	= $lname_ary[$fname_index];
			$TPINUserInfoAry[$TPINAry[$fname_index]]["father"]  = $fathers_name_ary[$fname_index];
			$TPINUserInfoAry[$TPINAry[$fname_index]]["dob"]		= $dob_ary[$fname_index];
			
			$header_data = array("Serial No.", "TPIN", "Name", "Father's Name", "Date of Birth");
			$nb=0;
			for($i=0;$i<count($header_data);$i++)
				$nb=max($nb, $this->NbLines($this->widths[$i], $header_data[$i]));
			$h=8*$nb;
			
			if($this->GetY()+$h>$this->PageBreakTrigger)
			{
				$this->SetDrawColor(0,0,0);
				$this->SetFillColor(50,50,50);
				$this->SetTextColor(255,255,255);
				$this->SetFont('Arial','B',9);
				$this->Row($header_data, 8, true);
				$this->SetTextColor(0,0,0);
				$this->SetFont('Times','B',9);
			}
			$this->Row(array($fname_index+1, $TPINAry[$fname_index], $fname_ary[$fname_index]." ".$lname_ary[$fname_index], $fathers_name_ary[$fname_index], date("d M Y",strtotime($dob_ary[$fname_index]))), 5);
		}
		return $TPINUserInfoAry;		
	}
	
	function OutputPDF()
	{
		$this->Output();
	}
}
/*$pdf = new COTFATTPIN("P", "mm", "A4", "Aptitude Assessment");

$pdf->GenerateTPINWithCandInfo(array("first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name"), array("first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name"), array("first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name"), array("first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name", "first name"));

$pdf->OutputPDF();*/
?>