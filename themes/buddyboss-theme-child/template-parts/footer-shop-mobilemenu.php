<?php
/**
 * Footer Shop Mobilemenu
 */

?>
 <div class="mobileFooter">
      <div class="innerWrap">
          <div class="item">
              <a href="<?php echo home_url('shop');?>" >
                  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/svg/shop/noun-market-place-1581277.svg" alt="shop" />
                  <h5><?php _e('Shop','myludis'); ?></h5>
              </a>
          </div>
          <div class="item">
              <a href="<?php echo home_url('my-account/orders/');?>" >
                  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/svg/shop/Group1066.svg" alt="My Stuff" />
                 
                  <h5><?php _e('My Stuff','myludis'); ?></h5>
              </a>
          </div>
          <div class="item">
              <!-- <a href="#!" class="logoTh" >
              </a> -->

              <div class="">
                                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="logoTh">
                                            <!-- <span data-balloon-pos="down" data-balloon="<?php _e( 'Cart', 'buddyboss-theme' ); ?>"> -->
                                            <!-- <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/svg/shop/cart.svg" alt="" /> -->
                                            <i class="bb-icon-l bb-icon-shopping-cart customicon"></i>
                                                <?php
                                                if ( is_object( WC()->cart ) ) {
                                                $wc_cart_count = wc()->cart->get_cart_contents_count();
                                                if( $wc_cart_count != 0 ) { ?>
                                                    <span class="customcount count"><?php echo wc()->cart->get_cart_contents_count(); ?></span>
                                                <?php }
                                                }
                                                ?>
                                            <!-- </span> -->
                            </a>
                            <section class="notification-dropdown">
                                <header class="notification-header">
                                    <h2 class="title"><?php esc_html_e( 'Shopping Cart', 'buddyboss-theme' ); ?></h2>
                                    <?php
                                    if ( is_object( WC()->cart ) ) {
                                        $wc_cart_count = wc()->cart->get_cart_contents_count();
                                        if ( 0 !== $wc_cart_count ) {
                                            ?>
                                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-view-cart-link">
                                                <?php esc_html_e( 'View Cart', 'buddyboss-theme' ); ?>
                                            </a>
                                            <?php
                                        }
                                    }
                                    ?>
                                </header>
                                <div class="header-mini-cart">
                                    <?php
                                    if ( is_object( WC()->cart ) ) {
                                        woocommerce_mini_cart();
                                    }
                                    ?>
                                </div>
                            </section>
            </div>
          </div>
          <div class="item">
              <a href="#!" >
                  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/svg/shop/wallet.svg" alt="Wallet" />
                  <h5><?php _e('Wallet','myludis'); ?></h5>
              </a>
          </div>
          <div class="item">
              <a href="#!" >
                  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/svg/shop/more-o.svg" alt="More" />
                  <h5><?php _e('More','myludis'); ?></h5>
              </a>
          </div>
      </div>
  </div>