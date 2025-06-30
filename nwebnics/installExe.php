<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 03. 13
//==================================================================
//== 게시판 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

$sqlStr = file_get_contents('cmsTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('boardconfigTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('boardTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('commentTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('memberTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('popupTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('staticsTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('commentsTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = file_get_contents('infoTbl');
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());


//== 새로 등록할 회원의 고유번호 생성
$max_idx = $db->getOne("SELECT MAX(idx) FROM wMember");
if(DB::isError($max_idx)) die($max_idx->getMessage());
if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
$sqlStr = "INSERT INTO wMember(idx, mId, passwd, mName, nickName, iQuestion, iAnswer, birthDay, solu, sex, email, zipcode, haddress1, haddress2, telNum, selNum, route, uJob, newsDm, smsDm, ulevel, signDate) VALUES ('".$new_idx."', '".$_POST[mId]."', '".md5($_POST[pass])."', '관리자', '관리자', '".$_POST[iQuestion]."', '".$_POST[iAnswer]."', '".$birthMix."', '".$_POST[solu]."', '".$_POST[sex]."', '".$emailMix."', '".$_POST[zipcode]."', '".$_POST[haddress1]."', '".$_POST[haddress2]."', '".$telMix."', '".$selMix."', '".$_POST[route]."', '".$_POST[uJob]."', '".$_POST[newsDm]."', '".$_POST[smsDm]."', '1', now())";
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = "INSERT INTO wboardConfig (idx, code, board_summary, list_level, write_level, reply_level, edit_level, delete_level, view_level, down_level, board_head, board_tail, log_info, out_login, num_per_page, num_per_block, reply_indent, adult, noreplebtn, skin, title_bar_text, meta_keyword, menu_depth, doctype, charset, language, hotclick, allow_delete_thread, overview, twinread, approve, pds_view, subject_cut, only_img, thumbnail_width, thumbnail_height, upload_max_size, upload_count, img_max_upload_width, img_view_size, relation_text, write_comment, comment_limit, board_class, set_cookie_data, recommend, view_layer, adminid, adminpass, best_img, html_editor, ps_center, real_name, onlySecret, top_inc, left_inc, right_inc, bottom_inc, atop_inc, aleft_inc, aright_inc, abottom_inc, signdate) VALUES
(1, '4_1', '공지사항', 5, 1, 1, 1, 1, 5, 5, '', '', 1, 0, 20, 10, 0, 0, 0, 'default', '웹닉스', '웹닉스', '', 'html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"', 'euc-kr', 'ko', 500, 0, 0, 0, 0, 0, 120, 0, 202, 164, 52777215, 1, 1000, 750, 0, 0, 0, '', 0, 0, '', '', '', 0, 7, 0, 0, 0, '', '', '', '', '', '', '', '', NOW());";
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage());

$sqlStr = "INSERT INTO infoTbl (idx, c_name, c_cate, c_event, zipcode, haddress1, haddress2, c_num, c_t_num, c_ceo, c_user, c_tel, c_fax, agreeinfo, privateinfo, private_agree, c_title, c_meta, c_noid) VALUES(1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');";
$m_url="/adm.htm";
$p_ment="웹닉스 솔루션을 설치중입니다. 잠시만 기다려주세요.";
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$m_url,$p_ment,5);

$db->disconnect();
?>