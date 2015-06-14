<?php

class PerchSetting extends PerchBase
{
    protected $table  = 'settings';
    protected $pk     = 'settingID';
    
    public function val()
    {
        return $this->settingValue();
    }
    
}