/* This is your custom Javascript */

jQuery(document).ready(function($){
    jQuery('.appWrapper .mobile-header-icon a').click(function(e){
        e.preventDefault();
        jQuery('.appWrapper .mobile-header-icon a').removeClass('tab-show');
        jQuery(this).addClass('tab-show');
        const activeTab = $(this).attr('href')
        const activeTabMenu = $(this).attr('menu-active')
        // console.log(activeTabMenu);
        // console.log('.mobile_icon_menu' + activeTabMenu);

        jQuery('.appWrapper .tabContentWrap .tabContent').removeClass('content-show');
        jQuery('.appWrapper .tabContentWrap .tabContent' + activeTab).addClass('content-show');
        jQuery('.mobile_icon_menu').removeClass('menu-show');
        jQuery('.mobile_icon_menu' + activeTabMenu).addClass('menu-show');

    });
}); 