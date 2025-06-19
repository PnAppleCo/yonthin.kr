/*!

 * Z Light Accordion
 * Author: Zalki-Lab
 * Version: 1.0


 */

;(function($, window, document) {

	$.fn.zLightAccordion = function(options) {

				var setting = $.extend({ // this settings for our plugin

				accordionWidth : '50%', // accordion width
				accordionMinWidth : '0', // accordion min-width

				handler : 'click', 			// click or mouseenter

				activeSwitch : 'off', 		// active tabs (off or on)
				activeNumber : 'first',		 // 1 active tab, use : 'first' 
											// last, use : 'last'
											// if 2, use '2', 3 use '3' etc

				deploymentSpeed : 1000, 	// deployment speed 
				foldingSpeed : 500,			// folding speed

				headerHeight : 'auto', 				// header height
				headerBcolor: '#474747', 			// header of accordion background color
				headerColor: '#fff',				// header text color
				headerFontSize : '18px',			// header font-size
				headerPadding: '0', 				// header padding
				iconHeaderUrl : false, 				// header icon (false if none or img.png)

				activeSectionBcolor: '#fff', 					// li active background color
				sectionBcolor: '#fff',							// li background color

				sectionActiveTextColor : '#474747',				// text color of active section header
				sectionTextColor: '#474747',					// text color section header

				contentBcolor : '#fafafa',					// content background color
				contentColor : '#474747',					// content text color

				borderWidth : '1px',
				borderStyle : 'solid',
				borderColor : '#dcdcdc',

				iconSwitchUrl: 'ch_right.png',
				iconSwitchUrl2: 'ch_down.png',

				easingEffect : 'easeOutExpo' // Use 1 of 30 effects
				// Use one of these effects
				// PopUpEasing Efeect ('easeInSine','easeOutSine','easeInOutSine'
			    // 'easeInQuad','easeOutQuad','easeInOutQuad','easeInCubic','easeOutCubic'
				// 'easeInOutCubic','easeInQuart','easeOutQuart','easeInOutQuart','easeInQuint'
				// 'easeOutQuint', 'easeInOutQuint', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo'
				// 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInBack', 'easeOutBack'
				// 'easeInOutBack', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic'
				// 'easeInBounce', 'easeOutBounce', 'easeInOutBounce' )
				}, options); //extend end

			var getAttrVal = this.attr('id'),
				$body = $('body'),
				$html = $('html');

 	return this.each(function() { // return this each start

			var $this = $(this),
				zl_li = $this.find('.zl_acc > li'),
				active_number,
				d_switch = 'switch',
				d_off = 'off',
				d_on = 'on',
				d_header = ':header',
				style_border_li = ' '+setting.borderWidth+' '+setting.borderStyle+' '+setting.borderColor+'  ',
				ifImg;

	(!setting.iconHeaderUrl === false) ? ifImg = 'background-image: url(/img/comm/'+setting.iconHeaderUrl+');' : ifImg = '';

			var styleAccordion = $('<style type="text/css">	#' + getAttrVal + '{width: '+setting.accordionWidth+';min-width:'+setting.accordionMinWidth+';} #' + getAttrVal + ' > .zl_header{padding:'+setting.headerPadding+';font-size:'+setting.headerFontSize+';height:'+setting.headerHeight+'; '+ ifImg +' background-color: '+setting.headerBcolor+';color: '+setting.headerColor+'; } #' + getAttrVal + ' > .zl_acc{border:0;} #' + getAttrVal + ' > .zl_acc > li{background-color: '+setting.sectionBcolor+' ;} #' + getAttrVal + ' > .zl_acc > li > div{background-color: '+setting.contentBcolor+'; color: '+setting.contentColor+' ;} #' + getAttrVal + ' > .zl_acc > li > h1, #' + getAttrVal + ' > .zl_acc > li > h2, #' + getAttrVal + ' > .zl_acc > li > h3, #' + getAttrVal + ' > .zl_acc > li > h4, #' + getAttrVal + ' > .zl_acc > li > h5, #' + getAttrVal + ' > .zl_acc > li > h6{background-image: url(/img/comm/'+setting.iconSwitchUrl+'); color: '+setting.sectionTextColor+' ;} #' + getAttrVal + '  .zl_img_switch{background-image: url(/img/comm/'+setting.iconSwitchUrl2+') !important;}#' + getAttrVal + '  .zl_img_switch>a{color:#fff !important;}</style>');

		$('html > head').append(styleAccordion);

	if(setting.activeSwitch === 'on'){ // calculate number of active tab open

		switch(setting.activeNumber){
			case 'first': active_number = '0'; break;
			case 'last': active_number = '-1'; break;
			case setting.activeNumber : active_number = setting.activeNumber - 1; break;
		};

		zl_li.eq(active_number).css('background-color', setting.activeSectionBcolor).find('div').data(d_switch,d_on).stop(true,true).slideDown()
		.end().children(d_header).css('color',setting.sectionActiveTextColor).addClass('zl_img_switch');

	}; // calculate number of active tab close

	zl_li.children(d_header).on(setting.handler + '.zl_handler',function(){ // HANDLER OPEN

		var $self = $(this),
			zl_div = $self.siblings('div');
		

	function fufEvents($self,zl_div){

		$self.addClass('zl_img_switch').css('color',setting.sectionActiveTextColor).siblings('div').stop(true,true).slideDown(setting.deploymentSpeed,setting.easingEffect).data(d_switch,d_on)
		.end().parent('li').css('background-color', setting.activeSectionBcolor).siblings('li').css('background-color','').children(d_header).removeClass('zl_img_switch').css('color','').siblings('div').stop(true,true).slideUp(setting.foldingSpeed,setting.easingEffect).data(d_switch,d_off)		 

	};

	if(setting.handler === 'click'){ // IF HANDLER OPEN

		(zl_div.data(d_switch) === d_off || !zl_div.data(d_switch)) ? fufEvents($self,zl_div)
			: 
			$self.removeClass('zl_img_switch').css('color','').siblings('div').stop(true,true).slideUp(setting.foldingSpeed,setting.easingEffect).data(d_switch,d_off).end().parent('li').css('background-color','');

	}else{

		if(zl_div.data(d_switch) === d_off || !zl_div.data(d_switch)){	fufEvents($self,zl_div) };

	}; // IF HANDLER CLOSE

	}); // HANDLER CLOSE

}); // return this each end

	};// function end

}) (jQuery, window, document);


