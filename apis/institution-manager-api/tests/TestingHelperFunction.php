<?php
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

trait TestingHelperFunction
{
    /**
     * @var $default_institution_code
    */
    protected $default_institution_code = 'x9qkVi0ZHA';

    /**
     * Disable exception handling for the test.
     *
     * @param  array  $except
     * @return $this
     */
    protected function withoutExceptionHandling(array $except = [])
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

    /**
     * Clear database
    */
    protected function clearDB(){
        Schema::disableForeignKeyConstraints();
        $db_name = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW TABLES');

        foreach($tables as $table){
            if ($table == 'migrations') {
                continue;
            }

            $table_name = $table->{"Tables_in_{$db_name}"};
            DB::table($table_name)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
