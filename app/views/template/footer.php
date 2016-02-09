<?php
	$src_url = rtrim(APP_URL,'/').'/views/';
	echo $this->load_view('widgets/modal-download-tweets');
?>

<script type="text/javascript" src="<?php echo $src_url; ?>src/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $src_url; ?>src/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $src_url; ?>src/js/custom.js"></script>
</body>
</html>