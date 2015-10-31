<?php
//Proxy scraper for orcahub.com. Written and made by CStress IP Stresser Booter
function request($url)
{
    $ch         = curl_init();
    $curlConfig = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEFILE => "cookie.txt",
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1",
        CURLOPT_FOLLOWLOCATION => 1
    );
    curl_setopt_array($ch, $curlConfig);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function get_string_between($string, $start, $end)
{
    $arr = explode($start, $string);
    $arr = explode($end, $arr[1]);
    return $arr[0];
}

$i       = 0;
$content = request('http://orcahub.com/proxy-lists/');
$rows    = explode("\n", $content);
foreach ($rows as $row) {
    if (!strpos($row, "Anonymous Proxy List") === FALSE) {
        $title    = get_string_between($row, "<a href='", "'>Anonymous");
        $link     = 'http://orcahub.com/proxy-lists/' . $title;
        $content2 = request($link);
        $rows2    = explode("\n", $content2);
        foreach ($rows2 as $row2) {
            if (!strpos($row2, ":") === FALSE && strpos($row2, " ") === FALSE) {
                $i++;
                file_put_contents("proxy_list.txt", "$row2\n", FILE_APPEND);
            }
        }
    }
}
echo "done. $i proxies found\n";