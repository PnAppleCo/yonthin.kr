<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'jisuk'
//== last modify date : 2011. 03. 02
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");

ob_start();
ini_set("memory_limit", -1);																																								//== 대용량 다운로드시 절대 필요

//== 파일의 존재 유무 검사후 다운로드
$saved_dir = $_GET['save_dir'].'/'.$_GET['filename'];

$_GET['filename']=iconv("UTF-8", "cp949", $_GET['filename']);

if(file_exists($saved_dir)) {
	if(preg_match('/(msie)[ \/]([\w.]+)/', $_SERVER['HTTP_USER_AGENT'])) {
		if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5")) {
			//$fName = urlencode($fNa->oFileName);
			//== 파일명 앤코드 전엔 6.0에서도 필요 한듯한데.( 한글일경우 )
			Header("Cache-Control: cache, must-revalidate"); //== 바로열기
			Header("Content-Type: doesn/matter");
			Header("Content-disposition: attachment; filename=$_GET[filename]");
			Header("Content-Length: ".filesize($saved_dir));
			Header("Content-Transfer-Encoding: binary");
			Header("Pragma: no-cache");
			Header("Expires: 0");
//			Header("Cache-control: private");
		}else if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0")) {
			//$fName = urlencode($fNa->oFileName); //== 엔코더 처리
			Header("Cache-Control: cache, must-revalidate"); //== 바로열기
			Header("Content-type: application/x-msdownload");
			Header("Content-Disposition: attachment; filename=$_GET[filename]");
			Header("Content-Length: ".filesize($saved_dir));
			Header("Content-Transfer-Encoding: binary");
			Header("Pragma: no-cache");
			Header("Expires: 0");
		}else {
			Header("Content-type: file/unknown");
			Header("Content-Disposition: attachment; filename=$_GET[filename]");
			Header("Content-Description: PHP3 Generated Data");
			Header("Pragma: no-cache");
			Header("Expires: 0");
		}
	}else {
		Header("Content-type: file/unknown");
		Header("Content-Length: ".filesize($saved_dir));
		Header("Content-Disposition: attachment; filename=$_GET[filename]");
		Header("Content-Description: PHP3 Generated Data");
		Header("Pragma: no-cache");
		Header("Expires: 0");
	}

	$fp = fopen($saved_dir, "r");
	if(!fpassthru($fp)) fclose($fp);

//기타 다운로드 방법 1
// header("location:$saved_dir");
// 기타 다운로드 방법 2(강제로 다운로드 시켜준다.)
// header("Content-type: application/octet-stream");
}else {
	error_view(999, "죄송합니다. 관리자에 의하여 삭제되었거나 존재하지 않는 파일입니다.","관리자에게 문의하시기 바랍니다.");
}
?>