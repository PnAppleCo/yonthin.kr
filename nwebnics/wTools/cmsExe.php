<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//== 파일 업로드
$filename = $_FILES['filename']['name'];
$filetype = $_FILES['filename']['type'];
$filesize = $_FILES['filename']['size'];
$filetmp = $_FILES['filename']['tmp_name'];
$fileerror = $_FILES['filename']['error'];

$up_load = file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, 99000000, $cmsDir,"");
//== 리턴받은 파일이름 지정(마지막에 ","가 삽입되어 있으므로 하나 빼줌)
$name_count = explode(",", $up_load);
for($i=0;$i<sizeof($name_count)-1;$i++) {
	$filename[$i] = $name_count[$i];
	$img_array = array("image/gif", "image/pjpeg", "image/jpeg", "image/bmp", "image/png","image/wbmp");
	if(in_array($filetype[$i], $img_array) && $filesize[$i]>0) {
		thumbnail($_SERVER["DOCUMENT_ROOT"].$cmsDir,$_SERVER["DOCUMENT_ROOT"].$cmsDir."thumbnail/",$filename[$i],160,105);
		//== 원본이미지 크기 추출후 상하 비율 측정
		$img_size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$cmsDir.$filename[$i]);
		//== 원본이미지가 지정된 최대크기보다 클경우 섬넬 처리
		if($img_size[0]>740) {
			$img_width=740;																													//== 가로 비율
			$img_height=(int)($img_size[1] * (740/$img_size[0]));		//== 세로 비율
			thumbnail($_SERVER["DOCUMENT_ROOT"].$cmsDir,$_SERVER["DOCUMENT_ROOT"].$cmsDir,$filename[$i],$img_width,$img_height);
		}
	}
}

//== 상세정보 슬래쉬 붙입
//$_POST[uContents]=addslashes($_POST[uContents]);
$_POST['cmsName']=str_replace("&nbsp;", " ", $_POST['cmsName']);

//== 등록 처리 ====================================================================================

if($_GET['mode']==="add") {

	//============================ 코드 중북 확인 ============================//
	$sqlStr = "SELECT COUNT(idx) FROM cmsList WHERE cmsCode='".$_POST['cmsCode']."'";
	$mCnt = $db->getOne($sqlStr);
	if(DB::isError($mCnt)) die($mCnt->getMessage());
	if($mCnt>0) js_action(1,$_POST['cmsCode']."은 이미 이미 등록되었습니다.","",-1);

	//== 새로 등록할 구독의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM cmsList");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	$sqlStr = "INSERT INTO cmsList(idx, cmsDivi, cmsName, cmsCode, cmsPath, filename0, filename1, uContents, cStatus, signDate) VALUES ('".$new_idx."', '".$_POST['cmsDivi']."', '".$_POST['cmsName']."', '".$_POST['cmsCode']."', '".$_POST['cmsPath']."', '".$filename[0]."', '".$filename[1]."', '".$_POST['uContents']."', '".$_POST['cStatus']."', now())";

//== 수정 처리 ====================================================================================

}else if($_GET['mode']==="edit") {
	if(member_session(1)==true && $_GET['idx']) {
		//== 첨부파일 수정/삭제
		for($i=0; $i<2; $i++) {
			if($filename[$i]) {																			//== 수정
				$delFile = $_SERVER['DOCUMENT_ROOT'].$cmsDir.$_POST['upFile'][$i];
				if(is_file($delFile)==true) unlink($delFile);
				$delThubFile = $_SERVER['DOCUMENT_ROOT'].$cmsDir."thumbnail/".$_POST['upFile'][$i];
				if(is_file($delThubFile)==true) unlink($delThubFile);
			}else {
				$filename[$i]=$_POST['upFile'][$i];
			}
			if($_POST['fChk'.$i]=="chk") {											//== 삭제
				$delete_file = $_SERVER['DOCUMENT_ROOT'].$cmsDir.$_POST['upFile'][$i];
				if(is_file($delete_file)==true) unlink($delete_file);
				$delete_file_thumbnail = $_SERVER['DOCUMENT_ROOT'].$cmsDir."thumbnail/".$_POST['upFile'][$i];
				if(is_file($delete_file_thumbnail)==true) unlink($delete_file_thumbnail);
				$filename[$i]="";
			}
		}
		$sqlStr = "UPDATE cmsList SET cmsDivi='".$_POST['cmsDivi']."', cmsName='".$_POST['cmsName']."', cmsCode='".$_POST['cmsCode']."', cmsPath='".$_POST['cmsPath']."', filename0='".$filename[0]."', filename1='".$filename[1]."', uContents='".$_POST['uContents']."', cStatus='".$_POST['cStatus']."', editDate=now() WHERE idx=".$_GET['idx']."";

	}else { js_action(1,"관리자 권한입니다.","",-1); }

//== 삭제 처리 ====================================================================================

}else if($_GET['mode']==="del") {
	if(member_session(1)==true && $_GET['idx']) {
		//== 등록된 첨부파일 삭제
		$add_del = "SELECT filename0, filename1 FROM cmsList WHERE idx=$_GET[idx]";
		$rows = $db->getRow($add_del,DB_FETCHMODE_ASSOC);
		if(DB::isError($rows)) die($rows->getMessage());
		for($i=0; $i<=count($rows); $i++) {
			if($rows['filename'.$i]) {
					$delete_file = $_SERVER['DOCUMENT_ROOT'].$cmsDir.$rows['filename'.$i];
					if(is_file($delete_file)) unlink($delete_file);
					$delete_file_tumbnail = $_SERVER['DOCUMENT_ROOT'].$cmsDir."thumbnail/".$rows['filename'.$i];
					if(is_file($delete_file_tumbnail)) unlink($delete_file_tumbnail);
			}
		}
		//== 에디터 파일 삭제
		$imgFolder="cms_".$_GET['idx'];
		$delPath=$_SERVER["DOCUMENT_ROOT"].$editorDir.$imgFolder;
		removeDir($delPath);

		$sqlStr= "DELETE FROM cmsList WHERE idx=".$_GET['idx']."";
	}else { js_action(1,"관리자만이 삭제할수 있습니다.","",-1); }
}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 처리 =====================================================================================

if($_GET['mode']==="add") {
	if(member_session(1)==true) $m_url="cmsList.php";
	$p_ment="콘텐츠 등록중입니다.";
}else if($_GET['mode']==="edit") {
	$m_url="cmsList.php?page=".$_GET['page'];
	$p_ment="콘텐츠 수정중입니다.";
}else if($_GET['mode']==="del") {
	$m_url="cmsList.php?page=".$_GET['page'];
	$p_ment="콘텐츠 삭제중입니다.";
}else {
	$m_url="/";
}

$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$m_url,$p_ment,1);
$db->disconnect();
?>