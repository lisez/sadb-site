<?php
//載入設定
require_once('lqsym.php');
require_once('misc.php');
require_once('getpetsinfo.php');
require_once('defines.php');

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

//$jsonFormat = '{ "id":%s, "name":%s, "time":%s, "banner":%s, "mobs":%s, "lineup":%s, "gifts":%s}';
$jsonFormat = '{ "id":%s, "name":%s, "banner":%s, "mobs":%s, "lineup":%s, "gifts":%s}';

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
    $bossMobsOnlyPetsRaw = explode(',',$matches[2]);
    $bossMobsOnlyPets = array();
    foreach($bossMobsOnlyPetsRaw as $raw){
      $pet = explode(':', $raw);
      array_push($bossMobsOnlyPets, $pet[0]);
    }
    //format
    $mbFormat = '"%s":[%s]';
    $bosMobsOnlyPetsTxt = implode($bossMobsOnlyPets, ',');

    array_push($bossMobsAry, sprintf($mbFormat, $matches[1], $bosMobsOnlyPetsTxt));
  }

  $bossMobs = '{'. implode($bossMobsAry, ',') .'}';

  //mobs line
  $bossLineup = JSONChar(' ');

  //boss gifts
  $bossGifts= JSONChar(' ');

  //push data
  array_push($jsonRaw, sprintf($jsonFormat, intval($value['boss_id']),
                                            JSONChar($value['boss_name']),
                                            //$bossTime,
                                            '"'.$value['boss_banner'].'"',
                                            $bossMobs,
                                            $bossLineup,
                                            $bossGifts));
}

//檔案類型
header('Content-Type: application/javascript; charset=utf-8;');

?>

var bossData=
[
<?php
echo implode($jsonRaw,",\n");
?>
];
