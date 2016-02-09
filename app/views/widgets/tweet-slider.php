<?php
	if(! isset($user_tweetes)){ return false; }
	$default_color = '55ACEE';
?>
<div id="userTwitteSlider" class="carousel slide usertwitte  "> 
	<!-- Indicators -->
	<div class="loader"><i class="glyphicon glyphicon-refresh "></i></div>
	<ol class="carousel-indicators">
		<?php
			foreach($user_tweetes as $key=>$tweet):
				$class = $key==0?'active':'';
				echo '<li data-target="#userTwitteSlider" data-slide-to="'.$key.'" class="'.$class.'"></li>';
			endforeach;
		?>
	</ol>
	<div class="carousel-inner">
		<?php foreach($user_tweetes as $key=>$tweet): $class = $key==0?'active':''; ?>
			<div class="item <?php echo $class; ?>">
				<?php 
					if($tweet['media']['image']){
						$background = $tweet['media']['image'][0]['url']; 
					}else{
						$image_color = isset($user['color'])&&!empty($user['color'])?$user['color']:$default_color;
						//$background = 'http://placehold.it/900x500/'.$image_color .'/FFFFFF/?text=No+Image';
						$background = '';
					}
					$class = array();
					$class['retweet'] = $tweet['retweeted']==1?'glyphicon glyphicon-refresh':'glyphicon glyphicon-refresh';
					$class['favorite'] = $tweet['favorited']==1?'glyphicon glyphicon-heart':'glyphicon glyphicon-heart-empty';
					
				?>
				<div class="fill" style="background-image:url('<?php echo $background; ?>');"></div>
				<?php /*?><img src="<?php echo $background; ?>" alt=""><?php */?>
				<div class="carousel-caption">
					<div class="content"><?php echo $tweet['text']?></div>
					<div class="footer">
						<ul class="list-inline tweet-footer">
							<li><a href="javascript:"><i class="<?php echo $class['retweet']; ?>"></i>&nbsp;<?php echo $tweet['retweet_count']?></a></li>
							<li><a href="javascript:"><i class="<?php echo $class['favorite']; ?>"></i>&nbsp;<?php echo $tweet['favorite_count']?></a></li>
							<?php if($tweet['media']['urls']): ?><li><a href="javascript:" onClick="site.showTweet('<?php echo $tweet['id']; ?>')">View Media</a></li><?php endif; ?>
						</ul>
					</div>
				</div>
			</div>	
		<?php endforeach;?>
	</div>
	<!-- Controls --> 
	<a class="left carousel-control" href="#userTwitteSlider" data-slide="prev"> <span class="icon-prev"></span> </a> 
	<a class="right carousel-control" href="#userTwitteSlider" data-slide="next"> <span class="icon-next"></span> </a> 
</div>