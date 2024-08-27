<?php

namespace Core\LowCode\Shortcodes;

interface ForEachLoopingStructure
{

    public function setLoopStatus(int $status):void;
    public function getLoopStatus():int;

    public function getVariable(string $varName):mixed;
    public function addVariable(string $key,mixed $value):void;
}
