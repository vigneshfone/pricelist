<?php
require_once 'class.DBQuery.php';
include_once 'style.php';
$db  = new DBQuery(HOST, USERNAME, PASSWORD, DATABASE_FONE);
$sql = "SELECT id, name FROM `data_makers`";
$queryResult = $db->query($sql);
while($brands[]=$db->fetchAssoc($queryResult)){
   
}
array_pop($brands);
?>
<div class="hero-unit span8">
    <h2 style="margin-bottom: 50px">Price List Generation</h2>
<form name="input" class="form-horizontal" action="display.php" method="post">
<div class="control-group">
<label class="control-label" for="inputBrand">Select Brand</label>
<div class="controls">
    <select name="brand" id="brand">
        <option value="">--Select any Brand--</option>
        <?php foreach ($brands as $brand){
            echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
        } ?>
    </select>
    <input type="hidden" name="model" value=""/>
     <button type="submit" class="btn">Submit</button>
</div>
</div>
</form>
</div>
<div class="row">
    <div class="span8" id="listPhones">
        
    </div>
</div>
</div>
<script type="text/javascript">
    var j = jQuery.noConflict();
    j(document).ready(function(){
        j("#brand").change(function(){
            var brand   =   j("#brand option:selected").text();
            j("input[name='model']").val(brand); 
        });
    });
</script>