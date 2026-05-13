<?php declare( strict_types=1 );

namespace Give\Vendors\LiquidWeb\Harbor\Http;

use Give\Vendors\LiquidWeb\Harbor\Consent\Consent_Repository;
use Give\Vendors\LiquidWeb\LicensingApiClientWordPress\Http\WordPressHttpClient;
use Give\Vendors\LiquidWeb\Harbor\Http\Null_Client;
use Give\Vendors\Nyholm\Psr7\Factory\Psr17Factory;
use Give\Vendors\Psr\Http\Client\ClientInterface;
use Give\Vendors\Psr\Http\Message\RequestFactoryInterface;
use Give\Vendors\Psr\Http\Message\StreamFactoryInterface;
use Give\Vendors\LiquidWeb\Harbor\Contracts\Abstract_Provider;

/**
 * Registers shared PSR-17 HTTP message factories in the DI container.
 *
 * @since 1.0.0
 */
final class Provider extends Abstract_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->singleton(
			ClientInterface::class,
			function (): ClientInterface {
				if ( ! $this->container->get( Consent_Repository::class )->has_consent() ) {
					return new Null_Client();
				}

				return new WordPressHttpClient();
			}
		);
		$this->container->singleton( Psr17Factory::class );
		$this->container->singleton( RequestFactoryInterface::class, Psr17Factory::class );
		$this->container->singleton( StreamFactoryInterface::class, Psr17Factory::class );
	}
}
