<?php
class single
{
    private $name;
    private function __construct() {}
    
    public static $instance;
    public static function getInstance()
    {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

$aha = single::getInstance();
$wha = single::getInstance();

$aha->setName('aha');
$wha->setName('aha');

echo $aha->getName().PHP_EOL;
echo $wha->getName();