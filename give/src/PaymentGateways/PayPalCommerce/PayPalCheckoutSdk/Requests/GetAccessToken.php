<?php

namespace Give\PaymentGateways\PayPalCommerce\PayPalCheckoutSdk\Requests;

use PayPalHttp\HttpRequest;

/**
 * Class GetAccessTokenWithClientCredentials
 *
 * @since 4.1.0 Add PayPal-Partner-Attribution-Id header
 * @since 2.32.0
 */
class GetAccessToken extends HttpRequest
{
    /**
     * @since 2.32.0
     *
     * @param array $requestBody Request body.
     * @param array $headers Headers to be added to the request.
     */
    public function __construct(array $requestBody, array $headers)
    {
        parent::__construct('/v1/oauth2/token', 'POST');

        $this->headers = wp_parse_args($headers, $this->headers);

        $this->headers["Content-Type"] = "application/x-www-form-urlencoded";
        $this->headers["PayPal-Partner-Attribution-Id"] = give('PAYPAL_COMMERCE_ATTRIBUTION_ID');
        $this->body = $requestBody;
    }
}
