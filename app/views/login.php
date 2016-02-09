<?php
$twitter_login_url = isset($twitter_login_url)?$twitter_login_url:'#';
$sub_data = array();
$sub_data['twitter_login_url'] = $twitter_login_url;
//echo $this->load_view('widgets/jumbotron',$sub_data);
?>
<div class="jumbotron">
	<div class="container">
		<h1>Hello, every one!</h1>
		<p>Please Login with Twitter by clicking this</p>
		<p><a class="btn btn-info btn-lg" href="<?php echo $twitter_login_url; ?>" role="button">Login with twitter</a></p>
	</div>
</div>
