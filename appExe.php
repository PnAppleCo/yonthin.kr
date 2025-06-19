<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 03. 13
//==================================================================
//== 게시판 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/sms_utf8.php";

//== 시설사용 등록 처리 ====================================================================================
if($_POST[mode]=="1") {

	//== 새로 등록할 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM appTbl");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	$sqlStr = "INSERT INTO appTbl(idx, useBuilding, useRoom, startDate, stopDate, startTime, stopTime, appName, telNo, selNo, appCorp, emailAddr, useCount, appTitle, signDate) VALUES ('".$new_idx."', '".$_POST[useBuilding]."','".$_POST[useRoom]."', '".$_POST[startDate]."', '".$_POST[stopDate]."', '".$_POST[startTime]."', '".$_POST[stopTime]."', '".$_POST[appName]."', '".$_POST[telNo]."', '".$_POST[selNo]."', '".$_POST[appCorp]."', '".$_POST[emailAddr]."', '".$_POST[useCount]."', '".$_POST[appTitle]."', now());";

	$m_url="/contents.htm?code=3_3_2";
	$p_ment="시설사용 접수중입니다. 담당자 확인후 연락 드리겠습니다.";

	$rst=$db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$m_url,$p_ment,2);

//== 시설사용 신청확인 ====================================================================================
}else if($_POST[mode]=="2") {
	if($_POST['uName'] && $_POST['selNo']) {
		$sqlStr="SELECT * FROM orderTbl WHERE uName='".$_POST['uName']."' && selNo='".$_POST['selNo']."'";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$uContents = stripslashes($view[uContents]);
			$rstData .= "<tr><td>".$view[orderItems]."</td><td align=center>".$dStatusArr[$view[dStatus]]."</td></tr>";
		}
		if($rstData) echo $rstData; else echo "<tr><td colspan=2 align=center>일치하는 주문정보를 찾을 수 없습니다.</td></tr>";
	}else {
		//echo iconv("CP949", "UTF-8", "입력데이터");
		echo "<tr>td colspan=2 align=center>입력 데이터가 올바르지 않습니다.</td></tr>";
	}
}
$db->disconnect();
?>