<?php
  require_once "db.php";
  
  //檢查 cookie 中的 passed 參數是否等於 TRUE
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
  //取當天的系統日期
  $today = date('Y-m-d');
?>
<!doctype html>
<script type="text/javascript" src="dist/html5shiv.js"></script>
<script type="text/javascript" src="Javascript/report_day_validate.js"></script>
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
                <li><a href="#"><span class="nav_text">Analysis</span></a></li>
      			<li><a href="log_out.php"><span class="nav_text"> Log out<?php echo $log_usr; ?> </span></a></li>
			</ul><!--navigation over-->	
  		</div><!--nav_horizontal over-->
  	</div><!--header over-->
    <div class="container">
    <aside>
    <a href="report_day_overview.php" target="_blank"><span> [ Overview ] </span></a>
    <?php
	//篩選出使用者的當月的數據, key: date_formate(date, format): date參數須為合法數值。format在規定日期/時間的輸出格式
	$reportDay_result = mysql_query("SELECT * FROM daily_report WHERE date_format(day_date, '%m')=date_format(NOW(), '%m') AND account='$log_usr' ORDER BY day_date ASC") or die(mysql_error());
	
	$reportDay_num = mysql_num_rows($reportDay_result);	
	if($reportDay_num > 0)
	{
		$x = 1;
		while($reportDay_rows = mysql_fetch_array($reportDay_result))
		{
			$daily_history_id = $reportDay_rows["day_id"];
			$daily_project_name = $reportDay_rows["day_project"];
			
			$project_data_result = mysql_query("SELECT projname FROM project_list WHERE projid=$daily_project_name");
			$project_data_rows = mysql_fetch_array($project_data_result);
	?>
    <ul>
      <li><span><?php echo $reportDay_rows["day_date"]; ?></span>
        <ul>
          <li>
          <span>(<?php echo $x; ?>)
          	<a href='report_day_modify.php?history_id=<?php echo $daily_history_id; ?>' target='_blank'>
            <span><?php echo  $project_data_rows["projname"]; ?></span>
            </a>
          </span>
          </li>
        </ul>
      </li>
    </ul><?php			
		$x++;
		}
	}
	?>
    </aside>
    <form action="report_day_action.php?type=add" name="report_day" method="post">
    <table id="dailyTable">
    <?php
	//合併project_list跟project_detail, 篩選projid, projname, detail_owner與detail_verify欄位，篩選條件是FK: projid。GROUP BY用途: 當select出來的資料有重複的值，則只列印一次。ORDER BY用途: 排序, 可搭配DESC(由大到小)跟ASC(由小到大)使用，default為ASC
    	$project_result = mysql_query("SELECT project_list.projid, project_list.projname, project_detail.detail_owner, project_detail.detail_verify FROM project_detail INNER JOIN project_list ON project_list.projid=project_detail.projid WHERE project_detail.detail_owner=$log_usr OR project_detail.detail_verify=$log_usr GROUP BY projname ORDER BY projid ASC") or die(mysql_error());
	
    	$project_num = mysql_num_rows($project_result);
	if($project_num > 0){
    ?>
    <caption><span class="text_color">Daily Report</span></caption>		
    <tbody  class='daily_tbody'>
    	<tr>
            <td><span class="text_color">Date</span></td>
            <td><input type='text' name='datepicker1' id='datepicker1' value="<?php echo $today; ?>" size='12'></td>
        </tr>
        <tr>
            <td><span class="text_color">Other units request?</span></td>
            <td>
            	<input type="radio" name="request" value="1"><span class="text_color">YES</span>
                <input type="radio" name="request" value="0"><span class="text_color">NO</span>
            </td>
        </tr>
	<tr>
            <td><span class="text_color">Project</span></td>
	    <td>
		<select name="project" id="project">
            		<option value="-1">select</option><?php 
				while($project_rows = mysql_fetch_array($project_result))
				{ ?>
					<option value="<?php echo $project_rows['projid']; ?>"><?php echo $project_rows['projname']; ?></option><?php
          			} ?>
		</select>
	    </td>
	 <tr>
            <td rowspan="2"><span class="text_color">Program</span></td>					
	    <td>
		<select name="program" id="program">
			<option value="">select</option><?php
				$program_result = mysql_query("SELECT program_id, program FROM task_type") or die(mysql_error());	
				while($program_rows = mysql_fetch_array($program_result))
				{ ?>
        				<option value="<?php echo $program_rows["program_id"];	?>"><?php echo $program_rows["program"]; ?></option><?php
				} ?>    
		</select>
	    </td>
        </tr>
        <tr>
	    <td>
		<div id="checkbox_list"><span>select program first</span></div>
	    </td>
        </tr>
        <tr>
            <td><span class="text_color">Type</span></td>
	    <td>
            	<select name="job_type" id="job_type">	
			<option value="-1">select</option>
			<?php 
			$job_result = mysql_query("SELECT job_id, job FROM job_type") or die(mysql_error());
			while($job_rows = mysql_fetch_array($job_result))
			{ 
			?>
    			<option value="<?php echo $job_rows["job_id"]; ?>"><?php echo $job_rows["job"]; ?></option>
			<?php	
			} 
			?>
		</select>
            </td>
        </tr>
        <tr>
            <td><span class="text_color">Status</span></td>
            <td>
                <input type="radio"  name="status" value="ongoing"><span>On going</span>
                <input type="radio" name="status" value="pending"><span>Pending</span>
                <input type="radio" name="status" value="finish"><span>Finish</span>
            </td>
        </tr>
        <tr>
            <td><span class="text_color">Remark</span></td>
            <td><textarea name="remark" id="remark" cols="45" rows="2"></textarea></td>
        </tr>
        <tr>
            <td><span class="text_color">Cost Time</span></td>
            <td>
            	<input type="text" name="cost" id="cost" size="15" placeholder="e.g., 1 or 1.5 or 1.25">
                <span class="text_color">(hr)</span>
            </td>
	</tr>
	<tr>
            <td colspan="2">
            	<input type="button" value="ADD" onClick="validate()">
                <input type="reset" value="RESET">
            </td>
        </tr><?php 
	 }
	 else{	echo "<span>恭喜你，目前沒有工作</span>";	}	
		
	@mysql_free_result($project_result);
	@mysql_free_result($program_result);
	@mysql_free_result($job_result);
	mysql_close($conn);
	?>
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
	else{ $('#checkbox_list').html('<span>select program first</span>'); }
    	});
}); 
</script>
</body>
</html>