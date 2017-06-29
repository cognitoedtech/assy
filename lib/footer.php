<?php
if (1) {//($user_type != CConfig::UT_INDIVIDAL && $bShowCKEditor == TRUE) {
?>
<div class="modal hide" id="ckeditor_modal_wrapper" tabindex="-1" role="dialog" aria-labelledby="ckeditor_modal_label">
	<div class="modal-dialog modal-lg" role="document" id="ckeditor_modal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" id="ckeditor_modal_btn">
					<i class="fa fa-window-minimize" aria-hidden="true"></i>
				</button>
				<h4 class="modal-title" id="ckeditor_modal_label">Universal <?php echo(CConfig::SNC_SITE_NAME);?> Question/Option Editor</h4>
			</div>
			<div class="modal-body">
				<form>
					<textarea name="ezeeassess_editor" id="ezeeassess_editor" rows="10"
						cols="80">
		                
		            </textarea>
					<script>
		                // Replace the <textarea id="editor1"> with a CKEditor
		                // instance, using default configuration.
		                CKEDITOR.replace( 'ezeeassess_editor' );
		            </script>
				</form>
			</div>
			<div class="modal-footer">
				<span class="form-control-static pull-left"> Copy &amp; Paste your
					content from source above and then paste it to <b>Excel sheet</b>
					or Submit <b>Single Question section's</b>
					Para/Direction/Question/Options
				</span>
			</div>
		</div>
	</div>
</div>
<div id="minimized_ckeditor_panel" class="col-lg-3 col-md-3 col-sm-3 minimized-shown">
	<div class="panel panel-default">
		<div class="panel-heading">
	    	<b style="color:teal;">Universal <?php echo(CConfig::SNC_SITE_NAME);?> Editor <i class="fa fa-hand-o-right" aria-hidden="true"></i></b>
	    	<button type="button" class="btn btn-primary btn-sm pull-right" id="minimized_ckeditor_panel_button" style="margin:-5px;">
				<i class="fa fa-clone" aria-hidden="true"></i>
			</button>
	  	</div>
	</div>
</div>
<?php
}
?>
<div id="footer" class="row text-center">
	<div
		class="col-sm-5 col-md-5 col-lg-5 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
		<br />Copyright &copy; <?php printf("%s", date("Y")); ?> <br /> <b><?php echo(CConfig::SNC_SITE_NAME);?>.co</b>
	</div>
</div>
<?php
if (1) { //($user_type != CConfig::UT_INDIVIDAL && $bShowCKEditor == TRUE) {
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#ckeditor_modal_btn").on("click", function()
		{
			$("#ckeditor_modal_wrapper").removeClass( "show" ).addClass( "hide" );
			$("#minimized_ckeditor_panel").removeClass( "minimized-hidden" ).addClass( "minimized-shown" );
	    });
		
		$("#minimized_ckeditor_panel_button").on("click", function() {   
			$("#minimized_ckeditor_panel").removeClass( "minimized-shown" ).addClass( "minimized-hidden" );
			$("#ckeditor_modal_wrapper").removeClass( "hide" ).addClass( "show" );
		}); 
	});
</script>
<?php 
}
?>