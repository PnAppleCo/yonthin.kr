<?php
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== ���ٱ��� ����
//$allow_ip = array("109.2.91.135");
//if(!in_array($_SERVER["REMOTE_ADDR"], $allow_ip)) { header('Location:/main.htm'); exit; }
//== Ÿ��Ʋ��
$Title_Txt="�ִ�Ÿ��";
//== ��ŸŰ����
$Keywords_Txt="�ִ�Ÿ��";
//== �ۼ���
$Description_Txt="���н��ַ��";
//== ������
$Author_Txt="���н�";

//== ù ������ ����
if(!$_GET['page']) $_GET['page'] = 1;
//== �������� ����
$num_per_page=20;
//== ������ �������� ����
$num_per_block=10;
?>