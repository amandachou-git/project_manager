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
	echo "</script>";
    header("Refresh:0; url= task_type.php");
    exit();
  }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Task</title>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/jquery-ui.min.css">
<script src="jQuery/jquery.min.js"></script>
<script src="jQuery/jquery-ui.min.js"></script>
<script type="text/javascript" src="dist/html5shiv.js"></script>
<script type="text/javascript">
function check_type_data()
{
	if(document.type_form.input_type.value == "")
	{
		alert("請勿空白");
	}
	else if(document.type_form.descriptor.value == "")
	{
		alert("請勿空白");
	}
	else
		type_form.submit();	
}

function check_item_data()
{
	if(document.item_form.select_type.value == "")
	{
		alert("choose one, thanks");
	}
	else if(document.item_form.input_item.value == "")
	{
		alert("請勿空白");
	}
	else
		item_form.submit();
}
</script>
<style>
.type_table{
	display:table;
	margin:30px;
	}
.item_table{
	display:table;
	margin:30px;
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
	width:auto;
	padding:3px;
	vertical-align: middle;
	border:2px solid #333;
	text-align: center;
	}
.view_td{
	display:table-cell;
	width: 300px;
	padding:6px;
	vertical-align: middle;
	border:2px solid #333;
	text-align: center;
	}
tbody{
	display:table-row-group;
	}
.underline{
	text-decoration:underline;
	}
</style>
</head>

<body>
<div class="wrapper">
  <div class="container">
  <aside>
  <!--Add new task type form-->
  <form action="taskAction.php?type=program" name="type_form" method="post">
  <table class="type_table">
    <tr>
      <td colspan="3">Add new type：</td>
    </tr>
    <tr>
      <td>Type name：</td>
      <td><input type="text" name="input_type" id="input_type" size="20"></td>
      <td rowspan="2"><input type="button" value="ADD" onClick="check_type_data()"></td>
    </tr>
    <tr>
      <td>Descriptor：</td>
      <td><textarea cols="20" rows="3" name="descriptor" id="descriptor"></textarea></td>
    </tr>
  </table>
  </form>
  <!--Add new task type form over-->
  <!--Add new task item form-->
  <form action="taskAction.php?type=item" name="item_form" method="post">
  <table class="item_table">
    <tr>
      <td colspan="3">Add new item</td>
    </tr>
    <tr>
      <td>Choose type：</td>
      <td>
        <select name="select_type">
          <option value="">select</option>
          <?php
			$type_result = mysql_query("SELECT * FROM task_type") or die(mysql_error());
			while($type_rows = mysql_fetch_array($type_result)){
		  ?>
           <option value="<?php echo $type_rows["program_id"]; ?>"><?php echo $type_rows["program"]; ?></option>
          <?php		
			}
          ?>
        </select>
      </td>
      <td rowspan="2"><input type="button" value="ADD" onClick="check_item_data()"></td>
    </tr>
    <tr>
      <td>Item name：</td>
      <td><input type="text" name="input_item" id="input_item" size="20"></td>
    </tr>
  </table>
  </form>
  <!--Add new task item form over-->  
  </aside>
  <table class="view_table">
    <tr>
      <td class="view_td">Type</td>
      <td class="view_td">Descript</td>
      <td class="view_td">Item</td>
    </tr>
    <!--select task type table-->
      <?php
	    $program_result = mysql_query("SELECT * FROM task_type") or die(mysql_error());
		while($program_rows = mysql_fetch_array($program_result))
		{
		  $program_id = $program_rows["program_id"];
	  ?>
      <script>
	    function DeleteType(program_id)
		{
			if(confirm("請確認是否要刪除此類別，且其中所有項目都會被刪除"))
				location.href = "deleteTypeAction.php?program_id="+program_id;
		}
	  </script>
    <tr>
      <td class="view_td"><span class="underline"><a href="#" onClick="DeleteType(<?php echo $program_id; ?>)"><?php echo $program_rows["program"]; ?></a></span></td>
      <td class="view_td"><span><?php echo $program_rows["descriptor"]; ?></span></td>
      <!--select task item table-->
      <td class="view_td">
      <?php
	    $subroutine_result = mysql_query("SELECT * FROM task_item WHERE program_id = $program_id") or die(mysql_error());
		while($subroutine_rows = mysql_fetch_array($subroutine_result))
		{
			$item_id = $subroutine_rows["item_id"];
	  ?>
      <script type="text/javascript">
	    function DeleteItem(program_id, item_id)
		{
			if(confirm("請確認是否刪除此項目"))
				location.href = "deleteItemAction.php?program_id="+program_id+"&item_id="+item_id;
		}
      </script>
        <span class="underline"><a href="#" onClick="DeleteItem(<?php echo $program_id; ?>, <?php echo $item_id; ?>)"><?php echo $subroutine_rows["subroutine"]; ?></a></span></br>
      <?php
		}
      ?>
      </td>
    </tr>
      <?php
		}
		//release 被占用的記憶體
		@mysql_free_result($type_result);
	    @mysql_free_result($program_result);
		@mysql_free_result($subroutine_result);
		
		//close database link
		mysql_close($conn);
      ?>
  </table>
  </div><!--container over-->
</div><!--wrapper over-->
</body>
</html>