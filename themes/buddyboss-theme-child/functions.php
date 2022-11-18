<?php
/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */


/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'buddyboss-theme', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'buddyboss_theme_child_languages' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles
  wp_enqueue_style( 'buddyboss-child-css', get_stylesheet_directory_uri().'/assets/css/custom.css' );

  // Javascript
  wp_enqueue_script( 'buddyboss-child-js', get_stylesheet_directory_uri().'/assets/js/custom.js' );
}
add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999 );


/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here
// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
      return $data;
    }

  $filetype = wp_check_filetype( $filename, $mimes );
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

add_action('buddyboss_theme_begin_content','afterHeaderIcon');
function afterHeaderIcon(){
  $html ='
  <!--
  <div class="mobile-header-icon">
      <div class="innerWrap">
          <div class="item"><a href="#!"><img src="/wp-content/uploads/2022/10/head-nav-icon-01.svg" alt="" /> </a></div>
          <div class="item"><a href="#!"><img src="/wp-content/uploads/2022/10/head-nav-icon-02.svg" alt="" /> </a></div>
          <div class="item"><a href="#!"><img src="/wp-content/uploads/2022/10/head-nav-icon-03.svg" alt="" /> </a></div>
      </div>
  </div>
  -->
  ';
  echo $html;
}
add_action('buddyboss_theme_after_footer','bottomfooterMenu');
function bottomfooterMenu(){
   $html ='
   <!--
   <div class="mobileFooter">
      <div class="innerWrap">
          <div class="item">
              <a href="#!" >
                  <img src="/wp-content/uploads/2022/10/foot-icon-activity.svg" alt="" />
                  <h5>Activity</h5>
              </a>
          </div>
          <div class="item">
              <a href="#!" >
                  <img src="/wp-content/uploads/2022/10/foot-icon-message.svg" alt="" />
                  <h5>Message</h5>
              </a>
          </div>
          <div class="item">
              <a href="#!" class="logoTh" >
                  <img src="/wp-content/uploads/2022/10/foot-icon-footerLogo.svg" alt="" />
              </a>
          </div>
          <div class="item">
              <a href="#!" >
                  <img src="/wp-content/uploads/2022/10/foot-icon-friends.svg" alt="" />
                  <h5>Friends</h5>
              </a>
          </div>
          <div class="item">
              <a href="#!" >
                  <img src="/wp-content/uploads/2022/10/foot-icon-more-o.svg" alt="" />
                  <h5>More</h5>
              </a>
          </div>
      </div>
  </div>

  -->
   ';
  // echo $html;
 get_template_part( 'template-parts/footer-mobilemenu' ); 
}

/**
 * Social Page
 */
add_action('mobile_menu_button','addbuttonActivitypage',90);
 function addbuttonActivitypage(){
    $html='<div class="mobile-header-icon"> 
                <div class="innerWrap">
                <div class="item"><a href="#swt-cart"  >
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/header/head-nav-icon-01.svg" alt="" /> </a></div>
                <div class="item"><a href="#swt-social" class="tab-show" data-active="swt-cart"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/header/head-nav-icon-02.svg" alt="" /> </a></div>
                <div class="item"><a href="#swt-lab"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/header/head-nav-icon-03.svg" alt="" /> </a></div>
                </div>
        </div>';
        echo $html;
}
add_filter('body_class','mobile_theme_body_class');     
function mobile_theme_body_class( $classes ){

    if ( wp_is_mobile() ){
        $classes[] = 'mobile';
    }
    else{
        $classes[] = 'desktop';
    }
    return $classes;
}
?>