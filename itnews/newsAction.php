<?php
require_once('mysqli.php');

$url  = 'https://www.ithome.com.tw/news';
$html = getHtmlContent($url);

//取得分頁連結，從第2頁開始到第10頁
preg_match_all('/\?page=(.*)">[1-9]/', $html, $pageArray);

//開始取資料並新增到資料庫
for ($page=0;$page<=count($pageArray[1]);$page++)
{
    // 取分頁的source
    $pageUrl  = ($page == 0)? $url : $url."?page=".$page;
    $pattern  = '/class="title"><a href="\/news\/(\d{6})"/';
    $pageHtml = getHtmlContent($pageUrl);

    //取分頁中每一篇新聞的URL
    preg_match_all($pattern, $pageHtml, $newsArray);

    for ($x=0; $x<=count($newsArray[1]); $x++)
    {
        // 取單篇新聞的source
        $newsID   = isset($newsArray[1][$x])?$newsArray[1][$x]:NULL;
        $newsUrl  = $url."/".$newsID;
        $newsData = getNewsContent($newsUrl, $newsID);

        // 檢查DB是否已存在資料
        $sql_id   = " SELECT id, like_total FROM news WHERE id = ".$newsID;

        if (!$result = mysqli_query($mysqli, $sql_id))
        {
            // 若 query 錯誤
            echo "error no : ".$mysqli->errno."\n";
            echo "error : ".$mysqli->error."\n";
        }
        else
        {
            // 若 query 沒問題
            if ($result->num_rows > 0)
            {
                // 若新聞已存在，解開 result。
                $row = mysqli_fetch_array($result);
        
                // 比對like_total是否相同，不同才做 update
                if ($row['like_total'] != $newsData['like_total'])
                {
                    $sql_update = " UPDATE news SET like_total = '".$newsData['like_total']."' WHERE id = ".$newsID;

                    $result_update = $mysqli->query($sql_update);
                    mysqli_free_result($result_update);
                }
                else
                {
                    break; // 相同就跳出迴圈
                }
            }
            else
            {
                $newsData['article'] = mysqli_real_escape_string($mysqli, $newsData['article']);

                $sql_insert = "INSERT IGNORE INTO news (id, title, created, author, like_total ,pic ,article)" ;
                $sql_insert = " VALUES ('".$newsID."','".$newsData['title']."','".$newsData['created']."' " ;
                $sql_insert = ",'".$newsData['author']."','".$newsData['like_total']."','".$newsData['pic']."' " ;
                $sql_insert = ",'".$newsData['article']."') " ;

                $result_insert = $mysqli->query($sql_insert);
                mysqli_free_result($result_insert);
            }
        }

        mysqli_free_result($result);
    }
}

header("Location:News.php");

function getNewsContent($newsUrl, $newsID)
{
    $likeUrl = "https://www.facebook.com/plugins/like.php?action=like&app_id=161989317205664&channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2Fvy-MhgbfL4v.js%3Fversion%3D44%23cb%3Df2a3c1fd1239aa2%26domain%3Dwww.ithome.com.tw%26origin%3Dhttps%253A%252F%252Fwww.ithome.com.tw%252Ff2bdaa337d274d4%26relation%3Dparent.parent&container_width=0&href=https%3A%2F%2Fwww.ithome.com.tw%2Fnews%2F".$newsID."&layout=button_count&locale=zh_TW&sdk=joey&share=true&show_faces=false";
    $html = getHtmlContent($newsUrl);
    $likeHtml = getHtmlContent($likeUrl);

    preg_match_all('/<h1 class="page-header">(.*)<\/h1>/', $html, $titles);
    preg_match_all('/<span class="created">(\d{4}-\d{2}-\d{2})<\/span>/', $html, $date);
    preg_match_all('/<a href="\/users\/(.*)">(.*)<\/a>/m', $html, $authors);
    preg_match_all('/<span class="_5n6h _2pih" id="u_0_3">(\d{1,4})<\/span>/m', $likeHtml, $like_total);
    preg_match_all('/<div[^>]*class="field-item even"[^>]*><img[^>]*src="(.*)"[^>]*>/Ui', $html, $pictures);
    preg_match_all('/<div[^>]*class="field field-name-body field-type-text-with-summary field-label-hidden"[^>]*>(.*?)<\/div>/si', $html, $articles);

    $data['title'] = isset($titles[1][0])?$titles[1][0]:NULL;
    $data['created'] = isset($date[1][0])?$date[1][0]:NULL;
    $data['author'] = isset($authors[2][0])?$authors[2][0]:NULL;
    $data['like_total'] = isset($like_total[1][0])?$like_total[1][0]:NULL;
    $data['pic'] = isset($pictures[1][0])?$pictures[1][0]:NULL;
    $data['article'] = isset($articles[1][0])?$articles[1][0]:NULL;

    return $data;
}

function getHtmlContent($url)
{   
    $ch = curl_init();  // initialization

    $ua = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.16 (KHTML, like Gecko) \ Chrome/24.0.1304.0 Safari/537.16';
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $html = trim(curl_exec($ch));

    if (curl_errno($ch)) {
        echo 'error no : '.curl_error($ch);
        exit;
    }

    curl_close($ch);

    return $html;
}

?>