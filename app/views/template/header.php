<?php
$src_url = rtrim(APP_URL,'/').'/views/';
$base_url = rtrim(getBaseurl(),'/').'/';
?>
<html>
<head>
	<title><?php echo SITE_NAME; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<link type="text/css" rel="stylesheet" href="<?php echo $src_url; ?>src/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="<?php echo $src_url; ?>src/css/bootstrap-theme.min.css">
	<link type="text/css" rel="stylesheet" href="<?php echo $src_url; ?>src/css/style.css">
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
		var api_url = '<?php echo rtrim(API_URL,'/').'/'; ?>';
		var base_url = '<?php echo getBaseUrl(); ?>';
		var absolute_base_url = '<?php echo rtrim(BASE_URL,'/').'/'; ?>';
		var user = Object();
		<?php 
			if(isset($_SESSION['user'])){
				echo "user.id =  '".$_SESSION['user']['id_str']."';   ";	
				echo "user.name =  '".$_SESSION['user']['name']."';   ";	
				echo "user.screen_name =  '".$_SESSION['user']['screen_name']."';   ";	
				echo "user.location =  '".$_SESSION['user']['location']."';   ";	
				echo "user.description =  '".$_SESSION['user']['description']."';   ";	
				echo "user.url =  '".$_SESSION['user']['url']."';   ";	
				//echo "user.id =  '".$_SESSION['user']['']."';   ";	
			}
		?>
	</script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container"> 
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-nav"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
			<a class="navbar-brand" href="<?php echo getBaseurl(); ?>"><?php echo SITE_NAME; ?></a> 
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="header-nav">
			<ul class="nav navbar-nav navbar-right">
				<li> <a href="<?php echo $base_url; ?>">Home</a> </li>
				<li> <a href="<?php echo $base_url.'about/'; ?>">About me</a> </li>
				<?php if(isset($_SESSION['access_token'])): ?>
					<li> <a href="<?php echo $base_url.'logout/'; ?>">Logout</a> </li>
				<?php endif; ?>
			</ul>
		</div>
		<!-- /.navbar-collapse --> 
	</div>
	<!-- /.container --> 
</nav>

