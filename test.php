<?php
//載入設定
require_once('lib/lqsym.php');
require_once('lib/misc.php');

//設定連線
$_login = new DBLogin('127.0.0.1', 'sujj', 'yxul4dj4m/4', 'sadb');

//建立連線
$sadb = new DBMySQL(  $_login -> the_host(),
                      $_login -> the_user(),
                      $_login -> the_pw(),
                      $_login -> the_db());

$sadb->query('SELECT * FROM `sadb_pets` AS t1 INNER JOIN `sadb_pets_ref` AS `t2` ON `t2`.`pets_reaid`=`t1`.`pets_rlid` WHERE `t1`.`pets_id`=1');

echo $sadb->getRowsNum();
echo $sadb->getData('MYSQLI_ASSOC')['ICON'];

?>
