<?php

//檔案類型
header('Content-Type: application/json; charset=utf-8;');

//require files
require_once('defines.php');

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'sadb_team';
 
// Table's primary key
$primaryKey = 'team_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'team_provider', 'dt' => 2, 'formatter' => function($d, $row){
        return htmlspecialchars($d);
    }),
    array(  'db' => 'team_type',  
            'dt' => 3 , 
            'formatter' => function($d, $row){
                $label = array('泛用','競技場','冒險','討伐','討伐首領','突襲戰','部落遠征');
                $adv   = array('冒險之章', '苦難之章', '外傳');
                $encounterTXT= array('機暴','地機虎','水機虎','火機虎','風機虎');
                $value = explode('=', $d, 2);
                switch (intval($value[0])) {
                    case 2:
                        $regexp = preg_match('/^(\d+)-(\d+)-(\d+)$/', $value[1], $matches);
                        $result = $adv[$matches[1]].' '.$matches[2].'-'.$matches[3];
                        break;
                    case 3:
                        $result = $label[$value[0]].' '.$value[1];
                        break;
                    case 4:
                        $result = $label[$value[0]].' <span class="add-pet-img" data-query-id="'.$value[1].'"></span>';
                        break;
                    case 5:
                        $result = $encounterTXT[intval($value[1])] . $label[$value[0]];
                        break;
                    default:
                        $result = $label[$value[0]];
                        break;
                }
                return $result;
    }),
    array(  'db' => 'team_settings',
            'dt' => 4 , 
            'formatter' => function($d, $row){
                $raw = explode(',', $d);
                $query = array();
                foreach ($raw as $key => $value) {
                    $regexp = preg_match('/^([A-Za-z]+)=([0-9]+)$/', $value, $matches);
                    array_push($query, $matches[2]);
                }
                $value = implode(',', $query);
                $result = "<span class=\"add-icon-list\" data-query-id=\"$value\"></span>";
                return $result;
    }),
    array(  'db' => 'team_id',
            'dt' => 1,
            'formatter' => function($d, $row){
                return "<a href=\"/team/$d/\">詳細</a>";
    }),
    array(  'db' => 'team_id',
            'dt' => 0
    )
);

// assign data
if($_GET['pets']){
    $queryRAW = preg_match('/(\d+)/', intval($_GET['pets']), $matches);
    $value = substr($matches[1], 0, 4);
    $query = "team_settings LIKE '%pet=$value,%'";
}else{
    $query = '';
}

// SQL server connection information

$sql_details = array(
    'user' => DB_MYSQL_USER,
    'pass' => DB_MYSQL_PW,
    'db'   => DB_MYSQL_DB,
    'host' => DB_MYSQL_HOST
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require_once( 'js_datatable/ssp.class.php' );
 
echo json_encode(
    //SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $query)
);