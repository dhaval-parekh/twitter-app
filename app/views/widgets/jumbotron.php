<?php
$twitter_login_url = isset($twitter_login_url)?$twitter_login_url:'#';
?>
<?php if(! isset($_SESSION['user'])): ?>
<div class="jumbotron">
	<div class="container">
		<h1>Hello, every one!</h1>
		<p>Please Login with Twitter by clicking this</p>
		<p><a class="btn btn-info btn-lg" href="<?php echo $twitter_login_url; ?>" role="button">Login with twitter</a></p>
	</div>
</div>
<?php else:  // User is Login in ?>
<div class="jumbotron">
	<div class="container">
		<h1>Hello, every one!</h1>
		<p>Please Login with Twitter by clicking this</p>
		<p><a class="btn btn-info btn-lg" href="<?php echo $twitter_login_url; ?>" role="button">Login with twitter</a></p>
	</div>
</div>
<?php endif; ?>