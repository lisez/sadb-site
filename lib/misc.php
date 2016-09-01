<?php
/* ########################################
 * 自訂常數
######################################## */

define('TXT_ERR_CHK', '[檢查錯誤]');
define('TXT_ERR_DB', '[資料庫錯誤]');

/* ########################################
 * 字串檢查
######################################## */

/**************************
 * 統一字串型態
 **************************/

//全部轉為小寫字
function UniString($string){
  return strtolower($string);
}

/**************************
 * JSON
 **************************/

function JSONChar($string)
{
  return json_encode($string);
}

/**************************
 * 避免數值溢位
 **************************/

//string
function StringSafe($string, $pos=0, $length){
  //先擷取至指定長度再指定型態輸出
  return substr(strval($string), $pos, $length);
}

//int
function IntSafe($int, $pos=0, $length){
  preg_match('/^[0-9\.\-Ee]+$/',$int,$_int);
  return intval(substr(strval($_int[0]), $pos, $length));
}

//float
function FloatSafe($int, $pos=0, $length){
  preg_match('/^[0-9\.\-Ee]+$/',$int,$_int);
  return floatval(substr(strval($_int[0]), $pos, $length));
}

/**************************
 * 陣列值檢查
 **************************/

 //以元素檢查
function ChkArrayByElement($thevalue, $thearray, $alertmsg='none'){
  if (!in_array($thevalue, $thearray))
  {
    die(sprintf(TXT_ERR_CHK.'%s不在 %s陣列值內（其他訊息：%s）', $thevalue, $thearray, $alertmsg));
  }

  return true;
}

//以索引檢查
function ChkArrayByIndex($theindex, $thearray, $alertmsg='none'){
  if (!$thearray[$theindex])
  {
    die(sprintf(TXT_ERR_CHK.'%s不在 %s陣列值內（其他訊息：%s）', $theindex, $thearray, $alertmsg));
  }

  return true;
}

/* ########################################
 * 效能測試
######################################## */

class PageBenchMark{
  //設定起始時間變數
  var $startTime  = 0;
  var $stopTime   = 0;

  //取得計時秒
  private function getMicroTime(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }

  //計時開時
  public function Start()
  {
    return $this->startTime = $this->getMicroTime();
  }

  //計時結束
  public function Stop()
  {
    return $this->stopTime = $this->getMicroTime();
  }

  //計算時間
  public function Bench()
  {
    return $this->stopTime - $this->startTime;
  }

  //以字串輸出計算時間
  public function BenchTxt()
  {
    $_txt = sprintf('頁面時間%s', $this->Bench());
    echo $_txt;
  }

}

?>
