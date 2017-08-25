<?php
  include_once('db.php');
  
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $log_usr = $_COOKIE["ACCOUNT"];
  $log_type = $_COOKIE["TYPE"];
  $passed = $_COOKIE["PASSED"];
  /*  如果 cookie 中的 passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 log_in.htm	*/
  if ($log_type != "1" || $passed != "TRUE")
  {
	echo "<script type='text/javascript'>";
	echo "alert('施工中');";
	echo "window.close();";
	echo "</script>";
    exit();
  }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Overview</title>
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
  <div class="selector">
  <tr>
  	<td><span>Year：</span></td>
    <td>
  	<select>
        <option value="">select</option>
    <?php 
		$year_result = mysql_query("SELECT DATE_FORMAT(day_date, '%Y') FROM daily_report GROUP BY DATE_FORMAT(day_date, '%Y')") or die(mysql_error());
		while($year_rows = mysql_fetch_array($year_result)){
			$year = $year_rows["DATE_FORMAT(day_date, '%Y')"];
	?>
    	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
    <?php
		}
    ?>
    </select>
    </td>
    <td><span>Month：</span></td>
    <td>
      <select>
    	  <option value="">select</option>
    	  <option value="1"><span>1</span></option>
          <option value="2"><span>2</span></option>
          <option value="3"><span>3</span></option>
          <option value="4"><span>4</span></option>
          <option value="5"><span>5</span></option>
          <option value="6"><span>6</span></option>
          <option value="7"><span>7</span></option>
          <option value="8"><span>8</span></option>
          <option value="9"><span>9</span></option>
          <option value="10"><span>10</span></option>
          <option value="11"><span>11</span></option>
          <option value="12"><span>12</span></option>
      </select>
    </td>
  </tr> 
  </div>
  <div class="view">
  </div><!-- view over -->
</div><!--container over-->
</div><!--wrapper over-->
</body>
</html>