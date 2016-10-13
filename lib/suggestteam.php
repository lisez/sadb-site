<?php

//mysql
require_once('defines.php');

session_start();

if(intval($_POST['thething'])!=1){ 
	echo json_encode(array('status'=>-1, 'msg'=>'不合法的輸入'));
	$_POST = array();
	exit();}
if(isset($_SESSION['addtime'])){
	$last = intval($_SESSION['addtime']);
	$now = intval(time());
	$leave = $now-$last;
	if($leave<30){ 
		echo json_encode(array('status'=>2, 'msg'=>'重複傳送時間過短，請再等待'.(30-$leave).'秒')); 
		$_POST = array();
		exit();
	}
}

//create data format
$inputData =array(
	'user_name',
	'user_ip',
	'add_time',
	//'team_name',
	'solution',
	'team_settings',
	'team_interview'
);

//set username, team name
$formUser = mb_substr($_POST['team-provider'], 0, 20, 'utf-8');
$chkUserName = ($formUser!='')? htmlspecialchars($formUser, ENT_QUOTES):'匿名';

//$formTeamName = mb_substr($_POST['team-name'], 0, 20, 'utf-8');
//$chkTeamName = ($formTeamName!='')? htmlspecialchars($formTeamName, ENT_QUOTES):'未命名';

//set ip
$ipinfo = array(
	'ORIGIN_IP' => array(
		'HTTP_CLIENT_IP' 			=> $_SERVER['HTTP_CLIENT_IP'],
		'HTTP_X_FORWARDED_FOR' 		=> $_SERVER['HTTP_X_FORWARDED_FOR'],
		'HTTP_X_FORWARDED' 			=> $_SERVER['HTTP_X_FORWARDED'],
		'HTTP_X_CLUSTER_CLIENT_IP' 	=> $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'],
		'HTTP_FORWARDED_FOR' 		=> $_SERVER['HTTP_FORWARDED_FOR'],
		'HTTP_FORWARDED' 			=> $_SERVER['HTTP_FORWARDED'],
		'REMOTE_ADDR' 				=> $_SERVER['REMOTE_ADDR'],
		'HTTP_VIA' 					=> $_SERVER['HTTP_VIA']
		),
	'CLOUDFLARE_IP' => array(
		'HTTP_CF_CONNECTING_IP'	=> $_SERVER['HTTP_CF_CONNECTING_IP'],
		'HTTP_CF_IPCOUNTRY'		=> $_SERVER['HTTP_CF_IPCOUNTRY'],
		'HTTP_CF_RAY'			=> $_SERVER['HTTP_CF_RAY'],
		'HTTP_CF_VISITOR'		=> $_SERVER['HTTP_CF_VISITOR']
		)
	);
$chkUserIP = json_encode($ipinfo);

//set time
date_default_timezone_set('Asia/Taipei');
$chkToday = date('Y-m-d H:i:s');

//set solution
$formType = intval(substr($_POST['team-type'],0,1));
$formTypeVal =array(2);

switch ($formType) {
	case 2 /*campiagn*/:
		$formTypeVal[0] = intval($_POST['team-type-adventure-mode']);
		$formTypeVal[1] = intval($_POST['team-type-adventure-chapter']);
		$formTypeVal[2] = intval($_POST['team-type-adventure-section']);
		break;
	case 3 /*raid*/:
		$formTypeVal[0] = intval($_POST['team-type-raid-chapter']);
		$formTypeVal[1] = intval($_POST['team-type-raid-section']);
		break;
	case 4 /*raidboss*/:
		$formTypeVal[0] = intval($_POST['team-type-raidboss-battle']);
		break;
	case 5 /*encounter*/:
		$formTypeVal[0] = intval($_POST['team-type-encounter-type']);
		break;
	default: 
		$formTypeVal[0] = 0;
		break;
}

foreach ($formTypeVal as $key => $value) {
	$formTypeVal[$key] = substr($value, 0, 4);
	if($formTypeVal[$key]==-1){
		echo json_encode(array('status'=>-1, 'msg'=>'不合法的輸入'));
		exit();
	}
}

$chkTeamType = $formType.'='. implode('-', $formTypeVal);

//set team setting
$formTeamSet = explode(',',$_POST['the-team-set'],7);
$tmpTeamSet = array();

foreach ($formTeamSet as $key => $value) {
	if($key==1 || $key==2){
		if($value===NULL){
			echo json_encode(array('status'=>-1, 'msg'=>'不合法的輸入'));
			exit();
		}
	}
	$label = ($key==0)? 'ppl=':'pet=';
	array_push($tmpTeamSet, $label . intval(substr($value,0,4)));
}

$chkTeamSet = implode(',', $tmpTeamSet);

//handle interview html code
$HTMLWhiteList = array(
	'div','span','br','p','pre','blockquote','q','hr',
	'table','td','th','tbody','thead','tfoot',
	'font','b','strong','s','i','big','small','tt','code','samp','var','del','cite','ins',
	'h1','h2','h3','h4','h5','h6',
	'ul','ol','li'
	);
$list = '<'.implode('><',$HTMLWhiteList).'>';
$maxvalue = pow(2, 32)-2;
$inputRaw = strip_tags($_POST['team-interview'], $list);
$rpTxt 	  = array("\n", "\r", "\r\n");
$outputRaw = str_replace($rpTxt, '<br>', $inputRaw);
$chkInterView = substr(htmlspecialchars($outputRaw, ENT_QUOTES), 0, $maxvalue);

//special key
$postkey = md5($ipinfo.$chkToday.$_POST.'-sadb');

// var_dump(
// $chkUserName,
// $chkUserIP,
// $chkToday,
// $chkTeamName,
// $chkTeamType,
// $chkTeamSet,
// $chkInterView
// );

//建立連線
$mysqli = new mysqli(	DB_MYSQL_HOST,
						DB_MYSQL_USER,
						DB_MYSQL_PW,
						DB_MYSQL_DB);

if (mysqli_connect_errno()) {
	echo json_encode(array('status'=>-99, 'msg'=>'資料庫連線錯誤：'.mysqli_connect_error()));
    exit();
}

$mysqli->set_charset('utf8');

$sql = 'INSERT INTO `sadb_team` VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?)';

$sqlQuery = $mysqli->prepare($sql);
$sqlQuery->bind_param('sssssss',	$postkey,
								$chkUserName,
								$chkUserIP,
								$chkToday,
								$chkTeamType,
								$chkTeamSet,
								$chkInterView);


$sqlQuery->execute();
echo json_encode(array('status'=>1, 'msg'=>'新增成功', 'key'=>$postkey));
//printf("%d Row inserted.\n", $sqlQuery->affected_rows);
$sqlQuery->close();
$mysqli->close();

$_POST = array();
$_SESSION['addtime']=time();

?>