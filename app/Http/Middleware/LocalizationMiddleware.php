<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\CustomResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LocalizationMiddleware {
    use CustomResponse;

    public function handle($request, Closure $next){
        $locale = $request->header('Content-Language');

        if(!$locale){
            $locale = Config::get('localization.locale');
        }

        $supported_languages = Config::get('localization.supported_languages');

        if (!array_key_exists($locale, $supported_languages)) {
            return $this->respondWithError('middleware.language', 403);
        }

        App::setLocale($locale);
        $response = $next($request);
        $response->headers->set('Content-Language', $locale);
        return $response;
    }
}
