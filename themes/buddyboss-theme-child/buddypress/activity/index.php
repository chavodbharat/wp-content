<?php
/**
 * BuddyBoss Activity templates
 *
 * @since BuddyPress 2.3.0
 * @version 3.0.0
 */
?>
<?php   if ( wp_is_mobile() ){ ?>

<div class="appWrapper">
		<div class="mobile-header-icon"> 
				<div class="innerWrap">
					<div class="item" id="shop_cart"><a href="#swt-cart" menu-active="#show-cartmenu" ><img src="/wp-content/uploads/2022/10/head-nav-icon-01.svg" alt="" /> </a></div>
					<div class="item" id="myludis_social"><a href="#swt-social" class="tab-show" menu-active="#show-socialmenu" data-active="swt-cart"><img src="/wp-content/uploads/2022/10/head-nav-icon-02.svg" alt="" /> </a></div>
					<div class="item" id="myludis_lab"><a href="#swt-lab"  menu-active="#show-labmenu"><img src="/wp-content/uploads/2022/10/head-nav-icon-03.svg" alt="" /> </a></div>
				</div>
		</div>
		<div class="tabContentWrap"> 
			<div class="tabContent shop-section" id="swt-cart">
					<?php echo do_shortcode('[products limit="12" columns="3" orderby="popularity" ]'); ?>
			</div>
		
			<div class="tabContent social-section content-show" id="swt-social">
				<?php bp_nouveau_before_activity_directory_content(); ?>

				<?php if ( is_user_logged_in() ) : ?>
					<?php bp_get_template_part( 'activity/post-form' ); ?>
				<?php endif; ?>

				<?php bp_nouveau_template_notices(); ?>

				<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

					<div class="flex actvity-head-bar">
						<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>
						<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>
					</div>

				<?php endif; ?>

				<div class="screen-content">

					<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>

					<div id="activity-stream" class="activity" data-bp-list="activity">

							<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

					</div> <!-- .activity -->

					<?php bp_nouveau_after_activity_directory_content(); ?>

				</div><!-- // .screen-content -->
			</div>	

			<div class="tabContent lab-section" id="swt-lab">
					<div class="limitAlert">
						<div class="innerWrap">
						<div class="img">
							<img src="/wp-content/uploads/2022/10/android-bulb.svg" alt="" />
						</div>
						<div class="cont">
							<h3>Wednesday Wisdom</h3>
							<p>Push your limits today for a better tomorrow</p>
						</div>
						</div>
					</div>   

					<div class="myStats">
						<div class="title">
						<h4>My Stats</h4>
						</div>
						<div class="itemWrap">
						<div class="item">
							<div class="graphImg">
								<img src="/wp-content/uploads/2022/10/graph.png" alt="" />
							</div>
							<div class="title">
								<h4>PROFILE</h4>
							</div>
						</div>
						<div class="item">
							<div class="graphImg">
								<img src="/wp-content/uploads/2022/10/graph.png" alt="" />
							</div>
							<div class="title">
								<h4>LUDIS</h4>
							</div>
						</div>
						<div class="item">
							<div class="graphImg">
								<img src="/wp-content/uploads/2022/10/graph.png" alt="" />
							</div>
							<div class="title">
								<h4>RANK</h4>
							</div>
						</div>
						<div class="item">
							<div class="graphImg">
								<img src="/wp-content/uploads/2022/10/graph.png" alt="" />
							</div>
							<div class="title">
								<h4>GENES</h4>
							</div>
						</div>
						<div class="item">
							<div class="graphImg">
								<img src="/wp-content/uploads/2022/10/graph.png" alt="" />
							</div>
							<div class="title">
								<h4>BLOOD</h4>
							</div>
						</div>
						<div class="item">
							<div class="graphImg">
								<img src="/wp-content/uploads/2022/10/graph.png" alt="" />
							</div>
							<div class="title">
								<h4>BODY</h4>
							</div>
						</div>
						</div>
					</div>
		
			</div>
	 	 </div> 	
</div>	
<?php } else { ?>
	
			<?php bp_nouveau_before_activity_directory_content(); ?>

			<?php if ( is_user_logged_in() ) : ?>
				<?php bp_get_template_part( 'activity/post-form' ); ?>
			<?php endif; ?>

			<?php bp_nouveau_template_notices(); ?>

			<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

				<div class="flex actvity-head-bar">
					<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>
					<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>
				</div>

			<?php endif; ?>

			<div class="screen-content">

				<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>

				<div id="activity-stream" class="activity" data-bp-list="activity">

						<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

				</div><!-- .activity -->

				<?php bp_nouveau_after_activity_directory_content(); ?>

			</div><!-- // .screen-content -->
			
<?php } ?>

