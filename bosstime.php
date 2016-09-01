---
layout: default
title: "討伐首領時間表"
php: "true"
---

<?php
/*載入設定*/
require_once('lib/lqsym.php');
require_once('lib/getpetsinfo.php');
require_once('lib/defines.php');

date_default_timezone_set("Asia/Taipei");

/*檢查效能*/
$pageTimer = new PageBenchMark;
$pageTimer->Start();

/* ########################################
 * 載入資料庫
######################################## */

/*設定連線*/
$_login = new DBLogin(DB_MYSQL_HOST, DB_MYSQL_USER, DB_MYSQL_PW, DB_MYSQL_DB);

/*建立連線*/
$bossTimeDB = new DBMySQL(  $_login -> the_host(),
                            $_login -> the_user(),
                            $_login -> the_pw(),
                            $_login -> the_db());

/*成功連線*/
$sql = 'SELECT * FROM `sadb_bosstime` AS `main` INNER JOIN `sadb_pets_ref` AS `ref` ON `main`.`boss_pets_rid` = `ref`.`pets_reaid` WHERE DATE(`boss_date`) BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 14 DAY ORDER BY `boss_date` ASC';

/*輸出資料*/
$bossTimeDB -> query($sql);
$bossTimeData = $bossTimeDB -> getAllData('MYSQLI_ASSOC');

/* ########################################
 * 建立物件
######################################## */

class SingleBossTime
{
  var $id;
  var $petsid;
  var $name;
  var $date;
  var $begin;
  var $end;
  var $img;

/*
[time_id] => 5
[boss_name] => 利維坦
[boss_pets_id] => 289
[boss_date] => 2016-08-29
[boss_time_start] => 15:00
[boss_time_end] => 16:00
*/

/*
  function __construct($id, $name, $pid, $date, $begin, $end)
  {
    $this->id = $id;
    $this->petsid = $pid;
    $this->name = $name;
    $this->date = $date;
    $this->begin = $begin;
    $this->end = $end;
  }
*/
  public function imgHTML()
  {
    $code='';
    if($this->img != ''){
      $code=sprintf('<img src="http://i.imgbox.com/%s" class="img-rounded" style="width:80px;">',$this->img);
    }
    return $code;
  }

  public function reDate($style)
  {
    return date($style, strtotime($this->date));
  }

  public function fullTime()
  {
    return $this->date .' '.$this->begin.'-'.$this->end.'(UTC+9)';
  }

  public function gapTime()
  {
    return $this->begin.'-'.$this->end.'(UTC+9)';
  }

}

/* ########################################
 * 整理資料
######################################## */

$showData=[];
foreach ($bossTimeData as $key => $value) {
  $showData[$key] = new SingleBossTime();
  $showData[$key] -> id      = $value['time_id'];
  $showData[$key] -> petsid  = $value['boss_pets_id'];
  $showData[$key] -> name    = $value['boss_name'];
  $showData[$key] -> date    = $value['boss_date'];
  $showData[$key] -> begin   = $value['boss_time_start'];
  $showData[$key] -> end     = $value['boss_time_end'];
  $showData[$key] -> img     = $value['ICON'];
}

/* ########################################
 * 輸出資料
######################################## */

?>
<section class="post container">
  <header class="post-header">
    <h2 class="post-title"><?php echo sprintf('%s - %s 討伐首領時間表', date('M d'), date('M d',strtotime('+14 days')));?></h2>
    <p>時間為UTC+9韓國標準時間，請玩家自行換算</p>
  </header>
</section>
<section class="post-list">
  <div class="container">
    <div class="row">
    <?php for ($b=0; $b < count($showData); $b++) { ?>
    <div class="col-xs-4 col-md-2">
      <div class="boss-cell <?php if($showData[$b]->reDate('M d')==date('M d')){echo'toady';};?>">
        <div><?=$showData[$b]->imgHTML();?></div>
        <div class="post-link"><a href="<?=sprintf('/pets-%s/',$showData[$b]->petsid);?>"><?=$showData[$b]->name;?></a></div>
        <span class="post-meta date-label"><?=$showData[$b]->reDate('M d');?></span>
        <span><?=$showData[$b]->begin;?></span>
        <span><?=$showData[$b]->end;?></span>
      </div>
    </div>
    <?php }?>
    </div>
  </div>
</section>

<span class="pagetimer"><?php $pageTimer->Stop();$pageTimer->BenchTxt();?></span>
