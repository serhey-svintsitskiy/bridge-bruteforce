<?php

declare(strict_types=1);

namespace BridgeBruteforce;

class BridgeBruteforce
{
    private const MIN_ALLOWED_PATH_LENGTH = 25;
    private const MAX_MOVE_COUNT = 5;

    private int $minPath = PHP_INT_MAX;
    
    private array $all = [];

    public static function start(array $movers)
    {
        $bruteforcer = new self();
        return $bruteforcer->bruteforce($movers);
    }

    public function bruteforce(array $movers): array
    {
        $this->minPath = PHP_INT_MAX;
        $this->all = [];
        $firstMoveForward = true;
        $this->multiply($firstMoveForward, $movers);

        return [$this->minPath, $this->getMovesByPath($this->minPath)];
    }
    
    public function getMovesByPath(int $path): array 
    {
        $result = [];
        
        foreach ($this->all as $solution) {
            if ($solution[0] === $path) {
                $result[] = $solution[1];
            }
        }
        
        return $result;
    }

    private function multiply(bool $isForward, array $a, array $b = [], int $path = 0, int $i = 0, array $log = []): void
    {
        $side = ($isForward) ? $a : $b;
        $count = ($isForward) ? 2 : 1;
        
        $combination = self::getComb($side, $count);
        foreach ($combination as $movers) {
            $this->doMove($movers, $isForward, $a, $b, $path, $i, $log);
        }
    }

    public function doMove(
        array $movers,
        bool $isForward,
        array $a,
        array $b = [],
        int $path = 0,
        int $i = 0,
        array $log = []
    ): void {
        if ($isForward) {
            [$a, $b, $path] = $this->move($movers, $a, $b, $path);
        } else {
            [$b, $a, $path] = $this->move($movers, $b, $a, $path);
        }
        $log[] = [$isForward, $movers];

        if (empty($a) || $i > self::MAX_MOVE_COUNT) {
            if ($path > self::MIN_ALLOWED_PATH_LENGTH) {
                return;
            }
            $this->all[] = [$path, $log];
            $this->minPath = min($this->minPath, $path);
        } else {
            $this->multiply(!$isForward, $a, $b, $path, $i + 1, $log);
        }
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