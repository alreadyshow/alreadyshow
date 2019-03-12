<?php
interface Car
{
    public function run();
}

class poneyCar implements Car
{
    public function run ()
    {
        return 'poneyCar';
    }
}

class oneCar implements Car
{
    public function run ()
    {
        return 'oneCar';
    }
}

class simpleFactory
{
    static function createPoneyCar()
    {
        return new poneyCar();
    }

    static function createOneCar()
    {
        return new oneCar();
    }
}

$poneyCar = (new simpleFactory)->createPoneyCar();

$poneyCar->run();