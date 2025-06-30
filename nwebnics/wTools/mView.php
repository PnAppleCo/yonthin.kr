<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == true) {
	if(!$_GET['idx'] && !$_GET['mid']) error_view(999, "필수정보를 찾을 수 없습니다..","재확인후 이용하세요.");
	$sqlStr="SELECT * FROM wMember";
	if($_GET['idx']) $addStr .= " idx=$_GET[idx] AND";
	if($_GET['mid']) $addStr .= " mId='$_GET[mid]' AND";
	if($addStr) $sqlStr .= " WHERE".substr($addStr,0,-3);
}else { redirect(1, "/nwebnics/wTools/", "관리자 로그인후 이용하세요.", 1); }
$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
if(DB::isError($view)) die($view->getMessage());
if(!$view) error_view(999, "죄송합니다!. 일치하는 정보를 찾을수 없습니다.","올바른 방법으로 이용하세요.");
$view['adminMemo']=htmlspecialchars($view['adminMemo']);
$view['adminMemo']=nl2br($view['adminMemo']);
$arrEmail=explode("@",$view['email']);
$arrTel=explode("-",$view['telNum']);
$arrSel=explode("-",$view['selNum']);
$v_birth=explode("-",$view['birthDay']);

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
		<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="js/viewCheck.js"></script>
		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
		<script>
			function DaumPostcode(mode) {
					new daum.Postcode({
							oncomplete: function(data) {
									// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

									// 각 주소의 노출 규칙에 따라 주소를 조합한다.
									// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
									var fullAddr = ''; // 최종 주소 변수
									var extraAddr = ''; // 조합형 주소 변수

									// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
									if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
											fullAddr = data.roadAddress;

									} else { // 사용자가 지번 주소를 선택했을 경우(J)
											fullAddr = data.jibunAddress;
									}

									// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
									if(data.userSelectedType === 'R'){
											//법정동명이 있을 경우 추가한다.
											if(data.bname !== ''){
													extraAddr += data.bname;
											}
											// 건물명이 있을 경우 추가한다.
											if(data.buildingName !== ''){
													extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
											}
											// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
											fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
									}
									if(mode == 1) {
										// 우편번호와 주소 정보를 해당 필드에 넣는다.
										document.getElementById('zipcode').value = data.zonecode; //5자리 새우편번호 사용
										document.getElementById('haddress1').value = fullAddr;
										// 커서를 상세주소 필드로 이동한다.
										document.getElementById('haddress2').focus();
									}else {
										// 우편번호와 주소 정보를 해당 필드에 넣는다.
										document.getElementById('czipcode').value = data.zonecode; //5자리 새우편번호 사용
										document.getElementById('caddress1').value = fullAddr;
										// 커서를 상세주소 필드로 이동한다.
										document.getElementById('caddress2').focus();

									}
							}
					}).open();
			}

				function emailCheck(form) {
					if(form.email_c.value == ""){
						form.email_b.readOnly = false;
						form.email_b.value = "";
						form.email_b.focus();
					}else{
						form.email_b.value = "";
						form.email_b.value = form.email_c.value;
						form.email_b.readOnly = true;
					}
				}

				$(function(){
					$('#limitDate').datepicker({
					 dateFormat: 'yy-mm-dd',
					 showMonthAfterYear:true,
					 buttonText: "달력",
					 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
					 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
					});
					$('#endDate').datepicker({
					 dateFormat: 'yy-mm-dd',
					 showMonthAfterYear:true,
					 buttonText: "달력",
					 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
					 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
					});
				});
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
						<h3 id="headTitle">회원 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="setForm">
									<fieldset>
										<legend>회원 정보 관리</legend>
											<table>
												<caption>회원 정보 관리</caption>
													<colgroup>
														<col width="15%" />
														<col width="35%" />
														<col width="15%" />
														<col width="35%" />
													</colgroup>
												<tbody>
													<tr>
														<th><label for="mId">아 이 디</label></th>
														<td ><input type="text" name="mId" size="15" maxlength="12" value="<?=$view['mId'];?>" class="wTbox" title="아이디입력"> <a href="javascript:dupl_id_check(1)" title="아이디 중복확인">중복확인</a> 4~12자 이하</td>
														<th><label for="nickname">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;별</label></th>
														<td>
															<input type="radio" name="sex" value="1"<?php if($view['sex']=='1') echo " checked";?> class="align_left_middle" />남 <input type="radio" name="sex"<?php if($view['sex']=='2') echo " checked";?> value="2" class="align_left_middle" />여
														</td>
													</tr>
													<tr>
														<th><label for="mName">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명</label></th>
														<td><input type="text" name="mName" size="15" maxlength="16" value="<?=$view['mName'];?>" class="wTbox" title="성명입력"></td>
														<th><label for="nickname">별&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명</label></th>
														<td><input type="text" name="nickName" size="15" maxlength="16" value="<?=$view['nickName'];?>" class="wTbox" title="별명입력"></td>
													</tr>
													<tr>
														<th><label for="password1">비밀번호</label></th>
														<td><input type="password" name="password1" size="18" maxlength="12" class="wTbox" title="비밀번호 입력" /></td>
														<th><label for="password2">비밀번호 확인</label></th>
														<td><input type="password" name="password2" size="18" maxlength="12" class="wTbox" title="비밀번호 확인" /></td>
													</tr>
													<!--
													<tr>
														<th><label for="iQuestion">비밀번호 찾기<br />연상문구</label></th>
														<td colspan="3">
															<select name="iQuestion" class="wSbox" title="비밀번호찾기 질문 선택">
																<option value="">선 택</option>
																<?php for($j=0; $j<count($findpassArr); $j++) {
																	if($view['iQuestion'] == $j) $pStatus=" selected"; else $pStatus="";
																	echo "<option value=\"".$j."\" ".$pStatus.">".$findpassArr[$j]."</option>";
																}?>
															</select>
															<input type="text" name="iAnswer" size="25" maxlength="255" value="<?=$view['iAnswer'];?>" class="wTbox" title="답변 입력"><br />
															비밀번호를 잊으신 경우 위에 작성하시는 질문과 답을 이용하여 본인확인 후 비밀번호를 재발급받게 되므로, 꼭 기억하시기 바랍니다.
														</td>
													</tr>
													-->
													<tr>
														<th><label for="birthYear">생년월일</label></th>
														<td colspan="3">
															<select name="birthYear" class="wSbox" title="년도선택">
																<option value="" selected>선 택</option>
																<?php for($i=date('Y'); $i>=1912; $i--) {
																	if($v_birth[0]==$i) $yStatus=" selected"; else $yStatus="";
																	echo "<option value=\"".$i."\" ".$yStatus.">".$i."</option>";
																}?>
															</select>
															<select name="birthMonth" class="wSbox" title="월선택">
																<option value="" selected>선 택</option>
																<?php for($i=1; $i<=12; $i++) {
																	if($v_birth[1]==$i) $mStatus=" selected"; else $mStatus="";
																	echo "<option value=\"".$i."\" ".$mStatus.">".$i."</option>";
																}?>
															</select>
															<select name="birthDay" class="wSbox" title="일선택">
																<option value="" selected>선 택</option>
																<?php for($i=1; $i<=31; $i++) {
																	if($v_birth[2]==$i) $dStatus=" selected"; else $dStatus="";
																	echo "<option value=\"".$i."\" ".$dStatus.">".$i."</option>";
																}?>
															</select>
															<input type="radio" name="solu" value="1"<?php if($view['solu']==1 || $_GET['mode']=="add") echo " checked";?> class="align_right_middle">양력&nbsp;<input type="radio" name="solu" value="2"<?php if($view['solu']==2) echo " checked";?> class="align_right_middle">음력
														</td>
													</tr>
													<tr>
														<th><label for="email_a">전자우편</label></th>
														<td colspan="3">
															<input name="email_a" size="15" maxlength="255" value="<?=$arrEmail[0];?>" class="wTbox" title="메일 아이디 입력">&nbsp;@
															<input name="email_b" size="15" maxlength="255" value="<?=$arrEmail[1];?>" class="wTbox" title="메일도메인 입력">
															<select name="email_c" onChange="emailCheck(this.form);" class="wSbox" title="메일도메인 선택">
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
															</select> 메일수신<input type="checkbox" name="newsDm" value="1" <?php if($view['newsDm']==1) echo " checked";?> title="메인수신여부" class="align_right_middle">
														</td>
													</tr>
													<tr>
														<th><label for="hzipcode1">주 소 지</label></th>
														<td colspan="3">
															<div><input type="text" id="zipcode" name="zipcode" size="5" maxlength="5" value="<?=$view['zipcode'];?>" class="wTbox" readonly title="우편번호"><span onClick="DaumPostcode('1');" style="cursor:pointer;">주소찾기</span></div>
															<div><input type="text" id="haddress1" name="haddress1" size="45" maxlength="60" value="<?=$view['haddress1'];?>" class="wTbox" title="주소 입력">
															<input type="text" id="haddress2" name="haddress2" size="30" maxlength="30" value="<?=$view['haddress2'];?>" class="wTbox" title="나머지 상세주소 입력"></div>
														</td>
													</tr>
													<tr>
														<th><label for="telNum01">일반전화</label></th>
														<td>
															<select name="telNum01" class="wSbox" title="일반전화 앞자리 선택">
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
															</select>-<input type="text" name="telNum02" size="5" maxlength="4" value="<?=$arrTel[1];?>" class="wTbox" title="일반전화 가운데자리 입력">-<input type="text" name="telNum03" size="5" maxlength="4" value="<?=$arrTel[2];?>" class="wTbox" title="일반전화 끝자리 입력">
														</td>
														<th><label for="selNum01">휴대전화</label></th>
														<td width="" class="line_solid">
															<select name="selNum01" class="wSbox" title="휴대전화 앞자리 입력">
																<option value="" selected>선택</option>
																<option value="010"<?php if($arrSel[0]=="010") echo " selected";?>>010</option>
																<option value="011"<?php if($arrSel[0]=="011") echo " selected";?>>011</option>
																<option value="016"<?php if($arrSel[0]=="016") echo " selected";?>>016</option>
																<option value="017"<?php if($arrSel[0]=="017") echo " selected";?>>017</option>
																<option value="018"<?php if($arrSel[0]=="018") echo " selected";?>>018</option>
																<option value="019"<?php if($arrSel[0]=="019") echo " selected";?>>019</option>
															</select>-<input type="text" name="selNum02" size="5" maxlength="4" value="<?=$arrSel[1];?>" class="wTbox" title="휴대전화 가운데 자리 입력">-<input type="text" name="selNum03" size="5" maxlength="4" value="<?=$arrSel[2];?>" class="wTbox" title="휴대전화 끝 자리 입력">
														</td>
													</tr>
													<tr>
														<th><label for="password1">회원등급</label></th>
														<td>
															<select name="ulevel" class="wSbox">
																<?php
																	$mLevel = $mLevel ?? []; // null 방지 
																	for($i=1; $i<=count($mLevel); $i++) {
																		if($view['ulevel']==$i) $lCheck=" selected"; else $lCheck="";
																		echo "<option value=\"$i\"$lCheck>".$mLevel[$i]."</option>\n";
																	}?>
															</select>
														</td>
														<th><label for="login">방문횟수</label></th>
														<td><input type="text" name="login" size="10" maxlength="10" class="wTbox" value="<?=$view['login'];?>" title="로그인 횟수 입력" />회</td>
													</tr>
													<tr>
														<th>가입일자</th>
														<td><?=strtr($view['signDate'],"-",".");?></td>
														<th>마지막 로그인</th>
														<td><?=strtr($view['lastLogin'],"-",".");?></td>
													</tr>
													<tr>
														<th><label for="password1">관리자 메모</label></th>
														<td colspan="3"><textarea name="adminMemo" rows="10" class="wTarea"><?=$view['adminMemo'];?></textarea></td>
													</tr>
												</tbody>
											</table>
									</fieldset>
								</form>
							</div>
							<div class="align_center_middle" style="padding-top:10px;">
								<input type="button" value="정보수정" onClick="move_url('','edit','<?=$view['idx'];?>','<?=$_GET['page'];?>');" class="wBtn" title="정보수정" />&nbsp;&nbsp;
								<input type="button" value="회원삭제" onclick="move_url('<?=$view['mName'];?>','del','<?=$view['idx'];?>','<?=$_GET['page'];?>');" class="button" title="회원삭제" />&nbsp;&nbsp;
								<input type="button" value="뒤로이동" onclick="javascript:history.back();" class="button" title="뒤로이동" />
							</div>
						</div>
						<!-- 콘텐츠 종료 -->
					</div>
					<!-- 콘텐츠 우측 -->
					<?if($Right_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Right_Inc_File);?>
				</div>
			</div>
			<!-- 주소 및 보텀 메뉴 시작 -->
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#footer-quick">주소 및 카피라이터 메뉴</a></h2>
			<?if($Foot_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Foot_Inc_File);?>
		</div>
	</body>
</html>
<?$db->disconnect();?>