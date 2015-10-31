<?php
//Proxy scraper for samair.ru. Written and made by CStress IP Stresser Booter
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

$c=0;
for ($i=1;$i<=30;$i++)
{
$content = request('http://www.samair.ru/proxy/proxy-'.$i.'.htm');
$content = str_replace('</td>','',$content);
$rows = explode('<td>',$content);
foreach ($rows as $row) {
    if (strlen($row) <= 30 && is_numeric(str_replace(array('.',':'),'',$row))) {
        file_put_contents("proxy_list.txt", "$row\n", FILE_APPEND);
		$c++;
    }
}
}

echo "done. $c proxies found\n";