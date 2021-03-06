<?php
//檔案類型
header('Content-Type: application/javascript; charset=utf-8;');

//失效期間
date_default_timezone_set('Asia/Taipei');
header('Expires: '. date("D, d M Y H:i:s", mktime(0,0,0, 10, 13, 2016)) . ' UTC+8');

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
'SELECT `t1`.`pets_id`,`t1`.`pets_name_cht`,`t1`.`pets_rarity`,`t1`.`pets_element`, `t1`.`pets_type`,`t1`.`pets_species`,`t1`.`pets_riding`,`t2`.`CHAR`,`t2`.`PROFILE`,`t2`.`ICON` FROM `sadb_pets` AS `t1` INNER JOIN `sadb_pets_ref` AS `t2` ON `t2`.`pets_reaid`=`t1`.`pets_rlid`;';
$jsondb -> query($jsondbSQL);

//輸出資料
$jsonRows = $jsondb->getAllData();
$jsonCount = $jsondb->getRowsNum();

/* ########################################
 * 輸出JSON
######################################## */

$jsonRaw=Array();

// {"i":1,"n":"帖伊諾斯","r":5,"c":"FTfosuIQ","p":"xreqKlWB.jpg"}
$jsonFormat = '{ "i":%s, "n":%s, "r":%s, "e":%s, "t":%s, "s":%s, "d":%s, "c":%s, "p":%s, "o":%s}';
//$jsonFormat = '{ "i":%s, "n":%s, "o":%s}';

$jsonTxt = preg_replace('/\s/','',$jsonFormat);

foreach ($jsonRows as $key => $value) {

  if($value['pets_name_cht']=="0") continue;

  $thisPet = new GetPetsInfo();
  $thisPet -> name = Array(   'cht' =>StringSafe($value['pets_name_cht'],0,255));
  $thisPet -> info = Array(
                              'id'  =>intval($value['pets_id']),
                              'rlid'=>intval($value['pets_rlid']),

                              'qty'     =>$value['pets_qty'],
                              'rarity'  =>intval($value['pets_rarity']),
                              'element' =>$value['pets_element'],
                              'type'    =>$value['pets_type'],
                              'species' =>$value['pets_species'],
                              'riding'  =>intval($value['pets_riding'])
                              //'icon'    =>$value['pets_icon'],
                              //'getloc'  =>strval($value['pets_getloc'])
                            );
  $thisPet -> imgFile = Array(  'char'  => strval($value['CHAR']),
                                'profile' => strval($value['PROFILE']),
                                'icon'  =>strval($value['ICON'])
                              );

  array_push($jsonRaw, sprintf($jsonTxt,  JSONChar($thisPet->info['id']),
                                          JSONChar($thisPet->name['cht']),
                                          JSONChar($thisPet->info['rarity']),
                                          JSONChar($thisPet->info['element']),
                                          JSONChar($thisPet->info['type']),
                                          JSONChar($thisPet->info['species']),
                                          JSONChar($thisPet->info['riding']),
                                          JSONChar($thisPet->imgFile['char']),
                                          JSONChar($thisPet->imgFile['profile']),
                                          JSONChar($thisPet->imgFile['icon'])));
}

?>

var getAllPets=
[
<?php
echo implode($jsonRaw,",\n");
?>
];
