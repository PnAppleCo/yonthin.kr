<?PHP
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2006. 02. 15
//==================================================================
if(!$_GET['image']) js_action(2, "중요정보를 찾을수 없습니다.","","");
// PHP82 변환
// if(eregi ("(([^/a-zA-Z]){1,})(\.jpg|\.jpeg|\.bmp|\.png|\.gif)",$_GET[image] ,$regs)) $v_img_dir = str_replace ($regs[1], urlencode($regs[1]),$_GET[image]);
if (preg_match("/(([^\/a-zA-Z])+)(\.jpg|\.jpeg|\.bmp|\.png|\.gif)$/i", $_GET['image'], $regs)) {
    $v_img_dir = str_replace($regs[1], urlencode($regs[1]), $_GET['image']);
} else {
    $v_img_dir = $_GET['image'];
}
//== 기본설정파일
$img_size = getimagesize($_GET['image']);
//== 이미지크기설정(비율에 맞게 조정)
if($img_size[0]>1000) {
	$img_width=1000;																								//== 가로 비율
	$img_height=((1000*$img_size[1])/$img_size[0]);	//== 세로 비율
}else {
	$img_width=$img_size[0];
	$img_height=$img_size[1];
}
?>
<html>
	<head>
		<title><?=$title_bar_text;?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="Keywords" content="<?=$meta_keyword;?>">
	</head>
	<body onload="init();" leftmargin="3" topmargin="3" marginwidth="3" marginheight="3">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr><td align="center">
			<img name="main" src="<?=$v_img_dir;?>" width="<?=$img_width?>" height="<?=$img_height;?>" border="0">
			</td></tr>
		</table>
	</body>
</html>