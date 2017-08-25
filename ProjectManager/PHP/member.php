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
<title>Member</title>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/PMS.css">
<script type="text/javascript" src="dist/html5shiv.js"></script>

<!--使導覽列出現向下或向右的箭頭圖案-->
<script type="text/javascript">
$(function(){
	$("ul.navigation > li:has(ul) > a").append('<div class="arrow-bottom"></div>');
	$("ul.navigation > li ul li:has(ul) > a").append('<div class="arrow-right"></div>');
			});
</script>
<style>
	table{
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
  <nav>
  	<ul class="navBar">
    	<li><a href="#"><span> Edit Member </span></a></li>
    </ul>
  </nav>
  </aside>
  
  <table>
  <caption><span>Member Info</span></caption>
  <?php
  	//select會員資料
    $member_result = mysql_query("SELECT * FROM member ORDER BY account") or die(mysql_error()); 
	
	//取得共有幾筆紀錄
    $num = mysql_num_rows($member_result);
	
	if($num > 0){
  ?>
    <tr>
    	<td><span> NO </span></td>
  		<td><span> ID </span></td>
  		<!--<td><span> Department </span></td>-->
  		<td><span> Chinese Name </span></td>
        <td><span> English Name </span></td>
  		<td><span> E-mail </span></td>
  	</tr>
  <?php	
  	$x = 1;	
	while($row = mysql_fetch_assoc($member_result)){
		$deptName_
  ?>
  	<tr>
    	<td><span><?php echo $x; ?></span></td>
  		<td><span><?php echo $row["account"]; ?></span></td>
  		<!--<td><span><?php //echo $row["department"]; ?></span></td>-->
  		<td><span><?php echo $row["chi_name"]; ?></span></td>
  		<td><span><?php echo $row["eng_name"]; ?></span></td>
  		<td><span><?php echo $row["email"]; ?></span></td>
  	</tr>
  <?php   
  	$x++;  
		}
	}
	else{	echo "請新增會員資料";		}
	
	mysql_free_result($member_result);
	mysql_close($conn);
	
  ?>
  </table>
  </div><!--container over-->
</div><!--wrapper over-->
</body>
</html>