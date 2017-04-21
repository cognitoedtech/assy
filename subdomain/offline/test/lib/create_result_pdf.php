<?php 
	include_once(dirname(__FILE__)."/../../3rd_party/fpdf/mem_image.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../3rd_party/pChart/class/pData.class.php");
	include_once(dirname(__FILE__)."/../../3rd_party/pChart/class/pDraw.class.php");
	include_once(dirname(__FILE__)."/../../3rd_party/pChart/class/pPie.class.php");
	include_once(dirname(__FILE__)."/../../3rd_party/pChart/class/pImage.class.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/tbl_result.php");
	include_once(dirname(__FILE__)."/test_helper.php");
	
	class CCreateResultPDF
	{
		var $objResult;
		 
		public function __construct()
		{
			$this->objResult = new CResult();
		}
		
		public function __destruct()
		{
			unset($this->objResult);
		}
		
		private function DrawOverallPerformancePieChart($CorrectAns,$WrongAns,$Unanswered)
		{
			/* Create and populate the pData object */
			$overAllData = new pData();
			$overAllData->addPoints(array($CorrectAns,$WrongAns,$Unanswered),"ScoreA");
			$overAllData->setSerieDescription("ScoreA","Application A");
				
			/* Define the absissa serie */
			$overAllData->addPoints(array("Correct","Wrong","Unanswered"),"Labels");
			$overAllData->setAbscissa("Labels");
				
			/* Create the pChart object */
			$overAllPerfImage = new pImage(700,230,$overAllData);
				
			/* Add a border to the picture */
			$overAllPerfImage->drawRectangle(200,0,550,229,array("R"=>0,"G"=>0,"B"=>0));
				
			/* Set the default font properties */
			$overAllPerfImage->setFontProperties(array("FontName"=>dirname(__FILE__)."/../../3rd_party/pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));
				
			/* Enable shadow computing */
			$overAllPerfImage->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>150,"G"=>150,"B"=>150,"Alpha"=>100));
				
			/* Create the pPie object */
			$PieChart = new pPie($overAllPerfImage,$overAllData);
			
			$PieChart->setSliceColor(0,array("R"=>60,"G"=>159,"B"=>216));
			$PieChart->setSliceColor(1,array("R"=>255,"G"=>163,"B"=>25));
			$PieChart->setSliceColor(2,array("R"=>194,"G"=>163,"B"=>102));
				
			/* Draw two AA pie chart */
			$PieChart->draw3DPie(350,115,array("Radius"=>75,"Border"=>TRUE,"WriteValues"=>TRUE));
				
			/* Write down the legend next to the 2nd chart*/
			$PieChart->drawPieLegend(465,70);
				
			/* Write a legend box under the 1st chart */
			$overAllPerfImage->setFontProperties(array("FontName"=>dirname(__FILE__)."/../../3rd_party/pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
				
			/* Write the bottom legend box */
			$overAllPerfImage->setFontProperties(array("FontName"=>dirname(__FILE__)."/../../3rd_party/pChart/fonts/Silkscreen.ttf","FontSize"=>6));
				
			if ( $overAllPerfImage->TransparentBackground ) {
				imagealphablending($overAllPerfImage->Picture,false); imagesavealpha($overAllPerfImage->Picture,true);
			}
			
			return $overAllPerfImage->Picture;
		}
		
		private function DrawBarChart($aryCorrectAns, $aryWrongAns, $aryUnanswered, $aryXAxisValues, $xAxisSeriesLabel)
		{
			/* Create and populate the pData object */
			$data = new pData();
			$data->addPoints($aryCorrectAns, "Correct");
			$data->addPoints($aryWrongAns, "Wrong");
			$data->addPoints($aryUnanswered, "Unanswered");
			$data->setAxisName(0,"Questions");
			$data->addPoints($aryXAxisValues,$xAxisSeriesLabel);
			$data->setSerieDescription($xAxisSeriesLabel, $xAxisSeriesLabel);
			$data->setAbscissa($xAxisSeriesLabel);
			
			$data->setSerieDrawable("Floating 0",FALSE);
				
			$data->setPalette("Correct", array("R"=>60,"G"=>159,"B"=>216));
			$data->setPalette("Wrong", array("R"=>255,"G"=>163,"B"=>25));
			$data->setPalette("Unanswered", array("R"=>194,"G"=>163,"B"=>102));
				
			/* Create the pChart object */
			$performanceImage = new pImage(700,430,$data);
				
			/* Turn of Antialiasing */
			$performanceImage->Antialias = FALSE;
				
			/* Set the default font */
			$performanceImage->setFontProperties(array("FontName"=>dirname(__FILE__)."/../../3rd_party/pChart/fonts/verdana.ttf","FontSize"=>7));
				
			/* Define the chart area */
			$performanceImage->setGraphArea(60,40,650,200);
				
			/* Draw the scale */
			$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE, "Mode"=>SCALE_MODE_ADDALL, "LabelRotation"=>30);
			$performanceImage->drawScale($scaleSettings);
				
			/* Turn on shadow computing */
			$performanceImage->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
				
			/* Write the chart legend */
			$performanceImage->drawLegend(580,12);
				
			//$settings = array("Interleave"=>1, "Floating0Serie"=>"Floating 0");
			$settings = array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO, "Floating0Serie"=>"Floating 0","Draw0Line"=>TRUE,"Gradient"=>TRUE, "DisplayShadow"=>TRUE,"Surrounding"=>10);
			$performanceImage->drawBarChart($settings);
				
			if ( $performanceImage->TransparentBackground ) {
				imagealphablending($performanceImage->Picture,false); imagesavealpha($performanceImage->Picture,true);
			}
			
			return $performanceImage->Picture;
		}
		
		
		//public function GenerateTestDNAPDF($test_pnr="c09466d6-bece-f774-995b-f2176dff728a")
		public function GenerateTestDNAPDF($test_pnr, $file_name = '', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
			
			$arySection 		= array();
			$arySubject 		= array();
			$aryTopic 			= array();
			$arySecCorrect 	 	= array();
			$arySecWrong    	= array();
			$arySecUnanswered  	= array();
			$arySubCorrect 	  	= array();
			$arySubWrong    	= array();
			$arySubUnanswered  	= array();
			$aryTpcCorrect 	  	= array();
			$aryTpcWrong    	= array();
			$aryTpcUnanswered  	= array();
			$aryDifCorrect 	  	= array();
			$aryDifWrong    	= array();
			$aryDifUnanswered 	= array();
						
			$ResultAry = $this->objResult->GetResultFromPNR($test_pnr);
			
			if(!empty($ResultAry))
			{
				$CorrectAns = 0;
				$WrongAns   = 0;
				$Unanswered	= 0;
				
				foreach ($ResultAry as $sectionName => $SectionAry)
				{
					array_push($arySection, $sectionName);
					
					$nSecCorrectAns = 0;
					$nSecWrongAns 	= 0; 
					$nSecUnanswered = 0;
					
					foreach ($SectionAry as $subjectName => $SubjectAry)
					{
						array_push($arySubject, $subjectName);
						
						$nSubCorrectAns = 0;
						$nSubWrongAns 	= 0; 
						$nSubUnanswered = 0;
						
						foreach ($SubjectAry as $topicName => $TopicAry)
						{
							$nDifCorrectAns = array();
							$nDifWrongAns 	= array();
							$nDifUnanswered = array();
							
							$nDifCorrectAns[1] = 0;
							$nDifCorrectAns[2] = 0; 
							$nDifCorrectAns[3] = 0;
							
							$nDifWrongAns[1] = 0; 
							$nDifWrongAns[2] = 0; 
							$nDifWrongAns[3] = 0;
							
							$nDifUnanswered[1] = 0; 
							$nDifUnanswered[2] = 0; 
							$nDifUnanswered[3] = 0;
							
							if(!isset($aryTopic[$subjectName]))
							{
								$aryTopic[$subjectName] 			= array();
								$aryTpcCorrect[$subjectName] 		= array();
								$aryTpcWrong[$subjectName] 			= array();
								$aryTpcUnanswered[$subjectName] 	= array();
								$aryDifCorrect[$subjectName] 		= array();
								$aryDifWrong[$subjectName] 			= array();
								$aryDifUnanswered[$subjectName] 	= array();
							}
							
							if(!isset($aryDifCorrect[$subjectName][$topicName]))
							{
								$aryDifCorrect[$subjectName][$topicName]		= array();
								$aryDifWrong[$subjectName][$topicName] 			= array();
								$aryDifUnanswered[$subjectName][$topicName] 	= array();
							}
							
							array_push($aryTopic[$subjectName], $topicName);
							
							$nTpcCorrectAns = 0;
							$nTpcWrongAns 	= 0; 
							$nTpcUnanswered = 0;
							
							foreach ($TopicAry as $difficulty => $QuestionAry)
							{
								foreach ($QuestionAry as $QuestionIdx => $Answer )
								{
									if($Answer == 0)
									{
										$WrongAns++;
										
										$nSecWrongAns++;
										$nSubWrongAns++;
										$nTpcWrongAns++;
										$nDifWrongAns[$difficulty]++;
										$nTotalWrongAns++;
									}
									else if($Answer == 1)
									{
										$CorrectAns++;
										
										$nSecCorrectAns++;
										$nSubCorrectAns++;
										$nTpcCorrectAns++;
										$nDifCorrectAns[$difficulty]++;
										$nTotalCorrectAns++;
									}
									else if($Answer == -1 || $Answer == -2)
									{
										$Unanswered++;
										
										$nSecUnanswered++;
										$nSubUnanswered++;
										$nTpcUnanswered++;
										$nDifUnanswered[$difficulty]++;
										$nTotalUnanswered++;
									}
								}
							}
							
							array_push($aryDifCorrect[$subjectName][$topicName], $nDifCorrectAns[1], $nDifCorrectAns[2], $nDifCorrectAns[3]);
							array_push($aryDifWrong[$subjectName][$topicName], $nDifWrongAns[1], $nDifWrongAns[2], $nDifWrongAns[3]);
							array_push($aryDifUnanswered[$subjectName][$topicName], $nDifUnanswered[1], $nDifUnanswered[2], $nDifUnanswered[3]);
							
							array_push($aryTpcCorrect[$subjectName], $nTpcCorrectAns);
							array_push($aryTpcWrong[$subjectName], $nTpcWrongAns);
							array_push($aryTpcUnanswered[$subjectName], $nTpcUnanswered);
						}
						array_push($arySubCorrect, $nSubCorrectAns);
						array_push($arySubWrong, $nSubWrongAns);
						array_push($arySubUnanswered, $nSubUnanswered);
					}
					array_push($arySecCorrect, $nSecCorrectAns);
					array_push($arySecWrong, $nSecWrongAns);
					array_push($arySecUnanswered, $nSecUnanswered);
				}
				
				$unpreparedResultAry = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
				
				$org_id 	  = $objDB->GetOrgIdByTestId($unpreparedResultAry['test_id']);
				
				$org_logo = $objDB->GetOrgLogoImage($org_id);
				
				if(empty($org_logo))
				{
					$org_logo = $objDB->GetOrganizationName($org_id);
				}
				
				$dtzone = new DateTimeZone($this->objResult->tzOffsetToName($time_zone));
				
				$dtTime  = new DateTime();
				$dtTime->setTimestamp(strtotime($unpreparedResultAry['test_date']));
				$dtTime->setTimezone($dtzone);
				
				$assessment_date = $dtTime->format("F d, Y");
				
				$hours_taken 	= floor($unpreparedResultAry['time_taken'] / 3600);
				$mitutes_taken 	= floor(($unpreparedResultAry['time_taken'] % 3600) / 60);
				$seconds_taken  = ($unpreparedResultAry['time_taken'] % 3600) % 60;
				
				$time_taken = "[".str_pad($hours_taken, 2, "0", STR_PAD_LEFT).":".str_pad($mitutes_taken, 2, "0", STR_PAD_LEFT).":".str_pad($seconds_taken, 2, "0", STR_PAD_LEFT)."]";
				
				$pdf = new PDF_MemImage('P','mm','A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken, false);;
				$pdf->SetProtection(array('print'));
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',14);
				$pdf->Cell(40,10,'Overall Performance Overview');
				$pdf->GDImage($this->DrawOverallPerformancePieChart($CorrectAns,$WrongAns,$Unanswered),30,50,140);
				$pdf->Line(10, 110, 200, 110);
				$pdf->Ln();
				$pdf->Cell(40,150,'Sectional Overview',0,0,'L');
				$pdf->GDImage($this->DrawBarChart($arySecCorrect, $arySecWrong, $arySecUnanswered, $arySection, "Section"),30,130,140);
				$pdf->Line(10, 190, 200, 190);
				$pdf->Ln();
				
				$pdf->Cell(40,10,'Subject Overview',0,0,'L');
				$pdf->GDImage($this->DrawBarChart($arySubCorrect, $arySubWrong, $arySubUnanswered, $arySubject, "Subject"),30,210,140);
				$pdf->Ln();
				
				$imageTopMargin = 50;
				$lineTopMargin  = 110;
				$i = 0;
				$cellTopMargin = 10;
				foreach($arySubject as $subject)
				{
					if($i%3 == 0)
					{
						$cellTopMargin = 10;
						$pdf->AddPage();				
						$imageTopMargin = 50;
						$lineTopMargin  = 110;
					}
					$pdf->Cell(40,$cellTopMargin,'Performance in Subject - '.$subject,0,0,'L');
					$pdf->GDImage($this->DrawBarChart($aryTpcCorrect[$subject], $aryTpcWrong[$subject], $aryTpcUnanswered[$subject], $aryTopic[$subject], $subject),30,$imageTopMargin,140);
					if($i%3 != 2)
					{
						$pdf->Line(10, $lineTopMargin, 200, $lineTopMargin);	
					}
					$pdf->Ln();
					
					$imageTopMargin += 80;
					$lineTopMargin  += 80;
					$i++;
					
					if($cellTopMargin == 10)
					{
						$cellTopMargin = 150;
					}
					else if($cellTopMargin == 150)
					{
						$cellTopMargin = 10;
					}
					
					foreach($aryTopic[$subject] as $topic)
					{
						
						if($i%3 == 0)
						{
							$cellTopMargin = 10;
							$pdf->AddPage();
							$imageTopMargin = 50;
							$lineTopMargin  = 110;
						}
						
						$pdf->Cell(40,$cellTopMargin,'Performance in Topic - '.$topic,0,0,'L');
						$pdf->GDImage($this->DrawBarChart($aryDifCorrect[$subject][$topic], $aryDifWrong[$subject][$topic], $aryDifUnanswered[$subject][$topic], array("Easy", "Moderate", "Hard"), $topic),30,$imageTopMargin,140);
						if($i%3 != 2)
						{
							$pdf->Line(10, $lineTopMargin, 200, $lineTopMargin);
						}
						$pdf->Ln();
						
						$imageTopMargin += 80;
						$lineTopMargin  += 80;
						$i++;
						
						if($cellTopMargin == 10)
						{
							$cellTopMargin = 150;
						}
						else if($cellTopMargin == 150)
						{
							$cellTopMargin = 10;
						}
					}
				}
				
				$pdf->Output($file_name);
			}
		}
		
		public function GenerateResultInspectionPDF($test_pnr, $file_name='', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
			
			$unpreparedResultAry = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
			
			$org_id 	  = $objDB->GetOrgIdByTestId($unpreparedResultAry['test_id']);
			
			$org_logo = $objDB->GetOrgLogoImage($org_id);
			
			if(empty($org_logo))
			{
				$org_logo = $objDB->GetOrganizationName($org_id);
			}
			
			$objTestHelper = new CTestHelper();
			$sectional_details = $objTestHelper->GetSectionDetails($unpreparedResultAry['test_id']);
				
			$dtzone = new DateTimeZone($this->objResult->tzOffsetToName($time_zone));
				
			$dtTime  = new DateTime();
			$dtTime->setTimestamp(strtotime($unpreparedResultAry['test_date']));
			$dtTime->setTimezone($dtzone);
				
			$assessment_date = $dtTime->format("F d, Y");
			
			$hours_taken 	= floor($unpreparedResultAry['time_taken'] / 3600);
			$mitutes_taken 	= floor(($unpreparedResultAry['time_taken'] % 3600) / 60);
			$seconds_taken  = ($unpreparedResultAry['time_taken'] % 3600) % 60;
				
			$time_taken = str_pad($hours_taken, 2, "0", STR_PAD_LEFT).":".str_pad($mitutes_taken, 2, "0", STR_PAD_LEFT).":".str_pad($seconds_taken, 2, "0", STR_PAD_LEFT);
			
			$pdf = new PDF_MemImage('P','mm','A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken);
			$pdf->SetProtection(array('print'));
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			
			$ResultAry = $this->objResult->GetResultInspectionFromPNR($test_pnr);
			
			$qIndex = 0;
			$secIndex = 0;
			$secQuesIndex = 0;
			
			$topMargin = 20;
			
			$pdf->SetFont('Times','BU',16);
			$pdf->MultiCell(190,5, "Section - ".$sectional_details[$secIndex]['name']);
			$pdf->Ln(10);
			while($qIndex < count($ResultAry))
			{
				if($secQuesIndex == $sectional_details[$secIndex]['questions'])
				{
					$secQuesIndex = 0;
					$pdf->SetFont('Times','BU',16);
					$pdf->MultiCell(190,5, "Section - ".$sectional_details[++$secIndex]['name']);
					$pdf->Ln(10);
				}
				
				$pdf->SetFont('Times','',12);
				if(!empty($ResultAry[$qIndex]['linked_to']))
				{
					$ResultAry[$qIndex]['para_desc'] = $objDB->GetParaDescription($ResultAry[$qIndex]['linked_to'], $ResultAry[$qIndex]['ques_type']);
					$pdf->SetFont('Times','B',14);
					if(CUtils::getMimeType($ResultAry[$qIndex]['para_desc']) != "application/octet-stream")
					{
						$y = $pdf->GetY();
						$uri = 'data://application/octet-stream;base64,' . base64_encode($ResultAry[$qIndex]['para_desc']);
						$ary = getimagesize($uri);
						if(($y+($ary[1]/5)+2) > 286)
						{
							$lineShift =  intval(287-$y);
							$pdf->Ln($lineShift);
						}
						$pdf->MultiCell(190,5, "PARAGRAPH :-");
						$pdf->SetFont('Times','',12);
						$pdf->GDImage($ResultAry[$qIndex]['para_desc'],25,($pdf->GetY()),$ary[0]/5, $ary[1]/5);
						$pdf->SetY($pdf->GetY()+($ary[1]/5)+2);
					}
					else
					{
						$pdf->MultiCell(190,5, "PARAGRAPH :-");
						$pdf->SetFont('Times','',12);
						$pdf->MultiCell(190,5,utf8_decode(html_entity_decode(str_replace('</div>',"\n",str_replace('<div class="mipcat_code_ques">',"\n",str_replace("\n", " ", trim($ResultAry[$qIndex]['para_desc']), $i))))));
						$pdf->Ln(2);
					}
				}
				
				if(CUtils::getMimeType($ResultAry[$qIndex]['question']) != "application/octet-stream")
				{	
					$y = $pdf->GetY();
					$uri = 'data://application/octet-stream;base64,' . base64_encode($ResultAry[$qIndex]['question']);
					$ary = getimagesize($uri);
					if(($y+($ary[1]/5)+5) > 286)
					{
						$lineShift =  intval(287-$y);
						$pdf->Ln($lineShift);
					}
					$pdf->SetFont('Times','B',12);
					$pdf->MultiCell(190,5,'Q.'.($qIndex + 1).'). ');
					$pdf->SetFont('Times','',12);
					$pdf->GDImage($ResultAry[$qIndex]['question'],25,($pdf->GetY()-5),$ary[0]/5, $ary[1]/5);
					$pdf->SetY($pdf->GetY()+($ary[1]/5)+2);
				}
				else 
				{
					$i = 1;
					$ques = array();
					$ques['bullet'] = 'Q.'.($qIndex + 1);
					$ques['margin'] = '). ';
					$ques['indent'] = 0;
					$ques['spacer'] = 0;
					$ques['text'] = array();
					$ques['text'][0] = utf8_decode(html_entity_decode(str_replace('</div>',"\n",str_replace('<div class="mipcat_code_ques">',"\n",str_replace("\n", " ", trim($ResultAry[$qIndex]['question']), $i)))));
					
					$pdf->MultiCellBltArray(190,5,$ques);
					$pdf->Ln(2);
				}
					
				$ansAry = array();
				for($opt_idx = 0; $opt_idx < count($ResultAry[$qIndex]['options']); $opt_idx++)
				{
					if(CUtils::getMimeType(base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option'])) != "application/octet-stream")
					{
						$y = $pdf->GetY();
						$uri = 'data://application/octet-stream;base64,' . $ResultAry[$qIndex]['options'][$opt_idx]['option'];
						$ary = getimagesize($uri);
						if(($y+($ary[1]/5)+5) > 250)
						{
							$lineShift =  intval(287-$y);
							$pdf->Ln($lineShift);
						}
						$pdf->SetFont('Times','B',12);
						$pdf->MultiCell(190,5,($opt_idx+1).'. ');
						$pdf->SetFont('Times','',12);
						$pdf->GDImage(base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option']),20,($pdf->GetY() - 5),$ary[0]/5, $ary[1]/5);
						$pdf->SetY($pdf->GetY()+($ary[1]/5)+1);
					}
					else
					{
						$option = array();
						$option['bullet'] = $opt_idx+1;
						$option['margin'] = '. ';
						$option['indent'] = 0;
						$option['spacer'] = 0;
						$option['text'] = array();
						$option['text'][0] = utf8_decode(html_entity_decode(str_replace('</div>',"\n",str_replace('<div class="mipcat_code_ques">',"\n",str_replace("\n", " ", base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option']), $i)))));
						$pdf->MultiCellBltArray(80,5,$option);
						$pdf->Ln(1);
					}
				
					if($ResultAry[$qIndex]['options'][$opt_idx]['answer'] == 1)
					{
						array_push($ansAry, ($opt_idx + 1));
					}
				}
				$pdf->Ln(2);
				$ResultAry[$qIndex]['answer'] = implode(",", $ansAry);
				$selected_answer = "Your Answer : ".implode(",",$ResultAry[$qIndex]['selected']);
				if(in_array(-1, $ResultAry[$qIndex]['selected']) || in_array(-2, $ResultAry[$qIndex]['selected']))
				{
					$selected_answer = "You did not attempt this question.";
				}
				$pdf->MultiCell(190,5,$selected_answer);
				$pdf->MultiCell(190,5,"Correct Answer : ".implode(",", $ansAry));
				$qIndex++;
				$secQuesIndex++;
				$topMargin += 80;
				$pdf->Ln(7);
			}
			$pdf->Output($file_name);
		}
	}
	
	
	/*$objCreatePDF = new CCreateResultPDF();
	$objCreatePDF->GenerateTestDNAPDF();
	$objCreatePDF->GenerateResultInspectionPDF();*/
?>