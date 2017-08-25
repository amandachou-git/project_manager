<?php
  require_once "db.php";
  
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $log_usr = @$_COOKIE["ACCOUNT"];
  $log_type = @$_COOKIE["TYPE"];
  $passed = @$_COOKIE["PASSED"];
	
  /*  如果 cookie 中的 passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 log_in.htm	*/
  if ($passed != "TRUE")
  {
    header("location:log_in.html");
    exit();
  }
?>
<script type="text/javascript" src="dist/html5shiv.js"></script>
<script type="text/javascript" src="Javascript/report_day_modify_validate.js"></script>
<script type="text/javascript">
    $(document).ready(function()
	{ 
 		$("#datepicker1<?php echo $i; ?>").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});  
	});
</script>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Report</title>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/PMS.css">
<link rel="stylesheet" type="text/css" href="CSS/report_day.css">
<link rel="stylesheet" type="text/css" href="CSS/jquery-ui.min.css">
</head>
<body>
<div class="wrapper">
<div class="header">
  	<div class="nav_horizontal">
    	<ul class="navigation">
			<li><a href="report_day.php"><span class="nav_text"> Daily </span></a></li>
      		<li><a href="task_type.php"><span class="nav_text"> Task Type </span></a></li>
      		<li><a href="proj_list.php"><span class="nav_text"> Project List </span></a></li>	
	  		<li><a href="member.php"><span class="nav_text"> Member </span></a></li>
            <li><a href="#"><span class="nav_text"> Report </span></a>
                <ul>
                    <li><a><span class="nav_text">Division</span></a></li>
                    <li><a><span class="nav_text">Department I</a></span></li>
                    <li><a><span class="nav_text">Department II</span></a></li>
                </ul>
            </li>
            <li><a href=""><span class="nav_text">Analysis</span></a></li>
      		<li><a href="log_out.php"><span class="nav_text"> Log out<?php echo $log_usr; ?> </span></a></li>
		</ul><!--navigation over-->	
  	</div><!--nav_horizontal over-->
</div><!--header over-->
<div class="container">
<form action="report_day_action.php" name="report_day" method="post">
<table id="dailyTable">
<?php 
if(isset($_GET['history_id'])){
	$history_id = $_GET['history_id'];
	}
	
	$day_report_result = mysql_query("SELECT * FROM daily_report WHERE day_id=$history_id") or die(mysql_error());
	$day_report_rows = mysql_fetch_array($day_report_result);
	
	$day_id = $day_report_rows["day_id"];
	$project_id = $day_report_rows["day_project"];
	$program_id = $day_report_rows["day_program"];
	$items = $day_report_rows["day_subroutine"];
	$job_type_id = $day_report_rows["day_type"];
	$status = $day_report_rows["day_status"];
	$remark = $day_report_rows["day_remark"];
	$cost = $day_report_rows["day_cost"];
	$request = $day_report_rows["day_request"];
	?>
<caption><span class="text_color">Daily Report<input type="hidden" name="day_id" value="<?php echo $day_id; ?>"></span></caption>		
<tbody  class='daily_tbody'>
  <tr>
    <td><span class="text_color">Date</span></td>
    <td><input type='text' name='datepicker1' id='datepicker1' value="<?php echo $day_report_rows["day_date"]; ?>" size='12'></td>
  </tr>
  <tr>
    <td><span class="text_color">Other units request?</span></td>
    <td>
    	<input type="radio" name="request" value="1"<?php if (!(strcmp($request, '1'))){echo 'checked="checked"'; }?>>
        <span class="text_color">YES</span>
        <input type="radio" name="request" value="0"<?php if (!(strcmp($request, '0'))){echo 'checked="checked"'; }?>>
        <span class="text_color">NO</span>
    </td>
  </tr>
  <tr>
    <td><span class="text_color">Project</span></td>
	<td>
<?php
    $project_name_result = mysql_query("SELECT projname FROM project_list WHERE projid=$project_id") or die(mysql_error());
	$project_name = mysql_fetch_array($project_name_result);
?>
	<select name="project" id="project">
      <option value="<?php echo $project = $day_report_rows["day_project"]; ?>"><?php echo $project_name["projname"]; ?></option>
<?php 
	$project_result = mysql_query("SELECT project_list.projid, project_list.projname, project_detail.detail_owner FROM project_detail INNER JOIN project_list ON project_list.projid=project_detail.projid WHERE project_detail.detail_owner='$log_usr' GROUP BY projname ORDER BY projid ASC") or die(mysql_error());
	
	while($project_rows = mysql_fetch_array($project_result)){	?>
	  <option value="<?php echo $project_rows['projid']; ?>"><?php echo $project_rows['projname']; ?></option><?php
      } ?>
	</select>
	</td>
  <tr>
    <td rowspan="2"><span class="text_color">Program</span></td>					
	<td>
<?php
	$day_programName = mysql_query("SELECT program FROM task_type WHERE program_id=$program_id") or die(mysql_error()); 
	$day_programName_rows = mysql_fetch_array($day_programName);
?>
	<select name="program" id="program">
		<option value="<?php echo $program_id; ?>"><?php echo $day_programName_rows["program"]; ?></option>
<?php	
	$program_result = mysql_query("SELECT program_id, program FROM task_type") or die(mysql_error());
	while($program_rows = mysql_fetch_array($program_result)){	?>
        <option value="<?php echo $program_rows["program_id"];	?>"><?php echo $program_rows["program"]; ?></option><?php				
	} ?>    
	</select>
	</td>
  </tr>
  <tr>
	<td>
	<div id="checkbox_list">
<?php 
	$items_array = explode(",", $items);
	$subroutine_result = mysql_query("SELECT subroutine FROM task_item WHERE program_id=$program_id") or die(mysql_error());
	while($subroutine_rows = mysql_fetch_array($subroutine_result))
	{
		$subroutine = $subroutine_rows["subroutine"];
?>
    		<input type="checkbox" name="item_list[]" id="item_list[]" value="<?php echo $subroutine; ?>"<?php if(in_array($subroutine, $items_array)){ echo "checked"; }?>><span><?php echo $subroutine; ?></span>
<?php
	}
?>
    	</div>
	</td>
  </tr>
  <tr>
    <td><span class="text_color">Type</span></td>
    <td>
<?php 
	$job_name_result = mysql_query("SELECT job FROM job_type WHERE job_id=$job_type_id") or die(mysql_error());
	$job_name_rows = mysql_fetch_array($job_name_result);
?>
    <select name="job_type" id="job_type">	
		<option value="<?php echo $job_type_id; ?>"><?php echo $job_name_rows["job"]; ?></option>
<?php 
	$job_result = mysql_query("SELECT job_id, job FROM job_type") or die(mysql_error());
	while($job_rows = mysql_fetch_array($job_result)){	?>
    	<option value="<?php echo $job_rows["job_id"]; ?>"><?php echo $job_rows["job"]; ?></option><?php	
		} ?>
	</select>
    </td>
  </tr>
  <tr>
    <td><span class="text_color">Status</span></td>
    <td>
    	<input type="radio"  name="status" value="ongoing"<?php if (!(strcmp($status, 'ongoing'))){echo 'checked="checked"'; }?>>	
        <span>On going</span>
        <input type="radio" name="status" value="pending"<?php if (!(strcmp($status, 'pending'))){echo 'checked="checked"'; }?>>
        <span>Pending</span>
        <input type="radio" name="status" value="finish"<?php if (!(strcmp($status, 'finish'))){echo 'checked="checked"'; }?>>
        <span>Finish</span>
	</td>
  </tr>
  <tr>
    <td><span class="text_color">Remark</span></td>
    <td><textarea name="remark" id="remark" cols="45" rows="2"><?php echo $remark; ?></textarea></td>
  </tr>
  <tr>
    <td><span class="text_color">Cost Time</span></td>
    <td><input type="text" name="cost" id="cost" size="3" value="<?php echo $cost; ?>"></td>
  </tr>
  <tr>
    <td colspan="2">
    	<input type="button" value="UPDATE" onClick="validate()">
        <input type="button" value="CLOSE" onClick="window.close();">
	</td>
  </tr>
</tbody>
</table>
</form>
</div><!--container over-->
</div><!--wrapper over-->
<script type="text/javascript" src="jQuery/jquery.min.js"></script>
<script type="text/javascript" src="jQuery/jquery-ui.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function()
{ 
 	$("#datepicker1").datepicker({dateFormat:"yy-mm-dd",changeMonth: true,});
});
	
$(document).ready(function(){
	$('#program').on('change',function(){
		
		var programID = $(this).val();
	
		if(programID)
		{
    		$.ajax
			({
    			type:'GET',
    			url:'ajaxAction.php',
    			data:{'id':$(this).val()},
			dataType:'html',
    			success:function(html){	$('#checkbox_list').html(html); }
    		}); 
    	}
		else{ $('#checkbox_list').html('<span>select program first</span>'); }
    });
}); 
</script>
</body>
</html>