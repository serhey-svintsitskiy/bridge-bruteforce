<?php

namespace sergo_sv\bridge_bruteforce;

/**
 * Class BridgeBruteforce
 * @package sergo_sv\bridge_bruteforce
 */
class BridgeBruteforce
{

    const MIN_ALLOWED_PATH_LENGTH = 25;
    /**
     * @var array
     */
    private $movers = [];
    /**
     * @var array
     */
    private $a = [];
    /**
     * @var array
     */
    private $b = [];
    /**
     * @var array
     */
    private $log = [];
    /**
     * @var int
     */
    private $path = 0;

    /**
     * Bridge constructor.
     * @param array $movers
     */
    public function __construct(array $movers)
    {
        $this->movers = $movers;
        $this->a = $this->movers;
        $this->b = [];
        $this->log = [];
        $this->path = 0;
    }

    /**
     * @param array $movers
     * @return array
     */
    public static function start(array $movers)
    {
        $bruteforcer = new self($movers);
        return $bruteforcer->bruteforce();
    }

    /**
     * @return array|string
     */
    public function bruteforce()
    {
        if ($this->multiply(true)) {
            return $this->getLog();
        } else {
            return 'Something went wrong.';
        }
    }

    /**
     * @return string
     */
    public function getLog()
    {
        $strlog = "Result:\nPath=$this->path.\n";
        foreach ($this->log as $logIndex => $logItem){
            $strlog .= "".($logIndex+1).". (".$logItem[0].") (".max($logItem[1]).") [".implode(',', $logItem[1])."];\n";
        }
        return $strlog;
    }

    /**
     * @param bool $isForward
     * @return bool
     */
    private function multiply($isForward)
    {
        $side = ($isForward) ? $this->a : $this->b;
        $count = ($isForward) ? 2 : 1;
        foreach (self::getComb($side, $count) as $movers) {
            if (!$this->tryToMove($movers, $isForward)) {
                continue;
            }
            if ($this->checkWin()) {
                return true;
            } else {
                return $this->multiply(!$isForward);
            }
        }
        return false;
    }

    /**
     * @param $movers
     * @param $isForward
     * @return bool
     */
    public function tryToMove($movers, $isForward)
    {
        $a = $this->a;
        $b = $this->b;
        $log = $this->log;
        $path = $this->path;
        if ($isForward) {
            $this->moveForward($movers);
        } else {
            $this->moveBack($movers);
        }

        if ($this->checkPath()) {
            $this->a = $a;
            $this->b = $b;
            $this->log = $log;
            $this->path = $path;
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param array $movers
     */
    private function calcPath($movers)
    {
        $this->path += max($movers);
    }

    /**
     * @param string $dir
     * @param array $movers
     */
    private function addLog($dir, $movers)
    {
        $this->log[] = [$dir, $movers];
    }


    /**
     * @param array $forwardMovers
     */
    private function moveForward($forwardMovers)
    {
        $this->a = array_diff($this->a, $forwardMovers);
        $this->b = array_merge($this->b, $forwardMovers);
        $this->calcPath($forwardMovers);
        $this->addLog('+', $forwardMovers);
    }

    /**
     * @param array $backMovers
     */
    private function moveBack($backMovers)
    {
        $this->b = array_diff($this->b, $backMovers);
        $this->a = array_merge($this->a, $backMovers);
        $this->calcPath($backMovers);
        $this->addLog('-', $backMovers);
    }

    /**
     * @return bool
     */
    private function checkWin()
    {
        return count($this->a) ? false : true;
    }

    /**
     * @return bool
     */
    private function checkPath()
    {
        return $this->path > self::MIN_ALLOWED_PATH_LENGTH;
    }

    /**
     * @param array $set
     * @param int $size
     * @return array
     */
    private static function getComb($set = [], $size = 0)
    {
        if ($size == 0) {
            return [[]];
        }
        if ($set == []) {
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