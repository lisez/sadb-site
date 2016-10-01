---
layout: compress
php: "true"
comments: true
---

<?php
/*檔案類型*/
header('Content-Type:text/html; charset=utf-8');

/*載入設定*/
require_once('lib/lqsym.php');
require_once('lib/getpetsinfo.php');
require_once('lib/defines.php');

ini_set('display_errors', '0');
error_reporting(E_ALL | E_STRICT);

/*檢查效能*/
$pageTimer = new PageBenchMark;
$pageTimer->Start();

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
  <div class="div-table-tr %s">
    <div class="div-table-td">%s</div>
    <div class="div-table-td">%s</div>
    <div class="div-table-td">%s</div>
    <div class="div-table-td">%s</div>
    <div class="div-table-td">%s</div>
    <div class="div-table-td">%s</div>
    <div class="div-table-td">%s</div>
  </div>
  ';
  /*output HTML code*/
  $iconHTML='<a href="/pets-%s/"><img src="%s"  class="img-rounded pets-series-thumbnails"></a>';
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
?>
<!DOCTYPE html>
<html>
  {% include head.html %}
  <body>
    <main class="wrapper">
      {% include header.html %}
  <script language="JavaScript" src="{{ '/js/petsInfoUse.js' | prepend: site.baseurl }}"></script>
  <script language="JavaScript">
    /*claim pets data*/
    var valStartLV=<?php echo $thisPet->showStartLV();?>;
    var valStart=[<?php echo implode($thisPet->pwr['start'],',');?>];
    var valMin=[<?php echo implode($thisPet->pwr['min'],',');?>];
    var valMax=[<?php echo implode($thisPet->pwr['max'],',');?>];

    window.addEventListener('load',function(){
      /*偵測有無支援JS*/
      indisplayNoJSAlert('js-no-script');

      /*自動帶入最大值*/
      insertVal(<?php echo implode($thisPet->pwr['max'],',');?>);

      /*自動全選輸入欄*/
      autoSelectInput();

      /*自動計算*/
      hookInputChange();

      /*偵測選單改變*/
      window.document.getElementsByClassName('pet_pwr_now')[0].addEventListener('change', function(){
        var chk = document.getElementsByClassName('pet_pwr_now')[0].value;
        /*依據選擇類型改變*/
        switch (chk) {
          case 'max':
            insertVal(valMax[0],valMax[1],valMax[2],valMax[3]);
            break;
          case 'min':
            insertVal(valMin[0],valMin[1],valMin[2],valMin[3]);
            break;
        };
      });
    });
  </script>
      <article class="post container" itemscope itemtype="http://schema.org/BlogPosting">
        <?php echo $twiceEleHTML;?>
        <header class="post-header">
          <h1 class="post-title" itemprop="name headline"><?php echo $pageTitle;?></h1>
        </header>
        <div class="post-content pets-info" itemprop="articleBody">
          <div class="js-no-script" style='display:block;'>
            <p>JavaScript無法正常啟用，將影響本頁部分內容顯示。請檢查是否有使用AdBlock等影響有JavaScript功能之擴充功能，或者升級瀏覽器至最新版本。如仍無法解決此問題，請洽本站管理員。</p>
          </div>
          <div class="row">
            <!-- pet image-->
            <div class="col-xs-6 col-md-4">
              <img src="<?php echo $thisPet->showImg('profile');?>" class="img-responsive">
            </div>
            <!-- pet basic info-->
            <div class="col-xs-6 col-md-3">
              <div class='div-table'>
                <div class='div-table-tr'>
                  <div class='div-table-th'>名稱</div>
                  <div class='div-table-td'><?php echo $thisPet->showName('cht');?></div>
                </div>
                <div class='div-table-tr'>
                  <div class='div-table-th'>原名</div>
                  <div class='div-table-td'><?php echo $thisPet->showName('kr');?></div>
                </div>
                <div class='div-table-tr'>
                  <div class='div-table-th'>屬性</div>
                  <div class='div-table-td'><?php echo $thisPet->info['element'];?></div>
                </div>
                <div class="div-table-tr">
                  <div class="div-table-th">稀有度</div>
                  <div class="div-table-td"><?php echo $thisPet->showRarityTxt();?></div>
                </div>
                <div class='div-table-tr'>
                  <div class='div-table-th'>類型</div>
                  <div class='div-table-td'><?php echo $thisPet->info['type'];?></div>
                </div>
              </div>
            </div>
            <!-- 基本能力值-->
            <div class="col-xs-12 col-md-5">
              <div class="div-table">
                <div class="div-table-tr">
                  <div class="div-table-th">
                    <select class="pet_lv_now">
                      <option value="10">LV.10</option>
                      <option value="20">LV.20</option>
                      <option value="30">LV.30</option>
                      <option value="40">LV.40</option>
                      <option value="50">LV.50</option>
                      <option value="60">LV.60</option>
                      <option value="63">LV.63</option>
                      <option value="66">LV.66</option>
                      <option value="69">LV.69</option>
                      <option value="72">LV.72</option>
                      <option value="75">LV.75</option>
                      <option value="78" selected>LV.78</option>
                    </select>
                  </div>
                  <div class="div-table-th">
                    <select class="pet_pwr_now">
                      <option value="max" selected>最大成長率</option>
                      <option value="min">最小成長率</option>
                    </select>
                  </div>
                  <div class="div-table-th">性格</div>
                  <div class="div-table-th">計算</div>
                </div>
                <div class="div-table-tr">
                  <div class="div-table-th">體力</div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_hp_now"></div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_hp_char" placeholder="±??%"></div>
                  <div class="div-table-td pet_hp_total"></div>
                </div>
                <div class="div-table-tr">
                  <div class="div-table-th">攻擊</div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_atk_now"></div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_atk_char" placeholder="±??%"></div>
                  <div class="div-table-td pet_atk_total"></div>
                </div>
                <div class="div-table-tr">
                  <div class="div-table-th">防禦</div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_def_now"></div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_def_char" placeholder="±??%"></div>
                  <div class="div-table-td pet_def_total"></div>
                </div>
                <div class="div-table-tr">
                  <div class="div-table-th">敏捷</div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_spd_now"></div>
                  <div class="div-table-td"><input type="number" step="0.1" class="pet_spd_char" placeholder="±??%"></div>
                  <div class="div-table-td pet_spd_total"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- 圖表1-->
            <div class="col-xs-12 col-md-5">
            </div>
          </div>

          <!-- 技能資料-->
          <div class="row">
            <div class="div-table">
<?php
/*格式化技能陣列*/
$_SkillRow   = Array('active','pas1','pas2','pas3');

/*HTML Code*/
echo $htmlSkillTitle=
'<div class="div-table-tr">
  <div class="div-table-th" style="width:2em;">#</div>
  <div class="div-table-th" style="width:6em;">技能</div>
  <div class="div-table-th" style="width:4em;">氣力</div>
  <div class="div-table-th">效果</div>
</div>';

$htmlSkillRow =
"<div class=\"div-table-tr\">
  <div class=\"div-table-th\">%s</div>
  <div class=\"div-table-td\">%s</div>
  <div class=\"div-table-td\">%s</div>
  <div class=\"div-table-td\">%s</div>
</div>";

/*輸出資料*/
$_id=1;
foreach ($_SkillRow as $value) {
    if ($value=='active') {
      echo sprintf( $htmlSkillRow,
                    $_id,
                    $thisPet->showSkill($value,'name'),
                    $thisPet->showSkill($value,'cost'),
                    $thisPet->showSkill($value,'desc').
                    $thisPet->showSkill($value,'eft'));
    }else{
      echo sprintf( $htmlSkillRow,
                    $_id,
                    $thisPet->showSkill($value,'name'),
                    '&nbsp;',
                    $thisPet->showSkill($value,'eft'));
    }
    $_id+=1;
};
?>
            </div>
          </div>

          <!-- same series pets-->
          <div class="row" style="text-align:center;">
            <div class="div-table" id="petsSeries">
              <!-- pets attributes header -->
              <div style="display:table-header-group;">
                <div class="div-table-tr">
                  <div class="div-table-th">#</div>
                  <div class="div-table-th">名稱</div>
                  <div class="div-table-th">成長率</div>
                  <div class="div-table-th">體力</div>
                  <div class="div-table-th">攻擊</div>
                  <div class="div-table-th">防禦</div>
                  <div class="div-table-th">敏捷</div>
                </div>
              </div>
              <div style="display:table-row-group;">
              <!-- pets data repeat -->
              <?php echo $fullSeresPetsHTML; ?>
              </div>
            </div>
          </div>
        </div>
      </article>
      {% include comments.html %}
      {% include footer.html %}
    </main>
  </body>
</html>
<span class="pagetimer"><?php $pageTimer->Stop();$pageTimer->BenchTxt();?></span>
