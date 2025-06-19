$(function(){
    $('.idx_section1').bxSlider({
        auto: false,
        pager: true,
        controls: true,
        pause: 5000,
        pagerCustom: '.idx_section1_pager'
    });

    $('.idx_section3_slider').bxSlider({
        auto: false,
        pager: true,
        controls: true,
        pause: 5000,
        pagerCustom: '.idx_section3_pager'
    }); 
	
    $('.idx_imgbanner_slider').bxSlider({
        auto: true,
        pager: true,
        controls: false,
        pause: 5000,
    }); 
    
    // index 팝업
    // $(".idx_popup_wrap").fadeIn(500);
    $(".bt_idx_popup_close").click(function(){
        $(".idx_popup_wrap").fadeOut();
    });
});
