<?php
  require_once "db.php";
  
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
<html>
<head>
<meta charset="utf-8">
<title>Project List</title>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/PMS.css">
<script type="text/javascript" src="dist/html5shiv.js"></script>

<!--使導覽列出現向下或向右的箭頭圖案-->
<script type="text/javascript">
$(function()
{
	$("ul.navigation > li:has(ul) > a").append('<div class="arrow-bottom"></div>');
	$("ul.navigation > li ul li:has(ul) > a").append('<div class="arrow-right"></div>');
});
</script>
<style>
	#main_table{
		display:table;
		border:1px solid #F00;
		margin:30px auto;
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
    		<li><a href="proj_add.php" target="_blank"><span> Edit Project </span></a>
            <!--<input type="button" name="addPROJ_btn" id="addPROJ_btn" value="新增專案" onClick="view1()"> --></li>
    	</ul>	
  	</aside>
  	<table id="main_table" style="80%">
    <?php 
    	$proj_result = mysql_query("SELECT * FROM project_list ORDER BY projid ASC") or die(mysql_error()); 
		$proj_num = mysql_num_rows($proj_result);
		
		if($proj_num > 0){						
	?>
    <caption><span class="text_color">Project Info</span></caption>	
  		<thead>
        	<tr>
            	<td><span> NO </span></td>
                <td><span> Project Type </span></td>
  				<td><span> Project Name </span></td>
  				<td><span> Requestor </span></td>
  				<td><span> Apply Date </span></td>
                <td><span> Reauest Date </span></td>
                <td><span> Sale Div. </span></td>
                
    <?php 
		$x = 1;
		while($row = mysql_fetch_array($proj_result))
		{
			//取得專案編號、名稱
		  	$proj_id = $row["projid"];
		  	$proj_type = $row["projtype"];
		  	$proj_name = $row["projname"];
		  	$proj_requestor = $row["requestor"];
		  	$proj_applydate = $row["applydate"];
		  	$proj_requestdate = $row['requestdate'];
		  	$proj_div = $row["projdiv"];	
	?>
  			</tr>
        <thead>
  		<tbody>
    <?php
		echo "<tr>
				<td><span> $x </span></td>
				<td><span> $proj_type </span></td>
				<td><a href = 'proj_detail.php?projlist_id=$proj_id'><span> $proj_name </span></a></td>";
	
	$requestor_result = mysql_query("SELECT div_name FROM division WHERE div_id='$proj_requestor'") or die(mysql_error());
	$requestor_name = mysql_result($requestor_result, 0, "div_name");
		echo "<td><span> $requestor_name </span></td>
				<td><span> $proj_applydate </span></td>
				<td><span> $proj_requestdate </span></td>
				<td><span> $proj_div </span></td>
		   	</tr>";
		$x++;
		}
		
		mysql_free_result($proj_result);
		mysql_free_result($requestor_result);
		mysql_close($conn);
  		?>
  		</tbody>
    	<?php 
			}
			else{
				echo "目前沒有進行中的專案";
				} 
		?>    
  	</table>
  </div><!--container over-->  
</div><!--wrapper over-->
</body>
</html>