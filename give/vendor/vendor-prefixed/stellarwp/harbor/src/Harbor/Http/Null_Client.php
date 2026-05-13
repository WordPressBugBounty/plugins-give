<?php declare( strict_types=1 );

namespace Give\Vendors\LiquidWeb\Harbor\Http;

use Give\Vendors\Psr\Http\Client\ClientInterface;
use Give\Vendors\Psr\Http\Message\RequestInterface;
use Give\Vendors\Psr\Http\Message\ResponseInterface;
use Give\Vendors\Nyholm\Psr7\Response;
use Give\Vendors\LiquidWeb\Harbor\Portal\Error_Code;

/**
 * Null client implementation of the PSR-18 HTTP client.
 *
 * @since 1.1.0
 */
final class Null_Client implements ClientInterface {
	/**
	 * Sends a PSR-7 request and returns a PSR-7 response.
	 *
	 * @since 1.1.0
	 *
	 * @param RequestInterface $request The request to send.
	 *
	 * @return ResponseInterface The response.
	 */
	public function sendRequest( RequestInterface $request ): ResponseInterface {
		return new Response(
			500,
			[],
			'',
			'1.1',
			Error_Code::API_COMMUNICATIONS_NOT_PERMITTED
		);
	}
}
