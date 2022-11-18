<?php
/**
 * Template name: Home template
 */
get_header()
?>
<div class="appWrapper">
   <div class="mobile-header-icon"> 
         <div class="innerWrap">
            <div class="item"><a href="#swt-cart" class="tab-show" data-active="swt-cart" ><img src="/wp-content/uploads/2022/10/head-nav-icon-01.svg" alt="" /> </a></div>
            <div class="item"><a href="#swt-social"><img src="/wp-content/uploads/2022/10/head-nav-icon-02.svg" alt="" /> </a></div>
            <div class="item"><a href="#swt-lab"><img src="/wp-content/uploads/2022/10/head-nav-icon-03.svg" alt="" /> </a></div>
         </div>
   </div>
   <div class="tabContentWrap"> 
      <div class="tabContent shop-section content-show" id="swt-cart">
         <?php echo do_shortcode('[products limit="12" columns="3" orderby="popularity" ]'); ?>
         <div class="mobile-shop-menu">
            <div class="mobileFooter">
               <div class="innerWrap wp-dark-mode-ignore">
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-activity.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Activity</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-message.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Message</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="logoTh wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-footerLogo.svg" alt="" class="wp-dark-mode-ignore">
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-friends.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Friends</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-more-o.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">More</h5>
                  </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="tabContent social-section" id="swt-social">
         Social
         <div class="mobile-shop-menu">
            <div class="mobileFooter">
               <div class="innerWrap wp-dark-mode-ignore">
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-activity.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Activity</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-message.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Message</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="logoTh wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-footerLogo.svg" alt="" class="wp-dark-mode-ignore">
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-friends.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Friends</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-more-o.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">More</h5>
                  </a>
                  </div>
               </div>
            </div>
         </div>
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
       
         <div class="mobile-shop-menu">
            <div class="mobileFooter">
               <div class="innerWrap wp-dark-mode-ignore">
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-activity.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Activity</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-message.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Message</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="logoTh wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-footerLogo.svg" alt="" class="wp-dark-mode-ignore">
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-friends.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">Friends</h5>
                  </a>
                  </div>
                  <div class="item wp-dark-mode-ignore">
                  <a href="#!" class="wp-dark-mode-ignore">
                     <img src="<?php echo get_template_directory_uri(); ?>/wp-content/uploads/2022/10/foot-icon-more-o.svg" alt="" class="wp-dark-mode-ignore">
                     <h5 class="wp-dark-mode-ignore">More</h5>
                  </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
get_footer()
?>