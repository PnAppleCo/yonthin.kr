<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danah'
//== last modify date : 2016. 06. 05
//==================================================================
//== 게시판 기본정보 로드
include $_SERVER['DOCUMENT_ROOT']."/nwebnics/inc/configInc.php";

ob_start();
ini_set("memory_limit", -1);																																								//== 대용량 다운로드시 절대 필요
$filename = $_SERVER['DOCUMENT_ROOT'].urldecode($_GET['downfile']);
//$filename = iconv('utf-8', 'euc-kr', $filename);
if(!is_file($filename)) die('File download error.');
$filepath = str_replace('\\', '/', realpath($filename));
$realname = substr(strrchr('/'.$filepath, '/'), 1);
$realname = iconv('utf-8', 'euc-kr', $realname);																									//== UTF-8 한글 파일명 처리
$extension = strtolower(substr(strrchr($filepath, '.'), 1));

switch($extension) {																																												//== 아직 미적용
	case "pdf": $mime="application/pdf"; break;
	case "exe": $mime="application/octet-stream"; break;
	case "zip": $mime="application/zip"; break;
	case "doc": $mime="application/msword"; break;
	case "xls": $mime="application/vnd.ms-excel"; break;
	case "ppt": $mime="application/vnd.ms-powerpoint"; break;
	case "gif": $mime="image/gif"; break;
	case "png": $mime="image/png"; break;
	case "jpeg":
	case "jpg": $mime="image/jpg"; break;
	case "mp3": $mime="audio/mpeg"; break;
	case "wav": $mime="audio/x-wav"; break;
	case "mpeg":
	case "mpg":
	case "mpe": $mime="video/mpeg"; break;
	case "mov": $mime="video/quicktime"; break;
	case "avi": $mime="video/x-msvideo"; break;
	case "php":$mime="application/force-download";break;
	case "htm":
	case "hwp":$mime="application/force-download";break;
	case "html":
	case 'txt' : $mime = "text/plain"; break;
	default: $mime="application/force-download";
}

if(file_exists($filepath)) {
	if(preg_match('/(msie)[ \/]([\w.]+)/', $_SERVER[HTTP_USER_AGENT])) {
		if(strstr($_SERVER[HTTP_USER_AGENT], "MSIE 5.5")) {
			//== 파일명 앤코드 전엔 6.0에서도 필요 한듯한데.( 한글일경우 )
			Header("Cache-Control: cache, must-revalidate"); //== 바로열기
			Header("Content-Type: doesn/matter");
			Header("Content-disposition: attachment; filename=$realname");
			Header("Content-Length: ".filesize($filepath));
			Header("Content-Transfer-Encoding: binary");
		}else if(strstr($_SERVER[HTTP_USER_AGENT], "MSIE 6.0")) {
			Header("Cache-Control: cache, must-revalidate"); //== 바로열기
			Header("Content-type: application/x-msdownload");
			Header("Content-Disposition: attachment; filename=$realname");
			Header("Content-Length: ".filesize($filepath));
			Header("Content-Transfer-Encoding: binary");
		}else {
			Header("Content-type: file/unknown");
			Header("Content-Disposition: attachment; filename=$realname");
			Header("Content-Description: PHP3 Generated Data");
		}
	}else {
		Header("Content-type: file/unknown");
		Header("Content-Length: ".filesize($filepath));
		Header("Content-Disposition: attachment; filename=$realname");
		Header("Content-Description: PHP3 Generated Data");
	}
	Header("Pragma: no-cache");
	Header("Expires: 0");

	$fp = fopen($filepath, "r");
	if(!fpassthru($fp)) fclose($fp);
	//@readfile($filepath);
	//기타 다운로드 방법 1
	// header("location:$filepath");
	// 기타 다운로드 방법 2(강제로 다운로드 시켜준다.)
	// header("Content-type: application/octet-stream");
}else {
	//die('File download error.'.$realname);
	die('File download error.');
}
?>