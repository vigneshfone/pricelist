<?php
require_once 'class.DBQuery.php';
include_once 'style.php';
if(!empty($_POST['brand']))
{
$mid=$_POST['brand'];
$brand= $_POST['model'];
$db  = new DBQuery(HOST, USERNAME, PASSWORD, DATABASE_FONE);
$sql = "SELECT id, model, mid, priceLatest FROM `phone` where mid=$mid AND StatusId = 1";
$queryResult = $db->query($sql);
$i=1;
?>
   <div class="row">
    <div class="input-append">
    Enter Keywords to search in the Table!
    <input class="span4" id="search" type="text">
    </div>
    <form name="priceGeneration" class="form-horizontal" action="preview.php" method="post">
   <table class="table table-striped" id="tblData">
       <tr><th>Id</th><th>Phone Id</th><th>Phone</th><th>New Price</th><th>Previous Price</th></tr>
<?php
while ($phone=$db->fetchAssoc($queryResult)){
print<<<HTML

       <tr><td>$i</td>
           <td>$phone[id]</td>
           <td>$brand $phone[model]</td>
           <td><input type="text" name="newPrice[]" class="newPrice"/>
                <input type="hidden" name="phone_name[]" value="$phone[model]"/>       
               <input type="hidden" name="phone_id[]" value="$phone[id]"/>               
               <input type="hidden" name="pprice[]" value="$phone[priceLatest]"/>
           </td>
           <td>$phone[priceLatest]</td>
       </tr>

HTML;
    $i++;
}
?>
       <input type="hidden" name="mid" value="<?php echo $brand;?>"/>
</table>
       <button type="submit" class="btn" id="submit">Save</button>
</form
</div>
<script>
$(document).ready(function()
{
	$('#search').keyup(function()
	{
		searchTable($(this).val());
	});
        
        $('.newPrice').blur(function(){
            var number = $(this).val();
            if(isNaN(number)){
                alert("Please Enter only numbers!");
                $(this).val("");
                $(this).focus();
            }
        });
        
//        $('#submit').click(function(){
//        $('.newPrice').filter(function() {
//        return !this.value || $.trim(this.value).length == 0;
//        }).remove();
//        });
});

function searchTable(inputVal)
{
	var table = $('#tblData');
	table.find('tr').each(function(index, row)
	{
		var allCells = $(row).find('td');
		if(allCells.length > 0)
		{
			var found = false;
			allCells.each(function(index, td)
			{
				var regExp = new RegExp(inputVal, 'i');
				if(regExp.test($(td).text()))
				{
					found = true;
					return false;
				}
			});
			if(found == true)$(row).show();else $(row).hide();
		}
	});
}    
</script>
<?php 
}else{
    echo '<h2>Select any Brand</h2>';
}

?>
</div>