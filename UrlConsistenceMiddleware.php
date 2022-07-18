<?php
declare(strict_types=1);

/**
 *  **********************************
 *  ||        COPYRIGHT NOTICE      ||
 *  **********************************
 *  ----------------------------------
 * @copyright  2022 SilvarCode.com
 * @link       https://silvarcode.com
 * @since      1.0.0
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 *  ----------------------------------
 */

namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * UrlConsistence middleware
 */
class UrlConsistenceMiddleware implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
		$here = $request->getAttribute('here');
		$query = $request->getQuery();
        $query = (!empty($query)) ? $here . '?' . http_build_query($query) : $query;
        $hereCheckUrl = [
			'country'=>$request->getParam(
				'country', 
				false
			),
			'lang'=>$request->getParam(
				'lang', 
				str_replace(
					'_',
					'-', 
					strtolower(
						Configure::read('App.defaultLocale')
					)
				)
			),
			
			'plugin'=>$request->getParam('plugin', false),
			'prefix'=>$request->getParam('prefix', false),
			'action'=>$request->getParam('action'),
			'controller'=>$request->getParam('controller'),
			'pass'=>$request->getParam('pass'),
			'_ext'=>$request->getParam('_ext'),
			'?'=>$query
		];
		
        unset($query);
		if ($pass = $request->getParam('pass')) {
			unset($hereCheckUrl['pass']);
			foreach ($pass as $passing) {
                $hereCheckUrl[] = $passing;
            }
		}
		
		$hereCheckUrl = \Cake\Routing\Router::normalize(
			$hereCheckUrl
		);
		
		if (($here) && ($here !== $hereCheckUrl)) {
			return (new Response())->withStatus(308)->withHeader(
				'Location', 
				$hereCheckUrl
			);
		}
		
		return $handler->handle($request);
    }
}
