<?php
  require_once "db.php";
  
  $log_usr = @$_COOKIE["ACCOUNT"];
  $log_type = @$_COOKIE["TYPE"];
  $passed = @$_COOKIE["PASSED"];
  /*  如果 cookie 中的log_type不等於1或passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 proj_list.php	*/
  if ($log_type != "1" || $passed != "TRUE")
  {
	echo "<script language='javascript'>";
	echo "alert('抱歉，你的權限不夠');";
	echo "window.close();";
	echo "</script>";
    //header("Refresh:0; url=proj_list.php");
    //exit();
  }

	$today = date('Y-m-d');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Project</title>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/proj_add.css">
<link rel="stylesheet" type="text/css" href="CSS/jquery-ui.min.css">
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
	width:150px;
	padding:6px;
	vertical-align: middle;
	border:3px solid #333;
	text-align: center;
	}
tbody{
	display:table-row-group;
	}
</style>
<script src="jQuery/jquery.min.js"></script>
<script src="jQuery/jquery-ui.min.js"></script>
<script type="text/javascript" src="dist/html5shiv.js"></script>
<script type="text/javascript"> 

$(document).ready(function(){ 
 	$("#datepicker1").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});  
	});
			
$(document).ready(function(){ 
 	$("#datepicker2").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});  
	}); 

function validate()
{
	var i=0,flag=true;
    while(i<add_project.proj_type.length && flag)
	{
		if(add_project.proj_type[i].checked)
		{
			flag=false;
		}
		i++;
	}
	
	var j=0, flag2=true;
	while(j<add_project.proj_div.length && flag2)
	{
		if(add_project.proj_div[j].checked)
		{
			flag2=false;
		}
		j++;
	}
		
    if(flag)
	{
		alert("Please choose project type");
		add_project.proj_type.select();
	    window.event.returnValue = false;
	 }
	 else if(document.add_project.proj_name.value == "")
	 {
	 	alert("Please input product name");
	 }
	 else if(document.add_project.requestor.value == "-1")
	 {
		alert("Please choose Requestor");
	 }
	else if(document.add_project.datepicker1.value == "")
	{
		alert("Please choose Apply Date");
	}
	else if(document.add_project.datepicker2.value == "")
	{
		alert("Please choose Request Date");	
	}
	else if(flag2)
	{
		alert("Please choose project division");
		add_project.proj_div.select();
		window.event.returnValue = false;
	}
	else
	{
		add_project.submit();
	}
}
</script>
</head>

<body>
<div class="wrapper">
  <div class="container">
  <aside>
  <form action="projAction.php?type=add" name="add_project" method="post">
  <table class="add_table">
  	<tr>
  		<td colspan="2"><font size="+2" face="Arial, Helvetica, sans-serif">New Project</font></td>
  	</tr>
  	<tr>
  		<td><font>Product Type：</font></td>
  		<td colspan="3">
  			<input type="radio" name="proj_type" value="type1">Gaming</br>
  			<input type="radio" name="proj_type" value="type2">Embedded</br>
  			<input type="radio" name="proj_type" value="type3">Networking</br>
  			<input type="radio" name="proj_type" value="type4">COMe</br>
  			<input type="radio" name="proj_type" value="type5">Others
  		</td>
  	<tr>
  		<td><font>Product Name：</font></td>
    	<td><input type="text" name="proj_name" size="10" maxlength="10"></td>
  	</tr>
  	<tr>
    	<td><font>Requestor：</font></td>
    	<td>
    		<select name="requestor">
        		<option value="-1">select</option>
            	<?php
            		$division_result = mysql_query("SELECT * FROM division") or die(mysql_error());
					while($division_rows = mysql_fetch_array($division_result)){
				?>
              		<option value="<?php echo $division_rows["div_id"]; ?>"><?php echo $division_rows["div_name"]; ?></option><?php
					} ?>
			</select>
    	</td>
  	</tr>
  	<tr>
  		<td><font>Apply Date：</font></td>
    	<td><input type="text" name="datepicker1" id="datepicker1" size="8" value="<?php echo date('Y-m-d'); ?>"></td>
  	</tr>
  	<tr>        
    	<td><font>Request Date：</font></td>
    	<td><input type="text" name="datepicker2" id="datepicker2" size="8"></td>
  	</tr>
  	<tr>
    	<td><font>Sales：</font></td>
    	<td colspan="3">
    		<input type="radio" name="proj_div" value="div1">Sale 1</br>
        	<input type="radio" name="proj_div" value="div2">Sale 2</br>
        	<input type="radio" name="proj_div" value="div3">Sale 3</br>
        	<input type="radio" name="proj_div" value="div4">Sale 4</br>
        	<input type="radio" name="proj_div" value="div5">China</br>
        	<input type="radio" name="proj_div" value="div6">USA</br>
        	<input type="radio" name="proj_div" value="div7">Std</br>
        	<input type="radio" name="proj_div" value="div8">Internal
    	</td>
  	</tr>
  	<tr>
  		<td colspan="4">
    		<input type="button" value="ADD" onClick="validate()">
        	<input type="reset" value="RESET">
    	</td>
  	</tr>
  </table>
  </form>
  </aside>
  <table class="view_table" width="75%">
  <?php
	$projList_result = mysql_query("SELECT * FROM project_list") or die(mysql_error());
	$projList_num = mysql_num_rows($projList_result);
		
	if ($projList_num > 0){
		echo "<tr>
				<td>Type</td>
				<td>Project</td>
				<td>Requestor</td>
				<td>Apply Date</td>
				<td>Request Date</td>
				<td>Sales</td>
				<td>Edit</td>
			  </tr>";
			
		$i = 0;
		$j = 0;
		while($projList_row = mysql_fetch_array($projList_result)){
			$projList_id = $projList_row["projid"];
			$requestor = $projList_row["requestor"];
  ?>
  <script language="javascript">
    $(document).ready(function()
	{ 
 		$("#datepicker1<?php echo $i; ?>").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});  
	});
	
	$(document).ready(function()
	{ 
 		$("#datepicker2<?php echo $j; ?>").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});  
	});
		
	function DeleteProj(proj_id){
		if(confirm("請確認是否刪除此專案?"))
			location.href = "projAction.php?proj_id="+proj_id;
		}
  </script>    
  	<tr>
		<td align="center"><?php echo $projList_row["projtype"]; ?></td>
		<td align="center"><?php echo $projList_row["projname"];?></td>
  <?php
  $div_result = mysql_query("SELECT div_name FROM division WHERE div_id='$requestor'") or die(mysql_error());
  $div_name = mysql_result($div_result, 0, "div_name");
  ?>
		<td><?php echo $div_name; ?></td>
		<td><input type="text" value="<?php echo $projList_row["applydate"];?>" id="datepicker1<?php echo $i; ?>"></td>
		<td><input type="text" value="<?php echo $projList_row["requestdate"];?>" id="datepicker2<?php echo $j; ?>"></td>
		<td><?php echo $projList_row["projdiv"]; ?></td>
		<td><a href="#" onClick="DeleteProj(<?php echo $projList_id; ?>)">Delete</a></td>
	</tr><?php
		$i++;
		$j++;
		}
	} 
	else{
			echo "目前無專案";
		}
		
		@mysql_free_result($division_result);
		@mysql_free_result($projList_result);
		mysql_close($conn);
	?>
    </table>
  </div>
</div>
</body>
</html>