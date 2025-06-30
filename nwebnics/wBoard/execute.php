<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 02
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");
//require_once $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/Thumbnail.class.php";
//== 접근 권한 설정(삭제시에만 권한설정)
if($_POST['mode']==="delete" && $_GET['idx']>0) require "./inc/levelCheck_Inc.php";

//== 글시작과 끝의 빈공백, 엔터 등 제거
$_POST['name'] = trim($_POST['name']);
$_POST['subject'] = trim($_POST['subject']);
$_POST['passwd'] = trim($_POST['passwd']);
$_POST['ucontents'] = trim($_POST['ucontents']);

//== 입력데이터 체크
require ('inc/postCheck_Inc.php');

//== 광고, 스팸 불량 단어 추출
if(content_check($_POST['subject'],"one")) js_action(1, "주제에 광고성문구가 있는지 확인하여 주십시오.", "", -1);
if(content_check($_POST['ucontents'],"two")) js_action(1, "내용에 광고성문구가 있는지 확인하여 주십시오", "", -1);

//== 제목과 내용의 특수문자 처리
$subject = addslashes($_POST['subject']);
$ucontents = addslashes($_POST['ucontents']);

//== 파일 업로드
if($board_info['upload_count']>0 && $_POST['mode']!=="delete") {
	$filename = $_FILES['filename']['name'];
	$filetype = $_FILES['filename']['type'];
	$filesize = $_FILES['filename']['size'];
	$filetmp = $_FILES['filename']['tmp_name'];
	$fileerror = $_FILES['filename']['error'];

	if($_POST['code']) $codeVal=$_POST['code']; else if($_GET['code']) $codeVal=$_GET['code']; else $codeVal="";
	$boardPath=$boardDir.$codeVal."/";

	$up_load = file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, $board_info['upload_max_size'], $boardPath, $board_info['only_img']);

	//== 리턴받은 파일이름 지정(마지막에 "|"가 삽입되어 있으므로 하나 빼줌)
	$name_count = explode("|", $up_load);
	for($i=0;$i<sizeof($name_count)-1;$i++) {
		$filename[$i] = $name_count[$i];

		$img_array = array("image/gif", "image/pjpeg", "image/bmp", "image/jpeg", "image/png");
		if($board_info['only_img']>0 && $filename[$i]) {
			if(!in_array($filetype[$i], $img_array)) js_action(1, "GIF, JPG, PNG 등의 이미지만 업로드 할수 있습니다.","",-1);
		}

		//== 이미지인경우 썸네일 처리
		if(in_array($filetype[$i], $img_array)) {
			//== 이미지 첫장 작은 썸네일 처리
			$img_size = @getimagesize("./files/".$_GET['code']."/".$filename[$i]);

			if($img_size[0]>$board_info['thumbnail_width']) {
				$thum_width=$board_info['thumbnail_width'];																														//== 가로 비율
				$thum_height=(int)($img_size[1] * ($board_info['thumbnail_width']/$img_size[0]));			//== 세로 비율
				thumbnail("./files/".$_GET['code']."/","./files/".$_GET['code']."/thumbnail/",$filename[$i],$thum_width,$thum_height);
			}
			if($filename[$i]) {
				if($board_info['thumbnail_width']) $thum_width = $board_info['thumbnail_width']; else $thum_width = 120;
				if($board_info['thumbnail_height']) $thum_height = $board_info['thumbnail_height']; else $thum_height = 90;
				thumbnail("./files/".$_GET['code']."/","./files/".$_GET['code']."/thumbnail/",$filename[$i],$thum_width,$thum_height);
			}

/*
					$sPath="files/".$_GET[code]."/";
					$dPath="files/".$_GET[code]."/thumbnail/";
					$exName=explode(".",$filename[$i]);
					$dName=$exName[0];
					$dExt=$exName[1];
					//Thumbnail::setOption('debug', true);
					Thumbnail::create($sPath.$filename[$i],
						$board_info[thumbnail_width], $board_info[thumbnail_height],
						SCALE_EXACT_FIT,
						Array(
							//'savepath' => '%PATH%%FILENAME%_thumb-case-1.%EXT%'
						'savepath' => $dPath.'%FILENAME%.'.$dExt
					));
*/
			//== 원본이미지 최대 크기가 지정된 경우 원본 썸네일 처리
			if($board_info['img_max_upload_width']>0 && $filename[$i]) {
				//== 원본이미지 크기 추출후 상하 비율 측정
				$img_size = @getimagesize("./files/".$_GET['code']."/".$filename[$i]);
				//== 원본이미지가 지정된 최대크기보다 클경우 섬넬 처리
				if($img_size[0]>$board_info['img_max_upload_width']) {
					if($img_size[0]>$board_info['img_max_upload_width']) {
						$img_width=$board_info['img_max_upload_width'];																											//== 가로 비율
						$img_height=(($board_info['img_max_upload_width']*$img_size[1])/$img_size[0]);				//== 세로 비율
						thumbnail("./files/".$_GET['code']."/","./files/".$_GET['code']."/",$filename[$i],$img_width,$img_height);
					}
				}
			}
		}
	}
}
//== 사용자 IP
$userIp=getenv('REMOTE_ADDR');
//== 비밀게시판일경우
if($board_info['onlySecret']>0) $_POST['secret']=1;

//=====================
//== 새글 작성 처리 루틴
//=====================
if($_POST['mode'] === "write") {
	//== 쿠키 저장(1개월)
	if($board_info['set_cookie_data'] == 1) {
		setcookie("cuk_mg_name","$_POST[name]",time()+2592000,"/");
		setcookie("cuk_mg_email","$_POST[email]",time()+2592000,"/");
		setcookie("cuk_mg_home","$_POST[homepage]",time()+2592000,"/");
	}else {
		setcookie("cuk_mg_name","",0,"/");
		setcookie("cuk_mg_email","",0,"/");
		setcookie("cuk_mg_home","",0,"/");
	}
	//== 새로 올릴 게시물의 idx값 추출
	$rst=$db->query("select max(idx), max(fid) from $b_cfg_tb[1]");
	if(DB::isError($rst)) die($rst->getMessage()); else $rows = $rst->fetchRow(DB_FETCHMODE_ORDERED);
	if($rows[0]) $new_idx = $rows[0] + 1;else $new_idx = 1;
	if($rows[1]) $new_fid = $rows[1] + 1; else $new_fid = 1;

	//== 민원처리시스템
	if($board_info['ps_center']>0) {
		$ps_sql_first = "svc_tel, ";
		$ps_sql_second = "'$_POST[svc_tel]', ";
	}

	//== 게시물 입력 질의
	$main_sql_str = "insert into $b_cfg_tb[1] (idx, code, fid, name, email, homepage, subject, ucontents, keytag, passwd, thread, html, auto_enter, secret, re_email, notice, approve, filename0, filename1, filename2, filename3, filename4, filename5, filename6, filename7, filename8, mem_id, b_class, ".$ps_sql_first."signdate, signtime, etc01, etc02, etc03, userip) values ($new_idx, '$_GET[code]', $new_fid, '$_POST[name]', '$_POST[email]', '$homepy', '$subject', '$ucontents', '$_POST[keytag]', password('$_POST[passwd]'), 'A', '$_POST[html]', '$_POST[auto_enter]', '$_POST[secret]', '$_POST[re_email]', '$_POST[notice]', '$_POST[approve]', '$filename[0]', '$filename[1]','$filename[2]', '$filename[3]', '$filename[4]', '$filename[5]','$filename[6]', '$filename[7]','$filename[8]', '$_SESSION[my_id]', '$_POST[b_class]', ".$ps_sql_second."now(), now(), '$_POST[etc01]', '$_POST[etc02]', '$_POST[etc03]', '$userIp')";

	$m_ment="새글을 등록중입니다.";

//=====================
//== 답글 작성 처리 루틴
//=====================
}else if($_POST['mode'] === "reple") {
	//== 원글의 입력값으로부터 답변글에 입력할 정보(정렬 및 indent에 필요한 thread필드값)를 추출
	$sql_str = "select thread,right(thread,1) from $b_cfg_tb[1] where code='$_GET[code]' and fid=$_POST[fid] and length(thread)=length('$_POST[thread]')+1 and locate('$_POST[thread]',thread)=1 order by thread desc";
	$rst=$db->query($sql_str);
	if(DB::isError($rst)) die($rst->getMessage()); else $rows = $rst->fetchRow(DB_FETCHMODE_ORDERED);

	if($rows) {
		$thread_head = substr($rows[0],0,-1);
		$thread_foot = ++$rows[1];
		$new_thread = $thread_head . $thread_foot;
	}else {
		$new_thread = $_POST['thread'] . "A";
	}

	//== 응답글 입력 질의문
	$main_sql_str = "insert into $b_cfg_tb[1] (fid, code, name, email, homepage, subject, ucontents, keytag, passwd, thread, html, auto_enter, secret, re_email, approve, filename0, filename1, filename2, filename3, filename4, filename5, filename6, filename7, filename8, mem_id, b_class, signdate, signtime) values ('$_POST[fid]', '$_GET[code]', '$_POST[name]', '$_POST[email]', '$homepy', '$subject', '$ucontents', '$_POST[keytag]', password('$_POST[passwd]'), '$new_thread', '$_POST[html]', '$_POST[auto_enter]', '$_POST[secret]', '$_POST[re_email]', '$_POST[approve]', '$filename[0]', '$filename[1]', '$filename[2]', '$filename[3]', '$filename[4]', '$filename[5]','$filename[6]', '$filename[7]','$filename[8]', '$_SESSION[my_id]', '$_POST[b_class]', now(), now())";
	$m_ment="답변글을 등록중입니다.";

//=====================
//== 글  수정  처리 루틴
//=====================
}else if($_POST['mode'] === "edit") {
	//== 임의의 URL 삭제 방지를 위한 히든 데이터 확인
	if(!$_GET['idx'] || !$_GET['code']) error_view(999, "죄송합니다. 요청하신 페이지는 열람이 불가합니다.","올바른 방법으로 URL을 요청하세요.");
	//== 업로드 데이터 체크
	for($i=0; $i<$board_info['upload_count']; $i++) {
		if($filename[$i]) {
			$delete_file = "./files/".$_GET['code']."/".$_POST['up_file'.$i];
			if(is_file($delete_file)==true) unlink($delete_file);
			$delete_file_thumbnail = "./files/".$_GET['code']."/thumbnail/".$_POST['up_file'.$i];
			if(is_file($delete_file_thumbnail)==true) unlink($delete_file_thumbnail);
		}else {
			$filename[$i]=$_POST['up_file'.$i];
		}
		//== 삭제파일
		if($_POST['deletefile'.$i]) {
			$delete_file = "./files/".$_GET['code']."/".$_POST['deletefile'.$i];
			if(is_file($delete_file)==true) unlink($delete_file);
			$delete_file_thumbnail = "./files/".$_GET['code']."/thumbnail/".$_POST['deletefile'.$i];
			if(is_file($delete_file_thumbnail)==true) unlink($delete_file_thumbnail);
			$filename[$i]="";
		}
	}
	if(member_session(1) || $board_info['adminid'] == $_SESSION['my_id']) {
		$mem_admin="notice='$_POST[notice]', approve='$_POST[approve]', ";
		//if($_POST[signdate]) $mem_admin .= "signdate='$_POST[signdate]', ";
		//== 민원처리
		if($board_info['ps_center']>0) {
			if($_POST['svc_name']) $mem_admin .= "svc_name='$_POST[svc_name]', ";
			if($_POST['svc_status']) $mem_admin .= "svc_status='$_POST[svc_status]', ";
			if($_POST['svc_reply']) $mem_admin .= "svc_reply='$_POST[svc_reply]', ";
			$mem_admin .= "svc_date=now(), ";
		}
	}
	if(member_session(1) == false && $board_info['ps_center']>0) {
		if($_POST['svc_grade']) $mem_admin .= "svc_grade='$_POST[svc_grade]', ";
		if($_POST['svc_tel']) $mem_admin .= "svc_tel='$_POST[svc_tel]', ";
	}
	//== 수정 질의문
	$main_sql_str = "update $b_cfg_tb[1] set name='$_POST[name]', subject='$subject', email='$_POST[email]', homepage='$homepy', ucontents='$ucontents', keytag='$_POST[keytag]', html='$_POST[html]', auto_enter='$_POST[auto_enter]', secret='$_POST[secret]', re_email='$_POST[re_email]', ".$mem_admin."filename0='$filename[0]', filename1='$filename[1]', filename2='$filename[2]', filename3='$filename[3]', filename4='$filename[4]', filename5='$filename[5]', filename6='$filename[6]', filename7='$filename[7]', filename8='$filename[8]', b_class='$_POST[b_class]', etc01='$_POST[etc01]', etc02='$_POST[etc02]', etc03='$_POST[etc03]'";
	//== 게시판 글과 등록 이미지 경로 이동
	if(member_session(1)==true && $_POST['move_code'] && $_POST['move_code']!=$_GET['code']) {
		$main_sql_str .= ", code='$_POST[move_code]'";
		//== 등록된 첨부파일 삭제
		$add_move = "select filename0,filename1,filename2,filename3,filename4,filename5,filename6,filename7,filename8 from $b_cfg_tb[1] where idx=$_GET[idx] and code='$_GET[code]'";
		$rows = $db->getRow($add_move,DB_FETCHMODE_ASSOC);
		if(DB::isError($rows)) die($rows->getMessage());
		for($i=0; $i<=count($rows); $i++) {
			if($rows['filename'.$i]) {
				//== 이동[원본]
				$move_source = "files/".$_GET['code']."/".$rows['filename'.$i];
				$move_destin="files/".$_POST['move_code']."/".$rows['filename'.$i];
				if(!is_dir("files/".$_POST['move_code'])) mkdir("files/".$_POST['move_code'],0777);
				if(is_file($move_source)) copy($move_source, $move_destin);
				//== 이동[썸네일]
				$move_thum_source = "files/".$_GET['code']."/thumbnail/".$rows['filename'.$i];
				$move_thum_destin="files/".$_POST['move_code']."/thumbnail/".$rows['filename'.$i];
				if(!is_dir("files/".$_POST['move_code']."/thumbnail/")) mkdir("files/".$_POST['move_code']."/thumbnail/",0777);
				if(is_file($move_thum_source)) copy($move_thum_source, $move_thum_destin);
				//== 삭제
				$del_file = "files/".$_GET['code']."/".$rows['filename'.$i];
				if(is_file($del_file)) unlink($del_file);
				$del_file_thum = "files/".$_GET['code']."/thumbnail/".$rows['filename'.$i];
				if(is_file($del_file_thum)) unlink($del_file_thum);
			}
		}
	}
	$main_sql_str .=" where code='$_GET[code]' and idx=$_GET[idx]";
	$m_ment="글을 수정중입니다.";


//=====================
//== 글  삭제  처리 루틴
//=====================
}else if($_POST['mode'] == "delete") {
	//== 임의의 URL 삭제 방지를 위한 히든 데이터 확인
	if(!$_GET['idx'] || !$_GET['code']) error_view(999, "죄송합니다. 요청하신 페이지는 열람이 불가합니다.","올바른 방법으로 URL을 요청하세요.");
	//== 답변글이 있을경우 삭제 불가
	if($board_info['allow_delete_thread'] > 0) {
		$sql_str = "select count(thread) from $b_cfg_tb[1] where fid=$_POST[fid] and length(thread) = length('$_POST[thread]')+1 and locate('$_POST[thread]',thread) = 1";
		$rows = $db->getOne($sql_str);
		if(DB::isError($rows)) die($rows->getMessage());
		if($rows>0) js_action(1, "답변글이 있는 글을 삭제가 불가능합니다.", "", -1);
	}
	//== 관리자도 아니고 자신의 글도 안일경우 비밀번호 비교
	if(login_session() == false || (member_session(1) == false && strcmp($_POST['mem_id'],$_SESSION['my_id']))) {
		if(compare_pass($b_cfg_tb[1], $_GET['code'], $_GET['idx'], $_POST['passwd'])==false) js_action(1, "죄송합니다. 비밀번호가 일치하지 않습니다.", "", -1);
	}
	//== 등록된 첨부파일 삭제
	$add_del = "select filename0,filename1,filename2,filename3,filename4,filename5,filename6,filename7,filename8 from $b_cfg_tb[1] where idx=$_GET[idx] and code='$_GET[code]'";
	$rows = $db->getRow($add_del,DB_FETCHMODE_ASSOC);
	if(DB::isError($rows)) die($rows->getMessage());
	for($i=0; $i<=count($rows); $i++) {
		if($rows['filename'.$i]) {
				$delete_file = "files/".$_GET['code']."/".$rows['filename'.$i];
				if(is_file($delete_file)) unlink($delete_file);
				$delete_file_tumbnail = "files/".$_GET['code']."/thumbnail/".$rows['filename'.$i];
				if(is_file($delete_file_tumbnail)) unlink($delete_file_tumbnail);
		}
	}

	//== 에디터 파일 삭제
	$imgFolder="b_".$_GET['code']."_".$_GET['idx'];
	$delPath=$_SERVER["DOCUMENT_ROOT"]."/eUpload/".$imgFolder;
	removeDir($delPath);

	//== 코멘트 삭제
	$sql_str = "delete from $b_cfg_tb[2] where board_idx='$_GET[idx]' and code='$_GET[code]'";
	$rst=$db->query($sql_str);
	if(DB::isError($rst)) die($rst->getMessage());

	//== 게시물 삭제
	$main_sql_str = "delete from $b_cfg_tb[1] where code='$_GET[code]' and idx=$_GET[idx]";
	$m_ment="글을 삭제중입니다.";

//=====================
//== 수정과 비밀글 처리
//=====================
}else if($_POST['mode']==="check" || $_POST['mode']==="secret") {
	//== 관리자도 아니고 자신의 글도 안일경우 비밀번호 비교
	if(compare_pass($b_cfg_tb[1], $_GET['code'], $_GET['idx'], $_POST['passwd']) == false) {
		js_action(1, "죄송합니다. 비밀번호가 일치하지 않습니다.", "", -1);
	}else {
		if($_POST['mode']==="secret") {
			//== 비밀글 10분간 열람설정 쿠키
			$secretCookie="secret".$_GET['code'].$_GET['idx'];
			if(!$_COOKIE[$secretCookie]) setcookie($secretCookie,base64_encode($secretCookie),time()+600,"/");
			redirect(1, "view.php?code=$_GET[code]&idx=$_GET[idx]&page=$_GET[page]&keyword=$_GET[keyword]&s_1=$_GET[s_1]&s_2=$_GET[s_2]&s_3=$_GET[s_3]","잠시만 기다려주세요.", 0);
		}elseif($_POST['mode']==="check") {
			//== 글수정 30분간 열람설정 쿠키
			$editCookie="edit".$_GET['code'].$_GET['idx'];
			if(!$_COOKIE[$editCookie]) setcookie($editCookie,base64_encode($editCookie),time()+1800,"/");
			redirect(1, "edit.php?code=$_GET[code]&idx=$_GET[idx]&page=$_GET[page]&keyword=$_GET[keyword]&s_1=$_GET[s_1]&s_2=$_GET[s_2]&s_3=$_GET[s_3]","잠시만 기다려주세요.", 0);
		}else {
			error_view(999, "작업모드가 선택되지 않았습니다.","올바른 방법으로 이용해 보세요.");
		}
	}

//=====================
//== 선택된 글 전체삭제
//=====================
}else if($_POST['mode']==="sBox") {

	if(count($_POST['sBox'])<=0) js_action(1,"삭제할 글을 1개 이상 선택하셔야 합니다.","",-1);
	for($i=0; $i<count($_POST['sBox']); $i++) aDelete($_POST['sBox'][$i]);
	redirect(1, "./list.php?code=$_GET[code]&page=$_GET[page]&keyword=$_GET[keyword]&s_1=$_GET[s_1]&s_2=$_GET[s_2]&s_3=$_GET[s_3]", "선택글 삭제중입니다.", 1);

}else {
	error_view(999, "작업모드가 선택되지 않았습니다.","올바른 방법으로 이용하세요..");
}

//=================================
//== 게시물 입력 수정 삭제  처리루틴
//=================================

$rst=$db->query($main_sql_str);
if(DB::isError($rst)) die($rst->getMessage()); else
redirect(1, "./list.php?code=$_GET[code]&page=$_GET[page]&keyword=$_GET[keyword]&s_1=$_GET[s_1]&s_2=$_GET[s_2]&s_3=$_GET[s_3]", $m_ment, 0);
$db->disconnect();
//=====================================
//=============== E N D ===============
//=====================================
?>