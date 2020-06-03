<?php
require_once('mysqli.php');

$page = $_POST["page"];
$json = array();

$num = 10;  //每頁顯示幾筆
$jump = ($page-1)*$num;  //跳過幾筆

//LIMIT：兩個參數, (1)跳過幾筆 (2)取多少筆
$sql = " SELECT * FROM news LIMIT ".$num." OFFSET ".$jump;
$result = $mysqli->query($sql);

if (!$result)
{
    echo "Invalid : ".$mysqli->errno."/".$mysqli->error."<br>";
}
else
{
    //解開, 包成json format
    while($row = mysqli_fetch_array($result))
    {
        $json[] = array(
            "id"         => $row['id'],
            "title"      => $row['title'],
            "created"    => $row['created'],
            "author"     => $row['author'],
            "like_total" => $row['like_total'],
            "pic"        => $row['pic'],
            "article"    => $row['article']
        );
    }
}

$jsonString = json_encode($json);

echo $jsonString;
?>