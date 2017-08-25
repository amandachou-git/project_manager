<?php require_once "db.php"; ?>
<?php
  header("Content-type:text/html; charset=utf-8");
  
  if($_GET["type"]=="program")
  {
	  $new_prgoram = $_POST["input_type"];
	  $new_descriptor = $_POST["descriptor"];
	  
	  $programID_result = mysql_query("SELECT ifnull(max(program_id), 0)+1 AS program_id FROM task_type") or die(mysql_error());
	  $program_id = mysql_result($programID_result, 0, "program_id");
	  
	  $program_insert = "INSERT INTO task_type(program_id, program, descriptor) VALUES('$program_id', '$new_prgoram', '$new_descriptor')";
	  mysql_query('SET NAMES UTF8');
	  mysql_query($program_insert) or die(mysql_error());
	  
	  @mysql_free_result($programID_result);
	  mysql_close($conn);
	  
	  echo "<script type='text/javascript'>";
	  echo "opener.location.reload();";
	  echo "</script>";
  }
  else if($_GET["type"]=="item")
  {
	  $select_type = $_POST["select_type"];
	  $new_item = $_POST["input_item"];
	  
	  $itemID_result = mysql_query("SELECT ifnull(max(item_id),0)+1 AS item_id FROM task_item") or die(mysql_error());
	  $item_id = mysql_result($itemID_result, 0, "item_id");
	  
	  $item_insert = "INSERT IGNORE INTO task_item(item_id, subroutine, program_id) VALUES('$item_id', '$new_item', '$select_type')";
	  mysql_query('SET NAMES UTF8');
	  mysql_query($item_insert) or  die(mysql_error());
	  
	  mysql_free_result($itemID_result);
	  mysql_close($conn);
	  
	  echo "<script type='text/javascript'>";
	  echo "opener.location.reload();";
	  echo "</script>";
  }

  //回到前一頁
  header("Location: ".$_SERVER['HTTP_REFERER']);
?>