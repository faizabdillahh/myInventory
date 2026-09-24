<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}
