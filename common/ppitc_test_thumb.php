<?php
header('Content-Type: text/html');

print "<h1>Which phantomjs:</h1>[<pre>".shell_exec('which phantomjs')."</pre>]\n";
$now = time();

$js = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ppitc_thumbs.js';
$url = 'http://bbc.co.uk';
$filename = sys_get_temp_dir() . 'thumb-' .$now . '.png';
$command = "phantomjs $js $url $filename";
print "<h1>Command:</h1>[<pre>${command}</pre>]\n";
$response = shell_exec($command);

print "<h1>Response:</h1>[<br><pre>$response</pre><br>]\n";
print "<h1>File (${filename}):</h1>[<pre>".print_r(stat($filename), true)."</pre>]\n";
