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
<title>Task Type</title>
<link rel="stylesheet" type="text/css" href="CSS/reset.css">
<link rel="stylesheet" type="text/css" href="CSS/PMS.css">
<script type="text/javascript" src="dist/html5shiv.js"></script>

<script type="text/javascript">
$(function(){
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
	caption{
		display:table-caption;
		}
	tr{
		display:table-row;
		border:1px solid #000;
		}
	td{
		display:table-cell;
		width:300px;
		padding:4px;
		vertical-align: middle;
		border:3px solid #000;
		text-align: center;
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
      			<li><a href="../log_out.php"><span class="nav_text"> Log out<?php echo $log_usr; ?> </span></a></li>
			</ul><!--navigation over-->
  		</div><!--nav_horizontal over-->
    </div><!--header over-->
    <div class="container">
    <aside>
    	<nav>
        	<ul class="navBar">
            	<li><a href="task_add.php" target="_blank"><span>Edit Task</span></a></li>
            </ul>
        </nav>
    </aside>
    <table id="main_table" style="80%">
    <caption><span>Task Info</span></caption>
  		<thead>
        	<tr>
            	<td><span>NO.</span></td>
  				<td><span>Type</span></td>
                <td><span>Descriptor</span></td>
  				<td><span>Content</span></td>
  			</tr>
        <thead>
  		<tbody>
        <?php
		//執行sql查詢，參數是sql指令字串的變數，可傳回符合條件的紀錄集合資源
		$type_result = mysql_query("SELECT * FROM task_type ORDER BY program ASC") or die(mysql_error());
		//取得紀錄筆數
        $num = mysql_num_rows($type_result);	
        if($num > 0)
		{	
			$i = 1;	
        	while($row = mysql_fetch_array($type_result))
			{
				$program_id = $row["program_id"];
  		?>                
  			<tr>
            	<td><span><strong><?php echo $i; ?></strong></span></td>
  				<td><span><strong><?php echo $row["program"]; ?></strong></span></td>
  				<td><span><strong><?php echo $row["descriptor"]; ?></strong></span></td>
                <td>
        	<?php
        		$item_result = mysql_query("SELECT * FROM task_item WHERE program_id=$program_id") or die(mysql_error());
				$j = 1;
				while($item_rows = mysql_fetch_array($item_result))
				{
			?>
        		<!--<td><span><?php //echo $j; ?></span></td>-->
                  <span><?php echo $j; ?>-<?php echo $item_rows["subroutine"]; ?></span></br>
        	<?php	
				$j++;
				}
			?>
              </td>
  			</tr>
		<?php
			$i++;
			}
        }
		//release 被占用的記憶體
		@mysql_free_result($type_result);
		@mysql_free_result($item_result);
		
		//close database link
		mysql_close($conn);
  		?>
  		</tbody>
  </table>
  </div><!--container over-->
</div><!--wrapper over-->
</body>
</html>