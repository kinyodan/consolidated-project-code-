<?php

use Illuminate\Contracts\Auth\Guard;

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = ''): string
    {
        return app()->basePath().DIRECTORY_SEPARATOR.'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if(!function_exists('public_path'))
{

    /**
     * Return the path to public dir
     * @param null $path
     * @return string
     */
    function public_path($path=null): string
    {
        return rtrim(app()->basePath('public/'.$path), '/');
    }
}

if(!function_exists('database_path'))
{

    /**
     * Return the path to database dir
     * @param null $path
     * @return string
     */
    function database_path($path=null): string
    {
        return app()->databasePath($path);
    }
}

if(!function_exists('resource_path'))
{

    /**
     * Return the path to resource dir
     * @param null $path
     * @return string
     */
    function resource_path($path=null): string
    {
        return app()->resourcePath($path);
    }
}

if ( ! function_exists('asset'))
{
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param null $secure
     * @return string
     */
    function asset(string $path, $secure = null): string
    {
        return app('url')->asset($path, $secure);
    }
}

if ( ! function_exists('elixir'))
{
    /**
     * Get the path to a versioned Elixir file.
     *
     * @param string $file
     * @return string
     */
    function elixir(string $file): string
    {
        static $manifest = null;
        if (is_null($manifest))
        {
            $manifest = json_decode(file_get_contents(public_path().'/build/rev-manifest.json'), true);
        }
        if (isset($manifest[$file]))
        {
            return '/build/'.$manifest[$file];
        }
        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}

if ( ! function_exists('auth'))
{
    /**
     * Get the available auth instance.
     *
     * @return Guard
     */
    function auth(): Guard
    {
        return app('Illuminate\Contracts\Auth\Guard');
    }
}

if ( ! function_exists('bcrypt'))
{
    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    function bcrypt(string $value, $options = array()): string
    {
        return app('hash')->make($value, $options);
    }
}

if ( ! function_exists('secure_asset'))
{
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @return string
     */
    function secure_asset(string $path): string
    {
        return asset($path, true);
    }
}

if ( ! function_exists('secure_url'))
{
    /**
     * Generate a HTTPS url for the application.
     *
     * @param string $path
     * @param mixed $parameters
     * @return string
     */
    function secure_url(string $path, $parameters = array()): string
    {
        return url($path, $parameters, true);
    }
}
