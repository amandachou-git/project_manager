<?php
  require_once "db.php";
	
  $log_usr = @$_COOKIE["ACCOUNT"];
  $log_type = @$_COOKIE["TYPE"];
  $passed = @$_COOKIE["PASSED"];
  /*  如果 cookie 中的log_type不等於99或passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 log_in.htm	*/
  if ($log_type != "1" || $passed != "TRUE")
  {
	echo "<script language='javascript'>";
	echo "alert('抱歉，你的權限不夠');";
	echo "window.close();";
	echo "</script>";
    //header("Refresh:0; url=proj_detail.php");
    //exit();	
  }
?>
<!doctype html>
<head>
<meta charset="utf-8">
<title>Edit Job</title>

<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/detail_addJob.css">
<link rel="stylesheet" type="text/css" href="CSS/jquery-ui.min.css">

<script src="jQuery/jquery.min.js"></script>
<script src="jQuery/jquery-ui.min.js"></script>
<script type="text/javascript" src="dist/html5shiv.js"></script>

<style>
.add_table{
    display:table;
    margin:30px auto;
    }
.view_table{
    display:table;
    margin:30px auto;
    }
aside{
    float:left;
    }
tr{
    display:table-row;
    }
td{
    display:table-cell;
    width:200px;
    padding:6px;
    vertical-align: middle;
    border:3px solid #333;
    text-align: center;
    }
tbody{
    display:table-row-group;
    }
</style>
    
<!--緊跟在第二層選單後插入jquery語法並送出至ajax_addJob.php作處理-->
<script type="text/javascript">
$(document).ready(function()
{
	$('#program').on('change',function()
	{
	var programID = $(this).val();
				
		if(programID)
		{
    		$.ajax({
    			type:'GET',
    			url:'ajaxAction.php',
    			data:{'id':$(this).val()},
			dataType:'html',
    			success:function(html){	$('#checkbox_list').html(html); }
    			}); 
    		}
		else{	$('#checkbox_list').html('select program first'); }
    	});
});

function validate()
{
if (document.add_job.program.value == "")
	alert("Please choose program");
else if (document.add_job.owner.value == "-1")
	alert("Please choose Job owner");
else
	add_job.submit(); 
}
</script>

</head>
<body>
<div class="wrapper">
    <div class="container">
    <aside>
    <form action="detail_JobAction.php?type=add" name="add_job" method="post">
    <table class="add_table">
    <?php
	
	//透過url的方式get projlist_id的value
	if(isset($_GET['projlist_id']))
	{
		$proj_id = $_GET['projlist_id'];
	}
			
	//取得專案名稱與專案管理員
	$proj_result = mysql_query("SELECT * FROM project_list WHERE projid = '$proj_id'") or die(mysql_error());
	$proj_num = mysql_num_rows($proj_result);
			
	if($proj_num > 0)
	{
		while($row = mysql_fetch_array($proj_result))
		{	
			$proj_name = $row["projname"];
			$proj_mgr = $row["projmgr"];
		}
	}
			
	$today = date('Y-m-d');
    ?>
        <tr>
            <td colspan="3">
		<font size="+2" face="Arial, Helvetica, sans-serif">New Job：</font>
                <font size="+2" color="#FF0000"><?php echo $proj_name; ?></font>
                <input type="hidden" name="proj_id" value="<?php echo $proj_id ?>">
            </td>
        </tr> 
        <tr>
	    <td>
                <font>Date:<?php echo $today ?></font>
            </td>
        </tr>
        <tr>
            <td>Program：
    <?php	$prog_result = mysql_query("SELECT program_id, program FROM task_type") or die(mysql_error());	?>
        	<select name="program" id="program">	
			<option value="">select</option>
			<?php
				//撈出資料庫主程式類別的值
				while($prog_row = mysql_fetch_array($prog_result))
				{ 
					echo '<option value="'.$prog_row['program_id'].'">'.$prog_row['program'].'</option>';					
				}
			?>
		</select>
            </td>
        </tr>
        <tr>
            <td>
        	<div id="checkbox_list">select program first</div>
            </td>
        </tr>
        <tr>
	    <td>Owner：
        	<select name="owner" id="owner">
			<option value="-1">select</option>
        		<?php
				$mem_result = mysql_query("SELECT * FROM member WHERE NOT usrlevel='99' ORDER BY account ASC");
				while($mem_row = mysql_fetch_array($mem_result))
				{	
			?>
                    	<option value="<?php echo $mem_row['account']; ?>"><?php echo $mem_row['eng_name']; ?>
                    	<?php
				}
        		?>
            	</select>
            </td>
         </tr>
         <tr>
            <td>
            <?php 
		$verify_result = mysql_query("SELECT * FROM member WHERE usrlevel='77' ORDER BY account ASC");
		while($verify_row = mysql_fetch_array($verify_result))
		{
	    ?>
            	<input type="checkbox" name="verify[]" value="<?php echo $verify_row["account"]; ?>"><?php echo $verify_row["eng_name"];?></br>
            <?php		
		}
	    ?>
            	<input type="checkbox" name="verify[]" value="NULL">NULL
            </td>
         </tr>
         <tr>
	    <td>
		<input type="button" value="ADD" onClick="validate()">
            	<input type="reset" value="RESET">
		<input type="button" value="BACK" onClick="window.close();">
            </td>            	
        </tr>
    </table>
    </form>
    </aside>

    <table class="view_table" width="75%">
    <?php
	$detail_result = mysql_query("SELECT * FROM project_detail WHERE projid='$proj_id'") or die(mysql_error());
	$num = mysql_num_rows($detail_result);
		
	if($num > 0){
	echo "<tr>
		<td>NO.</td>
		<td>Program</td>
            	<td>Subroutine</td>
            	<td>Target Date</td>
            	<td>Owner</td>
           	<td>Edit</td>
              </tr>";
				
	$i = 0;
	$x = 1;
	while($detail_row = mysql_fetch_array($detail_result))
	{ 
		$detail_id = $detail_row["detail_id"];
		$detail_owner = $detail_row["detail_owner"];
    ?>
	<script type="text/javascript">
    	$(document).ready(function()
	{ 
 		$("#datepicker1<?php echo $i; ?>").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});  
	});
		
	function DeleteJob(proj_id, detail_id)
	{
		if(confirm("請確認是否刪除此工作?"))
		location.href = "detail_JobAction.php?proj_id="+proj_id+"&detail_id="+detail_id;
	}
    </script>
    <?php
	$detail_program = $detail_row["detail_program"];
	$detail_subroutine = $detail_row["detail_subroutine"];
		
	//透過id找出相對應的名稱，並列印出來
	$program_result = mysql_query("SELECT program FROM task_type WHERE program_id=$detail_program");
	$program = mysql_result($program_result, 0, "program");
		
	//$subroutine_result = mysql_query("SELECT subroutine FROM task_item WHERE item_id=$detail_subroutine");
	//$subroutine = mysql_result($subroutine_result, 0, "subroutine");
    ?>
	<tr>
        	<td><?php echo $x; ?></td>
		<td><?php echo $program; ?></td>
		<td><?php echo $detail_subroutine; ?></td>
		<td><input type='text' name='datepicker1' id='datepicker1<?php echo $i; ?>' size='12'></td>
	<?php
		 $OwnerName = mysql_query("SELECT eng_name FROM member WHERE account=$detail_owner");
		 $name = mysql_result($OwnerName, 0, "eng_name");
	?>
		<td><?php echo $name; ?></td>
		<td><a href='#' onClick="DeleteJob(<?php echo $proj_id; ?>, <?php echo $detail_id; ?>)">Delete</a></td>
	</tr>
	<?php				
		$i++;	
		$x++;
		}
	}
	else{	echo "請新增工作";	}
			
	mysql_free_result($detail_result);
	mysql_close($conn);
    ?>
    </table>
    </div><!-- container over -->
</div><!-- wrapper over -->
</body>
</html>
