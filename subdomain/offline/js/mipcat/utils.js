var objUtils = function()
{
	return {
		dump: function(arr,level) {
			var dumped_text = "";
			if(!level) level = 0;
			
			//The padding given at the beginning of the line.
			var level_padding = "";
			for(var j=0;j<level+1;j++) level_padding += "    ";
			
			if(typeof(arr) == 'object') { //Array/Hashes/Objects 
				for(var item in arr) {
					var value = arr[item];
					
					if(typeof(value) == 'object') { //If it is an array,
						dumped_text += level_padding + "'" + item + "' ...\n";
						dumped_text += dump(value,level+1);
					} else {
						dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
					}
				}
			} else { //Stings/Chars/Numbers etc.
				dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
			}
			return dumped_text;
		},
		
		AdjustHeight: function(elemID, nMargin)
		{
			if(typeof(nMargin)==='undefined') nMargin = 100;
			//alert("#"+elemID);
			var contentHeight = $("#"+elemID)[0].scrollHeight;
			var prntID = $("#"+elemID).parent("div").attr("id");
			
			//alert("CH: "+contentHeight);
			//alert("CurID: "+elemID);
			//alert("PrntID: "+prntID);
			
			while(prntID !== undefined)
			{
				contentHeight += nMargin;
				$("#"+prntID).height(contentHeight);
				
				prntID = $("#"+prntID).parent("div").attr("id");
				//alert("Test: "+prntID);
			}
			
			contentHeight += nMargin;
			
			return contentHeight;
		},
		
		IFLoadPage: function( url) 
		{
			if(url.length > 0)
			{
				var rand_t = new Date().getTime();
				
				url += "?noisrev="+rand_t;
				$('#platform').attr('src',url);
			}
		},
		
		toHHMMSS: function ( val )
		{
		    sec_numb    = parseInt(val);
		    var hours   = Math.floor(sec_numb / 3600);
		    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
		    var seconds = sec_numb - (hours * 3600) - (minutes * 60);
		
		    if (hours   < 10) {hours   = "0"+hours;}
		    if (minutes < 10) {minutes = "0"+minutes;}
		    if (seconds < 10) {seconds = "0"+seconds;}
		    var time    = hours+':'+minutes+':'+seconds;
		    return time;
		},
		
		ucfirst: function (string)
		{
		    return string.charAt(0).toUpperCase() + string.slice(1);
		}
	};
}();