<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// Turn off all error reporting
error_reporting(0);
 $host = 'localhost'; // <--  db address
 $user = 'root'; // <-- db user name
 $pass = ''; // <-- password
 $db = 'pricelist'; // db's name
 $table = 'phone_price_generation'; // table you want to export
 $file = 'file_name'; // csv name.
 
$link = mysql_connect($host, $user, $pass) or die("Can not connect." . mysql_error());
 mysql_select_db($db) or die("Can not connect.");
 
$result = mysql_query("SHOW COLUMNS FROM ".$table."");
 $i = 0;
 
if (mysql_num_rows($result) > 0) {
while ($row = mysql_fetch_assoc($result)) {
$csv_output .= $row['Field']."|";
$i++;

}
}
$csv_output .= "\n";
 $values = mysql_query("SELECT * FROM ".$table."");
 
while ($rowr = mysql_fetch_row($values)) {
for ($j=0;$j<$i;$j++) {
$csv_output .= $rowr[$j]."| ";
}
$csv_output .= "\n";
}
 
$filename = $file."_".date("d-m-Y_H-i",time());
 
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
 
print $csv_output;
 
exit;
?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'class.DBQuery.php';
include_once 'style.php';
$db  = new DBQuery(HOST, USERNAME, PASSWORD, DATABASE_PRICE_LIST);
if(!empty($_REQUEST['start-date'])){
    $startDate= $_REQUEST['start-date'];
    $endDate= $_REQUEST['end-date'];    
    $sql= "SELECT * FROM phone_price_generation WHERE created BETWEEN str_to_date('{$startDate}','%Y-%m-%d') AND DATE_ADD(str_to_date('{$endDate}','%Y-%m-%d'), INTERVAL 1 DAY)";
    $result=$db->query($sql);
if(isset($_REQUEST['preview'])){

    ?>
<table class="table table-striped" id="tblData">
    <tr><th>Id</th><th>Brand</th><th>Phone Model</th><th>Phone Id</th><th>New Price</th><th>Previous Price</th><th>Created</th></tr>
<?php
$i=1;
    while($phone=$db->fetchAssoc($result)){
        print<<<HTML
           <tr><td>$i</td>
           <td>$phone[mid]</td>
           <td>$phone[phone_model]</td>
           <td>$phone[phone_id]</td>
           <td>$phone[new_price]</td>
           <td>$phone[prev_price]</td>
           <td>$phone[created]</td>
       </tr>
HTML;
$i++;
    }
?>
</table>
<?php

}  else {
    if(isset($_REQUEST['generate'])){
    $colResult = $db->query("SHOW COLUMNS FROM phone_price_generation");
 $i = 0;
 
if ($db->numRows($colResult) > 0) {
while ($row = $db->fetchAssoc($colResult)) {
$csv_output .= $row['Field']."|";
$i++;

}
}
$csv_output .= "\n";
 
while ($rowr = mysql_fetch_row($result)) {
for ($j=0;$j<$i;$j++) {
$csv_output .= $rowr[$j]."| ";
}
$csv_output .= "\n";
}
 
$filename = "Pricelist_".date("d-m-Y_H-i",time());
 
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
 
print $csv_output;
 
exit;

}
    }

}
?>
