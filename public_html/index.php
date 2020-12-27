<?php

use BridgeBruteforce\BridgeBruteforce;

set_time_limit(960);

require_once '../vendor/autoload.php';
print_r('<pre>'. BridgeBruteforce::start([1, 2, 5, 10]).'</pre>');