var objRAna = function()
{
	return {
		LoadCharts: function(arySection, arySubject, aryTopic, 
							arySecCorrect, arySecWrong, arySecUnanswered, 
							arySubCorrect, arySubWrong, arySubUnanswered,
							aryTpcCorrect, aryTpcWrong, aryTpcUnanswered,
							aryDifCorrect, aryDifWrong, aryDifUnanswered,
							nTotalCorrectAns, nTotalWrongAns, nTotalUnanswered)
		{
			this.PrepareCanvas(arySubject, aryTopic);
			var tickIntervalValue = Math.ceil((nTotalCorrectAns+nTotalWrongAns+nTotalUnanswered)/10);
			this.LoadFinalPie(nTotalCorrectAns, nTotalWrongAns, nTotalUnanswered);
			this.LoadSectionOverview(arySecCorrect, arySecWrong, arySecUnanswered, arySection, tickIntervalValue);
			this.LoadSubjectOverview(arySubCorrect, arySubWrong, arySubUnanswered, arySubject, tickIntervalValue);
			
			for (subIndex in arySubject)
			{
				this.LoadTopicOverview(aryTpcCorrect[arySubject[subIndex]], aryTpcWrong[arySubject[subIndex]], aryTpcUnanswered[arySubject[subIndex]], aryTopic[arySubject[subIndex]], arySubject[subIndex], "sub_topic_chart_"+subIndex, tickIntervalValue);
				
				for(topIndex in aryTopic[arySubject[subIndex]])
				{
					this.LoadTopicDiffOverview(aryDifCorrect[arySubject[subIndex]][aryTopic[arySubject[subIndex]][topIndex]], aryDifWrong[arySubject[subIndex]][aryTopic[arySubject[subIndex]][topIndex]], aryDifUnanswered[arySubject[subIndex]][aryTopic[arySubject[subIndex]][topIndex]], aryTopic[arySubject[subIndex]][topIndex], arySubject[subIndex], "topic_diff_chart_"+subIndex+"_"+topIndex, tickIntervalValue);
				}
			}
		},
		
		PrepareCanvas:function(arySubject, aryTopic)
		{
			$("#result_charts").empty();
			$("#result_charts").show();
			
			var sPane = "<h2> Overall Performance Overview </h2>";
			sPane += "<div id='overview_pie' align='center' style='height:240px;'></div>";
			sPane += "<hr/>";
			sPane += "<h2> Sectional Overview </h2>";
			sPane += "<div class='col-lg-offset-2' id='section_chart' align='center' style='width: 70%;height:300px;'></div>";
			sPane += "<hr/>";
			sPane += "<h2> Subject Overview </h2>";
			sPane += "<div class='col-lg-offset-2' id='subject_chart' align='center' style='width: 70%;height:300px;'></div>";
			
			for (subIndex in arySubject)
			{
				sPane += "<hr/>";
				sPane += "<h3>Performance in Subject - "+arySubject[subIndex]+"</h3>";
				sPane += "<div id='sub_topic_chart_"+subIndex+"' align='center' style='width: 100%;height:300px;'></div>";
				for(topIndex in aryTopic[arySubject[subIndex]])
				{
					sPane += "<hr/>";
					sPane += "<h3>Performance in Topic - "+aryTopic[arySubject[subIndex]][topIndex]+"</h3>";
					sPane += "<div class='col-lg-offset-2' id='topic_diff_chart_"+subIndex+"_"+topIndex+"' align='center' style='width: 70%;height:300px;'></div>";
				}
			}
			
			$("#result_charts").append(sPane);
		},
		
		LoadFinalPie: function(nTotalCorrectAns, nTotalWrongAns, nTotalUnanswered)
		{
			
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

		                "#4bb2c5",
		                "#eaa228",
		                "#c5b47f"                
		            ]);
			var chart = new CanvasJS.Chart("overview_pie",
				    {
					  colorSet: "customColors",
					  legend: {
					       fontSize: 15
					  },
				      data: [
				      {
				         type: "doughnut",
				         indexLabelFontSize: 15,
				       showInLegend: true,
				       dataPoints: [
				       {  y: nTotalCorrectAns, legendText:"Correct", indexLabel: "Correct", exploded: true  },
				       {  y: nTotalWrongAns, legendText:"Wrong", indexLabel: "Wrong", exploded: true  },
				       {  y: nTotalUnanswered, legendText:"Unanswered", indexLabel: "Unanswered", exploded: true  }
				       ]
				     }
				     ]
				   });

				    chart.render();
		},
		
		LoadSectionOverview: function(arySecCorrect, arySecWrong, arySecUnanswered, arySection, tickIntervalValue)
		{
			
			var correctDPS = new Array();
			
			for(var i = 0; i < arySecCorrect.length; i++)
			{
				correctDPS.push({y : arySecCorrect[i], label : arySection[i]});
			}
			
			var wrongDPS = new Array();
			
			for(var i = 0; i < arySecWrong.length; i++)
			{
				wrongDPS.push({y : arySecWrong[i], label : arySection[i]});
			}
			
			var unansweredDPS = new Array();
			
			for(var i = 0; i < arySecUnanswered.length; i++)
			{
				unansweredDPS.push({y : arySecUnanswered[i], label : arySection[i]});
			}
			
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

		                "#4bb2c5",
		                "#eaa228",
		                "#c5b47f"                
		            ]);
			var chart = new CanvasJS.Chart("section_chart",
				    {
					  theme: "theme3",
					  colorSet: "customColors",
					  axisX:{
						  labelAngle: 150,
					  },
					  legend: {
					       fontSize: 15
					  },
				      data: [
				      {
				    	type: "column",
				        name: "Correct",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: correctDPS
				      },
				      {
				    	type: "column",
					    name: "Wrong",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: wrongDPS
				      },
				      {
				    	type: "column",
					    name: "Unanswered",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: unansweredDPS
				      }
				      ]
				    });

				chart.render();
		},
		
		LoadSubjectOverview: function(arySubCorrect, arySubWrong, arySubUnanswered, arySubject, tickIntervalValue)
		{
			
			var correctDPS = new Array();
			
			for(var i = 0; i < arySubCorrect.length; i++)
			{
				correctDPS.push({y : arySubCorrect[i], label : arySubject[i]});
			}
			
			var wrongDPS = new Array();
			
			for(var i = 0; i < arySubWrong.length; i++)
			{
				wrongDPS.push({y : arySubWrong[i], label : arySubject[i]});
			}
			
			var unansweredDPS = new Array();
			
			for(var i = 0; i < arySubUnanswered.length; i++)
			{
				unansweredDPS.push({y : arySubUnanswered[i], label : arySubject[i]});
			}
			
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

		                "#4bb2c5",
		                "#eaa228",
		                "#c5b47f"                
		            ]);
			var chart = new CanvasJS.Chart("subject_chart",
				    {
					  theme: "theme3",
					  colorSet: "customColors",
					  axisX:{
						  labelAngle: 150,
					  },
					  legend: {
					       fontSize: 15
					  },
				      data: [
				      {
				    	type: "column",
				        name: "Correct",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: correctDPS
				      },
				      {
				    	type: "column",
					    name: "Wrong",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: wrongDPS
				      },
				      {
				    	type: "column",
					    name: "Unanswered",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: unansweredDPS
				      }
				      ]
				    });

				chart.render();
		},
		
		LoadTopicOverview: function(aryTpcCorrect, aryTpcWrong, aryTpcUnanswered, aryTopic, sSubjectName, ChartID, tickIntervalValue)
		{
			var correctDPS = new Array();
			
			for(var i = 0; i < aryTpcCorrect.length; i++)
			{
				correctDPS.push({y : aryTpcCorrect[i], label : aryTopic[i]});
			}
			
			var wrongDPS = new Array();
			
			for(var i = 0; i < aryTpcWrong.length; i++)
			{
				wrongDPS.push({y : aryTpcWrong[i], label : aryTopic[i]});
			}
			
			var unansweredDPS = new Array();
			
			for(var i = 0; i < aryTpcUnanswered.length; i++)
			{
				unansweredDPS.push({y : aryTpcUnanswered[i], label : aryTopic[i]});
			}
			
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

		                "#4bb2c5",
		                "#eaa228",
		                "#c5b47f"                
		            ]);
			var chart = new CanvasJS.Chart(ChartID,
				    {
					  theme: "theme3",
					  colorSet: "customColors",
					  axisX:{
						  labelAngle: 150,
					  },
					  legend: {
					       fontSize: 15
					  },
				      data: [
				      {
				    	type: "column",
				        name: "Correct",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: correctDPS
				      },
				      {
				    	type: "column",
					    name: "Wrong",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: wrongDPS
				      },
				      {
				    	type: "column",
					    name: "Unanswered",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: unansweredDPS
				      }
				      ]
				    });

				chart.render();
		},
		
		LoadTopicDiffOverview: function(aryDifCorrect, aryDifWrong, aryDifUnanswered, sTopicName, sSubjectName, ChartID, tickIntervalValue)
		{
			var ticks = ["Easy","Moderate","Hard"];
			
			var correctDPS = new Array();
			
			for(var i = 0; i < aryDifCorrect.length; i++)
			{
				correctDPS.push({y : aryDifCorrect[i], label : ticks[i]});
			}
			
			var wrongDPS = new Array();
			
			for(var i = 0; i < aryDifWrong.length; i++)
			{
				wrongDPS.push({y : aryDifWrong[i], label : ticks[i]});
			}
			
			var unansweredDPS = new Array();
			
			for(var i = 0; i < aryDifUnanswered.length; i++)
			{
				unansweredDPS.push({y : aryDifUnanswered[i], label : ticks[i]});
			}
			
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

		                "#4bb2c5",
		                "#eaa228",
		                "#c5b47f"                
		            ]);
			var chart = new CanvasJS.Chart(ChartID,
				    {
					  theme: "theme3",
					  colorSet: "customColors",
					  axisX:{
						  labelAngle: 150,
					  },
					  legend: {
					       fontSize: 15
					  },
				      data: [
				      {
				    	type: "column",
				        name: "Correct",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: correctDPS
				      },
				      {
				    	type: "column",
					    name: "Wrong",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: wrongDPS
				      },
				      {
				    	type: "column",
					    name: "Unanswered",
				        indexLabelFontSize: 15,
				        showInLegend: true,
				        dataPoints: unansweredDPS
				      }
				      ]
				    });

				chart.render();
		},
		
		OnChartClick: function(sChart, nPoint, nSeries, aryRef, sSubjectName)
		{
			// nPoint:  denotes section/subject/topic index in barcharts, in case of sChart = 'topic_perf'
			//			nPoint denotes Easy: 0, Moderate: 1, Hard: 2, in case of sChart = 'topic_perf'
			//			nPoint denotes correct:0, wrong:1, unanswered:2
			// nSeries: denotes correct:0, wrong:1, unanswered:2
			// sSTName:	denotes Subject (sChart='subject_overview') or Topic Name (sChart='topic_perf')
			//alert("Chart Name: " + sChart + ", Point: " + nPoint + ", Series: " + nSeries + ", AryRef: "+ aryRef +", Subject Name: "+ sSubjectName);
			
			var test_pnr = $('#dr_candidate_id').val();
			var ajaxUrl = "ajax/ajax_get_question_slider_data.php?";
			
			if(sChart.toLowerCase() == "test_overview")
			{
				ajaxUrl += $.param({ testpnr: test_pnr,
									 chart: sChart,
						        	 query: nPoint});
			}
			else if(sChart.toLowerCase() == "section_overview")
			{
				ajaxUrl += $.param({ testpnr: test_pnr,
									 chart: sChart,
						        	 query: nSeries,
									 reference_0: aryRef[nPoint]});
			}
			else if(sChart.toLowerCase() == "subject_overview")
			{
				ajaxUrl += $.param({ testpnr: test_pnr,
									 chart: sChart,
						        	 query: nSeries,
									 reference_0: aryRef[nPoint]});
			}
			else if(sChart.toLowerCase() == "topic_overview")
			{
				ajaxUrl += $.param({ testpnr: test_pnr,
									 chart: sChart,
						        	 query: nSeries,
						        	 reference_0: sSubjectName,
									 reference_1: aryRef[nPoint]});
			}
			else if(sChart.toLowerCase() == "topic_perf")
			{
				ajaxUrl += $.param({ testpnr: test_pnr,
									 chart: sChart,
						        	 query: nSeries,
									 difficulty: (nPoint+1),
									 reference_0: sSubjectName,
									 reference_1: aryRef});
			}
			
			$("#overlay_box").load(ajaxUrl, function(){
				var sFtr = "<p style='color:#666;text-align:right;margin:5px'>";
				sFtr += "To close, click the Close button or hit the ESC key.<br/>";
				sFtr += "<button onclick=\"$('#overlay_box').overlay().close()\"> Close </button>";
				sFtr += "</p>";
				
				$("#overlay_box").append(sFtr);
				$("#overlay_box").css({
	                'top': 0, 'margin-top': parent.window.pageYOffset
	            }).overlay().load();
			});
		}
	};
}();