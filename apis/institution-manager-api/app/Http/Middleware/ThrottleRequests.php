<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 20/12/2017
 * Time: 15:21
 */

namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests
{
	/**
	 * The rate limiter instance.
	 *
	 * @var RateLimiter
	 */
	protected $limiter;

    /**
	 * Create a new request throttler.
	 *
	 * @param RateLimiter $limiter
	 */
	public function __construct(RateLimiter $limiter){
		$this->limiter = $limiter;
	}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @return mixed
     */
	public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1){
		$key = $this->resolveRequestSignature($request);

		if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
			return $this->buildResponse($key, $maxAttempts);
		}

		$this->limiter->hit($key, $decayMinutes);

		$response = $next($request);

		return $this->addHeaders(
			$response, $maxAttempts,
			$this->calculateRemainingAttempts($key, $maxAttempts)
		);
	}

    /**
     * Resolve request signature.
     *
     * @param Request $request
     * @return string
     */
	protected function resolveRequestSignature(Request $request): string
    {
		return sha1(
			$request->method() .
			'|' . $request->header('x-agent') .
			'|' . $request->path() .
			'|' . CraydelHelperFunctions::getClientIPAddress()
		);
	}

    /**
     * Create a 'too many attempts' response.
     *
     * @param string $key
     * @param int $maxAttempts
     * @return \Illuminate\Http\Response
     */
	protected function buildResponse(string $key, int $maxAttempts){
		$response = new JsonResponse([
			'status' => false,
			'message' => LanguageTranslationHelper::translate('authorization.too_many_api_requests')
		], 200);

		$retryAfter = $this->limiter->availableIn($key);

		return $this->addHeaders(
			$response, $maxAttempts,
			$this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
			$retryAfter
		);
	}

    /**
     * Add the limit header information to the given response.
     *
     * @param Response $response
     * @param int $maxAttempts
     * @param int $remainingAttempts
     * @param int|null $retryAfter
     * @return mixed
     */
	protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts, $retryAfter = null): Response
    {
		$headers = [
			'X-RateLimit-Limit' => $maxAttempts,
			'X-RateLimit-Remaining' => $remainingAttempts,
		];

		if (! is_null($retryAfter)) {
			$headers['Retry-After'] = $retryAfter;
		}

		$response->headers->add($headers);

		return $response;
	}

    /**
     * Calculate the number of remaining attempts.
     *
     * @param string $key
     * @param int $maxAttempts
     * @param int|null $retryAfter
     * @return int
     */
	protected function calculateRemainingAttempts(string $key, int $maxAttempts, $retryAfter = null): int
    {
		if (! is_null($retryAfter)) {
			return 0;
		}

		return $this->limiter->retriesLeft($key, $maxAttempts);
	}
}
