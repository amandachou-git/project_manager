<?php require_once "db.php"; ?>
<?php
header("Content-type:text/html; charset=utf-8");

//如果type等於add, 則執行"新增數據到database"區塊		
if($_GET["type"]=="add"){
	$log_usr = $_COOKIE["ACCOUNT"];	
	$date = $_POST["datepicker1"];
	switch($_POST["request"])
	{
		case "1":
			$request = "1";
			break;
		case "0":
			$request = "0";
			break;
	}
	$project = $_POST["project"];
	$program = $_POST["program"];
	
	$subroutine = $_POST["item_list"];//checkbox values
	$items = implode(",", $subroutine);// use implode() store multiple checkbox values
	
	$job_type = $_POST["job_type"];
	
	switch($_POST["status"])
	{
		case "ongoing":
			$status = "ongoing";
			break;
		case "pending":
			$status = "pending";
			break;
		case "finish":
			$status = "finish";
			break;
	}
	
	$remark = $_POST["remark"];
	$cost = $_POST["cost"];
			
	$dayID_result = mysql_query("SELECT ifnull(max(day_id), 0) + 1 AS day_id FROM daily_report") or die(mysql_error());
	$day_id = mysql_result($dayID_result, 0, "day_id");
		
	$insert_reportDay = "INSERT IGNORE INTO daily_report(day_id, day_project, day_program, day_subroutine, day_type, day_status, day_remark, day_cost, day_request, day_date, account) VALUES('$day_id', '$project', '$program', '$items', '$job_type', '$status', '$remark', '$cost', '$request', '$date', '$log_usr')";
	mysql_query('SET NAMES UTF8'); 	
	mysql_query($insert_reportDay) or die(mysql_error());
	
	mysql_free_result($dayID_result);
	mysql_close($conn);
	
	header("Location: ".$_SERVER['HTTP_REFERER']);
	//新增數據到資料庫-----over
}//更新所修改的資料數據
else{	
	$day_id = $_POST["day_id"];
	
	$date = $_POST["datepicker1"];
	switch($_POST["request"])
	{
		case "1":
			$request = "1";
			break;
		case "0":
			$request = "0";
			break;
	}
	
	$project = $_POST["project"];
	$program = $_POST["program"];
	$subroutine = $_POST["subroutine"];
	$job_type = $_POST["job_type"];
	
	switch($_POST["status"])
	{
		case "ongoing":
			$status = "ongoing";
			break;
		case "pending":
			$status = "pending";
			break;
		case "finish":
			$status = "finish";
			break;
	}
	
	$remark = $_POST["remark"];
	$cost = $_POST["cost"];
		
	$update_report_day ="UPDATE daily_report SET day_project='$project', day_program='$program', day_subroutine='$subroutine', day_type='$job_type', day_status='$status', day_remark='$remark', day_cost='$cost', day_request='$request', day_date='$date' WHERE day_id='$day_id'";
	mysql_query('SET NAMES UTF8'); 	
	mysql_query($update_report_day) or die(mysql_error());	
	
	//用 mysqli 才會work, why?	
	//mysql_free_result($update_report_day);
	mysql_close($conn);
		
	echo "<script type='text/javascript'>";
	echo "alert('Updating Successful!');";
	echo "opener.location.reload();";//重新整理父視窗(report_day.php)
	echo "window.close();";//close 子視窗(report_day_modify.php)
	echo "</script>";
	}
//for delete() else{}
?>