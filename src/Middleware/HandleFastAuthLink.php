<?php

namespace Rigorbb\FastAuthLinks\Middleware;

use Closure;
use Rigorbb\FastAuthLinks\FastAuthLink;
use Rigorbb\FastAuthLinks\FastAuthLinkFacade;

class HandleFastAuthLink {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (FastAuthLinkFacade::checkLink($request->fullUrl())) {
            FastAuthLinkFacade::authByHash($request->fullUrl());
        }

        return $next($request);
    }
}