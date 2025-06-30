<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 01
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/nwebnics/wTools/", "관리자 로그인후 이용하세요.", 1);

if(!$_POST['code']) js_action(1,"코드정보를 찾을수 없습니다.","",-1);

//== 등록 기본데이터 가공
$_POST['board_head']=addslashes($_POST['board_head']);
$_POST['board_tail']=addslashes($_POST['board_tail']);
$_POST['title_bar_text']=addslashes($_POST['title_bar_text']);
$_POST['meta_keyword']=addslashes($_POST['meta_keyword']);

//== 게시판 등록 처리 ====================================================================================

if($_POST['mode'] === "add") {
	//== 게시판 코드의 중복체크
	$duple_check = $db->getOne("SELECT COUNT(idx) FROM $b_cfg_tb[0] WHERE code='$_POST[code]'");
	if(DB::isError($duple_check)) die($duple_check->getMessage());
	if($duple_check > 0) js_action(1,"코드가 이미등록되어 있습니다.","",-1);

	//== 새로 등록할 게시판의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM $b_cfg_tb[0]");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	$m_sql_str = "INSERT INTO $b_cfg_tb[0] (idx,code,board_summary,list_level,write_level,reply_level,edit_level,delete_level,view_level,down_level,board_head,board_tail,log_info,num_per_page,num_per_block,reply_indent,adult,noreplebtn,skin,title_bar_text,meta_keyword,menu_depth,doctype,charset,language,hotclick,allow_delete_thread,overview,twinread,approve,pds_view,subject_cut,only_img,thumbnail_width,thumbnail_height,upload_max_size,upload_count,img_max_upload_width,img_view_size,relation_text,write_comment,comment_limit,board_class,set_cookie_data,recommend,view_layer,adminid,adminpass,alignField,best_img,html_editor,ps_center,real_name,onlySecret,top_inc,left_inc,right_inc,bottom_inc,atop_inc,aleft_inc,aright_inc,abottom_inc,signdate)
VALUES ($new_idx,'$_POST[code]','$_POST[board_summary]','$_POST[list_level]','$_POST[write_level]','$_POST[reply_level]','$_POST[edit_level]','$_POST[delete_level]','$_POST[view_level]','$_POST[down_level]','$_POST[board_head]','$_POST[board_tail]','$_POST[log_info]','$_POST[num_per_page]','$_POST[num_per_block]','$_POST[reply_indent]','$_POST[adult]','$_POST[noreplebtn]','$_POST[skin]','$_POST[title_bar_text]','$_POST[meta_keyword]','$_POST[menu_depth]','$_POST[doctype]','$_POST[language]','$_POST[charset]','$_POST[hotclick]','$_POST[allow_delete_thread]','$_POST[overview]','$_POST[twinread]','$_POST[approve]','$_POST[pds_view]','$_POST[subject_cut]','$_POST[only_img]','$_POST[thumbnail_width]','$_POST[thumbnail_height]','$_POST[upload_max_size]','$_POST[upload_count]','$_POST[img_max_upload_width]','$_POST[img_view_size]','$_POST[relation_text]','$_POST[write_comment]','$_POST[comment_limit]','$_POST[board_class]','$_POST[set_cookie_data]','$_POST[recommend]','$_POST[view_layer]','$_POST[adminid]','$_POST[adminpass]','$_POST[alignField]','$_POST[best_img]','$_POST[html_editor]','$_POST[ps_center]','$_POST[real_name]','$_POST[onlySecret]','$_POST[top_inc]','$_POST[left_inc]','$_POST[right_inc]','$_POST[bottom_inc]','$_POST[atop_inc]','$_POST[aleft_inc]','$_POST[aright_inc]','$_POST[abottom_inc]',now());";

//== 게시판 수정 처리 ====================================================================================
}else if($_POST['mode']==="edit") {
	if(!$_POST['idx']) js_action("idx정보를 찾을수 없습니다.","back",-1);
	$m_sql_str = "UPDATE $b_cfg_tb[0] SET board_summary='$_POST[board_summary]',list_level='$_POST[list_level]',write_level='$_POST[write_level]',reply_level='$_POST[reply_level]',edit_level='$_POST[edit_level]',delete_level='$_POST[delete_level]',view_level='$_POST[view_level]',down_level='$_POST[down_level]',board_head='$_POST[board_head]',board_tail='$_POST[board_tail]',log_info='$_POST[log_info]',num_per_page='$_POST[num_per_page]',num_per_block='$_POST[num_per_block]',reply_indent='$_POST[reply_indent]',adult='$_POST[adult]',noreplebtn='$_POST[noreplebtn]',skin='$_POST[skin]',title_bar_text='$_POST[title_bar_text]',meta_keyword='$_POST[meta_keyword]',menu_depth='$_POST[menu_depth]',doctype='$_POST[doctype]',charset='$_POST[charset]',language='$_POST[language]',hotclick='$_POST[hotclick]',allow_delete_thread='$_POST[allow_delete_thread]',overview='$_POST[overview]',twinread='$_POST[twinread]',approve='$_POST[approve]',pds_view='$_POST[pds_view]',subject_cut='$_POST[subject_cut]',only_img='$_POST[only_img]',thumbnail_width='$_POST[thumbnail_width]',thumbnail_height='$_POST[thumbnail_height]',upload_max_size='$_POST[upload_max_size]',upload_count='$_POST[upload_count]',img_view_size='$_POST[img_view_size]',img_max_upload_width='$_POST[img_max_upload_width]',relation_text='$_POST[relation_text]',write_comment='$_POST[write_comment]',comment_limit='$_POST[comment_limit]',board_class='$_POST[board_class]',set_cookie_data='$_POST[set_cookie_data]',recommend='$_POST[recommend]',view_layer='$_POST[view_layer]',adminid='$_POST[adminid]',adminpass='$_POST[adminpass]',best_img='$_POST[best_img]',html_editor='$_POST[html_editor]',ps_center='$_POST[ps_center]',real_name='$_POST[real_name]',onlySecret='$_POST[onlySecret]',top_inc='$_POST[top_inc]',left_inc='$_POST[left_inc]',right_inc='$_POST[right_inc]',bottom_inc='$_POST[bottom_inc]',atop_inc='$_POST[atop_inc]',aleft_inc='$_POST[aleft_inc]',aright_inc='$_POST[aright_inc]',abottom_inc='$_POST[abottom_inc]',alignField='$_POST[alignField]' WHERE idx=$_POST[idx] LIMIT 1 ";

//== 게시판 삭제 처리 ====================================================================================

}else if($_POST['mode']==="del" && $_POST['idx']) {
	if(!$_POST['idx']) js_action(1,"게시판의 고유정보를 찾을수 없습니다.","",-1);
	$m_sql_str= "DELETE FROM $b_cfg_tb[0] WHERE idx=$_POST[idx]";
	//== 게시물 삭제
	$art_rst=$db->query("DELETE FROM $b_cfg_tb[1] WHERE code='$_POST[code]'");
	if(DB::isError($art_rst)) die($art_rst->getMessage());
	//== 코멘트 삭제
	$com_rst=$db->query("DELETE FROM $b_cfg_tb[2] WHERE code='$_POST[code]'");
	if(DB::isError($com_rst)) die($com_rst->getMessage());
	//== 업로드 자료 삭제[디렉토리 삭제]==>> 디렉토리 소유가 nobody 인경우 삭제 가능
	if(file_exists("../board/files/".$_GET['code'])==true) exec("rm -rf ../board/files/".$_POST['code']);
	//== 업로드 자료를 삭제하는 윈도우 버전을 개발해야함

}else {
	js_action(1, "죄송합니다. 요청하신 페이지는 열람이 불가능합니다.","",-1);
}

//== 질의 작업 처리 =====================================================================================

if($_POST['mode']==="add") {
	$m_url="./boardList.php?page=".$_POST['page'];
	$p_ment="게시판 등록 처리중입니다. 완료후 이동하겠습니다.";
}else if($_POST['mode']==="edit") {
	$m_url="./boardList.php?page=".$_POST['page'];
	$p_ment="게시판 수정 처리중입니다. 완료후 이동하겠습니다.";
}else if($_POST['mode']==="del") {
	$m_url="./boardList.php?page=".$_POST['page'];
	$p_ment="게시판 삭제 처리중입니다. 완료후 이동하겠습니다.";
}else {
	$m_url="/";
}

$rst=$db->query($m_sql_str);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1, $m_url, $p_ment, 2);
$db->disconnect();
?>