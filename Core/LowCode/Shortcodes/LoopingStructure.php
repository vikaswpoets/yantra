<?php

namespace Core\LowCode\Shortcodes;

interface LoopingStructure
{
    public function setConditionMet(bool $conditionMet):void;
    public function getConditionMet():bool;

    public function setLoopStatus(int $status):void;
    public function getLoopStatus():int;
}
