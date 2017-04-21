<script type="text/javascript">
	var scrWidth = 800;
	$(window).load(function() {
        if($(window).width() < scrWidth)
		{
			$('body').width(scrWidth);
		}
		else
		{
			$('body').width('99.2%');
		}
		
		$('#hmenu_logout').popover();
    });
    
    $(window).resize(function() {
		if($(window).width() < scrWidth)
		{
			$('body').width(scrWidth);
		}
		else
		{
			$('body').width('99.2%');
		}
	});
	
	// Google Analytics Code.
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-2246912-16', 'mipcat.com');
  ga('send', 'pageview');
</script>