<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php");

	$sFileInfo = '';
	$headers = array();

	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		}
	}

	$file = new stdClass;
	$file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");

	$filename_ext = strtolower(array_pop(explode('.',$file->name)));
	$allow_file = array("jpg", "png", "bmp", "gif");

	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$file->name;
	} else {
		//== 업로드 디렉토리 생성
		$folderName=$_GET['tcode'];
		$uploadDir = '../../../../..'.$editorDir.$folderName.'/';
		if(!is_dir($uploadDir)) mkdir($uploadDir, 0777);

		//$upFilename=iconv("utf-8", "cp949", $file->name);
		$upFilename=urlencode($file->name);
		$expName = explode(".", $upFilename);
		//== 이미지 파일명 숫자로 일괄 대체
		$upFilename=date("ymdHis").".".$expName[1];
		$newPath = $uploadDir.$upFilename;

		//== 파일명 중복 확인(추후)
		while(file_exists($newPath)) {

		}

		//==파일 업로드
		if(file_put_contents($newPath, $file->content)) {

			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=".$upFilename;
			$sFileInfo .= "&sFileURL=".$editorDir.$folderName."/".$upFilename;
		}

		//== 원본이미지가 너무 큰 경우 조정
		/*
		이미지파일이 너무 큰경우 업로드는 되지만, 에디터에 입력이 되지 않고 죽어버림, html5 버전에서만 문제됨
		다중 업로드가 안됨
		*/
		$imgSize = @getimagesize($newPath);
		$imgW='1000';
		$imgH='1000';
		if($imgSize[0]>$imgW) {
			$imgWidth=$imgW;																											//== 가로 비율
			$imgHeight=(($imgH*$imgSize[1])/$imgSize[0]);					//== 세로 비율
			@thumbnail($uploadDir,$uploadDir,$upFilename,$imgWidth,$imgHeight);
		}

		echo $sFileInfo;
	}
?>