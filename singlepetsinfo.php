---
layout: compress
php: "true"
comments: true
---

<?php include_once('lib/inc_singlepet.php');?>
<?php include_once('lib/inc_comments.php');?>
<!DOCTYPE html>
<html>
  {% include head.html %}
  <body>
    <main class="wrapper">
      {% include header.html %}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/jquery.dataTables.min.css">
      <style type="text/css">
      .dataTables_filter {
      display: none; 
      }
      </style>
      <article class="post container" itemscope itemtype="http://schema.org/BlogPosting">
        <div id="page-topper" class="row"></div>
        <?php echo $twiceEleHTML;?>
        <span class="boss-cell" style="float:right;"><a href="/team/suggest/">提供隊伍</a></span>
        <header class="post-header">
          <h1 class="post-title" itemprop="name headline"><?php echo $pageTitle;?></h1>
        </header>
        <div class="post-content pets-info" itemprop="articleBody">
          <div class="row page-help danger js-no-script" style='display:block;'>
            <p>JavaScript無法正常啟用，將影響本頁部分內容顯示。請檢查是否有使用AdBlock等影響有JavaScript功能之擴充功能，或者升級瀏覽器至最新版本。如仍無法解決此問題，請洽本站管理員。</p>
          </div>
          <div class="row page-help info">
            <p>計算機數值，因為採用圖鑑回推法，計算上會與實際數值有7-11之誤差。</p>
            <p>技能說明為由韓文翻譯，如果有與實際遊戲中效果，或官方說明有所出入者，敬請告知。</p>
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
            <hr>
            <table id="petsSeries">
              <thead>
                <th style="width:51px;">#</th>
                <th>名稱</th>
                <th>成長率</th>
                <th>體力</th>
                <th>攻擊</th>
                <th>防禦</th>
                <th>敏捷</th>
              </thead>
              <tbody>
                <?php echo $fullSeresPetsHTML; ?>
              </tbody>
            </table>
        </div>
        <!-- team suggest-->
        <div class="row">
          <hr>
          <table id="team-list">
            <thead>
              <th style="width:10%">#</th>
              <th style="width:10%">連結</th>
              <th style="width:15%">提供者</th>
              <th style="width:15%">場合</th>
              <th>隊伍配置</th>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </article>
      <?=$pageComments;?>
      {% include footer.html %}
    </main>
  </body>
</html>

<script type="text/javascript" src="/js/jslib/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/js_pets.js"></script>
<script type="text/javascript" src="/js/savar.js"></script>
<script type="text/javascript" src="/js/appendPetsImgIcon.js"></script>
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
<script type="text/javascript">
$(document).ready(function(){
    var TeamType = ['泛用','競技場','冒險','討伐','討伐首領','突襲戰','部落遠征'];
    for(var i=0; i<TeamType.length ; i++){
        var row = $('<option>').attr('value',i).append(TeamType[i]);
        $('#searchtype').append(row);
    }
    $('#searchtype').prepend($('<option>').attr('value','-1').append('請選擇'));

    function TeamQueryByType(val) {
        table.search(val).draw();
    }

    var table2 = $('#petsSeries').DataTable({
      "responsive": true,
      "paging": false,
      "info": false
    });

    var table = $('#team-list').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": '/lib/json_teamlist.php?pets=<?=$thisPet->info['id']?>',
        "scrollY": "300px",
        "paging": 10,
        "pagingType": "full_numbers",
        "oLanguage": {
            "sLengthMenu": "",
            "sZeroRecords": "查無隊伍組合建議。",
            "sInfo": "共 _MAX_ 筆",
            "sSearch": "搜尋",
            "sInfoFiltered": "找到 _TOTAL_ 筆 資料",
            "sInfoEmpty": "共 0 頁",
            "oPaginate": {
                "sPrevious": "«",
                "sNext": "»",
                "sFirst": "第一筆",
                "sLast": "最後一筆",
            }
        },
        "drawCallback": function(){loopSpan();}
    });

    $('#searchtype').bind('change', function(){
        TeamQueryByType($(this).val()+'=');
    });

    $('#searchtext').bind('keyup click', function(){
        TeamQueryByType($(this).val());
    });

});
</script>