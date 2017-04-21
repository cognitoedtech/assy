<?php
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objDB 	= new CMcatDB();
	
	$img_content = "";
		
	if($qry[0] == "qid")
	{
		$ques_id = trim($qry[1]);
		
		$ques_details = $objDB->GetQuestionDetails($ques_id);
		
		if($qry[2] == "opt")
		{
			$opt_value = trim($qry[3]);
			
			switch($opt_value)
			{
				case 0:
					$img_content = $ques_details['question'];
					break;
				
				default:
					$option = json_decode($ques_details['options'], true);
					$img_content = base64_decode($option[$opt_value-1]['option']);
					break;
			}
		}
		//echo $img_content;	
	}
	else if($qry[0] == "para_id")
	{
		$para_id = $qry[1];
		
		if($qry[2] == "ques_type")
		{
			$ques_type = $qry[3];
			
			$img_content = $objDB->GetParaDescription($para_id, $ques_type);
		}
	}
	else if($qry[0] == "org_logo_img")
	{
		$org_id = trim($qry[1]);
		
		$img_content = base64_decode($objDB->GetOrgLogoImage($org_id));
		
		//echo($img_content);
	}
	
	$image_type = CUtils::getMimeType($img_content);
	//echo($image_type."<br/><br/>");
		
	header('Content-Type: '.$image_type);
		
	$image = imagecreatefromstring($img_content);
	
	if($image !== FALSE)
	{
		if($image_type == "image/gif")
		{
			imagegif($image);
		}
		else if($image_type == "image/png")
		{
			imagepng($image);
		}
		else if($image_type == "image/jpeg")
		{
			imagejpeg($image);
		}
		
		imagedestroy($image);
	}
?>