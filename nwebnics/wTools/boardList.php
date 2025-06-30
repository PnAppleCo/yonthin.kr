<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2016. 02. 22
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/nwebnics/wTools/", "관리자 로그인후 이용하세요.", 1);

//== 전체 게시물 질의어 생성
$sql_str_1 = "SELECT COUNT(idx) FROM $b_cfg_tb[0]";
$sql_str_2 = "SELECT * FROM $b_cfg_tb[0]";

//== 전달된 파라메터의 카운터 리턴
$rst_get=get_value(1);

//== 검색조건 설정
if($rst_get>1) {
	$sql_str_1 .= " WHERE";
	$sql_str_2 .= " WHERE";
	if($_GET['use_price'] && $_GET['use_term']) {
		switch ($_GET['use_term']) {
			case(1) :
				$sql_str_1 .= " year_1_price<$_GET[use_price] AND";
				$sql_str_2 .= " year_1_price<$_GET[use_price] AND";
				break;
			case(2) :
				$sql_str_1 .= " year_2_price<$_GET[use_price] AND";
				$sql_str_2 .= " year_2_price<$_GET[use_price] AND";
				break;
			case(3) :
				$sql_str_1 .= " year_3_price<$_GET[use_price] AND";
				$sql_str_2 .= " year_3_price<$_GET[use_price] AND";
				break;
			case(4) :
				$sql_str_1 .= " year_4_price<$_GET[use_price] AND";
				$sql_str_2 .= " year_4_price<$_GET[use_price] AND";
				break;
			default :
				$sql_str_1 .= " year_4_price<$_GET[use_price] AND";
				$sql_str_2 .= " year_4_price<$_GET[use_price] AND";
		}
	}
	if($_GET['service_speed']) {
		if($_GET['service_speed']==21) {
			$sql_str_1 .= " service_speed>$_GET[service_speed] AND";
			$sql_str_2 .= " service_speed>$_GET[service_speed] AND";
		}else {
			$sql_str_1 .= " service_speed<=$_GET[service_speed] AND";
			$sql_str_2 .= " service_speed<=$_GET[service_speed] AND";
		}
	}
	$sql_str_1 = substr($sql_str_1,0,-3);
	$sql_str_2 = substr($sql_str_2,0,-3);
}
$sql_str_2 .= " ORDER BY idx DESC";
//echo $sql_str_2;
//== 총 레코드수 추출
$total = $db->getOne($sql_str_1);
if(DB::isError($total)) die($total->getMessage());

	if(!$total) {
		$first = 1;
		$last = 0;
	}else {
		$first = $num_per_page*($_GET['page']-1);
		$last = $num_per_page*$_GET['page'];
		$next = $total - $last;
		if($next > 0) {
			$last -= 1;
		}else {
			$last = $total - 1;
		}
	}
	//== 총 페이지수
	$total_page = ceil($total/$num_per_page);
	//== 일련번호 설정
	$article_num = $total - $num_per_page*($_GET['page']-1);
	//== 오늘 등록된 게시물
	$sql_str = "SELECT COUNT(idx) FROM $b_cfg_tb[0] WHERE signdate=now()";
	$today = $db->getOne($sql_str);
	if(DB::isError($today)) die($today->getMessage());

	//== 검색 최고시간 설정
	$time_limit = 60*60*24*$notify_new_article;
	$view = $db->getAll($sql_str_2,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	//== 페이지 현황정보
	$page_state=" 전체 : ".$total;
	$paging = new paging(); $viewPaging=$paging->page_display($total,$num_per_page, $num_per_block,$next);
?>
<!DOCTYPE <?=$doctypeSet;?>>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" lang="<?=$languageSet;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="robots" content="noindex,nofollow" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/wglobal.css" />
		<link rel="stylesheet" type="text/css" href="/css/wtools.css" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
		<!--
			function del(thisform,mode,code,idx) {
				var ok_no = confirm("삭제하시겠습니까?");
				if(ok_no == true) {
					thisform.mode.value = mode;
					thisform.code.value = code;
					thisform.idx.value = idx;
					thisform.action = "./boardExe.php";
					thisform.method = "POST";
					thisform.submit();
				}
			}
		//-->
		</script>
	</head>
	<body>
		<h1 class="blind"><?=$siteName;?> 홈페이지에 오신것을 환영합니다.</h1>
		<!-- 스킵 바로가기 메뉴 -->
		<ul id="skipmenu">
			<li><a href="#navi-quick">메인메뉴 바로가기</a></li>
			<li><a href="#content-quick">콘텐츠 바로가기</a></li>
			<li><a href="#footer-quick">카피라이터 바로가기</a></li>
		</ul>
		<!-- 바디 시작 -->
		<div id="wrapper">
			<h2 class="blind"><a name="navi-quick" id="navi-quick" href="#navi-quick">메인 메뉴</a></h2>
			<!-- 헤더 -->
			<?php if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?php if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">게시판 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">
							<div id="boardHead">
								<span class="tblLeft"><?=$page_state;?></span><span class="tblRight">[<a href="boardForm.php?mode=add" class="basic">등록</a>]</span>
							</div>
							<div class="wList">
								<table summary="게시판 관리 목록입니다.">
									<caption>게시판 목록</caption>
									<colgroup>
										<col width="10%" />
										<col width="26%" />
										<col width="10%" />
										<col width="15%" />
										<col width="10%" />
										<col width="12%" />
										<col width="12%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">No</th>
											<th scope="col">게시판설명</th>
											<th scope="col">게시판코드</th>
											<th scope="col">전체게시물</th>
											<th scope="col">스 킨</th>
											<th scope="col">개설일</th>
											<th scope="col">게시판관리</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											if(!$total) echo "<tr><td colspan=\"7\">현재 등록/검색된 게시판이 없습니다.</td></tr>";
											for($i = $first; $i <= $last; $i++) {
												$signdate=strtr($view[$i]['signdate'],"-",".");
												$p_isp_brand=$view[$i]['isp_brand'];
												$p_made_com=$view[$i]['made_com'];
												//== 링크설정
												$get_link=get_value(2);
												//== 전체게시물수
												$total_art = $db->getOne("select count(idx) from $b_cfg_tb[1] where code='".$view[$i]['code']."'");
												if(DB::isError($total_art)) die($total_art->getMessage());
												//== 금일게시물수
												$now_art = $db->getOne("select count(idx) from $b_cfg_tb[1] where code='".$view[$i]['code']."' and DATE_FORMAT(signdate, '%Y-%m-%d') = CURDATE()");
												if(DB::isError($now_art)) die($now_art->getMessage());
												$link_first="<a href=\"../wBoard/list.php?code=".$view[$i]['code']."\" target=\"_blank\">";
												$link_last="</a>";
												if($view[$i]['board_summary']) $board_summary_print=$view[$i]['board_summary']; else $board_summary_print="&nbsp;";
											?>
										<tr>
											<td><?=$article_num;?></td>
											<td class="ListAlign"><?=$link_first.$board_summary_print.$link_first;?></td>
											<td><?=$view[$i]['code'];?></td>
											<td><?=$link_first.$now_art."/".$total_art.$link_last;?></td>
											<td><?=$view[$i]['skin'];?></td>
											<td><?=$view[$i]['signdate'];?></td>
											<td>[<a href="./boardForm.php?mode=edit&idx=<?=$view[$i]['idx'];?>&page=<?=$_GET['page'];?>" class="basic">수정</a>] [<a href="javascript:del(document.sForm,'del','<?=$view[$i]['code'];?>','<?=$view[$i]['idx'];?>');" class="basic">삭제</a>]</td>
										</tr>
										<?php $article_num--; }?>
									</tbody>
								</table>
								<form name="sForm">
									<input type="hidden" name="mode" value="">
									<input type="hidden" name="idx" value="">
									<input type="hidden" name="code" value="">
								</form>
							</div>
							<div id="boardTail" style="text-align:center; padding:1em 0;"><?=$viewPaging;?></div>
						</div>
						<!-- 콘텐츠 종료 -->
					</div>
					<!-- 콘텐츠 우측 -->
					<?php if($Right_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Right_Inc_File);?>
				</div>
			</div>
			<!-- 주소 및 보텀 메뉴 시작 -->
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#footer-quick">주소 및 카피라이터 메뉴</a></h2>
			<?php if($Foot_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Foot_Inc_File);?>
		</div>
	</body>
</html>
<?php $db->disconnect();?>