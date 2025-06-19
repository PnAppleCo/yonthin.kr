//== 팝업창
function popupOpen(url, width, height) {
	LeftPosition=(screen.width)?(screen.width-width)/2:100;
	TopPosition=(screen.height)?(screen.height-height)/2:100;
	winOpts="scrollbars=no,toolbar=no,location=no,directories=no,width="+width+",height="+height+",resizable=no,mebar=no,left="+LeftPosition+",top="+TopPosition;
	window.open(url,'popupOpen', winOpts);
}

function winPopup(w_url,w_name,w_width,w_height,w_status,w_resize,w_scroll,w_top,w_left) {
	window.open(w_url,w_name,'width='+w_width+',height='+w_height+',status='+w_status+',resizable='+w_resize+',scrollbars='+w_scroll+',top=' +w_top+ ',left='+ w_left +'');
}

/* 로그인 체크 */
function divLogin() {
	if(!document.l_log_Form.mId.value) { alert('아이디를 입력하세요!'); document.l_log_Form.mId.focus(); return false; }
	if(!document.l_log_Form.pass.value) { alert('비밀번호를 입력하세요'); document.l_log_Form.pass.focus(); return false; }
	if(document.l_log_Form.pass.value.length < 4) { alert('최소 4자리 이상 입력!'); document.l_log_Form.pass.focus(); return false; }
	document.l_log_Form.action='/nwebnics/wMembers/login.php';
	document.l_log_Form.method='POST';
	document.l_log_Form.submit();
}

/* 로그인 체크 */
function divLogin_admin() {
	if(!document.loginForm.mId.value) { alert('아이디를 입력하세요!'); document.loginForm.mId.focus(); return false; }
	if(!document.loginForm.pass.value) { alert('비밀번호를 입력하세요'); document.loginForm.pass.focus(); return false; }
	if(document.loginForm.pass.value.length < 4) { alert('최소 4자리 이상 입력!'); document.loginForm.pass.focus(); return false; }
	document.loginForm.action='/nwebnics/wMembers/login.php';
	document.loginForm.method='POST';
	document.loginForm.submit();
}

//== 즐겨찾기
function bookmarksite(title,url) { 
	// Internet Explorer
	if(document.all) {
		window.external.AddFavorite(url, title); 
	// Google Chrome
	}else if(window.chrome) {
		alert("Ctrl+D키를 누르시면 즐겨찾기에 추가하실 수 있습니다.");
	// Firefox
	}else if (window.sidebar) {
		window.sidebar.addPanel(title, url, ""); 
	// Opera
	}else if(window.opera && window.print) {
		var elem = document.createElement('a'); 
		elem.setAttribute('href',url); 
		elem.setAttribute('title',title); 
		elem.setAttribute('rel','sidebar'); 
		elem.click(); 
	}
}

//== 슬라이딩 콘텐츠
$(document).ready(function(){
	$("#loginOpen").click(function(){
		$("#loginSection").slideToggle(500);
	});
	var site_visible = 1;
	$("#catebtn_area").click(function(){
		$("#catelist").slideToggle(400);
		if(site_visible == 0) {
			$("#catebtn > img").attr("src", "/img/index/category_arrow_on.png");
			$("#catebtn > img").attr("alt", "카테고리 닫기");
			site_visible = 1;
		}else {
			$("#catebtn > img").attr("src", "/img/index/category_arrow_off.png");
			$("#catebtn > img").attr("alt", "카테고리 열기");
			site_visible = 0;
		}
	});

	$(".localTabs img").each(function(n){
		$(this).click(function(){
			$(".localTabs img").each(function(n){
			this.src = this.src.replace("_on.gif", "_off.gif");
			this.style.cursor="pointer";
		});
		this.src = this.src.replace("_off.gif", "_on.gif");
		this.style.cursor="";
		$(".tabClass").hide();
		$($(".tabClass")[n]).show();
		});
	})

});

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
	}
	return "";
}

function setCookie(name, value, expiredays) {
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function closePop(end_name) {
	if(document.checkForm.pClose.checked) {
		setCookie(end_name, "no" , 1);
		self.close();
	}
}

function closeiPop(ipopname, end_name) {
	if(end_name) setCookie(end_name, "no" , 1);
	document.getElementById(ipopname).style.display='none';
}

$(document).ready(function() {
	$('.d_btn').click(function(){
		$('#idxE3_view').fadeIn();
	});
	$('#oCloseBtn').click(function(){
		$('#idxE3_view').fadeOut();
	});

	//settings
	var opacity = 0, toOpacity = 1, duration = 0;
	//set opacity ASAP and events
	$('.imgOver').hover(function() {
		$(this).children('.gnbTitle').fadeTo(duration,toOpacity);
	}, function() {
		$(this).children('.gnbTitle').fadeTo(duration,opacity);
	}
	);
});

function wrapWindowByMask(){
		//화면의 높이와 너비를 구한다.
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();  

		//마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
		$('#mask').css({'width':maskWidth,'height':maskHeight});  

		//애니메이션 효과 - 일단 1초동안 까맣게 됐다가 80% 불투명도로 간다.
		$('#mask').fadeIn(1000);
		$('#mask').fadeTo("slow",0.8);

		//윈도우 같은 거 띄운다.
		$('.windows').show();
}

$(document).ready(function(){
		//검은 막 띄우기
		$('.openMask').click(function(e){
				e.preventDefault();
				wrapWindowByMask();
		});

		//닫기 버튼을 눌렀을 때
		$('.window .loginclose').click(function (e) {
				//링크 기본동작은 작동하지 않도록 한다.
				e.preventDefault();
				$('#mask, .windows').hide();
		});

		//검은 막을 눌렀을 때
		$('#mask').click(function () {
				$(this).hide();
				$('.windows').hide();
		});

});

function loginForm(){
	jQuery(function(){
		wrapWindowByMask();
	});
}

$(function(){
	/*스크롤 탑*/
	$("div.gotop").fadeOut("slow");
	$(window).scroll(function(){
		setTimeout(scroll_top, 1000);//화살표가 반응하여 생기는 시간
	});
	$(".gotop").hover(function(){
		$(this).css("background-color","#0E68AE");
	}, function(){
		$(this).css("background-color","#CCCCCC");
		scroll_top()
	})
	$("#gotop").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);//화살표 클릭시 화면 스크롤 속도
			return false;
		});
})
/*스크롤 탑*/
function scroll_top(){
	if($(window).scrollTop()<=1) {
		$("#gotop").fadeOut("slow");
	}
	else {
		$("#gotop").fadeIn("slow");
	}
}

function layerOpen(el){
	var temp = $('#' + el);
	var bg = temp.prev().hasClass('bg');	//dimmed 레이어를 감지하기 위한 boolean 변수
	if(bg){
		$('.loginlayer').fadeIn();	//'bg' 클래스가 존재하면 레이어가 나타나고 배경은 dimmed 된다.
	}else{
		temp.fadeIn();
	}
	// 화면의 중앙에 레이어를 띄운다.
	if (temp.outerHeight() < $(document).height() ) temp.css('margin-top', '-'+temp.outerHeight()/2+'px');
	else temp.css('top', '0px');
	if (temp.outerWidth() < $(document).width() ) temp.css('margin-left', '-'+temp.outerWidth()/2+'px');
	else temp.css('left', '0px');
	temp.find('a.cbtn').click(function(e){
		if(bg){
			$('.loginlayer').fadeOut(); //'bg' 클래스가 존재하면 레이어를 사라지게 한다.
		}else{
			temp.fadeOut();
		}
		e.preventDefault();
	});
	$('.loginlayer .bg').click(function(e){	//배경을 클릭하면 레이어를 사라지게 하는 이벤트 핸들러
		$('.loginlayer').fadeOut();
		e.preventDefault();
	});
}


/* lnb */
/*
$(function(){
	$('.lnb_dep2>li>a').click(function() {
		var thisa = $(this);
		var checkElement = $(this).next();

		if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			thisa.removeClass("now");
		    checkElement.slideUp('normal');
		}
		if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$(".lnb_dep2>li>a").removeClass("now");
			thisa.addClass("now");
		    $('.lnb_dep2>li>ul:visible').slideUp('normal');
		    checkElement.slideDown('normal');
		}
		if($(this).closest('li').find('ul').children().length == 0) {
		    return true;
		} else {
		    return false;
		}
	});
});
*/
// LNB
$(function(){
	$('dd.lnb_dep1').hover(function(){
		$(this).css('background-image','url(/img/comm/lnb_arrow2.png)');
		$(this).find('> a').css('color','#ffd600');
		$('ul.lnb_dep1_open').show();
	}, function(){
		$(this).css('background-image','url(/img/comm/lnb_arrow1.png)');
		$(this).find('> a').css('color','#fff');
		//$(this).css('background-color','#B0105D');
		$('ul.lnb_dep1_open').stop().hide();
	});
	$('dd.lnb_dep2').hover(function(){
		$(this).css('background-image','url(/img/comm/lnb_arrow2.png)');
		$(this).find('> a').css('color','#ffd600');
		$('ul.lnb_dep2_open').show();
	}, function(){
		$(this).css('background-image','url(/img/comm/lnb_arrow1.png)');
		$(this).find('> a').css('color','#fff');
		//$(this).css('background-color','transparent');
		$('ul.lnb_dep2_open').stop().hide();
	});
});


// 슬라이드
$(function(){
	var $list = $(".slideList");
	var $box = $(".slideBox");
	var wd = $list.width();
	var num = $list.size();
	var margin,current,play;
	$box.width(wd*num);
	// AUTO PLAY
	setTimeout(function(){
		play = setInterval(play_next,2000);
	},3000)
	// IMG SLIDE
	function play_next(){
		margin = parseInt($box.css("margin-left"));
		if(margin < -(wd*(num-2))){
			$box.not(":animated").animate({"marginLeft":"0px"},"fast");
		}else{
			$box.not(":animated").animate({"marginLeft":"-="+wd+"px"},"fast");
		};
	}
	function play_prev(){
		clearInterval(play);
		margin = parseInt($box.css("margin-left"));
		if(margin == 0){
			$box.not(":animated").animate({"marginLeft":"-"+wd*(num-1)+"px"},"fast");
		}else{
			$box.not(":animated").animate({"marginLeft":"+="+wd+"px"},"fast");
		};
	}
	// BTN ACTION
	$(".slidePrev").click(function(){ play_prev(); return false; });
	$(".slideNext").click(function(){ clearInterval(play); play_next(); return false; });
});

/*
$(document).bind("contextmenu", function(e){
	alert("마우스 오른쪽 버튼 클릭 금지입니다. ^^");
	return false;
});
*/

// GNB
$(document).ready(function(){
	// PC
	$('#gnbFulldown_btn button.open').click(function(){
		$(this).hide();
		$('#gnbFulldown_btn button.close').show();
		$('#gnbFulldown').slideDown();
	});
	$('#gnbFulldown_btn button.close').click(function(){
		$(this).hide();
		$('#gnbFulldown_btn button.open').show();
		$('#gnbFulldown').slideUp();
	});
});

// 탭메뉴
$(function(){
	// tabA
	$('.tabA_view > li:first-child').show();
	$('.tabA > li').click(function(){
		var idx = $(this).index();
		$('.tabA > li').removeClass('tabA_on');
		$(this).addClass('tabA_on');
		$('.tabA_view > li').hide();
		$('.tabA_view > li:eq('+idx+')').show();
	});
	// tabB
	$('.tabB_view > li:last-child').show();
	$('.tabB > li').click(function(){
		var idx = $(this).index();
		$('.tabB > li').removeClass('tabB_on');
		$(this).addClass('tabB_on');
		$('.tabB_view > li').hide();
		$('.tabB_view > li:eq('+idx+')').show();
	});
	// tabC
	$('.tabC_view > li:first-child').show();
	$('.tabC > li').click(function(){
		var idx = $(this).index();
		$('.tabC > li').removeClass('tabC_on');
		$(this).addClass('tabC_on');
		$('.tabC_view > li').hide();
		$('.tabC_view > li:eq('+idx+')').show();
	});
});

/* gnb fixed */
$(document).ready(function(){
	var nav = $('.gnbFix');
	$(window).scroll(function () {
		if ($(this).scrollTop() > 45) {
			//nav.addClass("fixGnbs");
		}else {
			//nav.removeClass("fixGnbs");
		}
	});
});

$(function(){
	// 메뉴전체보기
	$("#gnb_bt_view").click(function(){
		$("#gnb_view").fadeIn();
	});
	$("#gnb_bt_hide").click(function(){
		$("#gnb_view").fadeOut();
	});
});

$(document).ready(function() {
	$("#printBtns").click(function(){
		window.print();
	});

	$("#snsBtns").click(function(){
		if($("#snsSend").is(":visible"))
			$("#snsSend").fadeOut();
		else
			$("#snsSend").fadeIn();
	});

	$("#sitemapBtn").click(function(){
		if($("#vSitemap").is(":visible")) {
			$("#sitemapToggle").attr("src","/img/comm/sitemap_icon.gif"); 
			$("#vSitemap").fadeOut();
		}else {
			$("#sitemapToggle").attr("src","/img/comm/sitemap_icon_on.gif"); 
			$("#vSitemap").fadeIn();
		}
	});
});

// MOBILE GNB
$(document).ready(function(){
	$('.mobileGnbOn').click(function(){ $('#mobileGnb').show(); $('#mobileGnb > ul').animate({ left:'0' },100); });
	$('.mobileGnbOff').click(function(){
		$('#mobileGnb').hide();
		$('#mobileGnb > ul').css('left','-70%');
		$('#mobileGnb > ul > li > h5').css('background','#fff').css('color','#222');
		$('#mobileGnb > ul > li > ul').hide();
	});
	$('.gnb_m').find('~ul').hide();
	$('.gnb_m').click(function() {
		$('.gnb_m').not(this).find('~ul').stop().hide();
		$('.gnb_m').not(this).removeClass('gnb_m_on');
		$(this).toggleClass('gnb_m_on');
		$(this).find('~ul').stop().slideToggle(300);
	});
});