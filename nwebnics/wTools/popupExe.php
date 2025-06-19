<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");
//require_once $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/Thumbnail.class.php";

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//== 파일 업로드
$filename = $_FILES['filename']['name'];
$filetype = $_FILES['filename']['type'];
$filesize = $_FILES['filename']['size'];
$filetmp = $_FILES['filename']['tmp_name'];
$fileerror = $_FILES['filename']['error'];

$up_load = file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, 10000000, $popupDir,"");
//== 리턴받은 파일이름 지정(마지막에 ","가 삽입되어 있으므로 하나 빼줌)
$name_count = explode("|", $up_load);
for($i=0;$i<sizeof($name_count)-1;$i++) {
	$filename[$i] = $name_count[$i];
	$img_array = array("image/gif", "image/pjpeg", "image/jpeg", "image/bmp", "image/png","image/wbmp");
	if(in_array($filetype[$i], $img_array) && $filesize[$i]>0) {
		thumbnail($_SERVER["DOCUMENT_ROOT"].$popupDir,$_SERVER["DOCUMENT_ROOT"].$popupDir."thumbnail/",$filename[$i],150,100);
/*
		$sPath=$_SERVER["DOCUMENT_ROOT"].$popupDir;
		$dPath=$_SERVER["DOCUMENT_ROOT"].$popupDir."/thumbnail/";
		$exName=explode(".",$filename[$i]);
		$dName=$exName[0];
		$dExt=$exName[1];

		//Thumbnail::setOption('debug', true);
		Thumbnail::create($sPath.$filename[$i],
			150, 100,
			SCALE_EXACT_FIT,
			Array(
			'savepath' => $dPath.'%FILENAME%.'.$dExt
		));
*/
/*
		//== 원본이미지 크기 추출후 상하 비율 측정
		$img_size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$popupDir.$filename[$i]);
		//== 원본이미지가 지정된 최대크기보다 클경우 섬넬 처리
		if($img_size[0]>1080) {
			$img_width=1080;																													//== 가로 비율
			$img_height=(int)($img_size[1] * (1080/$img_size[0]));		//== 세로 비율
			thumbnail($_SERVER["DOCUMENT_ROOT"].$popupDir,$_SERVER["DOCUMENT_ROOT"].$popupDir,$filename[$i],$img_width,$img_height);
		}
*/
	}
}

//== 팝업창 등록 처리 ====================================================================================

if($_GET[mode]==="add") {
	//== 새로 등록할 팝업창의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM wPopup");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
		$mSqlStr = "INSERT INTO wPopup (idx, popupTitle, popupType, startDate, stopDate, popWidth, popHeight, locationTop, locationLeft, linkUrl, linkTarget, scrollbar, ingTime, filename0, filename1, uContents, signDate ) VALUES ($new_idx, '$_POST[popupTitle]', '$_POST[popupType]', '$_POST[startDate]', '$_POST[stopDate]', '$_POST[popWidth]', '$_POST[popHeight]', '$_POST[locationTop]', '$_POST[locationLeft]', '$_POST[linkUrl]', '$_POST[linkTarget]', '$_POST[scrollbar]', '$_POST[ingTime]', '$filename[0]', '$filename[1]', '$_POST[uContents]', now());";

//== 팝업창 수정 처리 ====================================================================================

}else if($_GET[mode]==="edit") {
	if(!$_GET[idx]) js_action(1,"idx정보를 찾을수 없습니다.","",-1);
	//== 첨부파일 수정/삭제
	for($i=0; $i<5; $i++) {
		if($filename[$i]) {																			//== 수정
			$delFile = $_SERVER['DOCUMENT_ROOT'].$popupDir.$_POST[upFile][$i];
			if(is_file($delFile)==true) unlink($delFile);
			$delThubFile = $_SERVER['DOCUMENT_ROOT'].$popupDir."thumbnail/".$_POST[upFile][$i];
			if(is_file($delThubFile)==true) unlink($delThubFile);
		}else {
			$filename[$i]=$_POST[upFile][$i];
		}
		if($_POST[fChk.$i]=="chk") {											//== 삭제
			$delete_file = $_SERVER['DOCUMENT_ROOT'].$popupDir.$_POST[upFile][$i];
			if(is_file($delete_file)==true) unlink($delete_file);
			$delete_file_thumbnail = $_SERVER['DOCUMENT_ROOT'].$popupDir."thumbnail/".$_POST[upFile][$i];
			if(is_file($delete_file_thumbnail)==true) unlink($delete_file_thumbnail);
			$filename[$i]="";
		}
	}

	$mSqlStr = "UPDATE wPopup SET popupTitle='$_POST[popupTitle]', popupType='$_POST[popupType]', startDate='$_POST[startDate]', stopDate='$_POST[stopDate]', popWidth='$_POST[popWidth]', popHeight='$_POST[popHeight]', locationTop='$_POST[locationTop]', locationLeft='$_POST[locationLeft]', linkUrl='$_POST[linkUrl]', linkTarget='$_POST[linkTarget]', scrollbar='$_POST[scrollbar]', ingTime='$_POST[ingTime]', filename0='$filename[0]', filename1='$filename[1]', uContents='$_POST[uContents]' WHERE idx=".$_GET[idx]."";

//== 팝업창 삭제 처리 ====================================================================================

}else if($_GET[mode]==="del") {
	if(!$_GET[idx]) js_action(1,"idx정보를 찾을수 없습니다.","",-1);
	//== 등록된 첨부파일 삭제
	$add_del = "SELECT filename0,filename1 FROM wPopup WHERE idx=$_GET[idx]";
	$rows = $db->getRow($add_del,DB_FETCHMODE_ASSOC);
	if(DB::isError($rows)) die($rows->getMessage());
	for($i=0; $i<=count($rows); $i++) {
		if($rows[filename.$i]) {
				$delete_file = $_SERVER['DOCUMENT_ROOT'].$popupDir.$rows[filename.$i];
				if(is_file($delete_file)) unlink($delete_file);
				$delete_file_tumbnail = $_SERVER['DOCUMENT_ROOT'].$popupDir."thumbnail/".$rows[filename.$i];
				if(is_file($delete_file_tumbnail)) unlink($delete_file_tumbnail);
		}
	}
	//== 에디터 파일 삭제
	$imgFolder="popup_".$_GET[idx];
	$delPath=$_SERVER["DOCUMENT_ROOT"].$editorDir.$imgFolder;
	removeDir($delPath);

	$mSqlStr= "DELETE FROM wPopup WHERE idx=".$_GET[idx]."";

}else {
	js_action(1,"작업정보를 찾을수 없습니다.","",-1);
}

//== 질의 작업 처리 =====================================================================================

if($_GET[mode]=="add") {
	$p_ment="팝업을 등록중입니다. 완료후 이동하겠습니다.";
}else if($_GET[mode]=="edit") {
	$p_ment="팝업을 수정중입니다. 완료후 이동하겠습니다.";
}else if($_GET[mode]=="del") {
	$p_ment="팝업을 삭제중입니다. 완료후 이동하겠습니다.";
}else {
	$p_ment="예기치 못한 상황이 발생하였습니다.";
}

$rst=$db->query($mSqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1, "popupList.php?edit_idx=".$_GET[idx]."&page=".$_GET[page], $p_ment, 1);
$db->disconnect();
?>