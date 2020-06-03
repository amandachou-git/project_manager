<?php
require_once('mysqli.php');

$sql = " SELECT id FROM news ";
$result = $mysqli->query($sql);
$numRows = $result->num_rows;  // 總筆數

echo ceil($numRows/10);  // 總頁數，一頁9筆
?>