<?php
require_once('misc.php');

/**************************
 * 資料庫登入格式化
 **************************/
class DBLogin{
  var $user;
  var $password;
  var $host;
  var $db;

  function __construct($host, $user, $password, $dbname)
  {
    $this->host     = $host;
    $this->user     = $user;
    $this->password = $password;
    $this->db       = $dbname;
  }

  public function the_user()
  {
    return StringSafe($this->user,0,255);
  }

  public function the_pw()
  {
    return $this->password;
  }

  public function the_host()
  {
    return StringSafe($this->host,0,255);
  }

  public function the_db()
  {
    return StringSafe($this->db,0,255);
  }
}

/**************************
 * MySQL連線
 **************************/
class DBMySQL{
  //設定變數
  var $_dbConn;
  var $_qryRes;

  //連線MySQL
  // function connect($host,$user,$pw,$dbname){
  function __construct($hostname, $user, $password, $dbname){
    //建立連線
    $_mysql_conn = mysqli_connect($hostname,
                                  $user,
                                  $password,
                                  $dbname)
                                  or die(TXT_ERR_DB.' '.mysqli_connect_error());

    //設定UTF-8
    mysqli_set_charset($_mysql_conn, "utf8");

    $this -> _dbConn = $_mysql_conn;
    return true;
  }

  //關閉連線
  function __destruct()
  {
    mysqli_close($this->_dbConn);
  }

  //查詢資料
  public function query($sql){
    //
    //$_sql = mysqli_real_escape_string($this->_dbConn, $sql);
    $_sql=$sql;

    //撈資料
    $qryData = mysqli_query($this->_dbConn, $_sql)
                or die(TXT_ERR_DB.' '.mysqli_errno($this->_dbConn));

    //儲存資料後回傳
    $this->_qryRes = $qryData;
    return $qryData;
  }

  //複數SQL查詢
  public function queryMulti($sql)
  {
    //安全性
    //$_sql = mysqli_real_escape_string($this->_dbConn, $sql);
    $_sql=$sql;

    //撈資料
    if(mysqli_multi_query($this->_dbConn, $_sql)){
      do {
        if($_result = mysqli_store_result($this->_dbConn)){
          while ($_row = mysqli_fetch_all($_result, MYSQLI_ASSOC)) {
            $this->_qryRes[] = $_row;
          }
          mysqli_free_result($_result);
        }
      } while (mysqli_next_result($this->_dbConn));
    }

    return $this->_qryRes;
  }

  //回傳查詢值
  public function getAllData($type=null)
  {
    //檢查輸入值
    /*
    if($type)
    {
      $_term  = StringSafe($type,0,12);
      $_chk   = ['MYSQLI_NUM','MYSQLI_ASSOC','MYSQLI_BOTH'];
      ChkArrayByElement($_term, $_chk);
    }
    */

    switch ($_term) {
      case 'MYSQLI_NUM':
        return mysqli_fetch_all($this->_qryRes, MYSQLI_NUM);
        break;

      case 'MYSQLI_ASSOC':
        return mysqli_fetch_all($this->_qryRes, MYSQLI_ASSOC);
        break;

      case 'MYSQLI_BOTH':
        return mysqli_fetch_all($this->_qryRes, MYSQLI_BOTH);
        break;

      default:
        return mysqli_fetch_all($this->_qryRes, MYSQLI_ASSOC);
        break;
    }
  }

  public function getData($type=null)
  {
    //檢查輸入值
    /*
    if($type)
    {
      $_term  = StringSafe($type,0,12);
      $_chk   = ['MYSQLI_NUM','MYSQLI_ASSOC','MYSQLI_BOTH'];
      ChkArrayByElement($_term, $_chk);
    }
    */

    switch ($_term) {
      case 'MYSQLI_NUM':
        return mysqli_fetch_array($this->_qryRes, MYSQLI_NUM);
        break;

      case 'MYSQLI_ASSOC':
        return mysqli_fetch_array($this->_qryRes, MYSQLI_ASSOC);
        break;

      case 'MYSQLI_BOTH':
        return mysqli_fetch_array($this->_qryRes, MYSQLI_BOTH);
        break;

      default:
        return mysqli_fetch_array($this->_qryRes, MYSQLI_NUM);
        break;
    }
  }

  //回傳查詢值總數
  public function getRowsNum()
  {
    return mysqli_num_rows($this->_qryRes);
  }
}
?>
