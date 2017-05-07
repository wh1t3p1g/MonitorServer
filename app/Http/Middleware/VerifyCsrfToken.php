<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'api/*',
        'upload/up',
        'webshell/add/task',
        'webshell/task/*',
        'users/*',
        'monitor/path',
        'monitor/task/*',
        'webshell/discover/update',
        "configuration/update/*",
        "webshell/rules/*",
        "hosts/del"
    ];
}
