<?php
set_time_limit(960);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../vendor/autoload.php';
print_r('<pre>'.\sergo_sv\bridge_bruteforce\BridgeBruteforce::start([1, 2, 5, 10]).'</pre>');