<?php
//================================================= Ajax 카테고리 데이터 리턴 =============================================//
include ("inc/configInc.php");

$sqlStr = "SELECT board_class FROM wboardConfig WHERE code='".$_POST['cateA']."'";
$bClass = $db->getOne($sqlStr);
if(DB::isError($bClass)) die($bClass->getMessage());

if($bClass) {
	$bClasss=explode(',',$bClass);
	for($i=0; $i<count($bClasss); $i++) {
		$rData .= "<option value=".$bClasss[$i].">".$bClasss[$i]."</option>";
	}
	echo $rData;
	//echo rawurlencode(iconv("CP949", "UTF-8", $rData));
	//echo rawurlencode(substr($returnData,0,-1));
}else {
	echo "<option value=>선택없음</option>";
}
?>