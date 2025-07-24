<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Middleware\TrustProxies as MiddlewareTrustProxies;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies extends MiddlewareTrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*'; // Gunakan IP Cloudflare/nginx jika ingin lebih aman

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_FORWARDED;
}
