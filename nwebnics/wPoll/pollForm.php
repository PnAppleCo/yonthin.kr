<?
//========================================================//
//== last modify date : 2012. 05. 26
//========================================================//
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(login_session() == false) redirect(1, "/", "회원 로그인후 이용하세요.", 1);

if(!$_GET[code]) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

if($_GET[mode]=='edit') {
	if(!$_GET[idx]) js_action(1,"중요정보를 찾을수 없습니다.","",-1);
	$sqlStr="SELECT * FROM wPoll WHERE idx=$_GET[idx]";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$sContents=stripslashes($view[sContents]);
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
		<script type="text/javascript" src="/nwebnics/js/jquery-ui.min.js"></script>
		<script type="text/javascript">
			function addChk(thisform,code,mode,m_idx,v_page,mnv) {
				if(mode=='add' || mode=='edit') {
					if(!thisform.sSubject.value) { alert("설문제목을 입력하세요!"); thisform.sSubject.focus(); return; }
					if(!thisform.wName.value) { alert("글작성자를 입력하세요!"); thisform.wName.focus(); return; }
					//if(!thisform.endDate.value) { alert("종료일을 입력하세요!"); thisform.endDate.focus(); return; }
					if(mode=='add') {
						//if(!thisform.sNum.value) { alert("항목 갯수를 선택하세요!"); thisform.sNum.focus(); return; }
					}
					/*
					if(thisform.sNum.value>1) {
						obj = document.getElementsByName("censitem[]");
						for(i = 0; i < obj.length; i++) {
							var step = i + 1;
							if(!obj.censitem[i].value) { alert("항목"+step+"번을 입력하세요!"); thisform.sNum.focus(); return; }
						}
					}
					*/
				}
				if(mode=='edit') {
					if(!confirm("수정 하시겠습니까?")) { return false; }
					thisform.action = 'pollExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&page='+v_page+'&mnv='+mnv;
					thisform.method = 'POST';
					thisform.submit();
				}else if(mode=='add') {
					thisform.action = 'pollExe.php?mode='+mode+'&code='+code+'&mnv='+mnv;
					thisform.method = 'POST';
					thisform.submit();
				}else if(mode=='del') {
					if(!confirm("정말 삭제 하시겠습니까?")) { return false; }
					thisform.action = 'pollExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&page='+v_page+'&mnv='+mnv;
					thisform.method = 'POST';
					thisform.submit();
				}else { alert('작업모드가 선택되지 않았습니다.!'); }
			}

			function addRow() {
				var rowCount = $('.addfile').length;
				if(rowCount>9) { alert('10개 까지만 등록할수 있습니다.'); return; }
				var row = $("#templat").val();
				$(row).appendTo("#boardView");
			}
			function delRow(obj) {
				if(!window.confirm("선택된 목록을 삭제 하시겠습니까?")) return;
				$(obj).parent().parent().remove();
				/*
				var rows = $("input[name='rowNum']").each(function(i,val){ //ROW넘버 스크립트
				this.value=parseInt(i+1);
				});
				*/
			}
		//-->
		</script>
		<script type="text/javascript">
			$(function(){
				$('#endDate').datepicker({
				 showOn: "button",
				 buttonImage: "/nwebnics/img/calendar.gif",
				 buttonImageOnly: true,
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});
		</script>

<script language="javascript">
<!--
	//== 해당 tag 의 부모 객체를 리턴
	function jhj_get_HTML_parentObj(tagName) {
		var obj = event.srcElement
		//== TD가 나올때까지의 Object추출
		while (obj.tagName != tagName) {
			obj = obj.parentElement
		}
		return obj
	}

	//== textbox 추가
	function jhj_add_Option(tblId, cnt) {
		var row_cnt;
		var upload_count = 100;
		var objTbl;
		var objRow;
		var objCell_1, objCell_2;
		if(document.getElementById) {
			objTbl = document.getElementById(tblId);
		}else {
			objTbl = document.all[tblId];
		}
		row_cnt = objTbl.rows.length;

		if(upload_count && row_cnt >= upload_count) {
			alert("항목 최대 "+upload_count+"개 까지만 추가 가능합니다.");
			return;
		}

		objRow = objTbl.insertRow( objTbl.rows.length);
		objCell_1 = objRow.insertCell(0);
		objCell_2 = objRow.insertCell(1);

		//== td 속성 지정
		objCell_1.width = "730"
		objCell_1.align = "left"
		objCell_2.align = "left"
		objCell_1.innerHTML = "<div>항목"+cnt+" : <input type='text' size='50' maxlength='255' name='censitem[]' class='textbox'> <img src='/img/comm/i_delete.gif' style='align:left;' onclick=\"jhj_del_option("+ tblId +");\" alt='삭제' style='cursor:pointer;' /></div>";
	}

	function jhj_del_option(objTbl) {
		//== file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 0;
		//var objTbl = document.getElementById(delopt);
		var del_rowidx
		del_rowidx = jhj_get_HTML_parentObj('TD').parentElement.rowIndex ;
		objTbl.deleteRow(del_rowidx);
	}

	function qRoof(tvalue) {
		for(var i=1; i<= tvalue; i++) { jhj_add_Option('optcont', i); }
	}

//-->
</script>
	</head>
	<body>
		<h1 class="blind"><?=$Site_Info_Company;?> 홈페이지에 오신것을 환영합니다.</h1>
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
			<?if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<p id="siteDepth"><?=$Site_Path;?></p>
						<h3 id="headTitle"><img src="<?=$Title_Bar_Image;?>" /></h3>
						<!-- 콘텐츠 시작 -->
						<p id="contentsBody">

						<div class="tblList">
							<form name="pollForm" enctype="multipart/form-data">
								<fieldset>
									<legend><?=$porcessName;?></legend>
									<table class="boardView" summary="<?=$porcessName;?>">
										<caption><?=$porcessName;?></caption>
										<colgroup>
											<col width="15%" />
											<col width="35%" />
											<col width="15%" />
											<col width="35%" />
										</colgroup>
										<thead>
											<th colspan="4" style="text-align:left;">설문 등록</th>
										</thead>
										<tbody>
											<tr>
												<th>설문제목</th>
												<td colspan="3"><input type="text" name="sSubject" size="70" maxlength="255" class="textbox"<?if($view[sSubject]) echo " value=\"$view[sSubject]\"";?>></td>
											</tr>
											<tr>
												<th>글작성자</th>
												<td><input type="text" name="wName" size="15" maxlength="20" class="textbox"<?if($view[wName]) echo " value=\"".$view[wName]."\"";?><?if($_GET[mode]=="add") echo " value=\"".$_SESSION[my_name]."\"";?> readonly></td>
												<th>종 료 일</th>
												<td>
													<input type="text" name="endDate" id="endDate" size="15" value="<?if($view[endDate]!="0000-00-00") echo $view[endDate];?>" class="textbox">
												</td>
											</tr>
											<tr>
												<th>내 용</th>
												<td colspan="3">
												<?
													include("../htmlEditor/fckeditor2/fckeditor.php");
													$oFCKeditor = new FCKeditor('sContents');
													$oFCKeditor->BasePath='../htmlEditor/fckeditor2/';
													$oFCKeditor->Height=300;
													$oFCKeditor->ToolbarSet='webnics';
													$oFCKeditor->Value=$sContents;
													$oFCKeditor->Create();
												?>
												</td>
											</tr>
											<tr>
												<th>설문항목</th>
												<td colspan="3">
													<?if($_GET[mode]=="add") {?>
													<select name="sNum" class="selectbox" onchange="qRoof(this.value);">
														<option value="">선택:</option>
														<option value="2"<?if($view[sNum]=="2") echo " selected";?>>2</option>
														<option value="3"<?if($view[sNum]=="2") echo " selected";?>>3</option>
														<option value="4"<?if($view[sNum]=="3") echo " selected";?>>4</option>
														<option value="5"<?if($view[sNum]=="4") echo " selected";?>>5</option>
														<option value="6"<?if($view[sNum]=="5") echo " selected";?>>6</option>
														<option value="7"<?if($view[sNum]=="6") echo " selected";?>>7</option>
														<option value="8"<?if($view[sNum]=="7") echo " selected";?>>8</option>
														<option value="9"<?if($view[sNum]=="8") echo " selected";?>>9</option>
														<option value="10"<?if($view[sNum]=="9") echo " selected";?>>10</option>
													</select>
													<?}?>
													<table id="optcont<?=$i;?>" width="100%" border="0" cellspacing="0" cellpadding="1">
														<tr>
															<td style="border:none;">
																<?
																if($_GET[mode]=="edit") {
																for($k=0; $k<10; $k++) {?>
																<div><input type="textbox" name="censitem[]" size="48" maxlength="255" class="textbox" value="<?=$view[censitem.$k];?>" /></div>
																<?}}?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
									<div style="padding:5px; text-align:right;">
										<input type="hidden" name="upFile[]" value="<?=$view[filename0];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename1];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename2];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename3];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename4];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename5];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename6];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename7];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename8];?>">
										<input type="hidden" name="upFile[]" value="<?=$view[filename9];?>">
										<input type="hidden" name="code" value="<?=$_GET[code];?>">
										<?
											if($_GET[mode]=="add") {
												$v_img="i_write";
												$v_text="등 록";
											}else if($_GET[mode]=="edit") {
												$v_img="i_modify";
												$v_text="수 정";
												echo "<input type=\"hidden\" name=\"sNum\" value=\"".$view[sNum]."\">";
											}
										?>
										<img src="/img/comm/<?=$v_img;?>.gif" onClick="addChk(document.pollForm,'<?=$_GET[code];?>','<?=$_GET[mode];?>','<?=$view[idx];?>','<?=$_GET[page];?>','<?=$_GET[mnv];?>'); return false;" alt="<?=$v_text;?>" style="cursor:pointer;" />
									</div>
								</fieldset>
							</form>
						</div>
						</p>
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