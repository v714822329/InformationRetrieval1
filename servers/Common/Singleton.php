<?php

abstract class Singleton
{
    private static $sInstances = array();

    protected function __construct()
    {
    }

    final private function __clone()
    {
    }

    final public static function getInstance()
    {
        $calledClassName = get_called_class();
       
        if (! isset(self::$sInstances[$calledClassName]))
        {
            self::$sInstances[$calledClassName] = new $calledClassName();
            self::$sInstances[$calledClassName]->init();
        }
        
        return self::$sInstances[$calledClassName];        
    }
    
    final public static function destoryInstance()
    { 
        $calledClassName = get_called_class();
         
        if (isset(self::$sInstances[$calledClassName]))
        {
            self::$sInstances[$calledClassName] = null;
        }
    }

    public function init()
    { 
        return true;
    }
}

?>