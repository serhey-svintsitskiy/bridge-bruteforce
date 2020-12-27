<?php

declare(strict_types=1);

namespace BridgeBruteforce;

use RuntimeException;

class BridgeBruteforce
{
    private const MIN_ALLOWED_PATH_LENGTH = 25;

    private array $log = [];

    private float $min = INF;
    
    private array $all = [];

    public function __construct()
    {
    }

    public static function start(array $movers)
    {
        $bruteforcer = new self();
        return $bruteforcer->bruteforce($movers);
    }

    public function bruteforce(array $movers): string
    {
        $firstMoveForward = true;
        [$a, $b, $path] = $this->multiply($firstMoveForward, $movers, [], 0);

        return $this->getLog();
    }

    public function getLog(): string
    {
        $strlog = "Result:\n";
        foreach ($this->log as $logIndex => $logItem){
            $strlog .= "".($logIndex+1).". (".$logItem[0].") (".max($logItem[1]).") [".implode(',', $logItem[1])."];\n";
        }

        return $strlog;
    }

    private function multiply(bool $isForward, array $a, array $b, int $path): ?array
    {
        $side = ($isForward) ? $a : $b;
        $count = ($isForward) ? 2 : 1;
        $result = [];
        
        $combination = self::getComb($side, $count);
        foreach ($combination as $movers) {
            if ($isForward) {
                [$a, $b, $path] = $this->move($movers, $a, $b, $path);
            } else {
                [$b, $a, $path] = $this->move($movers, $b, $a, $path);
            }
            
            if (!empty($a)) {
                $result[] = $this->multiply(!$isForward, $a, $b, $path);
            }

            $this->all[] = $path;
            $this->min = min($this->min, $path);
            return [$a, $b, $path];
        }
        
        return $result;
    }

    public function tryToMove(array $movers, bool $isForward, array $a, array $b, int $path): array
    {
        $save = [$a, $b, $path];

        [$a, $b, $path] = $isForward ? $this->move($movers, $a, $b, $path) : $this->move($movers, $b, $a, $path);

        if ($path > self::MIN_ALLOWED_PATH_LENGTH) {
            [$a, $b, $path] = $save;
        }
        
        //$this->addLog($isForward ? '+' : '-', $movers);
        return [$a, $b, $path];
    }

    private function addLog(string $dir, array $movers, int $path = 0): void
    {
        $this->log[] = [$dir, $movers, $path];
    }

    private function move(array $movers, array $from, array $to, int $path): array
    {
        $from = array_diff($from, $movers);
        $to = array_merge($to, $movers);
        $path += max($movers);
        
        return [$from, $to, $path];
    }

    private static function getComb(array $set = [], int $size = 0): array
    {
        if (!$size) {
            return [[]];
        }
        if (empty($set)) {
            return [];
        }

        $prefix = [array_shift($set)];

        $result = [];
        foreach (self::getComb($set, $size - 1) as $suffix) {
            $result[] = array_merge($prefix, $suffix);
        }
        foreach (self::getComb($set, $size) as $next) {
            $result[] = $next;
        }

        return $result;
    }
}