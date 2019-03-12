<?php
abstract class eventGenerator
{
    private $observers = array();
    function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    function notify()
    {
        foreach ($this->observers as $observer) {
            echo $observer->update();
        }
    }
}

interface observer 
{
    function update();
}

class event extends eventGenerator
{
    function triger()
    {
        return 'Event'.PHP_EOL;
    }
}

class observer1 implements observer
{
    function update()
    {
        return 'ob1'.PHP_EOL;
    }
}

class observer2 implements observer
{
    function update()
    {
        return 'ob2'.PHP_EOL;
    }
}

$event = new event();
$event->addObserver(new observer1());
$event->addObserver(new observer2());
echo $event->triger();
echo $event->notify();