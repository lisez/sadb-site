<?php
/*****************************************************/
/* query from mysql */

//mysql
require_once('defines.php');

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

$sql='SELECT * FROM `sadb_team` WHERE `team_id`=?';
$sqlQuery = $mysqli->prepare($sql);
$sqlQuery->bind_param('s', $_GET['team']);
$sqlQuery->execute();

$row = $sqlQuery->get_result();
$result = $row->fetch_array(MYSQLI_ASSOC);

$sqlQuery->close();
$mysqli->close();

/*****************************************************/
/* basic */
$id 		= $result['team_id'];
$author 	= htmlspecialchars($result['team_provider']);
$teamtype 	= $result['team_type'];
$pets		= explode(',', $result['team_settings']);
$intro		= $result['team_intro'];

$zipped		= array("\t", "\n", "\r", "  ");

/*****************************************************/
/* team type */
$solutionTXT = array('泛用','競技場','冒險','討伐','討伐首領','突襲戰','部落遠征');
$advTXT   	 = array('冒險之章', '苦難之章', '外傳');
$encounterTXT= array('機暴','地機虎','水機虎','火機虎','風機虎');
$solutionValue = explode('=', $teamtype, 2);

switch (intval($solutionValue[0])) {
    case 2:
        $regexp = preg_match('/^(\d+)-(\d+)-(\d+)$/', $solutionValue[1], $matches);
        $result = $advTXT[$matches[1]].$matches[2].'-'.$matches[3];
        break;
    case 3:
        $result = $solutionValue[1];
        break;
    case 4:
        $result = '<span class="add-pet-img" data-query-id="'.$solutionValue[1].'"></span>';
        break;
    case 5:
    	$result = '—'.$encounterTXT[$solutionValue[1]];
    	break;
    default:
        $result = '';
        break;
}

$solution = ($solutionValue[0]==2)? $result: $solutionTXT[$solutionValue[0]]. $result;

/*****************************************************/
/* pets */
$petsPos = array('訓練師','騎寵','前中','前左','前右','待命1','待命2');
$petsCell = str_replace($zipped, '', '
<div class="team-set-cell team-pos-%s">
	<label>%s</label>
	<div><span data-query-id="%s" class="%s"></span></div>
</div>
');

for ($i=0; $i < count($pets); $i++) {
	$regexp = preg_match('/^([A-Za-z]+)=([0-9]+)$/', $pets[$i], $matches);
	if($matches[1]=='ppl'){
		$petsSettings.= sprintf($petsCell, $i, $petsPos[$i], $matches[2], 'add-trainers-img');
	}elseif($matches[1]=='pet'){
		$petsSettings.= sprintf($petsCell, $i, $petsPos[$i], $matches[2], 'add-pet-img');
	}
}

/*****************************************************/
/* team content */

$HTMLWhiteList = array(
	'div','span','br','p','pre','blockquote','q','hr',
	'table','td','th','tbody','thead','tfoot',
	'font','b','strong','s','i','big','small','tt','code','samp','var','del','cite','ins',
	'h1','h2','h3','h4','h5','h6',
	'ul','ol','li'
	);
$list = '<'.implode('><',$HTMLWhiteList).'>';
$introRAW = htmlspecialchars_decode($intro, ENT_QUOTES);
$introOutput = strip_tags($introRAW, $list);

/*****************************************************/


/*****************************************************/
/* output page content */

$pageTitle 	= "第 $id 號原始人勇士";
$pageContent= str_replace($zipped, '', "
<div class=\"row\">
	<div class=\"col-xs-12 col-md-6\">
		<div id=\"team-pets\">$petsSettings</div>
	</div>
	<div class=\"col-xs-12 col-md-6\">
		<table>
			<tbody>
				<tr>
					<th>提供者</th>
					<td>$author</td>
				</tr>
				<tr>
					<th>適用場合</th>
					<td>$solution</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class=\"row\" id=\"team-content\">
	<hr>
	$introOutput
</div>
");

$pageIdentifier = "team/$id/";
$pageURL = $pageIdentifier;
?>