<?
/*========================================= URL 열람 설정 안내 =========================================
* 게시판의 쓰기, 리플, 수정, 삭제 등의 메뉴는 권한설정 내용에 따라 노출 여부가 결정
* 각 레벨별로 접근 권한을 설정하였다하여 URL접근이 차단이 되는것은 아니며 차단시키려면 "회원전용"
 을 체크해주여야만 실질적으로 접근이 차단됨(비회원 게시판은 10 이상의 권한을 주었을때 차단됨)
* 삭제를 할경우 delete.php 파일이 없서 아래와 같이 체크
* 성인 게시판 역시 "회원전용"으로 차단한후 "외부로그인"을 체크하여야만 미성년자 체크가능
* 디폴트 count($mLevel)은 일반접속자 count($mLevel)이하는 회원 권한
* count($mLevel) => 전체권한의 갯수(관리자,부관리자,회원,비회원) 등 몇개의 권한레벨로 이루어져 있는지 확인(common_config.php에 있음)
=========================================== URL 열람 설정 안내 =========================================*/

//== 현재의 URL 절대주소 추출
$now_page = now_filename($_SERVER["PHP_SELF"]);

//== 권한별 접근제어
if(!strcmp($now_page, "list.php")) {
	if($board_info[list_level]<count($mLevel) && member_session($board_info[list_level])==false) redirect(1, "/", "회원 로그인후 이용하시기 바람니다.", 3);
}else if(!strcmp($now_page, "write.php")) {
	if($board_info[write_level]<count($mLevel) && member_session($board_info[write_level])==false) error_view(999, "죄송합니다. 요청하신 URL은 열람이 불가능합니다.","회원 로그인후 열람권한을 확인하세요.");
}else if(!strcmp($now_page, "edit.php")) {
	if($board_info[edit_level]<count($mLevel) && member_session($board_info[edit_level])==false) error_view(999, "죄송합니다. 요청하신 URL은 열람이 불가능합니다.","회원 로그인후 열람권한을 확인하세요.");
}else if(!strcmp($now_page, "reple.php")) {
	if($board_info[reply_level]<count($mLevel) && member_session($board_info[reply_level])==false) error_view(999, "죄송합니다. 요청하신 URL은 열람이 불가능합니다.","회원 로그인후 열람권한을 확인하세요.");
}else if(!strcmp($now_page, "view.php")) {
	if($board_info[view_level]<count($mLevel) && member_session($board_info[view_level])==false) error_view(999, "죄송합니다. 요청하신 URL은 열람이 불가능합니다.","회원 로그인후 열람권한을 확인하세요.");
}else if(!strcmp($now_page, "down.php")) {
	if($board_info[down_level]<count($mLevel) && member_session($board_info[down_level])==false) error_view(999, "죄송합니다. 요청하신 URL은 열람이 불가능합니다.","회원 로그인후 열람권한을 확인하세요.");
}else if($_POST[mode]==="delete" && $_GET[idx]>0) {
	if($board_info[delete_level]<count($mLevel) && member_session($board_info[delete_level])==false) error_view(999, "죄송합니다. 요청하신 URL은 열람이 불가능합니다.","회원 로그인후 열람권한을 확인하세요.");
}

//== 성인전용게시판이고 외부로그정보를 이용할 경우
if($board_info[adult]>0 && $board_info[out_login]>0) {
	$sql_str = "select jumin1,jumin2 from Members where id='$_SESSION[my_id]' and name='$_SESSION[my_name]'";
	$my_info = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
	if(DB::isError($my_info)) die($my_info->getMessage());
	if(!age_check($my_info[jumin1], $my_info[jumin2], "adult", 19)) error_view(999, "죄송합니다. 만19세 이상의 성인만 열람이 가능합니다.","");
}
?>