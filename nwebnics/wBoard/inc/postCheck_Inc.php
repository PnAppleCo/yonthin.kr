<?php
	if($_POST['mode']=="write" || $_POST['mode']=="edit") {
		//if(!preg_match("/^([가-힣a-zA-Z]+[0-9]*){3,15}$/u", $_POST[name])) error_view(999, "입력하신 성명이 적당하지 않습니다.","성명을 다시 입력해주세요.");
		//if(!preg_match("([^[:space:]]+)", $_POST[ucontents])) error_view(999, "입력하신 내용이 적당하지 않습니다.","내용을 다시 입력해주세요.");
		if($_POST['subject']) {
			//if(!preg_match("([^[:space:]]+)", $_POST[subject])) error_view(999, "글제목으로 적당하지 않습니다.","글제목을 다시 입력해주세요.");
		}
	}
	if($_POST['mode']=="write") {
		//if(!preg_match("(^[0-9a-zA-Z]{4,}$)", $_POST[passwd])) error_view(999, "입력하신 비밀번호가 적당하지 않습니다.","비밀번호를 다시 입력해주세요.");
	}
	if($_POST['email'] && ($board_info['skin'] != "news")) {	//== 뉴스 스킨의 전자우편은 이미지 설명에 사용
		//if(!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $_POST[email])) error_view(999, "입력하신 전자우편 주소가 적당하지 않습니다.","적당한 전자우편 주소를 다시 입력해주세요.");
	}
	if($_POST['homepage']) {
		$home=substr($_POST['homepage'],0,7);
		if(strcmp($home,"http://")) $homepy="http://".$_POST['homepage']; else $homepy=$_POST['homepage'];
		//if(!urlCheck($_POST[homepage])) error_view(999, "입력하신 홈페이지 주소가 적당하지 않습니다.","적당한 홈페이지 주소를 다시 입력해주세요.");
	}
	if(!$_POST['name'] && member_session(1)) $_POST['name']="관리자"; else if($_POST['name']) $_POST['name']=$_POST['name']; else $_POST['name']="미입력";
?>