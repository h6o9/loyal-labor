<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WsusCart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'wsuscart';
    }
}
