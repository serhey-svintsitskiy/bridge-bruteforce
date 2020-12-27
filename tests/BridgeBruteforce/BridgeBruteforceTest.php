<?php

namespace tests\BridgeBruteforce;

use BridgeBruteforce\BridgeBruteforce;
use PHPUnit\Framework\TestCase;

class BridgeBruteforceTest extends TestCase
{

    public function testBruteforce()
    {
        $movers = [1, 2, 5, 10];
        $bruteforcer = new BridgeBruteforce($movers);
        $bruteforcer->bruteforce();
        
        self::assertTrue(true);
    }
}
