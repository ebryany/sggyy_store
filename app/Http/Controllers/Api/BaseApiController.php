<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\HasQueryFilters;

class BaseApiController extends Controller
{
    use ApiResponse, HasQueryFilters;

    /**
     * Default per page for pagination
     */
    protected int $defaultPerPage = 15;

    /**
     * Maximum per page for pagination
     */
    protected int $maxPerPage = 100;
}

