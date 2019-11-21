<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FuncServiceProvider extends ServiceProvider
{
    
    public function apiReturn() {
        echo 123;die;
    }
}
