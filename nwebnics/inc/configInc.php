<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'boram'
//== last modify date : 2018. 05. 14
//==================================================================

//========================================================//
//== 기본 헤더 설정 =======================================//
//========================================================//
//== 항상 변경됨
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//== HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
//== HTTP/1.0
header("Pragma: no-cache");
header('Content-type: text/html; charset=utf-8');

//========================================================//
//== 기본 환경 설정 =======================================//
//========================================================//

//== 솔루션이 설치된 디렉토리 및 기본 환경 설정
$siteDomain="";
$siteName="청년독립지원청";
//== 회사전화
$companyTel="031-757-7052";
$companyFax="0504-395-1277";
$companyEmail="";
//== 회사주소
$companyAddress="(13320) 경기도 성남시 수정구 성남대로 1182(KT모란빌딩) 6층";
//== 유지보수 IDX
$compayIdx=4;
//== 유지보수 타입
$compayMType=4;

//== 기본환경 설정파일 로드
// include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/commonConfig.php";    // 타사이트자료
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/libInc.php";
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/mainviewInc.php";

//== 기본 문서셋 설정
$characterSet="euc-kr";
$languageSet="ko";
$doctypeSet="html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"";

//== 관리자 타입[1:일반관리, 2:슈퍼관리]
$wtoolType="2";

//========================================================//
//== 세션 환경설정 ========================================//
//========================================================//
session_save_path($_SERVER["DOCUMENT_ROOT"]."/nwebnics/sessionDir/");
ini_set("session.cache_expire", 60);											//= 세션 유효시간 : 분
ini_set("session.gc_maxlifetime", 3600);										//= 세션 가비지 컬렉션(로그인시 세션지속 시간) : 초
ini_set('memory_limit',-1);
ini_set("display_errors", 1);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING ^ E_STRICT);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
//session_cache_limiter('nocache, must-revalidate');
session_start();

//== 파일 업로드 디렉토리
$popupDir="/upload/popupDir/";
$editorDir="/upload/editorDir/";
$cmsDir="/upload/cmsDir/";
$bannerDir="/upload/bannerDir/";
$boardDir="/nwebnics/wBoard/files/";

//========================================================//
//== DB 환경 설정 =========================================//
//========================================================//
set_include_path($_SERVER["DOCUMENT_ROOT"].'/pear/PEAR' . PATH_SEPARATOR . get_include_path());
require_once('DB.php');
require_once('dbInfo.php');

//== PEAR DB DSN 설정
$dsn_str = db_type."://".db_user.":".db_pass."@".db_host.":".db_port."/".db_name;

$db = DB::connect($dsn_str);
if(DB::isError($db)) {
	echo($dsn_str);
	die($db->getMessage());
}
//mysql_query("set names utf8");
$db->query("SET NAMES 'utf8mb4'");

//============================================= Navigation Set ================================================//
// $gnb_Arr = array(1=>"회사소개",2=>"참여하기",3=>"사업소개",4=>"체험시리즈",5=>"커뮤니티",6=>"이용안내");
$gnb_Arr = array(1=>"회사소개",3=>"사업소개",4=>"체험시리즈",5=>"커뮤니티",6=>"이용안내");
// $lnb_Arr = array (
// 								1=>array(1=>"청년독립지원청 소개", 2=>"Contact", 3=>"연혁", 4=>"", 5=>"", 6=>""),
// 								2=>array(1=>"자립과 의존 체크리스트", 2=>"", 3=>"", 4=>"", 5=>"", 6=>""),
// 								3=>array(1=>"청년체험", 2=>"맞춤교육", 3=>"자산형성지원사업", 4=>"", 5=>"", 6=>""),
// 								4=>array(1=>"온라인 자기성찰형 지출관리", 2=>"경제 금융 지식 학습", 3=>"", 4=>"", 5=>"", 6=>""),
// 								5=>array(1=>"공지사항", 2=>"청년독립미션", 3=>"", 4=>"", 5=>"", 6=>""),
// 6=>array(1=>"개인정보처리방침", 2=>"", 3=>"")
// 								);
$lnb_Arr = array (
								1=>array(1=>"청년독립지원청 소개", 2=>"연혁", 3=>"Contact", 4=>"", 5=>"", 6=>""),
								3=>array(1=>"청년체험", 2=>"청년맞춤교육", 3=>"청년자산형성 사업 및 연구 컨설팅", 4=>"장애인 코칭 프로그램"),
								4=>array(1=>"특허기술 기반의 가계부PT", 2=>"알맹이청년생활경제지식", 3=>"", 4=>"", 5=>"", 6=>""),
								5=>array(1=>"공지사항", 2=>"청년독립미션", 3=>"후기공모전 수상작"),
6=>array(1=>"개인정보처리방침", 2=>"", 3=>"")
								);
$cnb_Arr = array (
								1=>array(
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>"")
											),
								2=>array(
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>""),
											5=>array(1=>"")
											),
								3=>array (
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>"")
											),
								4=>array (
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>"")
											),
								5=>array (
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>"")
											),
								6=>array (
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>"")
											),
								7=>array (
											1=>array(1=>""),
											2=>array(1=>""),
											3=>array(1=>""),
											4=>array(1=>"")
											),
);

// PHP82 변환
// $gnbPath=explode("_",$_GET["code"]);
if (isset($_GET["code"])) { 
    $gnbPath = explode("_", $_GET["code"]);
} else {
    $gnbPath = [];
}

//============================================= Contents Set ================================================//
switch ($gnbPath[0]) {

	case("1"):
		$Title_Txt=$siteName." > ".$gnb_Arr[1]." > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];																															//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".$gnb_Arr[$gnbPath[0]];																	//== 위치 네비게이션
		if($gnbPath[1]) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if($gnbPath[2]) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if($gnbPath[3]) $cTitle=$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]; else $cTitle=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code']."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
		$subTitleTxt="COMPANY";																																																										//== 영문소개
	break;

	case ("2"):
		$Title_Txt=$siteName." > ".$gnb_Arr[2]." > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];																															//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".$gnb_Arr[$gnbPath[0]];																	//== 위치 네비게이션
		if($gnbPath[1]) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if($gnbPath[2]) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if($gnbPath[2]) $cTitle=$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]; else $cTitle=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code']."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
		$subTitleTxt="BUSINESS";																																																										//== 영문소개
	break;

	case ('3'):
		$Title_Txt=$siteName." > ".$gnb_Arr[3]." > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];																															//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".$gnb_Arr[$gnbPath[0]];																	//== 위치 네비게이션
		if($gnbPath[1]) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if($gnbPath[2]) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if($gnbPath[2]) $cTitle=$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]; else $cTitle=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code']."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
		$subTitleTxt="PRODUCTS";																																																										//== 영문소개
	break;

	case ('4'):
		$Title_Txt=$siteName." > ".$gnb_Arr[4]." > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];																															//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".$gnb_Arr[$gnbPath[0]];																	//== 위치 네비게이션
		if($gnbPath[1]) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if($gnbPath[2]) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if($gnbPath[2]) $cTitle=$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]; else $cTitle=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code']."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
		$subTitleTxt="Data Room";																																																										//== 영문소개
	break;

	case ('5'):
		$Title_Txt=$siteName." > ".$gnb_Arr[5]." > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];																															//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".$gnb_Arr[$gnbPath[0]];																	//== 위치 네비게이션
		if($gnbPath[1]) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if($gnbPath[2]) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if($gnbPath[2]) $cTitle=$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]; else $cTitle=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code']."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
		$subTitleTxt="CUSTOMER SERVICE";																																																										//== 영문소개
	break;

	case ('6'):
		$Title_Txt=$siteName." > ".$gnb_Arr[6]." > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];																															//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".$gnb_Arr[$gnbPath[0]];																	//== 위치 네비게이션
		if($gnbPath[1]) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if($gnbPath[2]) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if($gnbPath[2]) $cTitle=$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]; else $cTitle=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code']."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
		$subTitleTxt="USE GUIDE";																																																										//== 영문소개
	break;

	default :
		$Title_Txt=$siteName;																																																														//== 타이틀바
		$Description_Txt="";																														//== 사이트설명
		$Keywords_Txt="";																								//== 검색키워드
		$Author_Txt=$siteName;																																																													//== 제작자
		$Top_Inc_File="/inc/contents_headInc.htm";																																														//== 상단 호출
		$Left_Inc_File="/inc/lnb_menuInc.htm";																																																		//== 좌측 호출
		$Right_Inc_File="";																																																																//== 우측 호출
		$Foot_Inc_File="/inc/footInc.htm";																																																						//== 하단 호출
		$Contents_File="/contents/".str_pad($gnbPath[0],2,'0',0)."/".isset($_GET['code']).".htm";																				//== 콘텐츠 호출
		$Site_Path="HOME > ".isset($gnb_Arr[$gnbPath[0]]);																	//== 위치 네비게이션
		if(isset($gnbPath[1])) $Site_Path .= " > ".$lnb_Arr[$gnbPath[0]][$gnbPath[1]];
		if(isset($gnbPath[2])) $Site_Path .= " > ".$cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]];
		if(isset($gnbPath[2])) $cTitle=isset($cnb_Arr[$gnbPath[0]][$gnbPath[1]][$gnbPath[2]]); else $cTitle=isset($lnb_Arr[$gnbPath[0]][$gnbPath[1]]);
		$Title_Bar_Image="/img/".str_pad($gnbPath[0],2,'0',0)."/".isset($_GET['code'])."_title.gif";																				//== 콘텐츠 타이틀
		$Access_Level=0;																																																																	//== 접근권한
}

//== SNS 공유
$snsUrl="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$snsImage="http://".$_SERVER['HTTP_HOST']."/img/comm/og.png";

?>
