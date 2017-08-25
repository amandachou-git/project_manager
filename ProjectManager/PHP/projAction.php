<?php require_once "db.php"; ?>
<?php
  header("Content-type:text/html; charset=utf-8");
  
  if($_GET['type']=='add')
  {  
	$log_usr = $_COOKIE["ACCOUNT"];
	
	//資料寫進資料庫
    switch($_POST["proj_type"])
    {
	  case "type1":
	    $proj_type = "Gaming";
		break;
	  case "type2":
	    $proj_type = "Embedded";
		break;
	  case "type3":
	    $proj_type = "Networking";
		break;
	  case "type4":
	    $proj_type = "COMe";
		break;
	  case "type5":
	  	$proj_type = "Others";
		break;
    }
	
    $proj_name = $_POST["proj_name"];
    $requestor = $_POST["requestor"];
    $apply_date = $_POST["datepicker1"];
    $request_date = $_POST["datepicker2"];
	
	switch($_POST["proj_div"])
    {
	  case "div1":
	    $proj_div = "Sale1";
		break;
	  case "div2":
	    $proj_div = "Sale2";
		break;
	  case "div3":
	    $proj_div = "Sale3";
		break;
	  case "div4":
	  	$proj_div = "Sale4";
		break;
	  case "div5":
	  	$proj_div = "China";
		break;
	  case "div6":
	  	$proj_div = "USA";
		break;
	  case "div7":
	  	$proj_div = "Std";
		break;
	  case "div8":
	  	$proj_div = "Internal";
		break;
    }
	//select id欄位的最大值，如果欄位有數據，則找出最大值並加1。如果沒有，則直接設定為1
    $result_projid = mysql_query("SELECT ifnull(max(projid), 0) + 1 AS projid FROM project_list") or die(mysql_error());
	//使用變數$proj_id代表select後的結果
    $proj_id = mysql_result($result_projid, 0, "projid");
  
    //將新資料新增至table
    $insert_proj = "INSERT IGNORE INTO project_list(projid, projtype, projname, requestor, applydate, requestdate, projdiv, projmgr) VALUES('$proj_id', '$proj_type', '$proj_name', '$requestor', '$apply_date', '$request_date', '$proj_div', '$log_usr')";
	//
    mysql_query('SET NAMES UTF8'); 
  
    $result_insert = mysql_query($insert_proj) or die("無法新增".mysql_error());
	
	mysql_free_result($result_projid);
	mysql_free_result($result_insert);
    mysql_close($conn);
  }
  else{
  		$log_usr = @$_COOKIE["ACCOUNT"];
  		$log_type = @$_COOKIE["TYPE"];
  		$passed = @$_COOKIE["PASSED"];

  		$proj_id = $_GET["proj_id"];
  
  		$delJob_result = mysql_query("DELETE FROM project_detail WHERE projid='$proj_id' AND EXISTS(SELECT * FROM project_list WHERE projid='$proj_id' AND projmgr='$log_usr')") or die(mysql_error());
  		$delProject_result = mysql_query("DELETE FROM project_list WHERE projid='$proj_id' AND projmgr='$log_usr'") or die(mysql_error());

  		mysql_free_result($delJob_result);	
  		mysql_free_result($delProject_result);
  		mysql_close($conn);
	  }
	  //回到前一頁
	header("Location: ".$_SERVER['HTTP_REFERER']);
?>

