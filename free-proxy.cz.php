<?php
//Proxy scraper for free-proxy.cz. Written and made by CStress IP Stresser Booter
set_time_limit(0);
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
for ($i=1;$i<=255;$i++)
{
$content = request('http://free-proxy.cz/en/proxylist/main/'.$i);
$content = get_string_between($content,'<tbody>','</tbody>');
$array = explode('<td class="left">',$content);
foreach ($array as $row)
{
    if (strpos($row,"glass_space") !== FALSE)
	{
	$base64 = get_string_between($row,'decode("','"))');
	$ip = base64_decode($base64);
	$port = get_string_between($row,"style=''>","</span>");
	file_put_contents("proxy_list.txt", $ip.':'.$port."\n", FILE_APPEND);
	$c++;
	}
}
}
echo "done. $c proxies found\n";