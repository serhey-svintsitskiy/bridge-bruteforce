<?php

declare(strict_types=1);

namespace BridgeBruteforce;

use RuntimeException;

class BridgeBruteforce
{
    private const MIN_ALLOWED_PATH_LENGTH = 25;

    private array $a;

    private array $b;

    private array $log;

    private int $path;

    public function __construct(array $movers)
    {
        $this->a = $movers;
        $this->b = [];
        $this->log = [];
        $this->path = 0;
    }

    public static function start(array $movers)
    {
        $bruteforcer = new self($movers);
        return $bruteforcer->bruteforce();
    }

    public function bruteforce(): string
    {
        $firstMoveForward = true;
        if (!$this->multiply($firstMoveForward)) {
            throw new RuntimeException('Something went wrong');
        }

        return $this->getLog();
    }

    public function getLog(): string
    {
        $strlog = "Result:\nPath=$this->path.\n";
        foreach ($this->log as $logIndex => $logItem){
            $strlog .= "".($logIndex+1).". (".$logItem[0].") (".max($logItem[1]).") [".implode(',', $logItem[1])."];\n";
        }

        return $strlog;
    }

    private function multiply(bool $isForward): bool
    {
        $side = ($isForward) ? $this->a : $this->b;
        $count = ($isForward) ? 2 : 1;
        foreach (self::getComb($side, $count) as $movers) {
            if (!$this->tryToMove($movers, $isForward)) {
                continue;
            }
            
            return $this->checkWin() ?: $this->multiply(!$isForward);
        }
        return false;
    }

    public function tryToMove(array $movers, bool $isForward): bool
    {
        $save = [$this->a, $this->b, $this->path];
        
        if ($isForward) {
            $this->moveForward($movers);
        } else {
            $this->moveBack($movers);
        }

        if ($this->path <= self::MIN_ALLOWED_PATH_LENGTH) {
            $this->addLog($isForward ? '+' : '-', $movers);
            return true;
        }
        [$this->a, $this->b, $this->path] = $save;
        
        return false;
    }

    private function calcPath(array $movers): void
    {
        $this->path += max($movers);
    }

    private function addLog(string $dir, array $movers): void
    {
        $this->log[] = [$dir, $movers];
    }

    private function moveForward(array $movers): void
    {
        $this->a = array_diff($this->a, $movers);
        $this->b = array_merge($this->b, $movers);
        $this->calcPath($movers);
    }

    private function moveBack(array $movers): void
    {
        $this->b = array_diff($this->b, $movers);
        $this->a = array_merge($this->a, $movers);
        $this->calcPath($movers);
    }

    private function checkWin(): bool
    {
        return count($this->a) === 0;
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