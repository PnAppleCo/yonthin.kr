<?php
//================================================================================================//
//====================================== 공통 함수 부분 ===========================================//
//================================================================================================//

//== 로그인여부 체크
function login_session() {
	global $_SESSION;
	$user_ip = getenv('REMOTE_ADDR');
	if(!$_SESSION['my_id'] || !$_SESSION['my_idx'] || !$_SESSION['my_name'] || !$_SESSION['my_level'] || ($_SESSION['my_ip'] != $user_ip)) return false; else return true;
}

//== 회원 로그인 세션정보 체크 함수
function member_session($level) {
	global $_SESSION;
	$user_ip = getenv('REMOTE_ADDR');
	if(!$_SESSION['my_idx'] || !$_SESSION['my_id'] || !$_SESSION['my_name'] || $_SESSION['my_level'] > $level || ($_SESSION['my_ip'] != $user_ip)) return false; else return true;
}

function session_starting($idx,$id,$name,$nickname, $level,$user_ip) {
	session_start();
	//== 세션등록
	$_SESSION['my_idx'] = $idx;
	$_SESSION['my_id'] = $id;
	$_SESSION['my_name'] = $name;
	$_SESSION['my_nick'] = $nickname;
	$_SESSION['my_level'] = $level;
	$_SESSION['my_ip'] = $user_ip;

}

//== 세션 종료 함수
function session_ending() {
	session_start();
	unset($_SESSION['my_idx'], $_SESSION['my_id'], $_SESSION['my_name'], $_SESSION['my_nick'], $_SESSION['my_level'], $_SESSION['my_ip']);
}

//== 글자 길이 자르는 함수
//$text = mb_substr($text,0,10,"euc_kr"); //한글의 경우 이와 같이 사용해도 된다. euc-kr인지 eur_kr인지는 잘.. 모르겠군.. 아직은 불안정
//--enable-mbstring : Enable mbstring functions. This option is required to use mbstring functions.
//--enable-mbstr-enc-trans : Enable HTTP input character encoding conversion using mbstring conversion engine. If this feature is enabled, //HTTP input character encoding may be converted to mbstring.internal_encoding automatically.

function han_cut($str, $length, $addstr = "…") {
	if($addstr) return mb_substr($str,0,$length,"utf-8").$addstr; else return mb_substr($str,0,$length,"utf-8");
}
/*
function han_cut($str, $length, $addstr = "") {
	if(!$str || $length >= strlen($str)) return $str;
	$rst_str = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$length));
	return $rst_str.$addstr;
}
*/
//== 위드랩함수
function han_wordwrap($str,$count=75,$newline="\n",$cut=0) {
	if($cut) $head="(?:[^\s]{".$count."})|";
	return preg_replace("/".$head."(?:(?>.{1,".$count."})$)|(?:(?:[\\x00-\\x7f]|[\\x80-\\xff].){1,".($count-1)."}\s)/s","\\0".$newline,$str);
}

//== 마이크로 시간을 구하는 함수
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

//== 임의 페이지 이동 함수
function redirect($mode, $gourl, $ment="", $delay=0) {
	global $_SERVER;
	$R_Head="<html>\n<head>\n<title>::: 작업이동중 :::</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n<meta name=\"viewport\" content=\"width=device-width, user-scalable=no, initial-scale=1.0\">\n<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/css.css\" /><style>body { background:#FFFFFF }</style>\n";

	switch ($mode) {
		case 1 :		//= 프로그레스바 없는 리다이렉트
			echo $R_Head."<meta http-equiv='Refresh' content='".$delay."; url=".$gourl."'></head><body><p class=\"loadingAlign\"><img src=\"/img/comm/loading.gif\"> <span  style=\"color:#000;\">".$ment."</span></p></body></html>";
		break;
		case 2 :		//== 프로그레스바 있는 리다이렉트(일반프로그레스)
			echo $R_Head."</head>\n<body>\n<p class=\"loadingAlign\">\n";
			require $_SERVER["DOCUMENT_ROOT"]."/nwebnics/js/page_loading.js";
			echo "<br />".$ment."\n</p>\n</body>\n</html>";
		break;
		case 3 :		//== 프로그레스바 있는 리다이렉트(윈도우 프로그레스)
			echo $R_Head."</head>\n<body>\n<p class=\"loadingAlign\">";
			require $_SERVER["DOCUMENT_ROOT"]."/nwebnics/js/xp_loading.js";
			echo "<br />".$ment."</p>\n</body>\n</html>";
		break;
		default :
			echo $R_Head."<meta http-equiv='Refresh' content='".$delay."; url=".$gourl."'></head><body><p class=\"centerAlign\" style=\"color:#fff;\">".$ment."</p></body></html>";
	}
	exit;
}

//== 뒤 페이지로 이동 함수
function js_action($mode, $ment, $gourl = "/", $count = -1) {
	switch ($mode) {
		case 1 :
			$js_action="history.go(".$count.");";
			break;
		case 2 :
			$js_action=$js_action="self.close();";
			break;
		case 3 :
			$js_action="location.href='".$gourl."';";
			break;
		default :
			$js_action="";
	}
	echo "<script language=\"javascript\">\nwindow.alert(\"$ment\");\n".$js_action."\n</script>\n";
	exit ;
}

//== confirm
function js_confirm($ment, $gourl = "/") {
	echo "<script language=\"javascript\">if(confirm('".$ment."')) { window.location.href='".$gourl."'; }else { history.go(-1); }</script>";
	exit ;
}

//== 오류 출력 함수
function error_view($Error_No, $Msg1, $Msg2) {
	global $_SERVER;
	//== 오류코드 999번은 사용자 지정이고 999이하는 고정 오류코드
	//require $Site_Root_Path."/webnics/inc/Error_Code.php";
	require $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/errorCode.php";
	echo "
	<html>
	<head>
	<title>::: 오류확인 :::</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
	<meta name=\"viewport\" content=\"width=device-width, user-scalable=no, initial-scale=1.0\">
	<link rel=\"stylesheet\" href=\"/css/css.css\" type=\"text/css\">
	</head>
	<body>
	<div class=\"errorAlign\">
		<p style=\"text-align:center; color:white; background-color:#FC8823; padding:2px; margin-bottom:3px; \"><strong>".$Error_Msg1."</strong></p>
		<p style=\"padding:2px;\">참고사항 : ".$Error_Msg2."</p>
		<p style=\"padding:2px;\">요청URL : ".$_SERVER['HTTP_REFERER']."</p>
		<p style=\"margin-top:3px; text-align:center; background-color:#D2D2D2; padding:2px;\">| <a href=\"/\" class=\"basic\">초기화면</a> | <a href=\"javascript:history.back();\">이전화면</a> |</p>
	</div>
	</body>
	</html>";
	exit;
}

//== 주민등록 검사 함수(제로보드)
function check_jumin($jumin) {
	$weight = '234567892345';
	$len = strlen($jumin);
	$sum = 0;

	if ($len <> 13) return false;
	for ($i = 0; $i < 12; $i++) {
		$sum = $sum + (substr($jumin,$i,1)*substr($weight,$i,1));
	}

	$rst = $sum%11;
	$result = 11 - $rst;

	if ($result == 10) {
		$result = 0;
	}else if($result == 11) {
		$result = 1;
	}

	$ju13 = substr($jumin,12,1);

	if ($result <> $ju13) return false;
	return true;
}

function userEnvInfo() {
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	//== 브라우저
	if(preg_match('/(chromium)[ \/]([\w.]+)/', $ua)) $browser = 'chromium';
	elseif(preg_match('/(chrome)[ \/]([\w.]+)/', $ua)) $browser = 'chrome';
	elseif(preg_match('/(safari)[ \/]([\w.]+)/', $ua)) $browser = 'safari';
	elseif(preg_match('/(opera)[ \/]([\w.]+)/', $ua)) $browser = 'opera';
	elseif(preg_match('/(msie)[ \/]([\w.]+)/', $ua)) $browser = 'msie';
	elseif(preg_match('/(mozilla)[ \/]([\w.]+)/', $ua)) $browser = 'mozilla';
	//== 버전
	preg_match('/('.$browser.')[ \/]([\w]+)/', $ua, $version);
	//== 플랫폼
	if(preg_match('/(Windows NT)/i', $ua)) $platform = 'Windows';
	elseif(preg_match('/(Mac)/i', $ua)) $platform = 'Mac OS X';
	elseif(preg_match('/(Linux)/i', $ua)) $platform = 'Linux';
	elseif(preg_match('/(Unix)/i', $ua)) $platform = 'Unix';
	elseif(preg_match('/(FreeBSD)/i', $ua)) $platform = 'FreeBSD';
	elseif(preg_match('/(Sun)/i', $ua)) $platform = 'Sun OS';
	else $platform = 'etc';

	return array('name'=>$browser,'version'=>$version[2],'platform'=>$platform);
}

//== 데이터의 크기를 구하는 함수
function file_view($rst_type,$savedir,$filename) {
	$rst_all = "";
	//== 파일의 크기를 구함
	$filepath=$savedir."/".$filename;
		$file_exe = substr($filename,-3);
		switch ($file_exe) {
			case("gif") : case("GIF") :
				$img_file = "gif.gif";
				break;
			case("jpg") : case("JPG") :
				$img_file = "jpg.gif";
				break;
			case("bmp") :
				$img_file = "bmp.gif";
				break;
			case("png") :
				$img_file = "gif.gif";
				break;
			case("zip") : case("alz") : case("arj") :
				$img_file = "zip.gif";
				break;
			case("rar") :
				$img_file = "rar.gif";
				break;
			case("tar") :
				$img_file = "zip.gif";
				break;
			case("hwp") :
				$img_file = "hwp.gif";
				break;
			case("exe") :
				$img_file = "exe.gif";
				break;
			case("mpg") :
				$img_file = "mpg.gif";
				break;
			case("mpeg") :
				$img_file = "mpg.gif";
				break;
			case("avi") :
				$img_file = "mpg.gif";
				break;
			case("dat") :
				$img_file = "mpg.gif";
				break;
			case("ra") :
				$img_file = "mpg.gif";
				break;
			case("mp3") :
				$img_file = "mp3.gif";
				break;
			case("mp2") :
				$img_file = "mp3.gif";
				break;
			case("mid") :
				$img_file = "mp3.gif";
				break;
			case("wav") :
				$img_file = "wav.gif";
				break;
			case("doc") :
				$img_file = "doc.gif";
				break;
			case("txt") : case("cap") :
				$img_file = "txt.gif";
				break;
			case("pdf") :
				$img_file = "pdf.gif";
				break;
			case("xls") :
				$img_file = "xls.gif";
				break;
			case("ppt") :
				$img_file = "ppt.gif";
				break;
			case("swf") :
				$img_file = "swf.gif";
				break;
			default :
				$img_file = "unknown.gif";
		}
	$rst_img = "<img src=\"./img/icon/$img_file\">";
	$size = @filesize($filepath);
	$unit=array("Bytes", "Kb", "Mb", "Gb");
		for ($i=0; $size>=1024; $size=$size/1024, $i++);
		$rst_all .= "".$filename." ".sprintf("%0.{$i}f$unit[$i]", $size);
		
	if($rst_type==1) return $rst_img; else return $rst_img.$rst_all;
}

//== 메일 보내는 함수(제로보드 함수) :1=>일반텍스트 2=> HTML
function send_mail($type, $to, $to_name, $from, $from_name, $subject, $comment, $cc="", $bcc="") {
	$recipient = "$to_name <$to>";
	if($type==1) $comment = nl2br($comment);
	$headers = "From: $from_name <$from>\n";
	$headers .= "X-Sender: <$from>\n";
	$headers .= "X-Mailer: PHP ".phpversion()."\n";
	$headers .= "X-Priority: 1\n";
	$headers .= "Return-Path: <$from>\n";
	if($type==1) $headers .= "Content-Type: text/plain;"; else $headers .= "Content-Type: text/html;";
	$headers .= "charset=euc-kr\n";
	if($cc) $headers .= "cc: $cc\n";
	if($bcc) $headers .= "bcc: $bcc";
	$comment = stripslashes($comment);
	$comment = str_replace("\n\r","\n", $comment);
	return mail($recipient , $subject , $comment , $headers);
}

//== 주어진 URL을 분리하여 페이지명을 구하는 함수(인자값으로 주어진 상대/절대 주소를 분리)
function now_filename($now_path) {
	if(!strpos("http",$now_path)) {
		$url = parse_url($now_path);
		$now_path = $url['path'];
	}
		$self_path = explode("/", $now_path);
		$org_path = count($self_path);
	if($org_path == 1) {
		$path_count = $org_path;
	}else {
		$path_count = $org_path-1;
	}
	$ok_path = $self_path[$path_count];
	return $ok_path;
}

// PHP82 변환
//== 광고 스팸 등의 불량 단어 추출을 위한 함수    
// function content_check($content,$mode) {
// $refuse_word = array("만원", "돈", "성인", "광고", "섹스", "전문가", "학원", "포트폴리오", "배당금", "홍보", "보지", "허락", "정액", "야동", "대행");
// for($i=0; $i<count($refuse_word); $i++)
// 	if(preg_match("/(".$refuse_word[$i].")/i", $content)) $find_word++;
// 	if($mode == "one") {
// 		if($find_word >= 2) return true;
// 	}else if($mode == "two") {
// 		if($find_word >= 5) return true;
// 	}else {
// 		if($find_word >= 10) return true;
// 	}
// }
function content_check(string $content, string $mode): bool {
    $refuse_word = ["만원", "돈", "성인", "광고", "섹스", "전문가", "학원","포트폴리오", "배당금", "홍보", "보지", "허락", "정액", "야동", "대행"];
    $find_word = 0;

    foreach ($refuse_word as $word) {
        // 특수문자 안전 처리
        if (preg_match('/' . preg_quote($word, '/') . '/iu', $content)) $find_word++;
    }

    // 모드별 기준 판단
    if ($mode === "one" && $find_word >= 2) {
        return true;
    } elseif ($mode === "two" && $find_word >= 5) {
        return true;
    } elseif ($find_word >= 10) { // default case
        return true;
    }

    return false;
}

// PHP82 변환
//== 페이징 클래스
// class paging {
// 	function page_display($total,$num_per_page, $num_per_block,$next) {
// 			$view_val=$this->val_reset();
// 			$total_page = ceil($total/$num_per_page);										//== 총페이지수
// 			$total_block = ceil($total_page/$num_per_block);					//== 총 블럭수
// 			$block = ceil($_GET['page']/$num_per_block);							//== 현재의 블럭
// 			$first_page = ($block-1)*$num_per_block;										//== 처음페이지
// 			$last_page = $block*$num_per_block;													//== 마지막페이지
// 			if($total_block <= $block) $last_page = $total_page;				//== 전체 블럭
// 			$link1 = "<a href=".$_SERVER['PHP_SELF'];
// 			$link2 = ">";
// 		//== 이전 10개
// 		if($block > 1) {
// 			$move_page = $first_page;
// 			$prev_page10 = "<a href=".$_SERVER['PHP_SELF']."?page=".$move_page.$view_val." class=\"bt first\">처음</a>";
// 			$prev_page10="";
// 		}
// 		//== 이전페이지
// 		if($_GET['page'] > 1) {
// 			$page_num = $_GET['page'] - 1;
// 			$prev_page = "<a href=".$_SERVER['PHP_SELF']."?page=".$page_num.$view_val." class=\"bt prev\">이전</a>";
// 		}else {
// 			$prev_page = "<a class=\"bt prev\">이전</a>";
// 		}
// 		//== 직접이동페이지
// 		for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
// 			if($_GET['page'] == $direct_page) {
// 				$now_page .= "<a href=\"#\" class=\"num on\">". $direct_page ."</a>";
// 			} else {
// 				$now_page .= "<a href=".$_SERVER['PHP_SELF']."?page=".$direct_page.$view_val." class=\"num\">".$direct_page."</a>";
// 			}
// 		}
// 		//== 이후페이지
// 		if($next > 0) {
// 			$page_num = $_GET['page'] + 1;
// 			$next_page = "<a href=".$_SERVER['PHP_SELF']."?page=".$page_num.$view_val." class=\"bt next\">다음</a>";
// 		}else {
// 			$next_page = "<a class=\"bt next\">다음</a>";
// 		}
// 		//== 이후10개
// 		if($block < $total_block) {
// 		$move_page = $last_page + 1;
// 			$next_page10 = "<a href=".$_SERVER['PHP_SELF']."?page=".$move_page.$view_val." class=\"bt last\"></a>";
// 		$next_page10="";
// 		}
// 		$paging = $prev_page10.$prev_page.$now_page.$next_page.$next_page10;
// 		return $paging;
// 	}

// 	//== 전달해야할 총변수를 자동전달이 가능하도록 대입
// 	function val_reset() {
// 		global $_GET;
// 		foreach($_GET AS $key=>$val) if($key != "page" && $val != NULL) $r_var .= "&".$key."=".urlencode($val);
// 		return $r_var;
// 	}
	
// }
class paging {

    public function page_display(int $total, int $num_per_page, int $num_per_block, int $next): string {
        $view_val = $this->val_reset();

        $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $total_page = (int)ceil($total / $num_per_page);             // 총 페이지 수
        $total_block = (int)ceil($total_page / $num_per_block);      // 총 블록 수
        $block = (int)ceil($current_page / $num_per_block);          // 현재 블록
        $first_page = ($block - 1) * $num_per_block;                 // 블록의 첫 페이지
        $last_page = $block * $num_per_block;                        // 블록의 마지막 페이지
        if ($last_page > $total_page) $last_page = $total_page;

        $prev_page10 = '';
        $prev_page = '';
        $next_page = '';
        $next_page10 = '';
        $now_page = '';

        $self = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES);

        // 이전 10페이지
        if ($block > 1) {
            $move_page = $first_page;
            $prev_page10 = "<a href=\"{$self}?page={$move_page}{$view_val}\" class=\"bt first\">처음</a>";
        }

        // 이전 페이지
        if ($current_page > 1) {
            $page_num = $current_page - 1;
            $prev_page = "<a href=\"{$self}?page={$page_num}{$view_val}\" class=\"bt prev\">이전</a>";
        } else {
            $prev_page = "<a class=\"bt prev\">이전</a>";
        }

        // 직접 이동 페이지 번호
        for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page++) {
            if ($current_page === $direct_page) {
                $now_page .= "<a href=\"#\" class=\"num on\">{$direct_page}</a>";
            } else {
                $now_page .= "<a href=\"{$self}?page={$direct_page}{$view_val}\" class=\"num\">{$direct_page}</a>";
            }
        }

        // 다음 페이지
        if ($next > 0 && $current_page < $total_page) {
            $page_num = $current_page + 1;
            $next_page = "<a href=\"{$self}?page={$page_num}{$view_val}\" class=\"bt next\">다음</a>";
        } else {
            $next_page = "<a class=\"bt next\">다음</a>";
        }

        // 다음 10페이지
        if ($block < $total_block) {
            $move_page = $last_page + 1;
            $next_page10 = "<a href=\"{$self}?page={$move_page}{$view_val}\" class=\"bt last\">마지막</a>";
        }

        return $prev_page10 . $prev_page . $now_page . $next_page . $next_page10;
    }

    // GET 파라미터 중 page 제외하고 재구성
    public function val_reset(): string {
        $r_var = '';
        foreach ($_GET as $key => $val) {
            if ($key !== 'page' && $val !== null && $val !== '') {
                $r_var .= '&' . urlencode($key) . '=' . urlencode($val);
            }
        }
        return $r_var;
    }
}

//== 유동적인 GET 데이터 조인
function get_value($mode=0, $del_var='') {
	global $_GET;
	$link_get = '';
	$del_var=explode("/", $del_var);
	for($i=0; $i<count($del_var); $i++) unset($_GET[$del_var[$i]]);			//== 데이터가 변경되는 변수 파괴
	$i=0;
	foreach($_GET as $key => $value) {
		if($value != NULL) $link_get .= $key."=".$value."&";
		$i++;
	}
	if($mode>0) return substr($link_get, 0, -1); else return $i;
}

//== 파일 업로드 함수(공백과 동일한 파일로인해 이름이 변경된경우 DB에 입력되는 데이터와 저장되파일이름이 다름 수정요망)
function file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, $upload_max_size,$updir,$only_img) {
	global $_SERVER;
	$data = "";
	//== 업로드파일의 갯수를 구해 갯수만큼 루프로 돌리며 업로드
	for($i=0;$i<sizeof($filename);$i++) {
		//== 이미지만을 업로드할 경우 체크함수 (php4.3.0 이상에서 동작하는 함수인데 현재버전 4.3.2버전에는 동작안함)
		//== if(!exif_imagetype($filename[$i])) error_view('이미지파일만을 업로드 할수 있습니다.','','');
		//== 이미지만 업로드를 허가할 경우
		$img_array = array("image/gif", "image/pjpeg", "image/bmp", "image/jpeg", "image/png");
		if($filename[$i] && $filesize[$i]) {
			if($only_img == 1) if(!in_array($filetype[$i], $img_array)) js_action(1, "GIF, JPG, PNG 등의 이미지만 업로드 할수 있습니다.","",-1);
		}

		switch ($fileerror[$i]) {
		case 0:
			//== 정상적인 경로인지 체크
			if(!is_uploaded_file($filename[$i])) {
				//== 파일 크기 제한
				if($filesize[$i] >= $upload_max_size) js_action(1, "파일의 용량을 확인해 주세요.","",-1);
				//== 공백을 "_"로 치환
				$filename[$i] = str_replace(" ","_", $filename[$i]);
				$filename[$i] = str_replace(",","_", $filename[$i]);
				$full_filename = explode(".", $filename[$i]);
				$extension = $full_filename[sizeof($full_filename)-1];

				//== 등록 불가한 파일을 제거후 이미지 업로드
				if(preg_match("/\.(php|html|phtm|inc|class|htm|shtm|pl|cgi|ztx|dot|php3)/i", $filename[$i])) {
					js_action(1, "업로드가 불가 파일입니다.","",-1);
				}else {
					//== 저장 디렉토리가 확인
					$upload_dir = $_SERVER["DOCUMENT_ROOT"].$updir;

					if(!is_dir($upload_dir)) mkdir($upload_dir,0777);

					//== 이미지는 무조건 난수로 이름을 다시 생성하고 기타파일은 중복 제거후 업로드
					if(in_array($filetype[$i], $img_array)) {
						$name = not_duple_rand("",6);
						$filename[$i] = $name.".".$extension;
						while(file_exists($upload_dir."/".$filename[$i])) {
							$name = not_duple_rand("",6);
							$filename[$i] = $name.".".$extension;
						}
					}else {
						//== 같은 파일이 있다면 이름 변경
						if(file_exists($upload_dir."/".$filename[$i])) {
							$name = $full_filename[0]."_";
							$filename[$i] = $name.".".$extension;
							while(file_exists($upload_dir."/".$filename[$i])) {
								$name = $full_filename[0]."_".not_duple_rand("",6);
								$filename[$i] = $name.".".$extension;
							}
						}
					}

					//== 업로드 제한 시간 설정(10분)
					set_time_limit(600);
					//== 이곳에 업로드중... 이란 글귀와 함께 연결이 끝기지않게 sleep 시켜주는 부분을 넣으면 좋을듯...
					if(!move_uploaded_file($filetmp[$i], $upload_dir."/".$filename[$i])) {
						//== 업로드 실패[임시파일 제거]
						@unlink($filetmp[$i]);
						js_action(1, "업로드중 예기치 못한 오류가 발생하였습니다.","",-1);
					}else {
						//== 업로드 성공 [임시파일 제거]
						@unlink($filetmp[$i]);
					}
				}
			}else {
				js_action(1,"올바른 방법으로 업로드하여 주십시오.","",-1);
			}
			break;
		case 1:
			js_action(1,"시스템에서 정의된 MAX_FILE_SIZE를 초과하였습니다.","",-1);
			break;
		case 2:
			js_action(1,"관리자가 정의한 MAX_FILE_SIZE를 초과하였습니다.","",-1);
			break;
		case 3:
			js_action(1,"파일의 일부분만이 업로드 되었습니다.","",-1);
			break;
//		case 4:
//			js_action(1,"업르드된 파일이 존재하지 않습니다..","",-1);
//			break;
//		case 5:
//			js_action(1,"0bytes가 업로드 되었습니다.","",-1);
//			break;
		}
	}
	for($j=0;$j<sizeof($filename);$j++) {
		$data .= $filename[$j].'|';
	}
	return $data;
}

//== 나이와 성인체크
function age_check($jumin1, $jumin2, $mode, $adult_age=19) {
	$user_year = substr($jumin1, 0, 2);
	$user_sex = substr($jumin2, 0, 1);

	if($mode === "age") {
		$age = date("Y", time()) - (floor(1900+(intval($user_sex/3)*100))+$user_year);
		return ++$age;
	}else if($mode === "adult"){
		$age = date("Y", time()) - (floor(1900+(intval($user_sex/3)*100))+$user_year);
		if($age < $adult_age) return false; else return true;
	}
}

// PHP82 변환
//== 중복없는 난수 생성 함수(char->대표문자 place->날짜를 제외한 난수자릿수)
// function not_duple_rand($r_char,$place) {
// //$rand_num=date("ymdHis").abs(microtime()); //=> 이와같이 날짜를 포함한 시분초까지 검출후 마이크로시간을 합해도 중복이 안됨 참고 바람
// 		$serial_make = "0123456789";
// 			srand((double)microtime()*1000000);
// 				for($i=0; $i<$place; $i++){
// 					$serial .= $serial_make[rand()%strlen($serial_make)];
// 					uniqid($serial);
// 				}
// 				$rand_num=$r_char.date('y').date('m').date('d').$serial;
// 			return $rand_num;
// }
function not_duple_rand(string $r_char, int $place): string {
    $serial_make = '0123456789';
    $serial = '';

    for ($i = 0; $i < $place; $i++) {
        $rand_index = random_int(0, strlen($serial_make) - 1);
        $serial .= $serial_make[$rand_index];
    }

    // 날짜 기반 프리픽스 (예: YMDD)
    $date_part = date('ymd'); // 년월일

    // 최종 랜덤 문자열
    return $r_char . $date_part . $serial;
}

// PHP82 변환
//== 아이디 등의 중복체크
// function duple_check($dbconn,$table_name,$compare_data,$check_data) {
// 	$query = "select count($compare_data) from $table_name where $compare_data = '$check_data'";
// 	$result = mysql_query($query);
// 		if(!$result) error_view("질의문에 다음과 같은 오류가 있습니다.","","ok");
// 		$rows = mysql_result($result,0,0);
// 		return $rows;
// }
function duple_check(mysqli $dbconn, string $table_name, string $compare_data, string $check_data): int {
    // 쿼리 준비 (SQL Injection 방지)
    $query = "SELECT COUNT(*) FROM `$table_name` WHERE `$compare_data` = ?";
    
    $stmt = $dbconn->prepare($query);
    if (!$stmt) {
        error_view("질의문에 다음과 같은 오류가 있습니다.", $dbconn->error, "ok");
        return 0;
    }

    $stmt->bind_param('s', $check_data);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;
}

//== 입력된 月의 日수를 구함
function get_total_days($year,$month) {
	$v_date = 1;
	while(checkdate($month,$v_date,$year)) $v_date++;
	$v_date--;
	return $v_date;
}

//== 달력 출력 함수
function show_calendar() {
	global $_GET;
	//== 주어진 날짜가 없을경우 오늘날짜 지정
	if(!$_GET['year'] || !$_GET['month']) {
		$_GET['year'] = date('Y');
		$_GET['month'] = date('m');
	}
	//== 시작주와 마지막 날짜 계산
	$first_week = date('w', mktime(0,0,0,$_GET['month'],1,$_GET['year']));
	$last_day = date('t', mktime(0,0,0, $_GET['month'],1,$_GET['year']));
	//== 전체일수를 계산
	$total_days=get_total_days($_GET['year'],$_GET['month']);

	//== 이전의 년/월 구하기
	$month_p = $_GET['month']-1;
	if($month_p < 1) {
		$month_p=12;
		$year_p=$_GET['year']-1;
	}else {
		$year_p=$_GET['year'];
	}
	//== 이후의 년/월 구하기
	$month_n = $_GET['month'] + 1;
	if($month_n > 12) {
		$month_n=1;
		$year_n=$_GET['year']+1;
	}else {
		$year_n=$_GET['year'];
	}
	$prev_link="<a href=\"$_SERVER[PHP_SELF]?year=$year_p&month=$month_p\"><img src=\"./img/prev_month.gif\" border=\"0\" alt=\"이전달\"></a>";
	$next_link="<a href=\"$_SERVER[PHP_SELF]?year=$year_n&month=$month_n\"><img src=\"./img/next_month.gif\" border=\"0\" alt=\"다음달\"></a>";
	//== 달력의 기본 정보 출력
	$view_title=$_GET['year']."년 ".$_GET['month']."월 ";
	echo "<table width=\"100%\" height=\"\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
	<tr>
		<td width=\"100%\" height=\"\" align=\"center\" class=\"\">
			<table width=\"100%\" height=\"\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td width=\"\" height=\"\" align=\"left\">&nbsp;$prev_link</td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><b>$view_title</b></td>
					<td width=\"\" height=\"\" align=\"right\">$next_link&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=\"100%\" height=\"\" align=\"center\" class=\"\">
			<table width=\"100%\" height=\"\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#CCCCCC\">
				<tr bgcolor=\"#F4F4F4\">
					<td width=\"\" height=\"25\" align=\"center\" class=\"font_kr\"><font color=\"#FF0000\"><b>일</b></font></td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><b>월</b></td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><b>화</b></td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><b>수</b></td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><b>목</b></td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><b>금</b></td>
					<td width=\"\" height=\"\" align=\"center\" class=\"font_kr\"><font color=\"#0056E6\"><b>토</b></font></td>
				</tr>
				<tr bgcolor=\"#FFFBF7\">";
	//== 달의 1일이 나오기 전까지 공백 출력
	$col = 0;
	for($i = 0; $i < $first_week; $i++) {
		echo "<td width=\"\" height=\"\" align=\"center\" class=\"\">&nbsp;</td>";
		$col++;
	}
	//== 날짜 출력 시작
	for($j = 1; $j <= $total_days; $j++) {
		$link_url="<a href=\"#\">";
		echo "<td width=\"\" height=\"45\" align=\"left\" valign=\"top\" class=\"font_en\"";
		echo " onMouseOver=\"this.style.backgroundColor='#F3FFEE'\" onMouseOut=\"this.style.backgroundColor='#FFFBF7'\">";
		if($col === 6) $bgcolor="#0056E6"; else if($col === 0) $bgcolor="#FF0000"; else $bgcolor="#000000";				//== 토/일/평일 색상구분
		if(date('d') == $j) $bgcolor="#52A34C";																																														//== 금일날짜 색상지정
		echo "<font color=\"".$bgcolor."\"><b>".$j."</b></font></a></td>";
		//== 일주일이 지나면 다음줄로
		$col++;
		if($col === 7) {
			echo "</tr> ";
			if($j != $total_days) echo "<tr bgcolor=\"#FFFBF7\">";
			$col = 0;
		}
	}
	//== 달의 마지막날 이후는 공백 출력
	while($col > 0 && $col < 7) {
		echo "<td width=\"\" height=\"\" align=\"center\">&nbsp;</td>";
		$col++;
	}
	echo "</tr></table></td></tr></table>";
}

// PHP82 변환
//== 회원 권한 뽐기
// function member_level_view($id,$divi) {
// 	global $db_conn;
// 	if($divi==="s") {
// 		$dbtable="s_members";
// 	}else {
// 		$dbtable="t_members";
// 	}
// 	$query = "select power_level from $dbtable where id = '$id'";
// 	$result = mysql_query($query);
// 		if(!$result) error_view("질의문에 다음과 같은 오류가 있습니다.","","ok");
// 		$rows = mysql_result($result,0,0);
// 		return $rows;
// }
function member_level_view(string $id, string $divi): ?int {
    global $db_conn;

    // 테이블명 하드코딩(화이트리스트) — 외부 입력은 위험하므로 권장
    $dbtable = ($divi === "s") ? "s_members" : "t_members";

    // Prepare 문 사용 (SQL Injection 방지)
    $query = "SELECT power_level FROM `$dbtable` WHERE id = ?";
    $stmt = $db_conn->prepare($query);
    if (!$stmt) {
        error_view("질의문에 다음과 같은 오류가 있습니다.", $db_conn->error, "ok");
        return null;
    }

	$power_level = '';
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->bind_result($power_level);
    $fetch_result = $stmt->fetch();
    $stmt->close();

    if ($fetch_result) {
        return $power_level;
    } else {
        return null;  // 결과가 없으면 null 반환
    }
}

// PHP82 변환
//== E-mail 주소가 올바른지 검사
// function ismail( $str ) {
// 	if( eregi("([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", $str) ) return $str; else return '';
// }
function ismail(string $str): string {
    // 대소문자 구분 없이 이메일 패턴 검사
    if (preg_match('/^[a-z0-9_\-\.]+@[a-z0-9_\-\.]+\.[a-z]{2,}$/i', $str)) {
        return $str;
    } else {
        return '';
    }
}

function thumbnail($src_img_path,$dst_img_path,$filename,$want_width,$want_height) {
	//== 썸네일 디렉토리 생성
	if(!is_dir($dst_img_path)) mkdir($dst_img_path,0777);
	//== 이미지정보 추출
	if(!file_exists($src_img_path.$filename)) error_view(999, "원본파일을 찾을수 없습니다.","다시 한번 업로드 해주세요.");
	$img_info=@getimagesize($src_img_path.$filename);

	switch($img_info[2]) {
		case 1 :
			$src_img = ImageCreateFromGif($src_img_path.$filename);
		break;
		case 2 :
			$src_img = ImageCreateFromJPEG($src_img_path.$filename);
		break;
		case 3 :
			$src_img = ImageCreateFromPNG($src_img_path.$filename);
		break;
		case 4 :
			$src_img = imageCreatefromwBmp($src_img_path.$filename);
		break;
		default :
			return false;
	}
	$src_width = $img_info[0];
	$src_height = $img_info[1];
	$dst_width = $want_width;
	$dst_height = $want_height;
		//== 이미지 비율에 따른 썸네일
		if($src_width > $want_width || $src_height > $want_height) {
		if($src_width == $src_height) {
			$dst_width = $want_width;
			$dst_height = $want_height;
		}else if ($src_width > $src_height){
			$dst_width = $want_width;
			$dst_height = ceil(($want_width / $src_width) * $src_height);
		}else {
			$dst_height = $want_height;
			$dst_width = ceil(($want_height / $src_height) * $src_width);
		}
	}else {
		$dst_width = $src_width;
		$dst_height = $src_height;
	}
	if($dst_width < $want_width) $srcx = ceil(($want_width - $dst_width)/2); else $srcx = 0;
	if($dst_height < $want_height) $srcy = ceil(($want_height - $dst_height)/2); else $srcy = 0;

	if($img_info[2] == 1) {
		$dst_img = imagecreate($want_width, $want_height);
	}else {
		$dst_img = imagecreatetruecolor($want_width, $want_height);
	}
	//== 이미지생성
	$bgcolor = ImageColorAllocate($dst_img, 255, 255, 255);
	ImageFilledRectangle($dst_img, 0, 0, $want_width, $want_height, $bgcolor);
	ImageCopyResampled($dst_img, $src_img, $srcx, $srcy, 0, 0, $dst_width, $dst_height, ImageSX($src_img),ImageSY($src_img));
	Imagejpeg($dst_img, $dst_img_path.$filename);
	ImageDestroy($dst_img);
}

//== 게시판의 스킨선택
function Dir_View($Dir_Path, $Error_Ment) {
	$rtn = '';
	if(is_dir($Dir_Path)) {
		if($dir_handle = opendir($Dir_Path)) {
			while (($file = readdir($dir_handle)) !== false) {
				if(filetype($Dir_Path.$file) === "dir" && $file != "." && $file != "..") $rtn .= $file."|";
			}
			closedir($dir_handle);
		}
	}else {
		error_view(999, $Error_Ment,"관리자에게 문의하세요.");
	}
	return substr($rtn,0,-1);
}

// PHP82 변환
//== 오늘 날짜 출력(삭제 아래로 대체)
// function Now_Date() {
// 	$now_date=date(Y.".".m.".".d);
// 	switch (date(w)) {
// 		case (0) :
// 			$week_name="일";
// 		break;
// 		case (1) :
// 			$week_name="월";
// 		break;
// 		case (2) :
// 			$week_name="화";
// 		break;
// 		case (3) :
// 			$week_name="수";
// 		break;
// 		case (4) :
// 			$week_name="목";
// 		break;
// 		case (5) :
// 			$week_name="금";
// 		break;
// 		case (6) :
// 			$week_name="토";
// 		break;
// 	}
// 	return $now_date."[".$week_name."]";
// }
function Now_Date(): string {
    // 날짜 형식: YYYY.MM.DD
    $now_date = date('Y.m.d');

    // 요일 배열
    $week_names = ["일", "월", "화", "수", "목", "금", "토"];

    // 요일 숫자 (0=일요일 ~ 6=토요일)
    $w = (int)date('w');

    // 해당 요일 한글 가져오기
    $week_name = $week_names[$w] ?? '';

    return $now_date . "[" . $week_name . "]";
}

// PHP82 변환
//==  선택된 날자의 요일
// function getWeekName($putDate) {
// 	if($putDate) $inDate=$putDate; else $putDate=date();
// 	$weekName = array("일","월","화","수","목","금","토");
// 	return ($weekName[date('w', strtotime($inDate))]);
// }
function getWeekName(?string $putDate = null): string {
    // 입력값 없으면 오늘 날짜 (Y-m-d 형식)
    $inDate = $putDate ?? date('Y-m-d');

    $weekName = ["일", "월", "화", "수", "목", "금", "토"];

    $w = (int)date('w', strtotime($inDate));

    return $weekName[$w];
}

//== 몇번째 주일지 체크
function Num_Handate ($timestamp) {
	$hanweek = array(1=>'첫', '둘', '셋', '넷', '다섯', '여섯');
	$current = getdate($timestamp);
	$week = (int)(($current['mday']-$current['wday']+2) / 7) + 1;
	return $current['year'].'년 '.$current['mon'].'월 '.$hanweek[$week].'째주';
}

//== 선택한 주의 일요일 날짜
function Get_Date($years, $months, $days) {
	$Time_Stamp = mktime(0,0,0,$months,$days,$years);
	$Offset = ((date('w',$Time_Stamp) + 7) % 7);

	$Get_Sunday = date("Y-m-d",$Time_Stamp-$Offset*86400);
	$Get_Friday = date("Y-m-d",$Time_Stamp+(6-$Offset)*86400);

	return $Get_Sunday;
}

//== 자동 회원 가입 방지 시작 ================================================================================================

// PHP82 변환
//== 중복없는 난수 생성(회원가입 자동 방지)
// function No_Duple_Str($char_length) {
// 	$serial_make = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
// 		srand((double)microtime()*1000000);
// 		$i=0;
// 		//== 중복없이 입력된 숫자만큼 난수 생성
// 		do {
// 			$serial = $serial_make[rand()%strlen($serial_make)];
// 			if(strchr($rst_srl, $serial) == false) {
// 				$rst_srl .= $serial;
// 				$i++;
// 			}
// 		} while ($i<$char_length);
// 		uniqid($rst_srl);
// 	return $rst_srl;
// }
function No_Duple_Str(int $char_length): string {
    $serial_make = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $rst_srl = "";

    $max_index = strlen($serial_make) - 1;

    while (strlen($rst_srl) < $char_length) {
        $rand_index = random_int(0, $max_index);
        $serial = $serial_make[$rand_index];

        if (strpos($rst_srl, $serial) === false) {
            $rst_srl .= $serial;
        }
    }

    return $rst_srl;
}

// PHP82 변환
// 자동가입 방지
// function Not_Aoto_Join($t_num=10, $r_num=5) {
// 	$rst=No_Duple_Str($t_num);																																//== 난수 생성
// 	for($i=0;$i<strlen($rst);$i++) $arr_rst[$i]=$rst[$i];																//== 생성된 난수를 배열에 저장
// 	$rand_rst=array_rand($arr_rst, $r_num);																											//== 배열에 저장된 난수에서 다시 5개 임의 추출하여 배열에 저장
// 	foreach($rand_rst AS $key => $value) $arr_select .= $arr_rst[$value];	//== 추출된 5개의 난수를 문자열로 변환
// 	//== 전체 문자열중 임의 추출된 문자열을 비교하여 구분색상적용
// 	for($i=0;$i<strlen($rst);$i++) {
// 		if(strchr($arr_select, $arr_rst[$i]) == false) {
// 			$view_string .=$arr_rst[$i];
// 		}else {
// 			$view_string .= "<font color=\"#2626C2\"><b>".$arr_rst[$i]."</b></font>";
// 			$md_str .= $arr_rst[$i];
// 		}
// 	}
// 	return $view_string.":".base64_encode($md_str);
// }
function Not_Aoto_Join(int $t_num = 10, int $r_num = 5): string {
    $rst = No_Duple_Str($t_num);  // 난수 생성

    // 난수 문자열을 배열로 변환
    $arr_rst = str_split($rst);

    // r_num이 배열 길이보다 크면 전체 배열 크기로 조정
    $r_num = min($r_num, count($arr_rst));

    // 배열에서 r_num개 임의 추출 (키 배열 반환)
    $rand_keys = array_rand($arr_rst, $r_num);

    // array_rand이 1개일 경우 정수 반환이므로 배열로 통일
    if (!is_array($rand_keys)) {
        $rand_keys = [$rand_keys];
    }

    // 선택된 문자들 문자열로 변환
    $arr_select = '';
    foreach ($rand_keys as $key) {
        $arr_select .= $arr_rst[$key];
    }

    $view_string = '';
    $md_str = '';

    // 전체 문자열을 돌면서 선택된 문자면 색상 적용
    foreach ($arr_rst as $char) {
        if (strpos($arr_select, $char) === false) {
            $view_string .= $char;
        } else {
            $view_string .= '<font color="#2626C2"><b>' . $char . '</b></font>';
            $md_str .= $char;
        }
    }

    return $view_string . ":" . base64_encode($md_str);
}
//== 자동 회원 가입 방지 종료 ================================================================================================

//== 이미지 일괄처리 썸네일 함수(미완성 30초 시간에 걸림)
function Batch_Thumbnail() {
	$path="/website/gaeyado/webnics/board/files/gallery/";
	$fp=opendir($path);
	$t_count=0;
	while($name = readdir($fp)) {
		if(file_exists($path.$name) == false) thumbnail($path,"/website/gaeyado/webnics/board/files/gallery/thumbnail/",$name,80,60);
		$t_count++;
		if(($t_count % 3) == 0) sleep(3);
	}
	closedir($fp);
	echo "총".$t_count."개를 썸네일하였습니다.";
}

//== 카운터 등록
function addCount() {
	global $db, $_SERVER, $_GET;
	$sqlStr = "SELECT COUNT(idx) FROM wStatics WHERE siteCode = '$_GET[code]' AND DATE_FORMAT(visitDate, '%Y-%m-%d') = CURDATE() AND user_ip='".getenv('REMOTE_ADDR')."'";
	$userStatus = $db->getOne($sqlStr);
	if(DB::isError($userStatus)) die($userStatus->getMessage());
	//== 첫 접속일 경우 정보 등록
	if($userStatus<=0) {
		$uEnv=userEnvInfo();
		$msqlStr = "INSERT INTO wStatics(siteCode, pageUrl, refererUrl, visitDate, visitTime, user_ip, userOs, userAgent) VALUES ('".$_GET['code']."', '".getenv('REQUEST_URI')."', '".$_SERVER['HTTP_REFERER']."', CURDATE(), CURTIME(), '".getenv('REMOTE_ADDR')."', '$uEnv[platform]', '$uEnv[name]')";
		$rst=$db->query($msqlStr);
		if(DB::isError($rst)) die($rst->getMessage());
	}
}

//== 영문주소 변환
function Zipcode_Change_Eng() {
	global $db,$_GET,$_POST;
	$eng_address = '';
	$zipcode=$_POST['hzipcode1'].$_POST['hzipcode2'];
	$sql_str="SELECT * FROM Zipcode_Eng WHERE zipcode='$zipcode'";
	$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$zipcode1=substr($view['zipcode'],0,-3);
	$zipcode2=substr($view['zipcode'],-3);
	$eng_address .= $zipcode1."-".$zipcode2." ".$_POST['haddress2'].",";
	if($view['etc']) $eng_address .= " ".$view['etc'].",";
	if($view['bunji']) $eng_address .= " ".$view['bunji'].",";
	if($view['ri']) $eng_address .= " ".$view['ri'].",";
	$eng_address .= $view['dong'].", ".$view['gugun'].", ".$view['sido'].", Korea";
	return $eng_address;
}

//== 날짜 비교
function get_dateDiff($date1, $date2) {
	$_date1 = explode("-", $date1);
	$_date2 = explode("-", $date2);
	$tm1 = mktime(0,0,0,$_date1[1],$_date1[2],$_date1[0]);
	$tm2 = mktime(0,0,0,$_date2[1],$_date2[2],$_date2[0]);
	return ($tm1 - $tm2) / 86400;
}


//make_thumbnail("/img/index/healing_tarot.gif", 83, 100, "");

function make_thumbnail($source_path, $width, $height, $thumbnail_path){
	list($img_width,$img_height, $type) = getimagesize($source_path);
	if ($type!=1 && $type!=2 && $type!=3 && $type!=15) return;
	if ($type==1) $img_sour = imagecreatefromgif($source_path);
	else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_path);
	else if ($type==3 ) $img_sour = imagecreatefrompng($source_path);
	else if ($type==15) $img_sour = imagecreatefromwbmp($source_path);
	if ($img_width > $img_height) {
			$w = round($height*$img_width/$img_height);
			$h = $height;
			$x_last = round(($w-$width)/2);
			$y_last = 0;
	} else {
			$w = $width;
			$h = round($width*$img_height/$img_width);
			$x_last = 0;
			$y_last = round(($h-$height)/2);
	}
	if ($img_width < $width && $img_height < $height) {
			$img_last = imagecreatetruecolor($width, $height);
			$x_last = round(($width - $img_width)/2);
			$y_last = round(($height - $img_height)/2);

			imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$w,$h);
			imagedestroy($img_sour);
			$white = imagecolorallocate($img_last,255,255,255);
			imagefill($img_last, 0, 0, $white);
	} else {
			$img_dest = imagecreatetruecolor($w,$h);
			imagecopyresampled($img_dest, $img_sour,0,0,0,0,$w,$h,$img_width,$img_height);
			$img_last = imagecreatetruecolor($width,$height);
			imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$w,$h);
			imagedestroy($img_dest);
	}
	if ($thumbnail_path) {
			if ($type==1) imagegif($img_last, $thumbnail_path, 100);
			else if ($type==2 ) imagejpeg($img_last, $thumbnail_path, 100);
			else if ($type==3 ) imagepng($img_last, $thumbnail_path, 100);
			else if ($type==15) imagebmp($img_last, $thumbnail_path, 100);
	} else {
			if ($type==1) imagegif($img_last);
			else if ($type==2 ) imagejpeg($img_last);
			else if ($type==3 ) imagepng($img_last);
			else if ($type==15) imagebmp($img_last);
	}
	imagedestroy($img_last);
}

function ageChk($type, $bdate) {
	$myBirthDate = strtotime($bdate);
	if($type=="1") {																											//== 만나이
		$birthDate1 = date( 'Ymd', $myBirthDate );
		$nowDate1 = date('Ymd');
		$uage = floor(($nowDate1 - $birthDate1) / 10000);
	}else if($type=="2") {																							//한국 나이
		$birthDate2 = date( 'Y', $myBirthDate );
		$nowDate2 = date('Y');
		$uage = $nowDate2 - $birthDate2 + 1 ;
	}
	return $uage;
}

function urlCheck($url) {
	$regex = "((https?|ftp)\:\/\/)?";																														//== SCHEME
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";			//== User and Pass
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";																										//== Host or IP
	$regex .= "(\:[0-9]{2,5})?";																																			//== Port
	$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";																							//== Path
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";												//== GET Query
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";																									//== Anchor
	if(preg_match("/^$regex$/", $url)) return true; else return false;
}

function isMobile(){
	$arr_browser = array ("iphone", "android", "ipod", "iemobile", "mobile", "lgtelecom", "ppc", "symbianos", "blackberry", "ipad");
	$httpUserAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
	// 기본값으로 모바일 브라우저가 아닌것으로 간주함
	$mobile_browser = false;
	// 모바일브라우저에 해당하는 문자열이 있는 경우 $mobile_browser 를 true로 설정
	for($indexi = 0 ; $indexi < count($arr_browser) ; $indexi++){
			if(strpos($httpUserAgent, $arr_browser[$indexi]) == true){
					$mobile_browser = true;
					break;
			}
	}
	return $mobile_browser;
}

// PHP82 변환
// function delAll($dir) {
// 	$d = @dir($dir);
// 	while ($entry = $d->read()) {
// 		if ($entry == "." || $entry == "..") continue;
// 		if(is_dir($entry)) delete_all($entry);
// 		else unlink($dir."/".$entry);
// 	}
// 	// 해당디렉토리도 삭제할 경우에는 아래 주석처리를 해제합니다.
// 	unlink($dir);
// }
function delAll(string $dir): void {
    // 디렉토리 열기
    $d = @dir($dir);
    if (!$d) return;

    while (($entry = $d->read()) !== false) {
        if ($entry === '.' || $entry === '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $entry;

        if (is_dir($path)) {
            // 재귀 호출로 하위 디렉토리 삭제
            delAll($path);
        } else {
            // 파일 삭제
            @unlink($path);
        }
    }

    $d->close();

    // 디렉토리 자체 삭제
    @rmdir($dir);  // unlink() → rmdir()로 변경
}

//== 디렉토리 및 하위 파일 삭제
function removeDir ($path) {
	// 디렉토리 구분자를 하나로 통일
	$path = str_replace('\'', '/', $path);
	// 경로 마지막에 존재하는 디렉토리 구분자는 삭제
	if ($path[(strlen($path)-1)] == '/') {
			$tmp = '';
			for ($i=0; $i < (strlen($path) -1); $i++) {
					$tmp .= $path[$i];
			}
			$path = $tmp;
	}
	// 존재하는 디렉토리인지 확인, 존재하지 않으면 false를 반환
	if (!file_exists($path)) {
		return false;
	}

	// 디렉토리 핸들러 생성
	$oDir = dir($path);

	// 디렉토리 하부 컨텐츠 각각에 대하여 분석하여 삭제
	while (($entry = $oDir->read())) {
			// 상위 디렉토리를 나타내는 문자열인 경우 처리하지 않고 continue
			if ($entry == '.' || $entry == '..') {
					continue;
			}

			// 또 다른 디렉토리인 경우 함수 실행
			// 파일인 경우 즉시 삭제
			if (is_dir($path.'/'.$entry)) {
					removeDir($path.'/'.$entry);
			} else {
					unlink($path.'/'.$entry);
			}
	}

	// 해당 디렉토리 삭제
	rmdir($path);

	// 결과에 따라 해당 디렉토리가 삭제되지 않고 존재하면 false를 반환 반대의 경우에는 true를 반환
	if (file_exists($path)) {
			return false;
	} else {
			return true;
	}
}

//== 숫자 단위 한글 변경
function number2hangul($number){
	$num = array('', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구');
	$unit4 = array('', '만', '억', '조', '경');
	$unit1 = array('', '십', '백', '천');

	$res = array();

	$number = str_replace(',','',$number);
	$split4 = str_split(strrev((string)$number),4);

	for($i=0;$i<count($split4);$i++){
		$temp = array();
		$split1 = str_split((string)$split4[$i], 1);
		for($j=0;$j<count($split1);$j++){
			$u = (int)$split1[$j];
			if($u > 0) $temp[] = $num[$u].$unit1[$j];
		}
		if(count($temp) > 0) $res[] = implode('', array_reverse($temp)).$unit4[$i];
	}
	return implode('', array_reverse($res));
}

//== D-day
function dDay($startDate, $stopDate=''){
	$nowDate=date('Ymd');
	$dDay=abs(intval((strtotime(date("Y-m-d",time()))-strtotime($startDate)) / 86400));
	if($startDate && !$stopDate) {																								//== 단일 행사
		if($startDate > $nowDate) return "D-".$dDay;
	}else if($startDate && $stopDate) {																				//== 장기 행사
		$eventTerm=$stopDate-$startDate;
		if($startDate <=$nowDate && $stopDate>=$nowDate) {				//== 행사중
			return (($nowDate-$startDate)+1)."일차";
		}else if($startDate > $nowDate) {																				//== 행사이전
			return "D-".$dDay;
		}
	}
}

//== Allow IP
function ipAllow(){
	global $_SERVER;
	$allow_ips = array("112.221.190","109.2");
	//var_dump($allow_ips);
	//echo $_SERVER['REMOTE_ADDR'];
	for ($i=0; $i<count($allow_ips); $i++) {
		$allowIps="/^".$allow_ips[$i]."/";
		if(!preg_match($allowIps,$_SERVER['REMOTE_ADDR'])) die('차단된 IP입니다. 관리자에게 문의하세요.');
		//if(!preg_match("/".str_replace("*","[0-9]{1,3}",implode('$|',$allowIps))."/", $_SERVER['REMOTE_ADDR'])) die('차단된 IP입니다. 관리자에게 문의하세요.');
	}
}

function boardCode($code) {
	global $db;
	$optionList = '';
		$sqlStr = "SELECT * FROM wboardConfig ORDER BY idx ASC";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			if($view['code']==$code) $codeChk=" selected"; else $codeChk="";
			$optionList .= "<option value=\"".$view['code']."\"".$codeChk.">".$view['board_summary']."</option>";
		}
		return $optionList;
}
?>