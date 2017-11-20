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
		
		LoadHolisticCharts: function(data, legend_ary, range_ary)
		{
			var max_marks = 0;
			var total_marks = 0;
			var subIndex = 0;
			var accuracy = 0;
			var speed = 0;
			$.each(data, function(key, value){
				if(key == "max_marks")
				{
					max_marks = value;
				}
				else if(key == "total_marks")
				{
					total_marks = value;
				}
				else if(key == "accuracy")
				{
					accuracy = value;
				}
				else if(key == "speed")
				{
					speed = value;
				}
			});
			var percentageOfMarks = Math.round((total_marks/max_marks) *100);
			var category = "";
			if(percentageOfMarks <= 20)
			{
				category = range_ary[0];
			}
			else if(percentageOfMarks <= 40 && percentageOfMarks >= 21)
			{
				category = range_ary[1];
			}
			else if(percentageOfMarks <= 60 && percentageOfMarks >= 41)
			{
				category = range_ary[2];
			}
			else if(percentageOfMarks <= 70 && percentageOfMarks >= 61)
			{
				category = range_ary[3];
			}
			else if(percentageOfMarks <= 80 && percentageOfMarks >= 71)
			{
				category = range_ary[4];
			}
			else if(percentageOfMarks <= 100 && percentageOfMarks >= 81)
			{
				category = range_ary[5];
			}
			this.PrepareHolisticViewCanvas(data, legend_ary, percentageOfMarks, category);
			var that = this;
			$.each(data, function(key, value){
				if(key != "max_marks" && key != "total_marks" && key != "speed" && key != "accuracy")
				{
					that.LoadSubjectTopicPercentageOverview(value, "hol_sub_"+subIndex, key);
					subIndex++;
				}
			});
			this.LoadAccuracyGuage(accuracy, "accuracy_div");
			this.LoadSpeedGuage(speed, "speed_div");
			this.LoadGaugeChart(percentageOfMarks);
		},
		
		PrepareHolisticViewCanvas: function(data, legend_ary, percentageOfMarks, category)
		{
			$("#holistic_view").empty();
			$("#holistic_view").show();
			
			var sPane = "<h2> Candidate's Performance is "+category+"</h2>";
			sPane += "<div class='row'><div id='overview_gauge' style='height:300px;' class='col-lg-7 col-lg-offset-1'></div>";
			sPane += "<div id='holistic_legends' style='height:300px;' class='col-lg-3'><br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #55BF3B;width:15px; height: 15px;background-color: #55BF3B'></span>&nbsp;&nbsp;"+legend_ary[5]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #4bb2c5;width:15px; height: 15px;background-color: #4bb2c5'></span>&nbsp;&nbsp;"+legend_ary[4]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #DDDF0D;width:15px; height: 15px;background-color: #DDDF0D'></span>&nbsp;&nbsp;"+legend_ary[3]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #FFA500;width:15px; height: 15px;background-color: #FFA500'></span>&nbsp;&nbsp;"+legend_ary[2]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #953579;width:15px; height: 15px;background-color: #953579'></span>&nbsp;&nbsp;"+legend_ary[1]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #DF5353; width:15px; height: 15px;background-color: #DF5353'></span>&nbsp;&nbsp;"+legend_ary[0];
			sPane += "</div></div>";
			sPane += "<div class='row'><hr/></div>";
			sPane += "<div class='row'>";
			sPane +="<h2 style='margin-left: 2%;'>Accuracy and Speed</h2>";
			sPane += "<div class='col-lg-6'>";
			sPane += "<br /><div id='accuracy_div' style='height:200px;'></div>";
			sPane += "</div>";
			sPane += "<div class='col-lg-6'>";
			sPane += "<br /><div id='speed_div' style='height:200px;'></div>";
			sPane += "<div class='col-lg-offset-2'>";
			sPane += "<span style='display:inline-block; border: 1px solid #55BF3B;width:15px; height: 15px;background-color: #55BF3B;position: relative;top: 2px;'></span>&nbsp;Amazing";
			sPane += "&nbsp;&nbsp;&nbsp;<span style='display:inline-block; border: 1px solid #4bb2c5;width:15px; height: 15px;background-color: #4bb2c5;position: relative;top: 2px;'></span>&nbsp;Good";
			sPane += "&nbsp;&nbsp;&nbsp;<span style='display:inline-block; border: 1px solid #DDDF0D;width:15px; height: 15px;background-color: #DDDF0D;position: relative;top: 2px;'></span>&nbsp;Average";
			sPane += "&nbsp;&nbsp;&nbsp;<span style='display:inline-block; border: 1px solid #FFA500;width:15px; height: 15px;background-color: #FFA500;position: relative;top: 2px;'></span>&nbsp;Slow";
			sPane += "&nbsp;&nbsp;&nbsp;<span style='display:inline-block; border: 1px solid #DF5353; width:15px; height: 15px;background-color: #DF5353;position: relative;top: 2px;'></span>&nbsp;Very Slow";
			sPane += "</div>";
			sPane += "</div>";
			sPane += "</div>";
			sPane += "<div class='row'><hr/></div>";
			
			var subIndex = 0;
			$.each(data, function(key, value){
				if(key != "max_marks" && key != "total_marks" && key != "speed" && key != "accuracy")
				{
					sPane += "<h2>Performance in "+key+"</h2>";
					sPane += "<div id='hol_sub_"+subIndex+"' class='col-lg-offset-2' align='center' style='width: 70%;height:300px;'></div>";
					sPane += "<hr/>";
					subIndex++
				}
			});
			$("#holistic_view").append(sPane);
		},
		
		LoadGaugeChart: function(percentageOfMarks)
		{
			var y_min = 0;
			var y_max = 100;
			if(percentageOfMarks < 0)
			{
				y_min = (parseInt(percentageOfMarks/10) * 10) - 10;
				y_max = 100 + y_min;
			}
			$(function () {
			    $('#overview_gauge').highcharts({

			        chart: {
			            type: 'gauge',
			            plotBackgroundColor: null,
			            plotBackgroundImage: null,
			            plotBorderWidth: 0,
			            plotShadow: false
			        },

			        title: {
			            text: ''
			        },

			        pane: {
			            startAngle: -150,
			            endAngle: 150,
			            background: [{
			                backgroundColor: {
			                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			                    stops: [
			                        [0, '#FFF'],
			                        [1, '#333']
			                    ]
			                },
			                borderWidth: 0,
			                outerRadius: '109%'
			            }, {
			                backgroundColor: {
			                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			                    stops: [
			                        [0, '#333'],
			                        [1, '#FFF']
			                    ]
			                },
			                borderWidth: 1,
			                outerRadius: '107%'
			            }, {
			                // default background
			            }, {
			                backgroundColor: '#DDD',
			                borderWidth: 0,
			                outerRadius: '105%',
			                innerRadius: '103%'
			            }]
			        },

			        // the value axis
			        yAxis: {
			            min: y_min,
			            max: y_max,

			            minorTickInterval: 'auto',
			            minorTickWidth: 1,
			            minorTickLength: 10,
			            minorTickPosition: 'inside',
			            minorTickColor: '#666',

			            tickPixelInterval: 30,
			            tickWidth: 2,
			            tickPosition: 'inside',
			            tickLength: 10,
			            tickColor: '#666',
			            labels: {
			                step: 2,
			                rotation: 'auto'
			            },
			            title: {
			                text: 'Percentage'
			            },
			            plotBands: [{
			                from: y_min,
			                to: 20,
			                color: '#DF5353' // red
			            }, {
			                from: 20,
			                to: 40,
			                color: '#953579' 
			            },{
			                from: 40,
			                to: 60,
			                color: '#FFA500' 
			            },{
			                from: 60,
			                to: 70,
			                color: '#DDDF0D' // yellow
			            }, {
			                from: 70,
			                to: 80,
			                color: '#4bb2c5'
			            },{
			                from: 80,
			                to: 100,
			                color: '#55BF3B' // green
			            }]
			        },

			        series: [{
			            name: 'Percentage',
			            data: [percentageOfMarks],
			            tooltip: {
			                valueSuffix: ' %'
			            }
			        }]

			    });
			});
		},
		
		LoadAccuracyGuage: function(accuracy, accuracy_div_id)
		{
			var accuracyColorAry = new Array();
			for(var stop = 0.01; stop <= 1.00; stop+=0.01)
			{
				var colorCode = "#fff";
				if(stop <= 0.21)
				{
					colorCode = "#DF5353";
				}
				else if(stop <= 0.41)
				{
					colorCode = "#953579";
				}
				else if(stop <= 0.61)
				{
					colorCode = "#FFA500";
				}
				else if(stop <= 0.71)
				{
					colorCode = "#DDDF0D";
				}
				else if(stop <= 0.81)
				{
					colorCode = "#4bb2c5";
				}
				else if(stop <= 1.00)
				{
					colorCode = "#55BF3B";
				}
				
				accuracyColorAry.push([stop, colorCode]);
			}
			
			$(function () {

			    var gaugeOptions = {

			        chart: {
			            type: 'solidgauge'
			        },

			        title: null,

			        pane: {
			            center: ['50%', '85%'],
			            size: '140%',
			            startAngle: -90,
			            endAngle: 90,
			            background: {
			                backgroundColor: '#fff',
			                innerRadius: '60%',
			                outerRadius: '100%',
			                shape: 'arc'
			            }
			        },

			        tooltip: {
			            enabled: false
			        },

			        // the value axis
			        yAxis: {
			        	minColor: '#fff',
			        	maxColor: '#fff',
			            stops: accuracyColorAry,
			            lineWidth: 0,
			            minorTickInterval: null,
			            tickPixelInterval: 400,
			            tickWidth: 0,
			            title: {
			                y: -70
			            },
			            labels: {
			                y: 16
			            }
			        },

			        plotOptions: {
			            solidgauge: {
			                dataLabels: {
			                    y: 5,
			                    borderWidth: 0,
			                    useHTML: true
			                }
			            }
			        }
			    };

			    // The speed gauge
			    $('#'+accuracy_div_id).highcharts(Highcharts.merge(gaugeOptions, {
			        yAxis: {
			            min: 0,
			            max: 100,
			            title: {
			                text: 'Accuracy'
			            }
			        },

			        credits: {
			            enabled: false
			        },

			        series: [{
			            name: 'Accuracy',
			            data: [accuracy],
			            dataLabels: {
			                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
			                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}%</span></div>'
			            },
			            tooltip: {
			                valueSuffix: '%'
			            }
			        }]

			    }));
			});
		},
		
		LoadSpeedGuage: function(speed, speed_div_id)
		{
			var speed_category = "Very Slow";
			var spped_to_show = speed;
			if(speed <= 50)
			{
				speed_category = "Very Slow";
				spped_to_show = 16;
			}
			else if(speed > 50 && speed <= 66)
			{
				speed_category = "Slow";
				spped_to_show = 33;
			}
			else if(speed > 66 && speed <= 83)
			{
				speed_category = "Average";
				spped_to_show = 50;
			}
			else if(speed > 83 && speed <= 100)
			{
				speed_category = "Good";
				spped_to_show = 75;
			}
			else if(speed > 100)
			{
				speed_category = "Amazing";
				spped_to_show = 100;
			}
		
			var colorAry = new Array();
			for(var stop = 0.01; stop <= 1.00; stop+=0.01)
			{
				var colorCode = "#fff";
				if(stop <= 0.17)
				{
					colorCode = "#DF5353";
				}
				else if(stop <= 0.34)
				{
					colorCode = "#FFA500";
				}
				else if(stop <= 0.51)
				{
					colorCode = "#DDDF0D";
				}
				else if(stop <= 0.76)
				{
					colorCode = "#4bb2c5";
				}
				else if(stop <= 1.00)
				{
					colorCode = "#55BF3B";
				}
				
				colorAry.push([stop, colorCode]);
			}
			
			$(function () {

			    var gaugeOptions = {

			        chart: {
			            type: 'solidgauge'
			        },

			        title: null,

			        pane: {
			            center: ['50%', '85%'],
			            size: '140%',
			            startAngle: -90,
			            endAngle: 90,
			            background: {
			                backgroundColor: '#fff',
			                innerRadius: '60%',
			                outerRadius: '100%',
			                shape: 'arc'
			            }
			        },

			        tooltip: {
			            enabled: false
			        },

			        // the value axis
			        yAxis: {
			        	minColor: '#fff',
			        	maxColor: '#fff',
			            stops: colorAry,
			            lineWidth: 0,
			            minorTickInterval: null,
			            tickPixelInterval: 400,
			            tickWidth: 0,
			            title: {
			                y: -70
			            },
			            labels: {
			                y: 16
			            }
			        },

			        plotOptions: {
			            solidgauge: {
			                dataLabels: {
			                    y: 5,
			                    borderWidth: 0,
			                    useHTML: true
			                }
			            }
			        }
			    };

			    // The speed gauge
			    $('#'+speed_div_id).highcharts(Highcharts.merge(gaugeOptions, {
			        yAxis: {
			            min: 0,
			            max: 100,
			            showFirstLabel: false,
			            showLastLabel: false,
			            title: {
			                text: 'Speed'
			            }
			        },

			        credits: {
			            enabled: false
			        },

			        series: [{
			            name: 'Speed',
			            data: [spped_to_show],
			            dataLabels: {
			                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
			                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+speed_category+'</span></div>'
			            },
			            tooltip: {
			                valueSuffix: ' '
			            }
			        }]

			    }));
			});
		},
		
		LoadSubjectTopicPercentageOverview : function(subject_data, chart_container_id , subject_name)
		{
			var subjectTopicData = new Array();
			
			var subject_max_marks = 0;
			var subject_total_marks = 0;
			var range_color = "#fff";
			$.each(subject_data, function(key, value){
				if(key == "max_marks")
				{
					subject_max_marks = value;
				}
				else if(key == "total_marks")
				{
					subject_total_marks = value;
				}
				else
				{
					var topic_max_marks = 0;
					var topic_total_marks = 0;
					$.each(value, function(topic_key, topic_value){
						if(topic_key == "max_marks")
						{
							topic_max_marks = topic_value;
						}
						else if(topic_key == "total_marks")
						{
							topic_total_marks = topic_value;
						}
					});
					var topicPercentageOfMarks = Math.round((topic_total_marks/topic_max_marks) *100);
					if(topicPercentageOfMarks <= 20)
					{
						range_color = "#DF5353";
					}
					else if(topicPercentageOfMarks <= 40 && topicPercentageOfMarks >= 21)
					{
						range_color = "#953579";
					}
					else if(topicPercentageOfMarks <= 60 && topicPercentageOfMarks >= 41)
					{
						range_color = "#FFA500";
					}
					else if(topicPercentageOfMarks <= 70 && topicPercentageOfMarks >= 61)
					{
						range_color = "#DDDF0D";
					}
					else if(topicPercentageOfMarks <= 80 && topicPercentageOfMarks >= 71)
					{
						range_color = "#4bb2c5";
					}
					else if(topicPercentageOfMarks <= 100 && topicPercentageOfMarks >= 81)
					{
						range_color = "#55BF3B";
					}
					subjectTopicData.push({y : topicPercentageOfMarks, label : key, color: range_color});
				}
			});
			
			/*subjectTopicData.push({y : 0, label : "  ", color: "#953579"});
			/subjectTopicData.push({y : 0, label : " ", color: "#953579"});*/
			
			var percentageOfMarks = Math.round((subject_total_marks/subject_max_marks) *100);
			if(percentageOfMarks <= 20)
			{
				range_color = "#DF5353";
			}
			else if(percentageOfMarks <= 40 && percentageOfMarks >= 21)
			{
				range_color = "#953579";
			}
			else if(percentageOfMarks <= 60 && percentageOfMarks >= 41)
			{
				range_color = "#FFA500";
			}
			else if(percentageOfMarks <= 70 && percentageOfMarks >= 61)
			{
				range_color = "#DDDF0D";
			}
			else if(percentageOfMarks <= 80 && percentageOfMarks >= 71)
			{
				range_color = "#4bb2c5";
			}
			else if(percentageOfMarks <= 100 && percentageOfMarks >= 81)
			{
				range_color = "#55BF3B";
			}
			subjectTopicData.push({y : percentageOfMarks, label : "Subject - "+subject_name, color: range_color});
			var chart = new CanvasJS.Chart(chart_container_id,
				    {
					  theme: "theme3",
				      title:{
				        text: ""
				      },
				      axisY:{
				    	interval: 10,
				    	title: "Percentage(%)"
				      },
				      data: [
				      {
				        type: "bar",
				        dataPoints: subjectTopicData
				      }
				      ]
				    });

				chart.render();
		},
		
		LoadIQResultCharts: function(data, image_url, iq_legend_ary, iq_range_ary)
		{
			var IQ_Val = 0;
			$.each(data, function(key, value){
				if(key == "iq")
				{
					IQ_Val = value;
					return;
				}
			});
			
			var category = "";
			if(IQ_Val <= 69)
			{
				category = iq_range_ary[0];
			}
			else if(IQ_Val <= 79 && IQ_Val >= 70)
			{
				category = iq_range_ary[1];
			}
			else if(IQ_Val <= 89 && IQ_Val >= 80)
			{
				category = iq_range_ary[2];
			}
			else if(IQ_Val <= 109 && IQ_Val >= 90)
			{
				category = iq_range_ary[3];
			}
			else if(IQ_Val <= 119 && IQ_Val >= 110)
			{
				category = iq_range_ary[4];
			}
			else if(IQ_Val <= 129 && IQ_Val >= 120)
			{
				category = iq_range_ary[5];
			}
			else if(IQ_Val >= 130)
			{
				category = iq_range_ary[6];
			}
			
			this.PrepareIQViewCanvas(data, image_url, iq_legend_ary, iq_range_ary, IQ_Val, category)
			
			var subIndex = 0;
			var that = this;
			$.each(data, function(key, value){
				if(key != "total_marks" && key != "max_marks" && key != "iq" && key != "accuracy")
				{
					that.LoadSubjectTopicPercentageOverview(value, "iq_sub_"+subIndex, key);
					subIndex++;
				}
			});
		},
		
		PrepareIQViewCanvas: function(data, image_url, iq_legend_ary, iq_range_ary, IQ_Val, category)
		{
			$("#iq_view").empty();
			$("#iq_view").show();
			
			var sPane = "<h2> Candidate's IQ Score is - "+IQ_Val+" </h2>";
			sPane += "<div class='row'>";
			sPane += "<div id='overview_iq' align='center' style='height:370px;' class='col-lg-7 col-lg-offset-1'>";
			sPane += "<br /><img style='height: 350px;' src='"+image_url+"' />";
			sPane += "</div>";
			sPane += "<div id='iq_legends' style='height:300px;' class='col-lg-3'><br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #55BF3B;width:15px; height: 15px;background-color: #55BF3B'></span>&nbsp;&nbsp;"+iq_legend_ary[6]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #4bb2c5;width:15px; height: 15px;background-color: #4bb2c5'></span>&nbsp;&nbsp;"+iq_legend_ary[5]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #DDDF0D;width:15px; height: 15px;background-color: #DDDF0D'></span>&nbsp;&nbsp;"+iq_legend_ary[4]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #FFA500;width:15px; height: 15px;background-color: #FFA500'></span>&nbsp;&nbsp;"+iq_legend_ary[3]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #953579;width:15px; height: 15px;background-color: #953579'></span>&nbsp;&nbsp;"+iq_legend_ary[2]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #DF5353; width:15px; height: 15px;background-color: #DF5353'></span>&nbsp;&nbsp;"+iq_legend_ary[1]+"<br /><br />";
			sPane += "<span style='display:inline-block; border: 1px solid #C0C0C0;width:15px; height: 15px;background-color: #C0C0C0'></span>&nbsp;&nbsp;"+iq_legend_ary[0];
			sPane += "</div></div>";
			sPane += "<div class='row'><hr/></div>";
			
			var subIndex = 0;
			$.each(data, function(key, value){
				if(key != "total_marks" && key != "max_marks" && key != "iq" && key != "accuracy")
				{
					sPane += "<h2>Performance in "+key+"</h2>";
					sPane += "<div id='iq_sub_"+subIndex+"' class='col-lg-offset-2' align='center' style='width: 70%;height:300px;'></div>";
					sPane += "<hr/>";
					subIndex++
				}
			});
			$("#iq_view").append(sPane);
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