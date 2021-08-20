<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'https://play.rajsreeonline.com/rajshree_single_api/public/api/*',
        'https://play.rajsreeonline.com/rajshree_single_api/public/api/*'
    ];
}
