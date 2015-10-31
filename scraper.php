<?php
#Automatically scrapes and filters proxies, can take a few minutes, be patient.
function partition( $list, $p ) {
        $listlen = count( $list );
        $partlen = floor( $listlen / $p );
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
                $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
                $partition[$px] = array_slice( $list, $mark, $incr );
                $mark += $incr;
        }
        return $partition;
}

$pattern = "/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?):\d{1,5}/";

$proxysites = array(
"http://googleproxies24.blogspot.com/",
"http://irc-proxies24.blogspot.com/",
"http://newfreshproxies24.blogspot.com/",
"http://proxyserverlist-24.blogspot.com/",
"http://socksproxylist24.blogspot.com/",
"http://sslproxies24.blogspot.com/",
"http://vip-socks24.blogspot.com/"
);
echo "Proxy scraper developed by Andy Quez\nStarting scraper...\n";
$proxies = array();
$start = time();
foreach ($proxysites as $proxysite)
{
		$main = file_get_contents($proxysite);
		preg_match_all("/<h3 class='post-title entry-title' itemprop='name'>\n<a href='(.*?)'/", $main, $fetched, PREG_SET_ORDER);
		foreach ($fetched as $fetch) {
        $contents = file_get_contents($fetch[1]);
        preg_match_all($pattern, $contents, $matches);

        foreach ($matches[0] as $match)
        {
                $proxies[] = $match;
				file_put_contents("proxy_list.txt", "$match\n", FILE_APPEND);
        }
	}
}

$count = count($proxies);

echo "Succesfully scraped $count proxies in ".(time() - $start)." seconds!\n\n";
