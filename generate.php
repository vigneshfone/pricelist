<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//echo $_SERVER["DOCUMENT_ROOT"];exit;
require_once 'class.DBQuery.php';
include_once 'style.php';
if(!empty($_REQUEST['start-date'])){
    //print_r($_REQUEST);
    $startDate= $_REQUEST['start-date'];
    $endDate= $_REQUEST['end-date'];
    $db['one']  = new DBQuery(HOST, USERNAME, PASSWORD, DATABASE_PRICE_LIST);
    $db['two']  = new DBQuery(HOST, USERNAME, PASSWORD, DATABASE_FONE);
    $sql= "SELECT * FROM phone_price_generation WHERE created BETWEEN str_to_date('{$startDate}','%Y-%m-%d') AND DATE_ADD(str_to_date('{$endDate}','%Y-%m-%d'), INTERVAL 1 DAY)";
    $result=$db['one']->query($sql);

    ?>
<table class="table table-striped" id="tblData">
    <tr><th>Id</th><th>mid</th><th>Phone Model</th><th>Phone Id</th><th>New Price</th><th>Previous Price</th><th>Created</th></tr>
<?php
$i=1;
    while($phone=$db['one']->fetchAssoc($result)){
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
$foo = date('F-j-y-', strtotime($startDate)).rand(1,100).'.csv';
$fileName= $_SERVER["DOCUMENT_ROOT"].'/pricelist/csv/'. $foo;
$importCsv="SELECT CONCAT(mid,' ',phone_model) AS 'Phone Name', phone_id, new_price, prev_price FROM `phone_price_generation` WHERE created BETWEEN str_to_date('{$startDate}','%Y-%m-%d') AND DATE_ADD(str_to_date('{$endDate}','%Y-%m-%d'), INTERVAL 1 DAY) INTO OUTFILE '{$fileName}' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
if($queryCsv=$db['one']->query($importCsv)){
     echo 'Right-click and select "Save link as..": <a href="csv/'.$foo.'" target="_blank">Download File</a>';
}
}
?>
