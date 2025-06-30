<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

//== 회원관 DB 분리
if($_GET['mId']) {
	if(!preg_match('/^[a-z\d_-]{5,20}$/i', $_GET['mId'])) js_action(2,"아이디는 최소5~20자의 영,숫가 조합되어야 합니다.","",-1);
}
if($_GET['divi']==1) $dbname="wMember"; else if($_GET['divi']==2) $dbname=""; else js_action(2,"올바른방법으로 검색하여 주시기 바랍니다.","",1);
//== 검색 모드 분리
if($_GET['mode']) $id = $_POST['checkid']; else $id = $_GET['mId'];
$sqlStr = "SELECT COUNT(mId) FROM $dbname WHERE mId = '$id'";
if(!DB::isError($db)) $rows = $db->getOne($sqlStr); else die($db->getMessage());
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
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
		<!--
			function focus() { document.setForm.checkid.focus(); }
			function focus_pass() { opener.setForm.password1.focus(); window.close(); }
			function insert_id(dd) {
				opener.document.setForm.mId.value = dd;
				opener.document.setForm.mName.focus();
				window.close();
			}
			function checkit() {
				if(!document.setForm.checkid.value) { alert('검색할 아이디를 입력하세요!'); document.setForm.checkid.focus(); return; }
				document.setForm.submit();
			}
		//-->
		</script>
	</head>
	<body onload="focus();">
		<h1 class="blind"><?=$siteName;?> 홈페이지에 오신것을 환영합니다.</h1>
		<div style="height:3px; background-color:#252525;"></div>
		<h3 style="padding:3px 0 5px 5px; background-color:#4A4949; color:#FFFFFF;">아이디 중복검사</h3>
		<div style="height:110px; margin:8px; border-bottom:1px solid #DDDDDD;">
			<div style="padding-top:30px; text-align:center;">
			<?php if($rows) {?>
			입력하신 <strong style="color:#FB5A20;"><?=$id;?></strong> 는 이미 등록되어 있습니다. 다른 아이디를 검색하세요.
			<?php }else {?>
			<strong style="color:#FB5A20;"><?=$id;?></strong> 는 사용 가능한 아이디 입니다. 사용하시겠습니까?<br /><br />
			[<a href="javascript:insert_id('<?=$id;?>');" title="아이디 사용하기"><strong style="color:#FB5A20;">사용하기</strong></a>]
			<?php }?>
			</div>
		</div>
		<div style="height:110px; margin:8px; padding-top:20px;">
			<div style="padding-left:50px;"><strong style="color:#030EA4;">다른 아이디를 사용하시려면,</strong><br />다른 아이디를 입력하시고 중복확인 버튼을 누르세요.</div>
			<div style="text-align:center; padding-top:10px;">
				<form name="setForm" method="post" action="<?=$_SERVER['PHP_SELF'];?>?mode=two&divi=<?=$_GET['divi'];?>">
					<fieldset>
						<legend>아이디 정보 입력</legend>
						<span><label for="checkid">다른 아이디</label></span> : <input type="text" name="checkid" size="15" maxlength="30" class="textbox" title="검색 아이디 입력">
						<input type="submit" value="중복확인" onClick="checkit(); return false;" class="button" title="중복확인">
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>