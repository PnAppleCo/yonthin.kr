<?php
include ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php");

// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];

	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = array("jpg", "png", "bmp", "gif", "jpeg");

	if(!in_array($filename_ext, $allow_file)) {
		$url .= '&errstr='.$name;
	}else {
		//== 업로드할 디렉토리 생성
		$folderName=$tcode;
		$uploadDir = '../../../../..'.$editorDir.$folderName.'/';
		if(!is_dir($uploadDir)) mkdir($uploadDir, 0777);

		$upFilename=urlencode($name);
		$expName = explode(".", $upFilename);
		//== 이미지 파일명 숫자로 일괄 대체
		$upFilename=date("ymdHis").".".$expName[1];
		$newPath = $uploadDir.$upFilename;

		//== 파일명 중복 확인(추후)
		while(file_exists($newPath)) {

		}

		//== 파일 업로드
		@move_uploaded_file($tmp_name, $newPath);

		//== 원본이미지가 너무 큰 경우 조정
		$imgSize = @getimagesize($newPath);
		$imgW='770';
		$imgH='770';
		if($imgSize[0]>$imgW) {
			$imgWidth=$imgW;																											//== 가로 비율
			$imgHeight=(($imgH*$imgSize[1])/$imgSize[0]);					//== 세로 비율
			@thumbnail($uploadDir,$uploadDir,$upFilename,$imgWidth,$imgHeight);
		}

		$url .= "&bNewLine=true";
		$url .= "&sFileName=".urlencode($upFilename);
		$url .= "&sFileURL=".$editorDir.$folderName."/".urlencode($upFilename);
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}

header('Location: '. $url);
?>