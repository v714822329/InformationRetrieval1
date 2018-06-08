<?php
/**
 * Created by PhpStorm.
 * User: ZSL
 * Date: 2018/1/31
 * Time: 16:18
 */

namespace Core;

use Conf\Hook;
class Core
{

    protected static $instance;
    private $preCall;
    function __construct($preCall)
    {
        $this->preCall = $preCall;
    }

    public static function getInstance(callable $preCall = null){
        if(!isset(self::$instance)){
            self::$instance = new static($preCall);
        }
        return self::$instance;
    }

    public function run(){
        $this->defineSysConst();
        $this->registerAutoLoader();
    }

    private function defineSysConst(){
        defined('ROOT') or define("ROOT",realpath(__DIR__ . '/../'));
    }

    private static function registerAutoLoader(){
        require_once __DIR__."/AutoLoader.php";
        $loader = AutoLoader::getInstance();
        $loader->addNamespace("App","App");
        $loader->addNamespace("Core","Core");
        $loader->addNamespace("Conf","Conf");
        //添加系统依赖组件
        $loader->addNamespace("FastRoute","Core/Vendor/FastRoute");
        $loader->addNamespace("SuperClosure","Core/Vendor/SuperClosure");
        $loader->addNamespace("PhpParser","Core/Vendor/PhpParser");
    }
}



$server = Core::getInstance();
$server->run();

Hook::test();