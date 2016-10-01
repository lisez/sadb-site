<?php
//檔案類型
header('Content-Type: application/json; charset=utf-8');

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
'SELECT * FROM `sadb_pets` AS `t1` INNER JOIN `sadb_pets_ref` AS `t2` ON `t2`.`pets_reaid`=`t1`.`pets_rlid` WHERE NOT ( (`t1`.`pets_element` REGEXP "^地地$|^水水$|^火火$|^風風$") OR `t1`.`pets_rarity` = 0);';
$jsondb -> query($jsondbSQL);

//輸出資料
$jsonRows = $jsondb->getAllData();
$jsonCount = $jsondb->getRowsNum();

/* ########################################
 * 輸出JSON
######################################## */

$jsonRaw=Array();

// ["<a href=\"../pets_1/\">帖伊諾斯</a>","水火","人龍改",63.7,83.6,21.9,12.1,13,7917,2078,1147,1236]
$jsonFormat = '[%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s]';

foreach ($jsonRows as $key => $value) {

  if($value['pets_name_cht']=='') continue;

  $pets_id    = intval($value['pets_id']);
  $pets_name  = $value['pets_name_cht'];
  $pets_ele   = $value['pets_element'];
  $pets_ser   = $value['pets_species'];

  $lv1_hp     = $value['HP']-$value['pets_min_hp']*$value['LV'];
  $lv1_atk    = $value['ATK']-$value['pets_min_atk']*$value['LV'];
  $lv1_def    = $value['DEF']-$value['pets_min_def']*$value['LV'];
  $lv1_spd    = $value['SPD']-$value['pets_min_spd']*$value['LV'];

  $lv78_hp    = $lv1_hp+$value['pets_max_hp']*78;
  $lv78_atk   = $lv1_atk+$value['pets_max_atk']*78;
  $lv78_def   = $lv1_def+$value['pets_max_def']*78;
  $lv78_spd   = $lv1_def+$value['pets_max_spd']*78;

  $maxGro     = $value['pets_max_hp']/5+$value['pets_max_atk']+$value['pets_max_def']+$value['pets_max_spd'];

  array_push($jsonRaw, sprintf($jsonFormat, JSONChar('<a href="/pets-'.$pets_id.'">'.$pets_name.'</a>'),
                                            JSONChar($pets_ele),
                                            JSONChar($pets_ser),

                                            round($maxGro,3),
                                            $value['pets_max_hp'],
                                            $value['pets_max_atk'],
                                            $value['pets_max_def'],
                                            $value['pets_max_spd'],

                                            $lv78_hp,
                                            $lv78_atk,
                                            $lv78_def,
                                            $lv78_spd
                                          ));
}
?>

{
  "data":
  [
<?php
echo implode($jsonRaw,",\n");
?>
  ]
}
