<?
//========================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2012. 10. 17
//========================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

$returnData="";
if(isset($_GET['rCate']) && $_GET['rCate']!=0) {
	$sqlStr = "SELECT cate,cateName FROM wCategory WHERE cateParent='".$_GET['rCate']."' ORDER BY idx ASC";
	$result = $db->query($sqlStr);
	if(DB::isError($result)) die($result->getMessage());
	while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
		$returnData .= $view[cateName].':'.$view[cate].';';
	}
	$rstCate=substr($returnData,0,-1);
	if($rstCate) echo $rstCate; else echo "미등록";
	//echo substr($rstCate,0,-1);
	//echo rawurlencode(iconv("CP949", "UTF-8", substr($returnData,0,-1)));
}
?>