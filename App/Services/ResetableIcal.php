<?php


namespace App\Services;


use ICal\ICal;

class ResetableIcal extends ICal
{
    public function reset()
    {
        $this->cal = array();
    }
}