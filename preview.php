<?php
require_once 'class.DBQuery.php';
include_once 'style.php';
if(!empty($_POST['newPrice'])){
    $mid=$_POST['mid'];
    $new_price_values=array_filter($_POST['newPrice']);
    $ids= array_keys($new_price_values);
    $allowed = array_flip($ids);
    $phone_id= $_POST['phone_id'];
    $allowed_phone_id=array_intersect_key($phone_id,$allowed);
    $pprice= $_POST['pprice'];
    $phoneName= $_REQUEST['phone_name'];
    $allowed_pprice=array_intersect_key($pprice,$allowed);
    $allowed_phone = array_intersect_key($phoneName,$allowed);
    $db  = new DBQuery(HOST, USERNAME, PASSWORD, DATABASE_PRICE_LIST);
    foreach ($new_price_values as $key => $values){
    $condQuery="SELECT count(*) AS number FROM `phone_price_generation` WHERE `phone_id`= $allowed_phone_id[$key]  AND `created` > ADDDATE(NOW(), INTERVAL -1 WEEK)";
    $result=$db->query($condQuery);
    while($phone=$db->fetchAssoc($result)){
        //echo ($phone['number']);
        if(empty($phone['number'])){
            //echo "Insert";
            $sql = "INSERT INTO `phone_price_generation` (id, mid,phone_model, phone_id, new_price, prev_price, created)
            VALUES('','{$mid}', '{$allowed_phone[$key]}',$allowed_phone_id[$key],$values,$allowed_pprice[$key],NOW())";
            $queryResult = $db->query($sql);
        }  else {
            //echo "Update";
            $sql = "UPDATE `phone_price_generation` SET new_price = $values, created= NOW() WHERE phone_id = $allowed_phone_id[$key]";
            $queryResult = $db->query($sql);
        }
    }        
    }
    ?>
<table class="table table-striped " id="tblData">
    <tr><th>Id</th><th>mid</th><th>Phone Name</th><th>Phone Id</th><th>New Price</th><th>Previous Price</th></tr>
<?php        
$i =1;
foreach ($new_price_values as $key => $values){
    print<<<HTML
           <tr><td>$i</td>
           <td>$mid</td>
           <td>$allowed_phone[$key]</td>
           <td>$allowed_phone_id[$key]</td>
           <td>$values</td>
           <td>$allowed_pprice[$key]</td>
       </tr>
HTML;
$i++;    
}    

?>
</table>
<?php
}
?>
<form action="generate.php" method="post" name="generateForm">
    <div class="alert alert-error" id="alert">
    <strong>Pick start date and end date.</strong>
</div>
<table class="table">
    <thead>
        <tr>
          <th>
              Start date
              <a href="#" class="btn small" id="date-start" data-date-format="yyyy-mm-dd" data-date="">Change</a>
          </th>
          <th>
              End date
              <a href="#" class="btn small" id="date-end" data-date-format="yyyy-mm-dd" data-date="">Change</a>
          </th>
        </tr>
    </thead>
    <tbody>
        <tr>
          <td><input type="text" value="" name="start-date" id="date-start-display"></td>
          <td><input type="text" value="" name="end-date" id="date-end-display"></td>
        </tr>
    </tbody>
</table>
    <button type="submit" class="btn" id="submit" name="generate">Generate Price-list</button>
</form>
<script>
var startDate = new Date();
var endDate = new Date();
$('#date-start')
    .datepicker()
    .on('changeDate', function(ev){
        if ((ev.date.valueOf() > endDate.valueOf())&& (endDate.date.vaueOf() != '')){
            $('#alert').show().find('strong').text('The start date must be before the end date.');
        } else {
            $('#alert').hide();
            startDate = new Date(ev.date);
            $('#date-start-display').val($('#date-start').data('date'));
        }
        $('#date-start').datepicker('hide');
    });
$('#date-end')
    .datepicker()
    .on('changeDate', function(ev){
        if (ev.date.valueOf() < startDate.valueOf()){
            $('#alert').show().find('strong').text('The end date must be after the start date.');
        } else {
            $('#alert').hide();
            endDate = new Date(ev.date);
            $('#date-end-display').val($('#date-end').data('date'));
        }
        $('#date-end').datepicker('hide');
    });
</script>