<?php
switch ($Error_No) {
	case "001":
		$Error_Msg1 = "";
		$Error_Msg2 = "";
		break;
	case "999": //== 사용용 정의 오류코드
		$Error_Msg1 = $Msg1;
		$Error_Msg2 = $Msg2;
		break;
	default :
		$Error_Msg1 = "알수없는 오류가 발생하였습니다.";
		$Error_Msg2 = "관리자에게 문의 하시기 바랍니다.";
		break;
}