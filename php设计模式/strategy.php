<?php
abstract class baseAgent 
{
    abstract function PrintPage();
}

class ieAgent extends baseAgent 
{
    function PrintPage()
    {
        return 'ie';
    }
}

class otherAgent extends baseAgent
{
    function PrintPage()
    {
        return 'other';
    }
}

class browser 
{
    public function call ($object)
    {
        return $object->PrintPage();
    }
}

$bro = (new browser())->call(new ieAgent());

echo $bro;