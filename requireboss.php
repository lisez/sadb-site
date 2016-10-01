<?php
//載入設定
require_once('lib/lqsym.php');
require_once('lib/misc.php');
require_once('lib/getpetsinfo.php');
require_once('lib/defines.php');

/* ########################################
 * 載入資料庫
######################################## */

//設定連線
$_login = new DBLogin(DB_MYSQL_HOST, DB_MYSQL_USER, DB_MYSQL_PW, DB_MYSQL_DB);

//建立連線
$jsondb = new DBMySQL($_login -> the_host(),
                      $_login -> the_user(),
                      $_login -> the_pw(),
                      $_login -> the_db());

//成功連線
$jsondbSQL =
'SELECT * FROM `sadb_raidboss`';
$jsondb -> query($jsondbSQL);

//輸出資料
$jsonRows = $jsondb->getAllData();
$jsonCount = $jsondb->getRowsNum();

/* ########################################
 * 輸出JSON
######################################## */

$jsonRaw=Array();

// {"i":1,"n":"帖伊諾斯","r":5,"c":"FTfosuIQ","p":"xreqKlWB.jpg"}
$jsonFormat = '{ "id":%s, "name":%s, "time":%s, "banner":%s, "mobs":%s, "lineup":%s, "gifts":%s}';

$jsonTxt = preg_replace('/\s/','',$jsonFormat);

foreach ($jsonRows as $key => $value) {
  //bosstime
  $bossTimeAry  = new SplFixedArray(8);
  $bossTimeAry  = array_fill(0, 8, -1);
  $bossTimeRaw  = explode('|',$value['boss_time']);

  foreach($bossTimeRaw as $schedule){
    $timeRaw = preg_match('/^(\d+)=(\d+)/', $schedule, $matches);
    if($matches[1]>=1 && $matches[1]<=7){
      $bossTimeAry[$matches[1]] = $matches[2];
    }
  }

  $bossTime = '['. implode($bossTimeAry, ',') .']';

  //bossMobs
  $bossMobsAry = array();
  $bossMobsRaw = explode('|',$value['boss_mobs']);

  foreach($bossMobsRaw as $mobline){
    //handle
    $mbRaw = preg_match('/^(\d+)=(.+)/', $mobline, $matches);
    //format
    $mbFormat = '"%s":[%s]';

    array_push($bossMobsAry, sprintf($mbFormat, $matches[1], $matches[2]));
  }

  $bossMobs = '{'. implode($bossMobsAry, ',') .'}';

  //mobs line
  $bossLineup = JSONChar(' ');

  //boss gifts
  $bossGifts= JSONChar(' ');

  //push data
  array_push($jsonRaw, sprintf($jsonFormat, intval($value['boss_id']),
                                            JSONChar($value['boss_name']),
                                            $bossTime,
                                            '"'.$value['boss_banner'].'"',
                                            $bossMobs,
                                            $bossLineup,
                                            $bossGifts));
}

header('Content-Type: application/json');

?>

[
<?php
echo implode($jsonRaw,",\n");
?>
]
