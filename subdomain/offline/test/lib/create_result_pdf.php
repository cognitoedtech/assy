<?php 
	include_once(dirname(__FILE__)."/../../3rd_party/fpdf/mem_image.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
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
		
		private function DrawHighchart($data)
		{
			$url = CSiteConfig::HIGHCHART_SERVER_URL;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data))
			);
				
			$response = curl_exec($ch);
			$data1 = base64_decode($response);
			curl_close($ch);
			return $data1;
		}
		
		private function DrawAccuracyChart($accuracy)
		{
			$accuracyColorAry =array();
			for($stop = 0.01; $stop <= 1.00; $stop+=0.01)
			{
				$colorCode = "#fff";
				if($stop <= 0.21)
				{
					$colorCode = "#DF5353";
				}
				else if($stop <= 0.41)
				{
					$colorCode = "#953579";
				}
				else if($stop <= 0.61)
				{
					$colorCode = "#FFA500";
				}
				else if($stop <= 0.71)
				{
					$colorCode = "#DDDF0D";
				}
				else if($stop <= 0.81)
				{
					$colorCode = "#4bb2c5";
				}
				else if($stop <= 1.00)
				{
					$colorCode = "#55BF3B";
				}
			
				array_push($accuracyColorAry,array($stop, $colorCode));
			}
			
			$data_ary = array(
					"chart"=>array("type"=>"solidgauge"),
					"title"=>null,
					"pane"=>array(
							"center"=>array('50%', '85%'),
							"size"=>'60%',
							"startAngle"=> -90,
							"endAngle"=>90,
							"background"=>array(
											"backgroundColor"=>"#fff",
											"shape"=>"arc",
											"outerRadius"=>"100%",
											"innerRadius"=>"60%")),
					"tooltip"=>array("enabled"=>false),
					"yAxis"=>array(
							"min"=>0,
							"max"=>100,
							"minColor"=>"#fff",
							"maxColor"=>"#fff",
							"stops"=>$accuracyColorAry,
							"lineWidth"=>0,
							"minorTickInterval"=>null,
							"tickPixelInterval"=>400,
							"tickWidth"=>0,
							"title"=>array(
									"y"=>-70,
									"text"=>"Accuracy"),
							"labels"=>array("y"=>16)),
					"plotOptions"=>array(
							"solidgauge"=>array(
									"dataLabels"=>array(
											"y"=>5,
											"borderWidth"=>0,
											"useHTML"=> false))),
					"credits"=>array("enabled"=>false),
					"series"=>array(
							array(
									"name"=>"Accuracy",
									"data"=>array($accuracy),
									"dataLabels"=>array(
											"format"=>"'<div style=\"text-align:center\"><span style=\"font-size:25px;color:' + ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '\">{y}%</span></div>'"),
									"tooltip"=>array("valueSuffix"=>"%"))));
			$data = json_encode(array("infile"=>json_encode($data_ary),"constr"=>"Chart"));
			$data1 = $this->DrawHighchart($data);
			return $data1;
		}
		
		public function DrawSpeedChart($speed)
		{
			$speed_category = "Very Slow";
			$speed_to_show = $speed;
			if($speed <= 50)
			{
				$speed_category = "Very Slow";
				$speed_to_show = 16;
			}
			else if($speed > 50 && $speed <= 66)
			{
				$speed_category = "Slow";
				$speed_to_show = 33;
			}
			else if($speed > 66 && $speed <= 83)
			{
				$speed_category = "Average";
				$speed_to_show = 50;
			}
			else if($speed > 83 && $speed <= 100)
			{
				$speed_category = "Good";
				$speed_to_show = 75;
			}
			else if($speed > 100)
			{
				$speed_category = "Amazing";
				$speed_to_show = 100;
			}
			
			$colorAry = array();
			for($stop = 0.01; $stop <= 1.00; $stop+=0.01)
			{
				$colorCode = "#fff";
				if($stop <= 0.17)
				{
					$colorCode = "#DF5353";
				}
				else if($stop <= 0.34)
				{
					$colorCode = "#FFA500";
				}
				else if($stop <= 0.51)
				{
					$colorCode = "#DDDF0D";
				}
				else if($stop <= 0.76)
				{
					$colorCode = "#4bb2c5";
				}
				else if($stop <= 1.00)
				{
					$colorCode = "#55BF3B";
				}
			
				array_push($colorAry, array($stop,$colorCode));
			}
			
			$data_ary = array(
					"chart"=>array("type"=>"solidgauge"),
					"title"=>null,
					"pane"=>array(
							"center"=>array('50%', '85%'),
							"size"=>'60%',
							"startAngle"=> -90,
							"endAngle"=>90,
							"background"=>array(
									"backgroundColor"=>"#fff",
									"shape"=>"arc",
									"outerRadius"=>"100%",
									"innerRadius"=>"60%")),
					"tooltip"=>array("enabled"=>false),
					"yAxis"=>array(
							"min"=>0,
							"max"=>100,
							"minColor"=>"#fff",
							"maxColor"=>"#fff",
							"stops"=>$colorAry,
							"lineWidth"=>0,
							"minorTickInterval"=>null,
							"tickPixelInterval"=>400,
							"tickWidth"=>0,
							"showFirstLabel"=>false,
							"showLastLabel"=>false,
							"title"=>array(
									"y"=>-70,
									"text"=>"Speed"),
							"labels"=>array("y"=>16)),
					"plotOptions"=>array(
							"solidgauge"=>array(
									"dataLabels"=>array(
											"y"=>5,
											"borderWidth"=>0,
											"useHTML"=> false))),
					"credits"=>array("enabled"=>false),
					"series"=>array(
							array(
									"name"=>"Speed",
									"data"=>array($speed_to_show),
									"dataLabels"=>array(
											"format"=>"'<div style=\"text-align:center\"><span style=\"font-size:25px;color:' + ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '\">".$speed_category."</span></div>'"),
									"tooltip"=>array("valueSuffix"=>" "))));
			$data = json_encode(array("infile"=>json_encode($data_ary),"constr"=>"Chart"));
			$data1 = $this->DrawHighchart($data);
			return $data1;
		}
		
		private function DrawHolisticGuageChart($percentageOfMarks)
		{
			$y_min = 0;
			$y_max = 100;
			if($percentageOfMarks < 0)
			{
				$y_min = (intval($percentageOfMarks/10) * 10) - 10;
				$y_max = 100 + $y_min;
			}
			$data_ary = array(
					"chart"=>array(
							"type"=>"gauge",
							"plotBackgroundColor"=>null,
							"plotBackgroundImage"=>null,
							"plotBorderWidth"=>0,
							"plotShadow"=>false),
					"title"=>array("text"=>""),
					"pane"=>array(
							"startAngle"=> -150,
							"endAngle"=>150,
							"size"=>200,
							"background"=>array(
									array("backgroundColor"=>array(
											"linearGradient"=>array(
													"x1"=>0,
													"y1"=>0,
													"x2"=>0,
													"y2"=>1),
											"stops"=>array(
													array(0, '#FFF'),
													array(1, '#333'))),
											"borderWidth"=>0,
											"outerRadius"=>'109%'),
									array("backgroundColor"=>array(
											"linearGradient"=>array(
													"x1"=>0,
													"y1"=>0,
													"x2"=>0,
													"y2"=>1),
											"stops"=>array(
													array(0, '#333'),
													array(1, '#FFF'))),
											"borderWidth"=>1,
											"outerRadius"=>'107%'),
									array(),
									array(
											"backgroundColor"=>"#DDD",
											"borderWidth"=>0,
											"outerRadius"=>"105%",
											"innerRadius"=>"103%"))),
					"yAxis"=>array(
							"min"=>$y_min,
							"max"=>$y_max,
							"minorTickInterval"=>"auto",
							"minorTickWidth"=>1,
							"minorTickLength"=>10,
							"minorTickPosition"=>"inside",
							"minorTickColor"=>"#666",
							"tickPixelInterval"=>30,
							"tickWidth"=>2,
							"tickPosition"=>"inside",
							"tickLength"=>10,
							"tickColor"=>"#666",
							"labels"=>array(
									"step"=>2,
									"rotation"=>"auto"),
							"title"=>array("text"=>"Percentage"),
							"plotBands"=>array(
									array(
											"from"=>$y_min,
											"to"=>20,
											"color"=>"#DF5353"),
									array(
											"from"=>20,
											"to"=>40,
											"color"=>"#953579"),
									array(
											"from"=>40,
											"to"=>60,
											"color"=>"#FFA500"),
									array(
											"from"=>60,
											"to"=>70,
											"color"=>"#DDDF0D"),
									array(
											"from"=>70,
											"to"=>80,
											"color"=>"#4bb2c5"),
									array(
											"from"=>80,
											"to"=>100,
											"color"=>"#55BF3B"))),
					"series"=>array(array(
							"name"=>"Percentage",
							"data"=>array($percentageOfMarks),
							"tooltip"=>array(
									"valueSuffix"=>" %"))));
			$data = json_encode(array("infile"=>json_encode($data_ary),"constr"=>"Chart"));
			$data1 = $this->DrawHighchart($data);
			return $data1;
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
		
		private function DrawSubjectTopicPercentageBarChart($subject_data)
		{
			$data_ary = array(
					"chart"=>array("type"=>"bar"),
					"title"=>null,
					"xAxis"=>array("categories"=>$subject_data['categories']),
					"yAxis"=>array("title"=>array("text"=>"Percentage(%)")),
					"legend"=>array("enabled"=>false),
					"series"=>array(array("name"=>" ","data"=>$subject_data['data'])));
			
			$data = json_encode(array("infile"=>json_encode($data_ary),"constr"=>"Chart"));
			$data1 = $this->DrawHighchart($data);
			return $data1;
		}
		
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
									else if($Answer == -1 || $Answer == -2 || $Answer == -3)
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
				
				$org_id = "";
				
				if($unpreparedResultAry['tschd_id'] != -100 && $unpreparedResultAry['tschd_id'] != CConfig::FEUC_TEST_SCHEDULE_ID)
				{
					$scheduled_test_ary = $objDB->GetScheduledTest($unpreparedResultAry['tschd_id']);
					$org_id = $objDB->GetOrgIdByUserId($scheduled_test_ary['scheduler_id']);
				}
				else 
				{
					$org_id = $objDB->GetOrgIdByTestId($unpreparedResultAry['test_id']);
				}
				
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
				
				$pdf = new PDF_MemImage('P','mm','A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken, false);
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
		
		public function GenerateHolisticViewPDF($test_pnr, $file_name = '', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
			
			$ResultParams = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
				
			$ResultAry = $this->objResult->GetHolisticMarks($ResultParams['ques_map'], $ResultParams['test_id'], $ResultParams['time_taken']);
			
			$org_id = "";
			
			if($ResultParams['tschd_id'] != -100 && $ResultParams['tschd_id'] != CConfig::FEUC_TEST_SCHEDULE_ID)
			{
				$scheduled_test_ary = $objDB->GetScheduledTest($ResultParams['tschd_id']);
				$org_id = $objDB->GetOrgIdByUserId($scheduled_test_ary['scheduler_id']);
			}
			else 
			{
				$org_id = $objDB->GetOrgIdByTestId($ResultParams['test_id']);
			}
			
			$org_logo = $objDB->GetOrgLogoImage($org_id);
			
			if(empty($org_logo))
			{
				$org_logo = $objDB->GetOrganizationName($org_id);
			}
			
			$dtzone = new DateTimeZone($this->objResult->tzOffsetToName($time_zone));
			
			$dtTime  = new DateTime();
			$dtTime->setTimestamp(strtotime($ResultParams['test_date']));
			$dtTime->setTimezone($dtzone);
			
			$assessment_date = $dtTime->format("F d, Y");
			
			$hours_taken 	= floor($ResultParams['time_taken'] / 3600);
			$mitutes_taken 	= floor(($ResultParams['time_taken'] % 3600) / 60);
			$seconds_taken  = ($ResultParams['time_taken'] % 3600) % 60;
			
			$time_taken = "[".str_pad($hours_taken, 2, "0", STR_PAD_LEFT).":".str_pad($mitutes_taken, 2, "0", STR_PAD_LEFT).":".str_pad($seconds_taken, 2, "0", STR_PAD_LEFT)."]";
			
			$max_marks = $ResultAry['max_marks'];
			$total_marks = $ResultAry['total_marks'];
			$accuracy = $ResultAry['accuracy'];
			$speed = $ResultAry['speed'];
			$percentageOfMarks = round(($total_marks/$max_marks)*100);
			
			$category = "";
			
			if($percentageOfMarks <= 20)
			{
				$category = CConfig::$HOLISTIC_CHART_LEGEND_ARY["Less than 20"];
			}
			else if($percentageOfMarks >= 21 && $percentageOfMarks <= 40)
			{
				$category = CConfig::$HOLISTIC_CHART_LEGEND_ARY["21 - 40"];
			}
			else if($percentageOfMarks >= 41 && $percentageOfMarks <= 60)
			{
				$category = CConfig::$HOLISTIC_CHART_LEGEND_ARY["41 - 60"];
			}
			else if($percentageOfMarks >= 61 && $percentageOfMarks <= 70)
			{
				$category = CConfig::$HOLISTIC_CHART_LEGEND_ARY["61 - 70"];
			}
			else if($percentageOfMarks >= 71 && $percentageOfMarks <= 80)
			{
				$category = CConfig::$HOLISTIC_CHART_LEGEND_ARY["71 - 80"];
			}
			else if($percentageOfMarks >= 81 && $percentageOfMarks <= 100)
			{
				$category = CConfig::$HOLISTIC_CHART_LEGEND_ARY["81 - 100"];
			}
			
			$pdf = new PDF_MemImage('P','mm','A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken, false);
			$pdf->SetProtection(array('print'));
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',14);
			$pdf->GDImage($this->DrawAccuracyChart($accuracy),0,90,120, 0);
			$pdf->GDImage($this->DrawSpeedChart($speed),90,90,120, 0);
			$pdf->GDImage($this->DrawHolisticGuageChart($percentageOfMarks),30,35,140);
			$pdf->Cell(40,10,"Candidate's Performance is ".$category);
			
			$pdf->SetDrawColor(85, 191, 59);
			$pdf->SetFillColor(85, 191, 59);
			$pdf->Rect(150, 55, 3, 3, 'F');
			
			$pdf->SetDrawColor(75, 178, 197);
			$pdf->SetFillColor(75, 178, 197);
			$pdf->Rect(150, 62, 3, 3, 'F');
			
			$pdf->SetDrawColor(221, 223, 13);
			$pdf->SetFillColor(221, 223, 13);
			$pdf->Rect(150, 69, 3, 3, 'F');
			
			$pdf->SetDrawColor(255, 165, 0);
			$pdf->SetFillColor(255, 165, 0);
			$pdf->Rect(150, 76, 3, 3, 'F');
			
			$pdf->SetDrawColor(149, 53, 121);
			$pdf->SetFillColor(149, 53, 121);
			$pdf->Rect(150, 83, 3, 3, 'F');
			
			$pdf->SetDrawColor(223, 83, 83);
			$pdf->SetFillColor(223, 83, 83);
			$pdf->Rect(150, 90, 3, 3, 'F');
			
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(235,43,"81 - 100 (".CConfig::$HOLISTIC_CHART_LEGEND_ARY["81 - 100"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(308,-29,"71 - 80 (".CConfig::$HOLISTIC_CHART_LEGEND_ARY["71 - 80"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(321,43,"61 - 70 (".CConfig::$HOLISTIC_CHART_LEGEND_ARY["61 - 70"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(311,-29,"41 - 60 (".CConfig::$HOLISTIC_CHART_LEGEND_ARY["41 - 60"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(307,43,"21 - 40 (".CConfig::$HOLISTIC_CHART_LEGEND_ARY["21 - 40"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(322,-29,"Less than 20 (".CConfig::$HOLISTIC_CHART_LEGEND_ARY["Less than 20"].")",0,0,'C');
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(40,85,"Accuracy and Speed");
			$pdf->Ln();
			
			$pdf->SetDrawColor(85, 191, 59);
			$pdf->SetFillColor(85, 191, 59);
			$pdf->Rect(110, 165, 3, 3, 'F');
			
			$pdf->SetDrawColor(75, 178, 197);
			$pdf->SetFillColor(75, 178, 197);
			$pdf->Rect(130, 165, 3, 3, 'F');
			
			$pdf->SetDrawColor(221, 223, 13);
			$pdf->SetFillColor(221, 223, 13);
			$pdf->Rect(145, 165, 3, 3, 'F');
			
			$pdf->SetDrawColor(255, 165, 0);
			$pdf->SetFillColor(255, 165, 0);
			$pdf->Rect(164, 165, 3, 3, 'F');
			
			$pdf->SetDrawColor(223, 83, 83);
			$pdf->SetFillColor(223, 83, 83);
			$pdf->Rect(178, 165, 3, 3, 'F');
			
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->Line(10, 110, 200, 110);
			$pdf->Line(10, 180, 200, 180);
			
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(220,10,"Amazing",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(255,-10,"Good",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(290,10,"Average",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(322,-10,"Slow",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(358,10,"Very Slow",0,0,'C');
			
			unset($ResultAry['max_marks']);
			unset($ResultAry['total_marks']);
			unset($ResultAry['accuracy']);
			unset($ResultAry['speed']);
			
			$resultIndex = 0;
			$imageTopMargin = 50;
			$lineTopMargin  = 110;
			$cellTopMargin = 10;
			foreach($ResultAry as $subject=>$values)
			{
				$categories_ary = array();
				$pecentageMarksColorAry = array();
				if(!in_array("Subject - ".$subject, $categories_ary))
				{
					array_push($categories_ary, "Subject - ".$subject);
					array_push($categories_ary, " ");
					array_push($categories_ary, " ");
					
					$percentageInSubject = round(($values['total_marks']/$values['max_marks'])*100);
					
					$range_color = "#DF5353";
					if($percentageInSubject <= 20)
					{
						$range_color = "#DF5353";
					}
					else if($percentageInSubject <= 40 && $percentageInSubject >= 21)
					{
						$range_color = "#953579";
					}
					else if($percentageInSubject <= 60 && $percentageInSubject >= 41)
					{
						$range_color = "#FFA500";
					}
					else if($percentageInSubject <= 70 && $percentageInSubject >= 61)
					{
						$range_color = "#DDDF0D";
					}
					else if($percentageInSubject <= 80 && $percentageInSubject >= 71)
					{
						$range_color = "#4bb2c5";
					}
					else if($percentageInSubject <= 100 && $percentageInSubject >= 81)
					{
						$range_color = "#55BF3B";
					}
					array_push($pecentageMarksColorAry, array('color'=>$range_color, 'y'=>$percentageInSubject));
					array_push($pecentageMarksColorAry, array('color'=>'#DF5353', 'y'=>0));
					array_push($pecentageMarksColorAry, array('color'=>'#DF5353', 'y'=>0));
				}
				
				if($values != "max_marks" && $values != "total_marks")
				{
					foreach($values as $topic=>$topic_values)
					{
						if(!in_array($topic, $categories_ary) && $topic != "max_marks" && $topic != "total_marks")
						{
							array_push($categories_ary, $topic);
							
							$percentageInTopic = round(($topic_values['total_marks']/$topic_values['max_marks'])*100);
								
							$range_color = "#DF5353";
							if($percentageInTopic <= 20)
							{
								$range_color = "#DF5353";
							}
							else if($percentageInTopic <= 40 && $percentageInTopic >= 21)
							{
								$range_color = "#953579";
							}
							else if($percentageInTopic <= 60 && $percentageInTopic >= 41)
							{
								$range_color = "#FFA500";
							}
							else if($percentageInTopic <= 70 && $percentageInTopic >= 61)
							{
								$range_color = "#DDDF0D";
							}
							else if($percentageInTopic <= 80 && $percentageInTopic >= 71)
							{
								$range_color = "#4bb2c5";
							}
							else if($percentageInTopic <= 100 && $percentageInTopic >= 81)
							{
								$range_color = "#55BF3B";
							}
							array_push($pecentageMarksColorAry, array('color'=>$range_color, 'y'=>$percentageInTopic));
						}
					}
				}
				$pdf->Ln();
				$pdf->SetFont('Arial','B',14);
				if($resultIndex == 0)
				{
					$pdf->Cell(40,35,"Performance in ".$subject);
					$pdf->GDImage($this->DrawSubjectTopicPercentageBarChart(array("categories"=>$categories_ary, "data"=>$pecentageMarksColorAry)),30,200,140,80);
					$pdf->Line(10, 180, 200, 180);
				}
				else 
				{
					if($resultIndex%2 == 1)
					{
						$cellTopMargin = 10;
						$pdf->AddPage();
						$imageTopMargin = 50;
						$lineTopMargin  = 140;
					}
					$pdf->Cell(40,$cellTopMargin,"Performance in ".$subject,0,0,'L');
					$pdf->GDImage($this->DrawSubjectTopicPercentageBarChart(array("categories"=>$categories_ary, "data"=>$pecentageMarksColorAry)),30,$imageTopMargin,140,80);
					if($resultIndex%2 == 1)
					{
						$pdf->Line(10, $lineTopMargin, 200, $lineTopMargin);	
					}
					$pdf->Ln();
					
					$imageTopMargin += 110;
					
					if($cellTopMargin == 10)
					{
						$cellTopMargin = 190;
					}
					else if($cellTopMargin == 190)
					{
						$cellTopMargin = 10;
					}
				}
				$resultIndex++;
			}
			$pdf->Ln();
			
			$pdf->Output($file_name);
		}
		
		public function GenerateIQViewPDF($test_pnr, $file_name = '', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
				
			$ResultParams = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
			
			$ResultAry = $this->objResult->GetIQResult($test_pnr, "", CConfig::UT_INDIVIDAL);
				
			$org_id = "";
			
			if($ResultParams['tschd_id'] != -100 && $ResultParams['tschd_id'] != CConfig::FEUC_TEST_SCHEDULE_ID)
			{
				$scheduled_test_ary = $objDB->GetScheduledTest($ResultParams['tschd_id']);
				$org_id = $objDB->GetOrgIdByUserId($scheduled_test_ary['scheduler_id']);
			}
			else 
			{
				$org_id = $objDB->GetOrgIdByTestId($ResultParams['test_id']);
			}
				
			$org_logo = $objDB->GetOrgLogoImage($org_id);
				
			if(empty($org_logo))
			{
				$org_logo = $objDB->GetOrganizationName($org_id);
			}
				
			$dtzone = new DateTimeZone($this->objResult->tzOffsetToName($time_zone));
				
			$dtTime  = new DateTime();
			$dtTime->setTimestamp(strtotime($ResultParams['test_date']));
			$dtTime->setTimezone($dtzone);
				
			$assessment_date = $dtTime->format("F d, Y");
				
			$hours_taken 	= floor($ResultParams['time_taken'] / 3600);
			$mitutes_taken 	= floor(($ResultParams['time_taken'] % 3600) / 60);
			$seconds_taken  = ($ResultParams['time_taken'] % 3600) % 60;
				
			$time_taken = "[".str_pad($hours_taken, 2, "0", STR_PAD_LEFT).":".str_pad($mitutes_taken, 2, "0", STR_PAD_LEFT).":".str_pad($seconds_taken, 2, "0", STR_PAD_LEFT)."]";
			
			$pdf = new PDF_MemImage('P','mm','A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken, false);
			$pdf->SetProtection(array('print'));
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(40,10,"Candidate's IQ Score is - ".$ResultAry['iq']);
			$pdf->Image(dirname(__FILE__)."/../../images/IQ_curve.png", 10,50,140);
			
			$pdf->SetDrawColor(85, 191, 59);
			$pdf->SetFillColor(85, 191, 59);
			$pdf->Rect(150, 55, 3, 3, 'F');
				
			$pdf->SetDrawColor(75, 178, 197);
			$pdf->SetFillColor(75, 178, 197);
			$pdf->Rect(150, 62, 3, 3, 'F');
				
			$pdf->SetDrawColor(221, 223, 13);
			$pdf->SetFillColor(221, 223, 13);
			$pdf->Rect(150, 69, 3, 3, 'F');
				
			$pdf->SetDrawColor(255, 165, 0);
			$pdf->SetFillColor(255, 165, 0);
			$pdf->Rect(150, 76, 3, 3, 'F');
				
			$pdf->SetDrawColor(149, 53, 121);
			$pdf->SetFillColor(149, 53, 121);
			$pdf->Rect(150, 83, 3, 3, 'F');
				
			$pdf->SetDrawColor(223, 83, 83);
			$pdf->SetFillColor(223, 83, 83);
			$pdf->Rect(150, 90, 3, 3, 'F');
			
			$pdf->SetDrawColor(192, 192, 192);
			$pdf->SetFillColor(192, 192, 192);
			$pdf->Rect(150, 97, 3, 3, 'F');

			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(249,43,"130 and above (".CConfig::$IQ_CHART_LEGEND_ARY["130 and above"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(315,-29,"120 - 129 (".CConfig::$IQ_CHART_LEGEND_ARY["120 - 129"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(324,43,"110 - 119 (".CConfig::$IQ_CHART_LEGEND_ARY["110 - 119"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(313,-29,"90 - 109 (".CConfig::$IQ_CHART_LEGEND_ARY["90 - 109"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(321,43,"80 - 89 (".CConfig::$IQ_CHART_LEGEND_ARY["80 - 89"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(315,-29,"70 - 79 (".CConfig::$IQ_CHART_LEGEND_ARY["70 - 79"].")",0,0,'C');
			$pdf->Ln();
			$pdf->Cell(330,43,"69 and below (".CConfig::$IQ_CHART_LEGEND_ARY["69 and below"].")",0,0,'C');
			
			$pdf->Line(10, 160, 200, 160);
			
			unset($ResultAry['max_marks']);
			unset($ResultAry['total_marks']);
			unset($ResultAry['accuracy']);
			unset($ResultAry['speed']);
			unset($ResultAry['iq']);
			
			$resultIndex = 0;
			$imageTopMargin = 50;
			$lineTopMargin  = 110;
			$cellTopMargin = 10;
			foreach($ResultAry as $subject=>$values)
			{
				$categories_ary = array();
				$pecentageMarksColorAry = array();
				if(!in_array("Subject - ".$subject, $categories_ary))
				{
					array_push($categories_ary, "Subject - ".$subject);
					array_push($categories_ary, " ");
					array_push($categories_ary, " ");
						
					$percentageInSubject = round(($values['total_marks']/$values['max_marks'])*100);
						
					$range_color = "#DF5353";
					if($percentageInSubject <= 20)
					{
						$range_color = "#DF5353";
					}
					else if($percentageInSubject <= 40 && $percentageInSubject >= 21)
					{
						$range_color = "#953579";
					}
					else if($percentageInSubject <= 60 && $percentageInSubject >= 41)
					{
						$range_color = "#FFA500";
					}
					else if($percentageInSubject <= 70 && $percentageInSubject >= 61)
					{
						$range_color = "#DDDF0D";
					}
					else if($percentageInSubject <= 80 && $percentageInSubject >= 71)
					{
						$range_color = "#4bb2c5";
					}
					else if($percentageInSubject <= 100 && $percentageInSubject >= 81)
					{
						$range_color = "#55BF3B";
					}
					array_push($pecentageMarksColorAry, array('color'=>$range_color, 'y'=>$percentageInSubject));
					array_push($pecentageMarksColorAry, array('color'=>'#DF5353', 'y'=>0));
					array_push($pecentageMarksColorAry, array('color'=>'#DF5353', 'y'=>0));
				}
			
				if($values != "max_marks" && $values != "total_marks")
				{
					foreach($values as $topic=>$topic_values)
					{
						if(!in_array($topic, $categories_ary) && $topic != "max_marks" && $topic != "total_marks")
						{
							array_push($categories_ary, $topic);
								
							$percentageInTopic = round(($topic_values['total_marks']/$topic_values['max_marks'])*100);
			
							$range_color = "#DF5353";
							if($percentageInTopic <= 20)
							{
								$range_color = "#DF5353";
							}
							else if($percentageInTopic <= 40 && $percentageInTopic >= 21)
							{
								$range_color = "#953579";
							}
							else if($percentageInTopic <= 60 && $percentageInTopic >= 41)
							{
								$range_color = "#FFA500";
							}
							else if($percentageInTopic <= 70 && $percentageInTopic >= 61)
							{
								$range_color = "#DDDF0D";
							}
							else if($percentageInTopic <= 80 && $percentageInTopic >= 71)
							{
								$range_color = "#4bb2c5";
							}
							else if($percentageInTopic <= 100 && $percentageInTopic >= 81)
							{
								$range_color = "#55BF3B";
							}
							array_push($pecentageMarksColorAry, array('color'=>$range_color, 'y'=>$percentageInTopic));
						}
					}
				}
				$pdf->Ln();
				$pdf->SetFont('Arial','B',14);
				if($resultIndex == 0)
				{
					$pdf->Cell(40,100,"Performance in ".$subject);
					$pdf->GDImage($this->DrawSubjectTopicPercentageBarChart(array("categories"=>$categories_ary, "data"=>$pecentageMarksColorAry)),30,180,140,80);
				}
				else
				{
					if($resultIndex%2 == 1)
					{
						$cellTopMargin = 10;
						$pdf->AddPage();
						$imageTopMargin = 50;
						$lineTopMargin  = 140;
					}
					$pdf->Cell(40,$cellTopMargin,"Performance in ".$subject,0,0,'L');
					$pdf->GDImage($this->DrawSubjectTopicPercentageBarChart(array("categories"=>$categories_ary, "data"=>$pecentageMarksColorAry)),30,$imageTopMargin,140,80);
					if($resultIndex%2 == 1)
					{
						$pdf->Line(10, $lineTopMargin, 200, $lineTopMargin);
					}
					$pdf->Ln();
						
					$imageTopMargin += 110;
						
					if($cellTopMargin == 10)
					{
						$cellTopMargin = 190;
					}
					else if($cellTopMargin == 190)
					{
						$cellTopMargin = 10;
					}
				}
				$resultIndex++;
			}
			$pdf->Ln();
			$pdf->Output($file_name);
		}
		
		public function GenerateEQViewPDF($test_pnr, $file_name = '', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
			
			$ResultParams = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
				
			$ResultAry = $this->objResult->GetEQResult($test_pnr);
			
			$org_id = "";
			
			if($ResultParams['tschd_id'] != -100 && $ResultParams['tschd_id'] != CConfig::FEUC_TEST_SCHEDULE_ID)
			{
				$scheduled_test_ary = $objDB->GetScheduledTest($ResultParams['tschd_id']);
				$org_id = $objDB->GetOrgIdByUserId($scheduled_test_ary['scheduler_id']);
			}
			else 
			{
				$org_id = $objDB->GetOrgIdByTestId($ResultParams['test_id']);
			}
			
			$org_logo = $objDB->GetOrgLogoImage($org_id);
			
			if(empty($org_logo))
			{
				$org_logo = $objDB->GetOrganizationName($org_id);
			}
			
			$dtzone = new DateTimeZone($this->objResult->tzOffsetToName($time_zone));
			
			$dtTime  = new DateTime();
			$dtTime->setTimestamp(strtotime($ResultParams['test_date']));
			$dtTime->setTimezone($dtzone);
			
			$assessment_date = $dtTime->format("F d, Y");
			
			$hours_taken 	= floor($ResultParams['time_taken'] / 3600);
			$mitutes_taken 	= floor(($ResultParams['time_taken'] % 3600) / 60);
			$seconds_taken  = ($ResultParams['time_taken'] % 3600) % 60;
			
			$time_taken = "[".str_pad($hours_taken, 2, "0", STR_PAD_LEFT).":".str_pad($mitutes_taken, 2, "0", STR_PAD_LEFT).":".str_pad($seconds_taken, 2, "0", STR_PAD_LEFT)."]";
			
			$pdf = new PDF_MemImage('P','mm','A4', $org_logo, $candidate_name, $candidate_email, $assessment_date, $time_taken, false);
			$pdf->SetProtection(array('print'));
			$pdf->AliasNbPages();
			$pdf->AddPage();
			
			$pdf->Ln();
			
			foreach($ResultAry as $section=>$description)
			{
				$pdf->SetFont('Arial','B',14);
				$pdf->MultiCell(190,5,trim($section), $i);
				$pdf->Ln(2);
				$pdf->SetFont('Times','',10);
				$pdf->MultiCell(190,5,utf8_decode(html_entity_decode(str_replace('</div>',"\n",str_replace('<div class="mipcat_code_ques">',"\n",str_replace("\n", " ", trim($description), $i))))));
				$pdf->Ln(10);
			}
			/*echo "<pre>";
			print_r($ResultAry);
			echo "</pre>";*/
			$pdf->Output($file_name);
		}
		
		public function GenerateResultInspectionPDF($test_pnr, $file_name='', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
			
			$unpreparedResultAry = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
			
			$org_id = "";
			
			if($unpreparedResultAry['tschd_id'] != -100 && $unpreparedResultAry['tschd_id'] != CConfig::FEUC_TEST_SCHEDULE_ID)
			{
				$scheduled_test_ary = $objDB->GetScheduledTest($unpreparedResultAry['tschd_id']);
				$org_id = $objDB->GetOrgIdByUserId($scheduled_test_ary['scheduler_id']);
			}
			else 
			{
				$org_id = $objDB->GetOrgIdByTestId($unpreparedResultAry['test_id']);
			}
			
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
				$ansValAry = array();
				
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
						
						$right_opt = base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option']);
						array_push($ansValAry, $right_opt);						
					}
					
					
				}
				
				$question_type = $ResultAry[$qIndex]['ques_type'];
				$correct_options = "";
				$selected_answer = "";
				
				if($question_type == CConfig::QT_MATRIX)
				{
						
					$correct_options = implode(",", $ansValAry);
					$selected_answer = implode(",",$ResultAry[$qIndex]['selected']);						
				}
				
				else if($question_type == CConfig::QT_INT)
				{
						
					$correct_options = implode(",", $ansValAry);
					$selected_answer = implode(",",$ResultAry[$qIndex]['selected']);
					if($selected_answer == "1") // Correct Ans Selected
					{
						$selected_answer = $correct_options;
					}				
				
				}
				else
				{
						
					$correct_options = implode(",", $ansAry);
					$selected_answer = implode(",",$ResultAry[$qIndex]['selected']);
						
				}
				
				$pdf->Ln(2);
				$ResultAry[$qIndex]['answer'] = $correct_options;//implode(",", $ansAry);
				$selected_answer = "Your Answer : ".$selected_answer;//implode(",",$ResultAry[$qIndex]['selected']);
				if(in_array(-1, $ResultAry[$qIndex]['selected']) || in_array(-2, $ResultAry[$qIndex]['selected']) || in_array(-3, $ResultAry[$qIndex]['selected']))
				{
					$selected_answer = "You did not attempt this question.";
				}
				$pdf->MultiCell(190,5,$selected_answer);
				$pdf->MultiCell(190,5,"Correct Answer : ".$correct_options);
				$qIndex++;
				$secQuesIndex++;
				$topMargin += 80;
				$pdf->Ln(7);
			}
			$pdf->Output($file_name);
		}
		
		
		public function GenerateResultInspectionPDFShort($test_pnr, $file_name='', $candidate_name, $candidate_email, $time_zone)
		{
			$objDB = new CMcatDB();
				
			$unpreparedResultAry = $this->objResult->GetUnpreparedResultFromPNR($test_pnr);
				
			$org_id = "";
				
			if($unpreparedResultAry['tschd_id'] != -100 && $unpreparedResultAry['tschd_id'] != CConfig::FEUC_TEST_SCHEDULE_ID)
			{
				$scheduled_test_ary = $objDB->GetScheduledTest($unpreparedResultAry['tschd_id']);
				$org_id = $objDB->GetOrgIdByUserId($scheduled_test_ary['scheduler_id']);
			}
			else
			{
				$org_id = $objDB->GetOrgIdByTestId($unpreparedResultAry['test_id']);
			}
				
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
			//$pdf->SetFont('Times','',12);
				
			$ResultAry = $this->objResult->GetResultInspectionFromPNR($test_pnr);
				
			//file_put_contents("result_ins.txt", print_r($ResultAry,true));
			
			
			$qIndex = 0;
			$secIndex = 0;
			$secQuesIndex = 0;
				
			$topMargin = 20;
				
			$header = array('S. No.', 'QID', 'CORRECT OPTION', 'YOUR SELECTION','CONCLUSION');
			$pdf->SetFont('Arial','',14);			
			$pdf->MultiCell(190,5, "Section - ".$sectional_details[$secIndex]['name']);
			//$pdf->Ln(10);
			
			//$pdf->SetFont('Times','',10);
			
			$data = array();
			//$pdf->Ln(5);
			
			$correct_count = 0;
			$unans_count =0;
			$partial_count = 0;
			$wrong_count = 0;
			
			while($qIndex < count($ResultAry))
			{
				$s_no = $secQuesIndex + 1;
				if($secQuesIndex == $sectional_details[$secIndex]['questions'])
				{
					
					$pdf->Ln(5);														
					$this->ImprovedTable($header,$data,$pdf);
					//$pdf->MultiCell(190, 5, "Summary: Correct->".$correct_count." Wrong->".$s_no -($correct_count + $unans_count)."Unans->".$unans_count);
					$pdf->Ln(2);
					$wrong = $s_no -1 - ($correct_count + $unans_count);
					$summary = "Summary-> Correct:".$correct_count . ", Wrong:".$wrong_count . ", Unanswered:".$unans_count . ", Partial Correct:".$partial_count;
					$pdf->Cell(190, 5,$summary);
					
					$pdf->Ln(10);
					$data = array();
					$secQuesIndex = 0;
					$correct_count= 0;
					$unans_count = 0;
					$partial_count = 0;
					$wrong_count = 0;
					$pdf->Ln(5);
					$pdf->MultiCell(190,5, "Section - ".$sectional_details[++$secIndex]['name']);																				
				}
		
				//$pdf->SetFont('Times','',12);
				
				$s_no = $secQuesIndex + 1;
				$qid = $ResultAry[$qIndex]['ques_id'];
			 
				$ansAry = array();
				$ansOptArray = array();
				$selectedOptArray = array();
				
				
				for($opt_idx = 0; $opt_idx < count($ResultAry[$qIndex]['options']); $opt_idx++)
				{ 
				
				if($ResultAry[$qIndex]['options'][$opt_idx]['answer'] == 1)
				{
				 array_push($ansAry, ($opt_idx + 1));
				 $right_opt = base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option']);				 
				 array_push($ansOptArray, $right_opt);
				}			
				}
				$correct_options = "";
				$selected_answer = "";
				$correct_array = array();
				$selected_array = array();
				
				//CUtils::LogDataInFile("opans.txt", $ansOptArray, true);
				
				$question_type = $ResultAry[$qIndex]['ques_type'];
				
				if($question_type == CConfig::QT_MATRIX)
				{
					
					$correct_options = implode(",", $ansOptArray);
					$correct_array = $ansOptArray;
					$selected_array = $ResultAry[$qIndex]['selected'];
					
					$selected_answer = implode(",",$ResultAry[$qIndex]['selected']);				
					
				}
				
				else if($question_type == CConfig::QT_INT)
				{
					
					$correct_options = implode(",", $ansOptArray);
				    $selected_answer = implode(",",$ResultAry[$qIndex]['selected']);
				    
				    $correct_array = $ansOptArray;
				    $selected_array = $ResultAry[$qIndex]['selected'];
				    
				    if($selected_answer == "1") // Correct Ans Selected
				    {
				    	$selected_answer = $correct_options;
				    	$selected_array = $correct_array;
				    }

				}
				else
				{
					
					$correct_options = implode(",", $ansAry);
					$correct_array = $ansAry;
					$selected_answer = implode(",",$ResultAry[$qIndex]['selected']);
					$selected_array = $ResultAry[$qIndex]['selected'];									
				}
		
				
				$conclusion = "";
				
				if(count( array_diff($selected_array, $correct_array) ) == 0 && count( array_diff($correct_array, $selected_array) ) == 0)
				{
					$conclusion = "Correct";
					$correct_count++;
				}
				else if(count( array_intersect($selected_array, $correct_array) ) > 0)
				{
					$crt_cnt = 0;
					foreach($selected_array as $selected_value) {
						if(in_array($selected_value, $correct_array)) {
							$crt_cnt++;
						}
						else {
							$crt_cnt = 0;
							break;
						}
					}
					
					if($crt_cnt > 0) {
						$conclusion = "Partial Correct";
						$partial_count++;
					}
					else {
						$conclusion = "Wrong";
						$wrong_count++;
					}
				}
				else if(in_array(-1, $ResultAry[$qIndex]['selected']) || in_array(-2, $ResultAry[$qIndex]['selected']) || in_array(-3, $ResultAry[$qIndex]['selected']))
				{
					$selected_answer = "Not Answered";
					$conclusion = "Not Answered";
					$unans_count++;
				}
				else
				{
					$conclusion = "Wrong";
					$wrong_count++;
					
				}

				//$pdf->MultiCell(190,5, "S. No. |    QID     |  CORRECT OPTION  | YOUR SELECTION | CONCLUSION");
				//$pdf_line = $s_no . "       |   ". $qid."       |  ".$correct_options."            | ".$selected_answer."               |  ".$conclusion;
				//$pdf->MultiCell(190,5, $pdf_line);	
				
				$data[$qIndex] = array($s_no, $qid, $correct_options, $selected_answer, $conclusion);
				
				$qIndex++;
				$secQuesIndex++;
				$topMargin += 80;
				//$pdf->Ln(5);
			}
			
			if($secIndex > 0 && !empty($data))
			{
				$pdf->Ln(5);				
				$this->ImprovedTable($header,$data,$pdf);
				$wrong = $s_no - ($correct_count + $unans_count);
				$summary = "Summary-> Correct:".$correct_count . ", Wrong:".$wrong_count. ", Unanswered:".$unans_count . ", Partial Correct: ". $partial_count;
				$pdf->Ln(2);
				$pdf->Cell(590, 5,$summary);
				//$pdf->MultiCell(190, 5, "Summary: Correct->".$correct_count." Wrong->".$s_no -($correct_count + $unans_count)."Unans->".$unans_count);
			}
			
			
			
			$pdf->Output($file_name);
			}
			
			function ImprovedTable($header, $data,$pdf,$section_name)
			{
				// Column widths
				$w = array(15, 35, 50, 45,50);
				
				
				
				// Header
				for($i=0;$i<count($header);$i++)
					$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
				
					$pdf->Ln();
					// Data
					foreach($data as $row)
					{
					$pdf->Cell($w[0],6,$row[0],'LR');
					$pdf->Cell($w[1],6,$row[1],'LR');
					$pdf->Cell($w[2],6,$row[2],'LR',0,'R');
					$pdf->Cell($w[3],6,$row[3],'LR',0,'R');
					$pdf->Cell($w[4],6,$row[4],'LR',0,'R');
					$pdf->Ln();
					}
					// Closing line
					$pdf->Cell(array_sum($w),0,'','T');
			}
		
	}
	
	
	
	
	
	//$objCreatePDF = new CCreateResultPDF();
	//$objCreatePDF->GenerateEQViewPDF("fb4a3be6-d5c7-53c4-4dfc-cd0c5ade0a47", "", "Mansi Patel", "somebody3@somewhere.com", 5.5);
	//$objCreatePDF->GenerateResultInspectionPDF();
?>