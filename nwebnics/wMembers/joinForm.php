<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2014. 11. 12
//==================================================================
//== 게시판 기본정보 로드
session_cache_limiter('nocache, must-revalidate');
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 작업모드 설정/체크
if(!$_GET['mode']) {
	js_action(1,"작업모드를 찾을수 없습니다.","",-1);
}else {
	if($_GET['mode']==="edit") {
		//== 수정할 회원 질의
		$sql_str="SELECT * FROM wMember WHERE";
		if(member_session(2)==true) {
			if($_GET['idx']) $sql_str .= " idx=$_GET[idx]"; else $sql_str .= " idx='$_SESSION[my_idx]' and id='$_SESSION[my_id]'";
		}else if(login_session()==true) {
			$sql_str .= " idx='$_SESSION[my_idx]' and id='$_SESSION[my_id]'";
		}else {
			js_action(1,"로그인후 이용하세요.","",-1);
		}
		$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
		if(DB::isError($view)) die($view->getMessage());
		//== 데이터 분리
	$jumin=substr($view['jumin2'],0,1);
	$view['ment']=stripslashes($view['ment']);
	$view['ment']=htmlspecialchars($view['ment']);
	$view['ment']=nl2br($view['ment']);
	$arrEmail=explode("@",$view['email']);
	$arrTel=explode("-",$view['tel']);
	$arrSel=explode("-",$view['sel']);
	$v_birth=explode("-",$view['birthday']);
		if(!$view['idx'] && !$view['id']) error_view(999, "죄송합니다. 고객님의 정보를 찾을수 없습니다.","로그아웃후에 다시 로그인하세요.");
	}
}

//== 자동가입 랜덤 이미지
$Rst_Str=explode(":", Not_Aoto_Join(10, 5));
?>
<!DOCTYPE <?=$doctypeSet;?>>
<!--[if lt IE 7 ]><html class="no-js ie6 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 7 ]><html class="no-js ie7 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 8 ]><html class="no-js ie8 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 9 ]><html class="no-js ie9" lang="<?=$languageSet;?>"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" class="no-js" lang="<?=$languageSet;?>">
<!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0">
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/css.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/responsive.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" media="all"/>
		<script type="text/javascript" src="/nwebnics/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jquery.slides.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jcarousellite_1.0.1.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="/nwebnics/js/common.js"></script>
		<script type="text/javascript" src="js/formCheck.js"></script>
		<script src="http://dmaps.daum.net/map_js_init/postcode.js"></script>
		<script>

			function openDaumPostcode() {
				new daum.Postcode({
					oncomplete: function(data) {
						// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
						// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
						document.getElementById('hzipcode1').value = data.postcode1;
						document.getElementById('hzipcode2').value = data.postcode2;
						document.getElementById('haddress1').value = data.address;
						//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
						//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
						//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
						//document.getElementById('addr').value = addr;
						document.getElementById('haddress2').focus();
					}
				}).open();
			}

			// 우편번호 찾기 iframe을 넣을 element
			var element = document.getElementById('iwrap');
			function foldDaumPostcode() {
					// iframe을 넣은 element를 안보이게 한다.
					var element = document.getElementById('iwrap');
					element.style.display = 'none';
			}
			function expandDaumPostcode() {
					// 현재 scroll 위치를 저장해놓는다.
					var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
					var element = document.getElementById('iwrap');
					new daum.Postcode({
							oncomplete: function(data) {
									// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
									// 우편번호와 주소 및 영문주소 정보를 해당 필드에 넣는다.
									document.getElementById('hzipcode1').value = data.postcode1;
									document.getElementById('hzipcode2').value = data.postcode2;
									document.getElementById('haddress1').value = data.address;
									// iframe을 넣은 element를 안보이게 한다.
									element.style.display = 'none';
									// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
									document.body.scrollTop = currentScroll;
							},
							// 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
							// iframe을 넣은 element의 높이값을 조정한다.
							onresize : function(size) {
									element.style.height = size.height + "px";
							},
							width : '100%',
							height : '100%'
					}).embed(element);
					// iframe을 넣은 element를 보이게 한다.
					element.style.display = 'block';
			}
		</script>
		<!-- <script type="text/javascript" src="/nwebnics/js/jquery-migrate.min.js"></script> -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/nwebnics/js/css3-mediaqueries.js"></script>
		<script type="text/javascript" src="/nwebnics/js/respond.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/html5shiv.min.js"></script>
		<![endif]-->
		<!--[if lte IE 6]><script type="text/javascript">location.href='/contents/NoticeIE6.php';</script><![endif]-->
	</head>
	<body>
		<h1 class="blind"><?=$siteName;?> 홈페이지에 오신것을 환영합니다.</h1>
		<hr/>
		<!-- 스킵 바로가기 메뉴 -->
		<ul id="skipmenu">
			<li><a href="#navi-quick">메인메뉴 바로가기</a></li>
			<li><a href="#content-quick">콘텐츠 바로가기</a></li>
			<li><a href="#footer-quick">카피라이터 바로가기</a></li>
		</ul>
		<div id="layoutWrap">
			<hr/>
			<h2 class="blind"><a name="navi-quick" id="navi-quick" href="#navi-quick">메인 메뉴</a></h2>
			<!-- 헤더 섹션 시작 -->
			<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/headInc.htm";?>
			<!-- 헤더 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">콘텐츠</a></h2>
			<!-- 콘텐츠 섹션 시작 -->
			<div id="contentsWrap">

				<div id="contentsArea">

					<!-- 콘텐츠 시작 -->
					<div id="contentsView" style="position:relative; float:none; max-width:740px; margin:0 auto;">
						<div id="titleWrap">
							<div id="contentsDepth"><?=$Site_Path;?></div>
							<h3 id="contentsTitle"><?=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];?></h3>
						</div>

						<div id="contentsPrint">

							<div class="divForm">
								<form name="setForm">
									<fieldset>
										<legend>회원 정보입력</legend>
											<table summary="회원 정보입력">
												<caption>회원 기본 정보</caption>
													<colgroup>
														<col width="12%" />
														<col width="33%" />
														<col width="12%" />
														<col width="33%" />
													</colgroup>
												<tbody>
													<tr>
														<th><label for="id">아 이 디</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td colspan="3"><input type="text" name="id" size="15" maxlength="20"<?php if($view['id']) echo " value=\"$view[id]\"";?> class="textbox" title="아이디입력"> <a href="javascript:dupl_id_check(1)" title="아이디 중복확인"><img src="./img/id_check(btn).gif" align="absmiddle" alt="중복확인"></a> ※ 5~20자 이하 영,숫자 조합(특수문자 "-", "_"만 허용)</td>
													</tr>
													<tr>
														<th><label for="name">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td><input type="text" name="name" size="15" maxlength="16"<?php if($view['name']) echo " value=\"$view[name]\"";?> class="textbox" title="성명입력"></td>
														<th><label for="nickname">별&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명</label></th>
														<td><input type="text" name="nickname" size="15" maxlength="16"<?php if($view['nickname']) echo " value=\"$view[nickname]\"";?> class="textbox" title="별명입력"></td>
													</tr>
													<tr>
														<th><label for="password1">비밀번호</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td><input type="password" name="password1" size="18" maxlength="12" class="textbox" title="비밀번호 입력"> ※ 5~20자 이하 영,숫자 조합</td>
														<th><label for="password2">비밀번호확인</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td><input type="password" name="password2" size="18" maxlength="12" class="textbox" title="비밀번호 확인"> ※ 5~20자 이하 영,숫자 조합</td>
													</tr>
													<tr>
														<th><label for="iQuestion">비밀번호 찾기<br />연상문구</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td colspan="3">
															<select name="iQuestion" class="selectbox" title="비밀번호찾기 질문 선택">
																<option value="" selected>선 택</option>
																<?php
																for($j=0; $j<count($findpassArr); $j++) {
																	if($view['iQuestion'] == $j) $pStatus=" selected"; else $pStatus="";
																	echo "<option value=\"".$j."\" ".$pStatus.">".$findpassArr[$j]."</option>";
																}
																?>
															</select>
															<input type="text" name="iAnswer" size="25" maxlength="255"<?php if($view['iAnswer']) echo " value=\"$view[iAnswer]\"";?> class="textbox" title="답변 입력"><br />
															비밀번호를 잊으신 경우 위에 작성하시는 질문과 답을 이용하여 본인확인 후 비밀번호를 재발급받게 되므로, 꼭 기억하시기 바랍니다.
														</td>
													</tr>
													<tr>
														<th><label for="birthyear">생년월일</label></th>
														<td colspan="3">
															<select name="birthyear" class="selectbox" title="년도선택">
																<option value="" selected>선 택</option>
																<?php 
																for($i=date('Y'); $i>=1912; $i--) {
																	if($arrTel[0]===$i) $yStatus=" selected"; else $yStatus="";
																	echo "<option value=\"".$i."\" ".$yStatus.">".$i."</option>";
																}
																?>
															</select>
															<select name="birthmonth" class="selectbox" title="월선택">
																<option value="" selected>선 택</option>
																<?php 
																for($i=1; $i<=12; $i++) {
																	if($arrTel[1]===$i) $mStatus=" selected"; else $mStatus="";
																	echo "<option value=\"".$i."\" ".$mStatus.">".$i."</option>";
																}
																?>
															</select>
															<select name="birthday" class="selectbox" title="일선택">
																<option value="" selected>선 택</option>
																<?php 
																for($i=1; $i<=31; $i++) {
																	if($arrTel[1]===$i) $dStatus=" selected"; else $dStatus="";
																	echo "<option value=\"".$i."\" ".$dStatus.">".$i."</option>";
																}
																?>
															</select>
															<input type="radio" name="solu" value="1"<?php if($view['solu']==1 || $_GET['mode']==="add") echo " checked";?> class="align_right_middle">양&nbsp;<input type="radio" name="solu" value="2"<?if($view['solu']==2) echo " checked";?> class="align_right_middle">음
														</td>
													</tr>
													<tr>
														<th><label for="email_a">전자우편</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td colspan="3">
															<input name="email_a" size="15" maxlength="25"<?php if($arrEmail[0]) echo " value=\"$arrEmail[0]\"";?> class="textbox" title="메일 아이디 입력">&nbsp;@
															<input name="email_b" size="15" maxlength="25"<?php if($arrEmail[1]) echo " value=\"$arrEmail[1]\"";?> class="textbox" title="메일도메인 입력">
															<select name="email_c" onChange="emailCheck(this.form);" class="selectbox" title="메일도메인 선택">
																<option value="">--직접 입력하세요--</option>
																<option value="chollian.net">chollian.net</option>
																<option value="dreamwiz.com">dreamwiz.com</option>
																<option value="empal.com">empal.com</option>
																<option value="freechal.com">freechal.com</option>
																<option value="hanmail.net">hanmail.net</option>
																<option value="hotmail.com">hotmail.com</option>
																<option value="hanmir.net">hanmir.net</option>
																<option value="hitel.com">hitel.com</option>
																<option value="intizen.com">intizen.com</option>
																<option value="korea.com">korea.com</option>
																<option value="lycos.co.kr">lycos.co.kr</option>
																<option value="nate.com">nate.com</option>
																<option value="naver.com">naver.com</option>
																<option value="netian.net">netian.net</option>
																<option value="orgio.net">orgio.net</option>
																<option value="shinbiro.com">shinbiro.com</option>
																<option value="yahoo.com">yahoo.com</option>
																<option value="yahoo.co.kr">yahoo.co.kr</option>
															</select>메일수신<input type="checkbox" name="news_dm" value="1" <?php if($view['news_dm']==1) echo " checked";?> title="메인수신여부" class="align_right_middle">
														</td>
													</tr>
													<tr>
														<th><label for="hzipcode1">주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소</label></th>
														<td colspan="3">
															<input type="text" id="hzipcode1" name="hzipcode1" size="3" maxlength="3" value="<?=$view['hzipcode1'] ?>"class="textbox" readonly title="우편번호 첫자리">&nbsp;-&nbsp;<input type="text" id="hzipcode2"  name="hzipcode2" size="3" maxlength="3" class="textbox" value="<?=$view['hzipcode2'] ?>"readonly title="우편번호 두번째자리"> <span onClick="openDaumPostcode();" style="cursor:pointer;">주소찾기</span></a>
															<br />
															<div id="iwrap" style="position:absolute;display:none;border:1px solid;width:500px;height:300px;margin:5px 0;-webkit-overflow-scrolling:touch;"><img src="/img/comm/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px" onclick="foldDaumPostcode()" alt="접기 버튼"></div>
															<input type="text" id="haddress1"  name="haddress1" size="45" maxlength="60" value="<?=$view['haddress1'] ?>" class="textbox" title="주소 입력">
															<input type="text" id="haddress2"  name="haddress2" size="30" maxlength="30" value="<?=$view['haddress2'] ?>" class="textbox" title="나머지 상세주소 입력">
														</td>
													</tr>
													<tr>
														<th><label for="tel_1">일반전화</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td>
															<select name="tel_1" class="selectbox" title="일반전화 앞자리 선택">
																<option value="" selected>선택</option>
																<option value="02"<?php if($arrTel[0]=="02") echo " selected";?>>02</option>
																<option value="031"<?php if($arrTel[0]=="031") echo " selected";?>>031</option>
																<option value="032"<?php if($arrTel[0]=="032") echo " selected";?>>032</option>
																<option value="033"<?php if($arrTel[0]=="033") echo " selected";?>>033</option>
																<option value="041"<?php if($arrTel[0]=="041") echo " selected";?>>041</option>
																<option value="042"<?php if($arrTel[0]=="042") echo " selected";?>>042</option>
																<option value="043"<?php if($arrTel[0]=="043") echo " selected";?>>043</option>
																<option value="051"<?php if($arrTel[0]=="051") echo " selected";?>>051</option>
																<option value="052"<?php if($arrTel[0]=="052") echo " selected";?>>052</option>
																<option value="053"<?php if($arrTel[0]=="053") echo " selected";?>>053</option>
																<option value="054"<?php if($arrTel[0]=="054") echo " selected";?>>054</option>
																<option value="055"<?php if($arrTel[0]=="055") echo " selected";?>>055</option>
																<option value="061"<?php if($arrTel[0]=="061") echo " selected";?>>061</option>
																<option value="062"<?php if($arrTel[0]=="062") echo " selected";?>>062</option>
																<option value="063"<?php if($arrTel[0]=="063") echo " selected";?>>063</option>
																<option value="064"<?php if($arrTel[0]=="064") echo " selected";?>>064</option>
																<option value="070"<?php if($arrTel[0]=="070") echo " selected";?>>070</option>
															</select>-<input type="text" name="tel_2" size="5" maxlength="4"<?if($arrTel[1]) echo " value=\"$arrTel[1]\"";?> class="textbox" title="일반전화 가운데자리 입력">-<input type="text" name="tel_3" size="5" maxlength="4"<?php if($arrTel[2]) echo " value=\"$arrTel[2]\"";?> class="textbox" title="일반전화 끝자리 입력">
														</td>
														<th><label for="sel_1">휴대전화</label></th>
														<td width="" class="line_solid">
															<select name="sel_1" class="selectbox" title="휴대전화 앞자리 입력">
																<option value="" selected>선택</option>
																<option value="010"<?if($arrSel[0]=="010") echo " selected";?>>010</option>
																<option value="011"<?if($arrSel[0]=="011") echo " selected";?>>011</option>
																<option value="016"<?if($arrSel[0]=="016") echo " selected";?>>016</option>
																<option value="017"<?if($arrSel[0]=="017") echo " selected";?>>017</option>
																<option value="018"<?if($arrSel[0]=="018") echo " selected";?>>018</option>
																<option value="019"<?if($arrSel[0]=="019") echo " selected";?>>019</option>
															</select>-<input type="text" name="sel_2" size="5" maxlength="4"<?php if($arrSel[1]) echo " value=\"$arrSel[1]\"";?> class="textbox" title="휴대전화 가운데 자리 입력">-<input type="text" name="sel_3" size="5" maxlength="4"<?php if($arrSel[2]) echo " value=\"$arrSel[2]\"";?> class="textbox" title="휴대전화 끝 자리 입력">
														</td>
													</tr>
													<tr>
														<th><label for="route">가입경로</label></th>
														<td>
															<select name="route" class="selectbox" title="가입경로 선택">
																<option value="" selected >-- 선 택 --</option>
																<option value="2"<?php if($view['route']==2) echo " selected";?>>신문광고</option>
																<option value="3"<?php if($view['route']==3) echo " selected";?>>주변소개</option>
																<option value="4"<?php if($view['route']==4) echo " selected";?>>기타광고</option>
																<option value="5"<?php if($view['route']==5) echo " selected";?>>기타경로</option>
															</td>
															<th><label for="ujob">직&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;업</label></th>
															<option value="1"<?php if($view['route']==1) echo " selected";?>>검색엔진</option>
															<td>
																<select name="ujob" class="selectbox" title="직업 선택">
																	<option value="" selected >--- 선 택 ---</option>
																	<option value="1"<?php if($view['ujob']==1) echo " selected";?>>주 부</option>
																</select>
																<option value="2"<?php if($view['ujob']==2) echo " selected";?>>학 생</option>
																<option value="3"<?php if($view['ujob']==3) echo " selected";?>>상 업</option>
																<option value="4"<?php if($view['ujob']==4) echo " selected";?>>공무원</option>
																<option value="5"<?php if($view['ujob']==5) echo " selected";?>>농/수/임업</option>
																<option value="6"<?php if($view['ujob']==6) echo " selected";?>>회사원</option>
																<option value="7"<?php if($view['ujob']==7) echo " selected";?>>교 육</option>
																<option value="8"<?php if($view['ujob']==8) echo " selected";?>>의료/법률</option>
																<option value="9"<?php if($view['ujob']==9) echo " selected";?>>예 술</option>
																<option value="10"<?php if($view['ujob']==10) echo " selected";?>>유통업</option>
																<option value="11"<?php if($view['ujob']==11) echo " selected";?>>금융/증권/보험</option>
																<option value="12"<?php if($view['ujob']==12) echo " selected";?>>서비스업</option>
																<option value="13"<?php if($view['ujob']==13) echo " selected";?>>컴퓨터/인터넷</option>
																<option value="14"<?php if($view['ujob']==14) echo " selected";?>>군 인</option>
																<option value="15"<?php if($view['ujob']==15) echo " selected";?>>언 론</option>
																<option value="16"<?php if($view['ujob']==16) echo " selected";?>>운송업</option>
																<option value="17"<?php if($view['ujob']==17) echo " selected";?>>제조업</option>
																<option value="18"<?php if($view['ujob']==18) echo " selected";?>>건설업</option>
																<option value="19"<?php if($view['ujob']==19) echo " selected";?>>무 직</option>
																<option value="20"<?php if($view['ujob']==20) echo " selected";?>>기 타</option>
															</select>
														</td>
													</tr>
													<?php if($_GET['mode']=="add") {?>
													<tr>
														<th><label for="Divi_Str_d">자동가입방지</label><span style="color:red; vertical-align:bottom;"><strong>*</strong></span></th>
														<td colspan="3">
															<?=$Rst_Str[0];?>
															<input type="text" name="Divi_Str_d" size="10" maxlength="10" class="textbox" title="자동가입 방지 문자열 입력">&nbsp;* 대·소문자를 구분하여 왼쪽의 문자중 <font color="#2626C2">파란색</font>만을 순서대로 입력
															<input type="hidden" name="Divi_Str_s" value="<?=$Rst_Str[1];?>">
														</td>
													</tr>
													<?php }?>
													<?php if($siteType==2) include ("./inc/shopDetails_inc.php");?>
											</table>
									</fieldset>
								</form>
							</div>
							<div class="align_center_middle" style="padding-top:10px; margin-bottom:330px;">
								<?php
									if($_GET['mode']==="add") {
										echo "<button type=\"button\" onclick=\"check_member('$_GET[mode]'); return false;\" style=\"border:0 groove; background-color:#FFFFFF; cursor:hand;\"><img src=\"./img/join_ok.gif\" border=\"0\" alt=\"회원가입\"></button>";
										echo " <button type=\"button\" onClick=\"history.back();\" style='border:0 groove; background-color:#FFFFFF; cursor:hand;'><img src=\"./img/join_cancel.gif\" alt=\"뒤로이동\"></button>";
									}else if($_GET['mode']==="edit") {
										echo "<button type=\"button\" onclick=\"check_member('$_GET[mode]','$view[idx]','$_GET[page]','$_GET[keyword]','$_GET[keyfield]'); return false;\" style=\"border:0 groove; background-color:#FFFFFF; cursor:hand;\"><img src=\"./img/info_edit.gif\" border=\"0\" alt=\"정보수정\"></button>";
									}
								?>
							</div>

						</div>

					</div>
					<!-- 콘텐츠 종료 -->

					<!-- 로컬 메뉴 섹션 시작 -->
					<?php if($gnbPath[0]!=6) include $_SERVER["DOCUMENT_ROOT"]."/inc/lnbInc.htm";?>
					<!-- 로컬 메뉴 섹션 종료 -->

				</div>

			</div>
			<!-- 콘텐츠 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#navi-quick">카피라이터</a></h2>
			<!-- 풋터 섹션 시작 -->
			<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?>
			<!-- 풋터 섹션 종료 -->
		</div>

		<div id="gotop" class="gotop">
			<div></div>
		</div>

		<script type='text/javascript' src='/nwebnics/js/bunyad-theme.js'></script>

	</body>
</html>
<?php 
$db->disconnect();
?>