<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'boram'
//== last modify date : 2018. 6. 10
//==================================================================
//== 게시판 기본정보 로드
include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wBoard/inc/boardLib.php");
//include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/Thumbnail.class.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

$sCode=$_REQUEST['code'];

//== 파일 업로드
$filename = $_FILES['filename']['name'];
$filetype = $_FILES['filename']['type'];
$filesize = $_FILES['filename']['size'];
$filetmp = $_FILES['filename']['tmp_name'];
$fileerror = $_FILES['filename']['error'];

if($_POST['code']) $codeVal=$_POST['code']; else if($sCode) $codeVal=$sCode; else $codeVal="";
$boardPath=$boardDir.$codeVal."/";

// PHP82 변환 추가
if(!empty($filename))
{
	$up_load = file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, 10000000, $boardPath,"");

	//== 리턴받은 파일이름 지정(마지막에 "|"가 삽입되어 있으므로 하나 빼줌)
	$name_count = explode("|", $up_load);

	for($i=0;$i<sizeof($name_count)-1;$i++) {
		$filename[$i] = $name_count[$i];
		$img_array = array("image/gif", "image/pjpeg", "image/jpeg", "image/bmp", "image/png","image/wbmp");
		if(in_array($filetype[$i], $img_array) && $filesize[$i]>0) {

			//== 이미지 첫장 작은 썸네일 처리
			$img_size = @getimagesize("./files/".$sCode."/".$filename[$i]);

			if($img_size[0]>$board_info['thumbnail_width']) {
				$thum_width=$board_info['thumbnail_width'];																														//== 가로 비율
				$thum_height=(int)($img_size[1] * ($board_info['thumbnail_width']/$img_size[0]));			//== 세로 비율
				thumbnail($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wBoard/files/".$sCode."/",$_SERVER["DOCUMENT_ROOT"]."/nwebnics/wBoard/files/".$sCode."/thumbnail/",$filename[$i],$thum_width,$thum_height);
			}
			if($filename[$i]) {
				if($board_info['thumbnail_width']) $thum_width = $board_info['thumbnail_width']; else $thum_width = 120;
				if($board_info['thumbnail_height']) $thum_height = $board_info['thumbnail_height']; else $thum_height = 90;
				thumbnail($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wBoard/files/".$sCode."/",$_SERVER["DOCUMENT_ROOT"]."/nwebnics/wBoard/files/".$sCode."/thumbnail/",$filename[$i],$thum_width,$thum_height);
			}

			if($board_info['img_max_upload_width']>0 && $filename[$i]) {
				//== 원본이미지 크기 추출후 상하 비율 측정
				$img_size = @getimagesize("./files/".$sCode."/".$filename[$i]);
				//== 원본이미지가 지정된 최대크기보다 클경우 섬넬 처리
				if($img_size[0]>$board_info['img_max_upload_width']) {
					if($img_size[0]>$board_info['img_max_upload_width']) {
						$img_width=$board_info['img_max_upload_width'];																											//== 가로 비율
						$img_height=(($board_info['img_max_upload_width']*$img_size[1])/$img_size[0]);				//== 세로 비율
						thumbnail("./files/".$sCode."/","./files/".$sCode."/",$filename[$i],$img_width,$img_height);
					}
				}
			}

	/*
			$sPath=$_SERVER["DOCUMENT_ROOT"].$boardPath;
			$dPath=$_SERVER["DOCUMENT_ROOT"].$boardPath."/thumbnail/";
			$exName=explode(".",$filename[$i]);
			$dName=$exName[0];
			$dExt=$exName[1];
			$waterMark = new ThumbnailWatermark;
			//Thumbnail::setOption('debug', true);

			Thumbnail::create($sPath.$filename[$i],
				$board_info[thumbnail_width], $board_info[thumbnail_height],
				SCALE_EXACT_FIT,
				Array(
				//'preprocess' => Array(&$waterMark, 'preprocess'),
				'savepath' => $dPath.'%FILENAME%.'.$dExt
			));

			//== 원본이미지 크기 추출후 상하 비율 측정
			$img_size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$boardPath.$filename[$i]);
			//== 원본이미지가 지정된 최대크기보다 클경우 섬넬 처리
			if($img_size[0]>1080) {
				$img_width=1080;																													//== 가로 비율
				$img_height=(int)($img_size[1] * (1000/$img_size[0]));			//== 세로 비율
				//thumbnail($_SERVER["DOCUMENT_ROOT"].$boardPath,$_SERVER["DOCUMENT_ROOT"].$boardPath,$filename[$i],$img_width,$img_height);
				$ssPath=$_SERVER["DOCUMENT_ROOT"].$boardPath;
				$sdPath=$_SERVER["DOCUMENT_ROOT"].$boardPath;
				Thumbnail::create($ssPath.$filename[$i],
					1080, 750,
					SCALE_EXACT_FIT,
					Array(
					//'preprocess' => Array(&$waterMark, 'preprocess'),
					'savepath' => $sdPath.'%FILENAME%.'.$dExt
				));
			}
	*/
		}
	}
}



//== 사용자 IP
$userIp=getenv('REMOTE_ADDR');
//== 비밀게시판일경우
if($board_info['onlySecret']>0) $_POST['secret']=1;
//== 제목과 내용의 특수문자 처리
$subject = addslashes($_POST['subject']);
$ucontents = addslashes($_POST['ucontents']);

//== 등록 처리 ====================================================================================
if($_GET['mode']=="add") {
	//== 새로 등록할 고유번호 생성
	$rst=$db->query("SELECT max(idx), max(fid) FROM $b_cfg_tb[1]");
	if(DB::isError($rst)) die($rst->getMessage()); else $rows = $rst->fetchRow(DB_FETCHMODE_ORDERED);
	if($rows[0]) $new_idx = $rows[0] + 1;else $new_idx = 1;
	if($rows[1]) $new_fid = $rows[1] + 1; else $new_fid = 1;

	$mSqlStr = "INSERT INTO $b_cfg_tb[1] (idx, code, fid, name, email, homepage, subject, ucontents, keytag, passwd, thread, html, auto_enter, secret, re_email, notice, approve, filename0, filename1, filename2, filename3, filename4, filename5, filename6, filename7, filename8, mem_id, b_class, signdate, signtime, etc01, etc02, etc03, youtube, userip) VALUES ($new_idx, '$_POST[code]', $new_fid, '$_POST[name]', '$_POST[email]', '$homepy', '$subject', '$ucontents', '$_POST[keytag]', password('$_POST[passwd]'), 'A', '$_POST[html]', '$_POST[auto_enter]', '$_POST[secret]', '$_POST[re_email]', '$_POST[notice]', '$_POST[approve]', '$filename[0]', '$filename[1]','$filename[2]', '$filename[3]', '$filename[4]', '$filename[5]','$filename[6]', '$filename[7]','$filename[8]', '$_SESSION[my_id]', '$_POST[b_class]', now(), now(), '$_POST[etc01]', '$_POST[etc02]', '$_POST[etc03]', '$_POST[youtube]', '$userIp')";

//== 수정 처리 ====================================================================================
}else if($_GET['mode']=="edit") {
	if(!$_GET['idx']) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

	//== 기존첨부파일(게시판 코드 변경시 기존에 등록된 첨부 자료 삭제)
	if($_POST['code'] != $_POST['oCode']) {
		$moveStr = "SELECT filename0,filename1,filename2,filename3,filename4,filename5,filename6,filename7,filename8 FROM $b_cfg_tb[1] WHERE idx=$_GET[idx]";
		$moveFile = $db->getRow($moveStr,DB_FETCHMODE_ASSOC);
		if(DB::isError($moveFile)) die($moveFile->getMessage());
		$boardDir=$_SERVER['DOCUMENT_ROOT'].$boardDir;
		for($i=0; $i<=count($moveFile); $i++) {
			if($moveFile['filename'.$i]) {
				//== 이동[원본]
				$move_source = $boardDir.$_POST['oCode']."/".$moveFile['filename'.$i];
				$move_destin=$boardDir.$_POST['code']."/".$moveFile['filename'.$i];
				if(!is_dir($boardDir.$_POST['code'])) mkdir($boardDir.$_POST['code'],0777);
				if(is_file($move_source)) copy($move_source, $move_destin);
				//== 이동[썸네일]
				$move_thum_source = $boardDir.$_POST['oCode']."/thumbnail/".$moveFile['filename'.$i];
				$move_thum_destin=$boardDir.$_POST['code']."/thumbnail/".$moveFile['filename'.$i];
				if(!is_dir($boardDir.$_POST['code']."/thumbnail/")) mkdir($boardDir.$_POST['code']."/thumbnail/",0777);
				if(is_file($move_thum_source)) copy($move_thum_source, $move_thum_destin);
				//== 삭제
				$del_file = $boardDir.$_POST['oCode']."/".$moveFile['filename'.$i];
				if(is_file($del_file)) unlink($del_file);
				$del_file_thum = $boardDir.$_POST['oCode']."/thumbnail/".$moveFile['filename'.$i];
				if(is_file($del_file_thum)) unlink($del_file_thum);
			}
		}
		//== 스마트에디터 이미지 이동 처리
	}

	//== 새로 등록된 첨부파일
	for($i=0; $i<10; $i++) {
		if($filename[$i]) {																			//== 수정
			$delFile = $_SERVER['DOCUMENT_ROOT'].$boardPath.$_POST['upFile'][$i];
			if(is_file($delFile)==true) unlink($delFile);
			$delThubFile = $_SERVER['DOCUMENT_ROOT'].$boardPath."thumbnail/".$_POST['upFile'][$i];
			if(is_file($delThubFile)==true) unlink($delThubFile);
		}else {
			$filename[$i]=$_POST['upFile'][$i];
		}
		if($_POST['fChk'.$i]=="chk") {											//== 삭제
			$delete_file = $_SERVER['DOCUMENT_ROOT'].$boardPath.$_POST['upFile'][$i];
			if(is_file($delete_file)==true) unlink($delete_file);
			$delete_file_thumbnail = $_SERVER['DOCUMENT_ROOT'].$boardPath."thumbnail/".$_POST['upFile'][$i];
			if(is_file($delete_file_thumbnail)==true) unlink($delete_file_thumbnail);
			$filename[$i]="";
		}
	}

	if($_POST['signdate']) $addStr = ", signdate='$_POST[signdate]', signtime='$_POST[signtime]'";

	$mSqlStr = "UPDATE $b_cfg_tb[1] SET code='$_POST[code]', name='$_POST[name]', subject='$subject', email='$_POST[email]', homepage='$homepy', ucontents='$ucontents', keytag='$_POST[keytag]', html='$_POST[html]', auto_enter='$_POST[auto_enter]', secret='$_POST[secret]', re_email='$_POST[re_email]', filename0='$filename[0]', filename1='$filename[1]', filename2='$filename[2]', filename3='$filename[3]', filename4='$filename[4]', filename5='$filename[5]', filename6='$filename[6]', filename7='$filename[7]', filename8='$filename[8]', b_class='$_POST[b_class]', etc01='$_POST[etc01]', etc02='$_POST[etc02]', etc03='$_POST[etc03]', youtube='$_POST[youtube]'".$addStr." WHERE idx=$_GET[idx]";

//== 삭제 처리 ====================================================================================
}else if($_GET['mode']=="del" && $_GET['idx']) {
	if(!$_GET['idx'] || !member_session(1)) js_action(1,"중요정보를 찾을수 없습니다.","",-1);
	//== 등록된 첨부파일 삭제
	$delStr = "SELECT filename0,filename1,filename2,filename3,filename4,filename5,filename6,filename7,filename8 FROM $b_cfg_tb[1] WHERE idx=$_GET[idx]";

	$uFiles = $db->getRow($delStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($uFiles)) die($uFiles->getMessage());
	for($i=0; $i<=count($uFiles); $i++) {
		if($uFiles['filename'.$i]) {
			$delete_file = $_SERVER['DOCUMENT_ROOT'].$boardPath.$uFiles['filename'.$i];
			if(is_file($delete_file)) unlink($delete_file);
			$delete_file_tumbnail = $_SERVER['DOCUMENT_ROOT'].$boardPath."thumbnail/".$uFiles['filename'.$i];
			if(is_file($delete_file_tumbnail)) unlink($delete_file_tumbnail);
		}
	}

	//== 에디터 파일 삭제
	$imgFolder="b_".$codeVal."_".$_GET['idx'];
	$delPath=$_SERVER["DOCUMENT_ROOT"].$editorDir.$imgFolder;
	removeDir($delPath);

	//== 코멘트 삭제
	$commentStr = "DELETE FROM $b_cfg_tb[2] WHERE board_idx='$_GET[idx]'";
	$rst=$db->query($commentStr);
	if(DB::isError($rst)) die($rst->getMessage());

	//== 게시물 삭제
	$mSqlStr = "DELETE FROM $b_cfg_tb[1] WHERE idx=$_GET[idx]";

}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 작업 처리 =====================================================================================
if($_GET['mode']=="add") {
	$p_ment="등록 처리중입니다. 잠시만 기다려주십시오.";
}else if($_GET['mode']=="edit") {
	$p_ment="수정 처리중입니다. 잠시만 기다려주십시오.";
}else if($_GET['mode']=="del") {
	$p_ment="삭제 처리중입니다. 잠시만 기다려주십시오.";
}else { $p_ment="오류가 발생하였습니다."; }

$rst=$db->query($mSqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,"articleList.php?idx=".$_GET['idx']."&page=".$_GET['page'],$p_ment,1);
$db->disconnect();
?>