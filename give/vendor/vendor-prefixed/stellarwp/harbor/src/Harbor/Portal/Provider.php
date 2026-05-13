<?php declare( strict_types=1 );

namespace Give\Vendors\LiquidWeb\Harbor\Portal;

use Give\Vendors\LiquidWeb\Harbor\Consent\Consent_Repository;
use Give\Vendors\LiquidWeb\Harbor\Portal\Clients\Portal_Client;
use Give\Vendors\LiquidWeb\Harbor\Portal\Clients\Http_Client;
use Give\Vendors\LiquidWeb\Harbor\Portal\Clients\Null_Client;
use Give\Vendors\LiquidWeb\Harbor\Config;
use Give\Vendors\LiquidWeb\Harbor\Contracts\Abstract_Provider;
use Give\Vendors\LiquidWeb\Harbor\Portal\Contracts\Download_Url_Builder;
use Give\Vendors\Psr\Http\Client\ClientInterface;
use Give\Vendors\Nyholm\Psr7\Factory\Psr17Factory;

/**
 * Registers the Catalog subsystem in the DI container.
 *
 * @since 1.0.0
 */
final class Provider extends Abstract_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->singleton(
			Portal_Client::class,
			function (): Portal_Client {
				if ( ! $this->container->get( Consent_Repository::class )->has_consent() ) {
					return new Null_Client();
				}

				return new Http_Client(
					$this->container->get( ClientInterface::class ),
					$this->container->get( Psr17Factory::class ),
					Config::get_portal_base_url()
				);
			}
		);

		$this->container->singleton( Catalog_Repository::class );
		$this->container->singleton( Herald_Url_Builder::class );
		$this->container->singleton( Download_Url_Builder::class, Herald_Url_Builder::class );

		add_action(
			'lw-harbor/unified_license_key_changed',
			function () {
				$this->container->get( Catalog_Repository::class )->delete_catalog();
			}
		);
	}
}
