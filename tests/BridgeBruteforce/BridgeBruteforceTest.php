<?php

namespace tests\BridgeBruteforce;

use BridgeBruteforce\BridgeBruteforce;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class BridgeBruteforceTest extends TestCase
{
    private function callMethod($object, string $method, array $parameters = [])
    {
        try {
            $className = get_class($object);
            $reflection = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            throw new Exception($e->getMessage());
        }

        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testBruteforce(): void
    {
        $movers = [1, 2, 5, 10];
        $bruteforcer = new BridgeBruteforce();
        $bruteforcer->bruteforce($movers);

        self::assertTrue(true);
    }

    public function testSimpleMove(): void
    {
        $bruteforcer = new BridgeBruteforce();
        [$a, $b, $path] = $this->callMethod($bruteforcer, 'move', [[1, 2], [1, 2, 5, 10], [], 0]);
        //[$a, $b, $path] = $this->callMethod($bruteforcer, 'move', [[1, 2], $b, $a, $path]);
        self::assertEquals([5, 10], array_values($a));
        self::assertEquals([1, 2], $b);
        self::assertEquals(2, $path);
    }
}
