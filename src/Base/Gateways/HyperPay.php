<?php

namespace Xgenious\Paymentgateway\Base\Gateways;

use GuzzleHttp\Client;
use Xgenious\Paymentgateway\Traits\CurrencySupport;
use Xgenious\Paymentgateway\Base\PaymentGatewayBase;
use Xgenious\Paymentgateway\Traits\PaymentEnvironment;

class HyperPay extends PaymentGatewayBase
{
    use PaymentEnvironment, CurrencySupport;

    protected $token = 'OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=';

    private function getAuthorizationToken()
    {
        return $this->token;
    }

    private function setAuthorizationToken($value)
    {
        $this->token = $value;
    }

    public function charge_amount($amount)
    {
        return $amount;
    }

    public function ipn_response(array $args = []): array
    {
        if (!empty($transaction_id)) {
            return $this->verified_data([
                'transaction_id' => $transaction_id,
                'order_id' => $stripe_order_id
            ]);
        }

        return ['status' => 'failed'];
    }

    public function charge_customer(array $args)
    {
        return $this->hyperpay_view($args);
    }

    public function hyperpay_view($args)
    {
        return view('paymentgateway::hyperpay', [
            'checkout_id' => $this->generateCheckoutID($args),
            'url' => $args['ipn_url']
        ]);
    }

    private function generateCheckoutID($args): string
    {
        $this->setCurrency('JOD');

        $response = self::client()->post('checkouts', [
            'form_params' => [
                'entityId' => '8a8294174b7ecb28014b9699220015ca',
                'amount' => '92.00',
                'currency' => 'EUR',
                'paymentType' => 'DB',
                'merchantTransactionId' => $args['order_id'],
                'createRegistration' => $args['payment_type'] === 'monthly' ? 'true' : 'false',
                'testMode' => 'EXTERNAL'
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAuthorizationToken(),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        return json_decode($response->getBody(), true)['id'] ?? '';
    }

    /**
     * this will return payment gateway charge currency
     * */
    public function charge_currency()
    {
        return $this->getCurrency();
    }

    /**
     * this will return payment gateway name
     * */
    public function gateway_name(): string
    {
        return 'hyperpay';
    }

    public function supported_currency_list(): array
    {
        return [
            'USD',
            'EUR',
            'JOD',
            'SAR'
        ];
    }

    private static function client()
    {
        return new Client(['base_uri' => 'https://eu-test.oppwa.com/v1/']);
    }
}
