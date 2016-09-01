<?php
require_once('misc.php');

/**************************
 * 寵物資訊格式化
 **************************/
class GetPetsInfo{
  //宣告寵物數值
  var $name     = Array(  'cht','kr');        //名稱
  var $imgFile  = Array(  'char',             //圖片
                          'profile',
                          'icon');
  var $pwr      = Array(  'min',              //能力值
                          'max',
                          'start',
                          'lv');
  var $info     = Array(  'id', 'rlid',       //其他資訊
                          'qty', 'rarity',
                          'element',
                          'type', 'species',
                          'riding',
                          'getloc');
  var $skill    = Array(  'active',           //技能
                          'pas1','pas2','pas3');

  //輸出名稱
  public function showName($lang)
  {
    //檢查
    $_chk=Array('kr','cht');
    $_term = UniString($lang);
    ChkArrayByElement($_term, $_chk,'寵物名稱');

    return $this -> name[$_term];
  }

  //輸出圖片
  public function showImg($type)
  {
    //設定變數
    $_baseURL='http://i.imgbox.com/';

    //檢查
    $_term = UniString($type);
    $_chk = array('char','profile','icon');
    ChkArrayByElement($_term, $_chk,'寵物圖片');

    return strval($_baseURL.$this -> imgFile[$_term]);
  }

  //輸出等級
  public function showStartLV(){
    return IntSafe($this->pwr['lv'],0,2);
  }

  //輸出稀有度字串
  public function showRarityTxt()
  {
    $_index     = IntSafe($this->info['rarity'],0,1);
    $_rarityTxt = Array('首領','普通','高級','稀有','英雄','傳說');
    ChkArrayByIndex($_index, $_rarityTxt);
    return $_rarityTxt[$_index];
  }

  //輸出成長率
  public function showPwr($pwr, $type)
  {
    //檢查輸入值
    $_chkType=Array('max','min','start');
    $_chkPwr=Array('hp','atk','def','spd');

    ChkArrayByElement($type,$_chkType);
    ChkArrayByElement($pwr,$_chkPwr);

    //回傳
    return $this -> pwr[$type][$pwr];
  }

  //輸出成長值
  public function showSumGroth($hp,$atk,$def,$spd)
  {
    return round(floatval($hp)/5+floatval($atk)+floatval($def)+floatval($spd),2);
  }

  //輸出最大成長值
  public function showMaxGroth()
  {
    return $this-> showSumGroth($this->showPwr('hp','max'),
                                $this->showPwr('atk','max'),
                                $this->showPwr('def','max'),
                                $this->showPwr('spd','max'));
  }

  //輸出最小成長值
  public function showMinGroth()
  {
    return $this-> showSumGroth($this->showPwr('hp','min'),
                                $this->showPwr('atk','min'),
                                $this->showPwr('def','min'),
                                $this->showPwr('spd','min'));
  }

  //技能輸出格式化
  private function showSkillStyle($string){
    $_stringPreg    = preg_replace('/(\[[^\]]+\])([^\|]+)/i', '<strong>${1}</strong>${2}', strval($string));
    $_string        = explode('|', strval($_stringPreg));
    $_stringFormat  = '<p>%s</p>';
    return sprintf($_stringFormat, implode('<br>', $_string));
  }

  //輸出技能
  public function showSkill($type, $col)
  {
    //檢查
    $_type  = UniString($type);
    $_col   = UniString($col);
    $_chkType   = Array('active','pas1','pas2','pas3');
    $_chkActive = Array('name','cost','desc','eft');
    $_chkPas    = Array('name','eft');

    ChkArrayByElement($_type, $_chkType);
    if($_type=='active')
    {
      ChkArrayByElement($_col, $_chkActive);
    }else{
      ChkArrayByElement($_col, $_chkPas);
    }

    //回傳
    return $this -> showSkillStyle($this->skill[$_type][$_col]);
  }
}
?>
