<?php
/*載入設定*/
require_once('lqsym.php');
require_once('getpetsinfo.php');
require_once('defines.php');

ini_set('display_errors', '0');
error_reporting(E_ALL | E_STRICT);


/* ########################################
 * 載入資料庫
######################################## */

/*設定連線*/
$_login = new DBLogin(DB_MYSQL_HOST, DB_MYSQL_USER, DB_MYSQL_PW, DB_MYSQL_DB);

/*建立連線*/
$sadb = new DBMySQL(  $_login -> the_host(),
                      $_login -> the_user(),
                      $_login -> the_pw(),
                      $_login -> the_db());

/*成功連線*/
$item = IntSafe($_GET['petsid'],0,3);

$sql = Array(
sprintf('SELECT * FROM `sadb_pets` AS `t1` INNER JOIN `sadb_pets_ref` AS `t2` ON `t2`.`pets_reaid`=`t1`.`pets_rlid` WHERE `t1`.`pets_id`=%s;', $item),

sprintf('SELECT `pets_id`,`pets_reaid`,`t1`.`pets_name_cht` AS `pets_name_cht`,`t1`.`pets_rarity`,`t1`.`pets_element`, `t1`.`pets_max_hp`,`t1`.`pets_max_atk`, `t1`.`pets_max_def`, `t1`.`pets_max_spd`,`ICON` FROM `sadb_pets` AS `t1` INNER JOIN `sadb_pets_ref` AS `t2` ON `t2`.`pets_reaid`=`t1`.`pets_rlid` WHERE (`t1`.`pets_id`= %s) OR (`t1`.`pets_species`= (SELECT `pets_species` FROM `sadb_pets` WHERE `pets_id`= %s AND `t1`.`pets_rarity` != 0));', $item, $item));

/*輸出資料*/
$sadb_raw = $sadb -> queryMulti(implode($sql,' '));
$sadb_row = $sadb_raw[0][0];

/*$sadb_row = $sadb->getData('MYSQLI_ASSOC');*/

/* ########################################
 * 處理寵物資料
######################################## */

/*格式化寵物資料*/
$thisPet = new GetPetsInfo;
$thisPet -> name = Array(   'cht' =>strval($sadb_row['pets_name_cht']),
                            'kr'  =>strval($sadb_row['pets_name_kr'])
                          );
$thisPet -> info = Array(
                            'id'  =>intval($sadb_row['pets_id']),
                            'rlid'=>intval($sadb_row['pets_rlid']),

                            'qty'     =>$sadb_row['pets_qty'],
                            'rarity'  =>$sadb_row['pets_rarity'],
                            'element' =>$sadb_row['pets_element'],
                            'type'    =>$sadb_row['pets_type'],
                            'species' =>$sadb_row['pets_species'],
                            'riding'  =>boolval($sadb_row['pets_riding']),
                            'icon'    =>$sadb_row['pets_icon'],
                            'getloc'  =>strval($sadb_row['pets_getloc'])
                          );
$thisPet -> imgFile = Array(  'char'  => strval($sadb_row['CHAR']),
                              'profile' => strval($sadb_row['PROFILE']),
                              'icon'  =>strval($sadb_row['ICON'])
                            );
$thisPet -> pwr['min'] = Array(
                              'hp'  => $sadb_row['pets_min_hp'],
                              'atk' => $sadb_row['pets_min_atk'],
                              'def' => $sadb_row['pets_min_def'],
                              'spd' => $sadb_row['pets_min_spd']
                              );
$thisPet -> pwr['max'] = Array(
                              'hp'  => $sadb_row['pets_max_hp'],
                              'atk' => $sadb_row['pets_max_atk'],
                              'def' => $sadb_row['pets_max_def'],
                              'spd' => $sadb_row['pets_max_spd']
                              );
$thisPet -> pwr['start'] = Array(
                              'hp'  => $sadb_row['HP'],
                              'atk' => $sadb_row['ATK'],
                              'def' => $sadb_row['DEF'],
                              'spd' => $sadb_row['SPD'],
                                );
$thisPet -> pwr['lv'] = $sadb_row['LV'];
$thisPet -> skill['active'] = Array(
                              'name'=>$sadb_row['pets_skill_name'],
                              'cost'=>$sadb_row['pets_skill_cost'],
                              'desc'=>$sadb_row['pets_skill_desc'],
                              'eft'=>$sadb_row['pets_skill_eft']);
$thisPet -> skill['pas1'] = Array(
                              /*'icon'=>$sadb_row['pets_pas_1_icon'],*/
                              'name'=>$sadb_row['pets_pas_1_name'],
                              'eft'=>$sadb_row['pets_pas_1_eft']);
$thisPet -> skill['pas2'] = Array(
                              /*'icon'=>$sadb_row['pets_pas_2_icon'],*/
                              'name'=>$sadb_row['pets_pas_2_name'],
                              'eft'=>$sadb_row['pets_pas_2_eft']);
$thisPet -> skill['pas3'] = Array(
                              /*'icon'=>$sadb_row['pets_pas_3_icon'],*/
                              'name'=>$sadb_row['pets_pas_3_name'],
                              'eft'=>$sadb_row['pets_pas_3_eft']);

/*page title*/
$pageTitle=sprintf('%s%s——%s', $thisPet->info['element'], $thisPet->info['species'],$thisPet->showName('cht'));

/*處理同系列寵物資料*/
$twiceElePet=Array(
  'id'=> -1,
  'label'=>''
);
$fullSeresPetsHTML='';
foreach ($sadb_raw[1] as $key => $value) {
  /*except pets*/
  if($value['pets_name_cht']=='0') continue;
  /*handle twice element*/
  $skipPetsElement = Array('地地','水水','火火','風風');
  if(
  mb_strlen($thisPet->info['element'],'utf-8')==1 && preg_match('/('.$thisPet->info['element'].')\1/',$value['pets_element']))
  {
    $twiceElePet['id']    = $value['pets_id'];
    $twiceElePet['label'] = $value['pets_element'];
  }
  if(in_array($value['pets_element'],$skipPetsElement)) continue;
  /*detect this id*/
  $assignPointer='';
  if($value['pets_reaid']==$thisPet->info['rlid']){
    $assignPointer='today';
  }
  /*default HTML theme*/
  $rowHTML='
  <tr class="%s">
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
  </tr>
  ';
  /*output HTML code*/
  $iconHTML='<a href="/pets-%s/"><img src="%s" class="img-rounded img-icon"></a>';
  $imageURL=($value['ICON']!=''?'http://i.imgbox.com/'.$value['ICON']:'/assets/images/none.png');
  $navHTML=sprintf($iconHTML, $value['pets_id'], $imageURL);
  $fullHTML=sprintf($rowHTML, $assignPointer,
                              $navHTML,
                              $value['pets_name_cht'],
                              round($value['pets_max_hp']/5+$value['pets_max_atk']+$value['pets_max_def']+$value['pets_max_spd'],3),
                              $value['pets_max_hp'],
                              $value['pets_max_atk'],
                              $value['pets_max_def'],
                              $value['pets_max_spd']);
  $fullSeresPetsHTML.=$fullHTML;
};

/*處理雙屬性寵物資料*/
$twiceEleHTML='';
if($twiceElePet['id']!=-1){
  $twiceEleHTML=sprintf('<span class="boss-cell" style="float:right;"><a href="/pets-%s">查詢雙屬性</a></span>',$twiceElePet['id']);
}

/*comments*/
$pageIdentifier = 'pets-' . $thisPet->info['id'] . '/';
$pageURL = $pageIdentifier
?>