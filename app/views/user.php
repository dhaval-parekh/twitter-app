<?php 
	$default_color = '55ACEE';
	$user_info = (array) $_SESSION['user'];
	$user = array();
	$user['name'] = $user_info['name'];
	$user['screen_name'] = $user_info['screen_name'];
	$user['location'] = $user_info['location'];
	
	$user['description'] = $user_info['description'];
	$user['url'] = $user_info['url'];
	// banner 
	$user['banner_image'] = isset($user_info['profile_banner_url'])?$user_info['profile_banner_url']:'';
	
	// link color =
	$user['color'] = $user_info['profile_link_color'];
	
	// profile 
	$user['profile_pic'] = $user_info['profile_image_url'];
	
	$user['followers'] = isset($user_info['followers_count'])&&is_numeric($user_info['followers_count'])?$user_info['followers_count']:0;
	$user['following'] = isset($user_info['following'])&&is_numeric($user_info['following'])?$user_info['following']:0;
?>
<div class="jumbotron profile-banner">
	<div class="profile-banner-image-container">
		<img class="profile-banner-image" src="<?php echo $user['banner_image']; ?>" alt="<?php echo $user['name']; ?>">
	</div> 
	<div class="banner-bottom-bar navbar navbar-default">
		<div class="container">
			<div class="row">
				<div class="col-sm-2">
					<div class="profile-image-container ">
						<img src="<?php echo $user['profile_pic']; ?>" data-toggle="collapse" data-target="#profile-nav" alt="<?php echo $user['name']; ?>">
					</div>
				</div>
				<div class="col-sm-10">
					<div id="profile-nav" class="collapse navbar-collapse profile-nav">
						<ul class="nav navbar-nav ">
							<li class="active"><a href="#">Tweets</a></li>
							<li><a href="#">Follower (<?php echo $user['followers']; ?>)</a></li>
							<li><a href="#">Following (<?php echo $user['following']; ?>)</a></li>
							<?php /*?><li><a href="#">Likes</a></li><?php */?>
						</ul>
						<ul class="nav navbar-nav pull-right">
							<li ><a href="javascript:" onClick="site.openDownloadModal();" title="Download All Tweets"><i class="glyphicon glyphicon-cloud-download"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<section class="main-section">
	<div class="container">
		<div class="row">
			<!-- User Twitter Slider -->
			<div class="col-sm-9 user-tweets-slider-container loader-container">
				<div class="loader"><i class="glyphicon glyphicon-refresh "></i></div>
				<?php echo $this->load_view('widgets/tweet-slider',array('user_tweetes'=>$user_tweetes)); ?>
			</div>
			<!-- /User Twitter Slider -->
			<!-- User Followers -->
			<div class="col-sm-3 ">
				<?php echo $this->load_view('widgets/user-follower',array('user_followers'=>$user_followers)); ?>
			</div>
			<!-- /User Followers -->
		</div>
	</div>
</section>

<?php
	$this->Template->setCallback(function(){
		$src_url = rtrim(APP_URL,'/').'/views/';
		?>
			<script type="text/javascript" src="<?php echo $src_url.'src/js/typeahead/bootstrap-typeahead.min.js'; ?>"></script>
			<script type="text/javascript">
				jQuery('#searchFollower').typeahead({
					ajax: {
						url: api_url+'getuserfollowers/',
						timeout: 500,
						displayField: 'name', //"html",
						valueField:'screen_name',
						triggerLength: 3,
						method: "POST",
						preDispatch: function (query) {
							jQuery('.searchFollower-container').addClass('loading');
							return { screen_name:user.screen_name , query: query	};
						},
						preProcess: function (data) {
							jQuery('.searchFollower-container').removeClass('loading');
							if (data.status !== 200) {
								alert('No Follower Found')
								return false;
							}

							// We good!
							for(i in data.payload){
								data.payload[i]['html'] = data.payload[i].name+'<a href="javascript:">@'+data.payload[i].screen_name+'</a>';
								//console.log(obj);
							}
							return data.payload ;
						}
					},
					onSelect: function(item) {
						site.getUserTweets(item.value);
					},
				});
			</script>
		<?php
	});
?>