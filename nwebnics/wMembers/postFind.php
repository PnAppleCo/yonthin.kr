<?php
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

function findAddress() {
	global $db,$_POST,$_GET;
	$rst_data = '';
	$sqlStr = "SELECT * FROM zipcodeKor WHERE dong like '%".$_POST['find_str']."%' or ri LIKE '%".$_POST['find_str']."%' OR etc LIKE '%".$_POST['find_str']."%' ORDER BY idx";
	//$sqlStr = iconv('utf-8','euc-kr',"SELECT * FROM zipcodeKor WHERE dong like '%".$_POST[find_str]."%' or ri LIKE '%".$_POST[find_str]."%' OR etc LIKE '%".$_POST[find_str]."%' ORDER BY idx");

	$rst = $db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage());
	$rst_data .= "<div style=\"text-align:center; color:#E87100;\"><strong>해당주소를 클릭하면 자동 입력됩니다.</strong></div>\n";
	$i=0;
	while($view = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		unset($address);
		$zipcode1=substr($view['zipcode'],0,-3);
		$zipcode2=substr($view['zipcode'],-3);
		$address .= $view['sido']." ".$view['gugun']." ".$view['dong'];
		if($view['ri']) $address .= " ".$view['ri'];
		if($view['bunji']) { $address .= " ".$view['bunji']; }else { $vaddress=$address; }
		if($view['etc']) $address .= " ".$view['etc'];
		$link1="<a href=\"javascript:insert('$zipcode1', '$zipcode2', '$vaddress', '$_GET[mode]')\" title=\"주소입력\">";
		$link2="</a>";
		$rst_data .= "<li style=\"padding-bottom:3px;\">".$zipcode1."-".$zipcode2."&nbsp;:&nbsp;".$link1.$address.$link2."</li>\n";
		$i++;
	}
	if($i<1) $rst_data .= "<div style=\"text-align:center;\"><strong style=\"color:#E87100;\">".$_POST[find_str]."</strong>와(과) 일치 되는 주소를 찾을수 없습니다.</div>";
	return $rst_data;
}
?>
<!DOCTYPE <?=$doctypeSet;?>>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" lang="<?=$languageSet;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/css.css" />
		<script type="text/javascript" src="/nwebnics/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/common.js"></script>
		<script type="text/javascript">
		<!--
			function insert(post1, post2, add, mode) {
				var parant_form=opener.document.setForm;
				if(mode == 1) {
					parant_form.hzipcode1.value = post1;
					parant_form.hzipcode2.value = post2;
					parant_form.haddress1.value = add;
					parant_form.haddress2.focus();
					window.close();
				}else if(mode == 2) {
					parant_form.ozipcode1.value = post1;
					parant_form.ozipcode2.value = post2;
					parant_form.oaddress1.value = add;
					parant_form.oaddress2.focus();
					window.close();
				}else {
					alert('작업 모드가 선택되지 않았습니다.!');
					history.back();
				}
			}

			function checklt() {
				if(!document.findForm.find_str.value) { alert('찾을 주소를 입력하세요!'); document.findForm.find_str.focus(); return; }
				document.findForm.submit();
			}
		//-->
		</script>

	</head>
	<body>
		<h1 class="blind"><?=$siteName;?> 홈페이지에 오신것을 환영합니다.</h1>
		<div style="height:3px; background-color:#252525;"></div>
		<h3 style="padding:3px 0 5px 5px; background-color:#4A4949; color:#FFFFFF;">우편번호검색</h3>
		<div style="padding-top:5px; padding-bottom:15px; text-align:center; margin:8px; border-bottom:1px solid #DDDDDD;">
		찾고자 하는 주소의 <strong style="color:#FB5A20;">동/읍/면/리</strong> 이름을 정확히 입력하세요.<br />(예:문화동/문화읍/문화면/문화리)
			<form name="findForm" action="<?=$_SERVER['PHP_SELF'];?>?mode=<?=$_GET['mode'];?>" method="post">
				<fieldset>
					<legend>주소 입력</legend>
					<input type="text" name="find_str" size="30" maxlength="50" class="textbox" title="검색 읍/면/동 입력" />
					<input type="submit" value="주소검색" onClick="checklt(); return false;" class="button" title="주소검색" />
				</fieldset>
			</form>
		</div>
		<div style="margin:8px; padding-top:5px; padding-left:20px;">
			<ul><?php if($_POST['find_str']) echo findAddress();?></ul>
		</div>
	</body>
</html>