<?php 
/**
 * Common Footer menu
 */

?>
<div class="mobile_menu_item">
        <div class="mobile_icon_menu mobile-shop-menu" id="show-cartmenu" >
                <?php get_template_part( 'template-parts/footer-shop-mobilemenu' ); ?> 
        </div>

        <div class="mobile_icon_menu mobile-social-menu menu-show" id="show-socialmenu">
                <?php get_template_part( 'template-parts/footer-social-mobilemenu' ); ?> 
        </div>

        <div class="mobile_icon_menu mobile-lab-menu" id="show-labmenu">
                <?php get_template_part( 'template-parts/footer-labs-mobilemenu' ); ?> 
        </div>
</div>        
