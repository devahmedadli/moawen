<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponse;
use App\Traits\HasQueryBuilder;

abstract class Controller
{
    use ApiResponse;
    use HasQueryBuilder;
}
