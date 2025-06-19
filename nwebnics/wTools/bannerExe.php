<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 6. 10
//==================================================================
//== 게시판 기본정보 로드
include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wTools/inc/configInc.php");
include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/Thumbnail.class.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//== 파일 업로드
$filename = $_FILES['filename']['name'];
$filetype = $_FILES['filename']['type'];
$filesize = $_FILES['filename']['size'];
$filetmp = $_FILES['filename']['tmp_name'];
$fileerror = $_FILES['filename']['error'];

$up_load = file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, 10000000, $bannerDir,"");

//== 리턴받은 파일이름 지정(마지막에 ","가 삽입되어 있으므로 하나 빼줌)

$name_count = explode("|", $up_load);

for($i=0;$i<sizeof($name_count)-1;$i++) {
	$filename[$i] = $name_count[$i];
	$img_array = array("image/gif", "image/pjpeg", "image/jpeg", "image/bmp", "image/png","image/wbmp");
	if(in_array($filetype[$i], $img_array) && $filesize[$i]>0) {
		//thumbnail($_SERVER["DOCUMENT_ROOT"].$bannerDir,$_SERVER["DOCUMENT_ROOT"].$bannerDir."thumbnail/",$filename[$i],320,206);
		$sPath=$_SERVER["DOCUMENT_ROOT"].$bannerDir;
		$dPath=$_SERVER["DOCUMENT_ROOT"].$bannerDir."/thumbnail/";
		$exName=explode(".",$filename[$i]);
		$dName=$exName[0];
		$dExt=$exName[1];
		$waterMark = new ThumbnailWatermark;
		//Thumbnail::setOption('debug', true);

		Thumbnail::create($sPath.$filename[$i],
			352, 220,
			SCALE_EXACT_FIT,
			Array(
			'preprocess' => Array(&$waterMark, 'preprocess'),
			'savepath' => $dPath.'%FILENAME%.'.$dExt
		));

		//== 원본이미지 크기 추출후 상하 비율 측정
		$img_size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$bannerDir.$filename[$i]);
		//== 원본이미지가 지정된 최대크기보다 클경우 섬넬 처리
		if($img_size[0]>700) {
			$img_width=700;																													//== 가로 비율
			$img_height=(int)($img_size[1] * (700/$img_size[0]));		//== 세로 비율
			//thumbnail($_SERVER["DOCUMENT_ROOT"].$bannerDir,$_SERVER["DOCUMENT_ROOT"].$bannerDir,$filename[$i],$img_width,$img_height);
			$ssPath=$_SERVER["DOCUMENT_ROOT"].$bannerDir;
			$sdPath=$_SERVER["DOCUMENT_ROOT"].$bannerDir;
			Thumbnail::create($ssPath.$filename[$i],
				770, 500,
				SCALE_EXACT_FIT,
				Array(
				'preprocess' => Array(&$waterMark, 'preprocess'),
				'savepath' => $sdPath.'%FILENAME%.'.$dExt
			));
		}
	}
}

//== 등록 처리 ====================================================================================
if($_GET[mode]=="add") {
	//== 새로 등록할 유지보수의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM bannerTbl");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $newIdx=1; else $newIdx=$max_idx+1;
	$mSqlStr = "INSERT INTO bannerTbl (
idx,
bName,
sections,
linkUrl,
linkTarget,
filename0,
contents,
sStatus,
signDate
) VALUES (
$newIdx,
'$_POST[bName]',
'$_POST[sections]',
'$_POST[linkUrl]',
'$_POST[linkTarget]',
'$filename[0]',
'$_POST[contents]',
'$_POST[sStatus]',
now());";

//== 수정 처리 ====================================================================================
}else if($_GET[mode]=="edit") {
	if(!$_GET[idx]) js_action(1,"중요정보를 찾을수 없습니다.","",-1);
	//== 첨부파일 수정/삭제
	for($i=0; $i<5; $i++) {
		if($filename[$i]) {																			//== 수정
			$delFile = $_SERVER['DOCUMENT_ROOT'].$bannerDir.$_POST[upFile][$i];
			if(is_file($delFile)==true) unlink($delFile);
			$delThubFile = $_SERVER['DOCUMENT_ROOT'].$bannerDir."thumbnail/".$_POST[upFile][$i];
			if(is_file($delThubFile)==true) unlink($delThubFile);
		}else {
			$filename[$i]=$_POST[upFile][$i];
		}
		if($_POST[fChk.$i]=="chk") {											//== 삭제
			$delete_file = $_SERVER['DOCUMENT_ROOT'].$bannerDir.$_POST[upFile][$i];
			if(is_file($delete_file)==true) unlink($delete_file);
			$delete_file_thumbnail = $_SERVER['DOCUMENT_ROOT'].$bannerDir."thumbnail/".$_POST[upFile][$i];
			if(is_file($delete_file_thumbnail)==true) unlink($delete_file_thumbnail);
			$filename[$i]="";
		}
	}

	$mSqlStr = "UPDATE bannerTbl SET
bName='$_POST[bName]',
sections='$_POST[sections]',
linkUrl='$_POST[linkUrl]',
linkTarget='$_POST[linkTarget]',
contents='$_POST[contents]',
sStatus='$_POST[sStatus]',
filename0='$filename[0]' WHERE idx='$_GET[idx]'";

//== 삭제 처리 ====================================================================================
}else if($_GET[mode]=="del" && $_GET[idx]) {
	if(!$_GET[idx] || !member_session(1)) js_action(1,"중요정보를 찾을수 없습니다.","",-1);
	//== 등록된 첨부파일 삭제
	$add_del = "SELECT filename0 FROM bannerTbl WHERE idx=$_GET[idx]";
	$rows = $db->getRow($add_del,DB_FETCHMODE_ASSOC);
	if(DB::isError($rows)) die($rows->getMessage());
		for($i=0; $i<=count($rows); $i++) {
			if($rows[filename.$i]) {
					$delete_file = $_SERVER['DOCUMENT_ROOT'].$bannerDir.$rows[filename.$i];
					if(is_file($delete_file)) unlink($delete_file);
					$delete_file_tumbnail = $_SERVER['DOCUMENT_ROOT'].$bannerDir."thumbnail/".$rows[filename.$i];
					if(is_file($delete_file_tumbnail)) unlink($delete_file_tumbnail);
			}
		}
	$mSqlStr= "DELETE FROM bannerTbl WHERE idx=$_GET[idx]";

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
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,"bannerList.php?idx=".$_GET[idx]."&page=".$_GET[page],$p_ment,1);
$db->disconnect();
?>