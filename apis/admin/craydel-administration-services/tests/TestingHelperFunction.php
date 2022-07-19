<?php
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Route;

trait TestingHelperFunction
{
    /**
     * Disable exception handling for the test.
     *
     * @param  array  $except
     * @return $this
     */
    public function withoutExceptionHandling(array $except = []): static
    {
        $this->app->instance(\Illuminate\Contracts\Debug\ExceptionHandler::class, new class extends Handler
        {
            public function render($request, \Throwable $e)
            {
                throw $e;
            }
        });
    }

    /**
     * @param $route
     * @return bool
     */
    public function checkIfRouteExists($route): bool
    {
        if($route[0] === "/"){
            $route = substr($route, 1);
        }
        $routes = Route::getRoutes();

        foreach ($routes as $r) {
            $r = is_array($r) ? (object)$r : $r;

            if(is_object($r)){
                if ($r->uri == $route) {
                    return true;
                }

                if (isset($r->action['as'])) {
                    if ($r->action['as'] == $route) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
