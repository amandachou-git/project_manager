<?php require_once "db.php"; ?>
<?php	
header("Content-type:text/html; charset=utf-8");
	
if($_GET['type']=='add')
{
	//搜尋最大的 detail_id值, 如果有值, 則最大值加1; 如果沒有值, 則0+1
    $result_detailID = mysql_query("SELECT ifnull(max(detail_id), 0) + 1 AS detail_id FROM project_detail");
    $detail_id = mysql_result($result_detailID, 0, "detail_id");
	
	$program = $_POST["program"];
	
	$subroutine_list = $_POST["item_list"];//checkbox values
	$items = implode(",", $subroutine_list);// use implode() store multiple checkbox values 
	
	$owner = $_POST["owner"];
	
	$verify_engineer = $_POST["verify"];//the logic is same with $items
	$DQE = implode(",", $verify_engineer);
	
	$proj_id = $_POST["proj_id"]; 
		
	$sql = mysql_query("INSERT IGNORE INTO project_detail(detail_id, detail_program, detail_subroutine, detail_owner, detail_verify, projid) VALUES('$detail_id','$program', '$items', '$owner', '$DQE', '$proj_id')") or die(mysql_error());
	
	@mysql_free_result($result_detailID);
	mysql_close($conn);
}
else{
		$log_usr = @$_COOKIE["ACCOUNT"];
  		$log_type = @$_COOKIE["TYPE"];
  		$passed = @$_COOKIE["PASSED"];

  		$proj_id = $_GET["proj_id"];
  		$detail_id = $_GET["detail_id"];
  
  		$delete_job_result = mysql_query("DELETE FROM project_detail WHERE detail_id=$detail_id AND EXISTS(SELECT * FROM project_list WHERE projid=$proj_id AND projmgr=$log_usr)", $conn) or die(mysql_error());
		
		mysql_free_result($delete_job_result);
  		mysql_close($conn); 
	}
	
header("Location: ".$_SERVER['HTTP_REFERER']);
?>