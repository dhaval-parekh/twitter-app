<?php 
	if(! isset($_SESSION['user'])){ return false; } 
	$base_url = rtrim(getBaseUrl(),'/').'/';
	$download_url = $base_url.'download/tweets/?format=';
?>
<div id="modalDownloadTweets" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Download</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-6"><a href="<?php echo $download_url.'json'; ?>" target="_blank" class="btn btn-primary btn-block">JSON</a></div>
					<div class="col-xs-6"><a href="<?php echo $download_url.'xml'; ?>" target="_blank" class="btn btn-primary btn-block">XML</a></div>
				</div>
				<div class="row margin-top-xs">
					<div class="col-xs-6"><a href="<?php echo $download_url.'csv'; ?>" target="_blank" class="btn btn-primary btn-block">CSV</a></div>
					<div class="col-xs-6"><a href="<?php echo $download_url.'xls'; ?>" target="_blank" class="btn btn-primary btn-block">XLS</a></div>
				</div>
			</div>
			<?php /*?><div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div><?php */?>
		</div>
	</div>
</div>
