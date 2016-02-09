
<div class="widget user-followers">
	<div class="widget-title"></div>
	<div class="widget-content">
		<?php if(! (isset($user_followers) && $user_followers)): ?>
			<div class="text-center">
				<h2><small>No Follower Found :-(</small></h2>
			</div>
		<?php else: ?>
			<div class="searchbox-container searchFollower-container form-group loader-container">
				<input type="text" id="searchFollower" name="searchFollower" class="form-control" placeholder="Search Followers">
				<div class="loader"><i class="glyphicon glyphicon-refresh "></i></div>
			</div>
			<ul class="list-unstyled followers-list">
				<?php foreach($user_followers as $follower): // display($follower); ?>
					<li class="clear ">
						<ul class="list-inline single-follower">
							<li><a style="background-image: url('<?php echo $follower['profile_pic']; ?>');" class="profile-pic" href="<?php echo 'javascript:'; //$follower['url']?>" onClick="site.getUserTweets('<?php echo $follower['screen_name']; ?>')" ></a></li>
							<li class="user-name">
								<a href="<?php echo 'javascript:'; //$follower['url']?>" onClick="site.getUserTweets('<?php echo $follower['screen_name']; ?>')"  ><?php echo $follower['name']; ?></a>
								<small>
									<ul class="list-unstyled" >
										<?php if(!empty($follower['description'])): ?><li><?php echo substr($follower['description'],0,50); ?></li><?php endif; ?>
										<?php if(!empty($follower['location'])): ?><li><i class="glyphicon glyphicon-map-marker"></i>&nbsp;<?php echo $follower['location']?></li><?php endif; ?>
									</ul>
								</small>
							</li>
							<?php /*?><li class="pull-right  "><a href="javascript:" class=" btn-unsubscribe text-thin"><i class="fa fa-times">&times;</i></a></li><?php */?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>