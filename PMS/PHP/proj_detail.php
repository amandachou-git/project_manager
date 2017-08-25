<?php
  include("db.php");
  
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $log_usr = $_COOKIE["ACCOUNT"];
  $log_type = $_COOKIE["TYPE"];
  $passed = $_COOKIE["PASSED"];
  /*  如果 cookie 中的 passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 log_in.htm	*/
  if ($passed != "TRUE")
  {
    header("location:log_in.html");
    exit();
  }
?>
<!doctype html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>PMS demo</title>
<script type="text/javascript" src="dist/html5shiv.js"></script>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/PMS.css">
<style>
#main_table{
	display:table;
	border:1px solid #F00;
	margin:30px auto;
	}
caption{
	display:table-caption;
	}
tr{
	display:table-row;
	}
tbody{
	display:table-row-group;
	}
</style>
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
  	<aside>
    	<ul class="navBar">
    	<?php
		$proj_id = @$_GET['projlist_id']; 
	?>
	<!--使用URL的方式將projlist_id帶到detail_addJob.php-->
        	<li><a href='detail_addJob.php?projlist_id=<?php echo $proj_id; ?>' target='_blank'><span> Edit Job </span></a></li>
        </ul>
    </aside>
    <table id='main_table'>
    <?php 			    
	//取得專案名稱及專案管理者
  	$proj_result = mysql_query("SELECT projname FROM project_list WHERE projid = $proj_id") or die(mysql_error());
	$proj_name = mysql_result($proj_result, 0, "projname");
				
	//取得projrct detail table的數據資料
	$detail_result = mysql_query("SELECT * FROM project_detail WHERE projid = $proj_id") or die(mysql_error());
	//計算資料量
	$detail_num = mysql_num_rows($detail_result);
		
	//如果資料數量大於1，執行if條件式		
	if($detail_num > 0){ 
	?>
        <tr>
        	<td><span> Week：<?php echo date("W");?> </span>&nbsp;&nbsp;&nbsp;</td>	
           	<td colspan="9"><span><?php echo $proj_name;?>&nbsp;&nbsp;</span><span>/&nbsp;&nbsp;HW ready:</span></td>
        </tr>
        <tr>
        	<td><span> NO </span></td>
        	<td><span> Program </span></td>
           	<td><span> Subroutine </span></td>
            	<td><span> Owner </span></td>
            	<td><span> Verify </span></td>
            	<td><span> Est </span></td>	
            	<td><span> Ast </span></td>
            	<td><span> Status </span></td>
            	<td><span> Remark </span></td>
        </tr>
	<?php
		$x = 1;			
		while($row = mysql_fetch_array($detail_result))
		{		
			$detail_id = $row["detail_id"];	
			$job_owner = $row["detail_owner"];  
			$verify_owner = $row["detail_verify"];	
	?>						
    	<tr>
        	<td><span> <?php echo $x; ?> </span></td>
    <?php
    	$detail_program = $row["detail_program"];
	$detail_subroutine = $row["detail_subroutine"];
	
	//透過id找出相對應的名稱，並列印出來
	$program_result = mysql_query("SELECT program FROM task_type WHERE program_id=$detail_program");
	$program = @mysql_result($program_result, 0, "program");
    ?>
		<td><span> <?php echo $program; ?> </span></td>
		<td><span> <?php echo $detail_subroutine; ?> </span></td>
    <?php
	$owner_result = mysql_query("SELECT eng_name FROM member WHERE account=$job_owner") or die(mysql_error());
	$owner_name = mysql_fetch_array($owner_result);		 
    ?>
		<td><span> <?php echo $owner_name["eng_name"]; ?> </span></td>
    <?php
    	$verify_result = mysql_query("SELECT * FROM member WHERE account=$verify_owner") or die(mysql_error());
	$verify_name = mysql_fetch_array($verify_result);
    ?>
    		<td><span> <?php echo $verify_name["eng_name"]; ?> </span></td>
		<td><span> <?php echo $row["detail_est"]; ?> </span></td>
		<td><span> <?php echo $row["detail_ast"]; ?> </span></td>
		<td><span> <?php echo $row["detail_status"]; ?> </span></td>
		<td><span> <?php echo $row["detail_remark"]; ?> </span></td>
	</tr>
    <?php
		$x++;
		}
	}
	else{	
		echo "<span>目前無工作內容</span>";	
		}
				
	mysql_free_result($detail_result);
	mysql_close($conn);
    ?>
        </table>
	</div><!--container over-->	
</div><!--wrapper over-->	
</body>
</html>