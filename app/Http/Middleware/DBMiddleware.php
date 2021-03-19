<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use App\Traits\CustomResponse;
use Closure;

class DBMiddleware{
    use CustomResponse;

    public function handle($request, Closure $next){
        try {
            DB::getPdo();
        }
        catch(\Exception $e) {
            return $this->respondWithError('middleware.database', 503);
        }
        return $next($request);
    }
}