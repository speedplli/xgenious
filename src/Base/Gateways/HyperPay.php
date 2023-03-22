<?php

namespace Xgenious\Paymentgateway\Base\Gateways;

use GuzzleHttp\Client;
use Xgenious\Paymentgateway\Traits\CurrencySupport;
use Xgenious\Paymentgateway\Base\PaymentGatewayBase;
use Xgenious\Paymentgateway\Traits\PaymentEnvironment;

class HyperPay extends PaymentGatewayBase
{
    use PaymentEnvironment, CurrencySupport;

    protected $token;
    protected $billing = [];
    protected $customer = [];
    protected $entity_id;

    private function getAuthorizationToken()
    {
        return $this->token;
    }

    public function setAuthorizationToken($value)
    {
        $this->token = $value;
    }

    private function getEntityID()
    {
        return $this->entity_id;
    }

    public function setEntityID($value)
    {
        $this->entity_id = $value;
    }

    private function getCustomer(): array
    {
        return $this->customer;
    }

    public function setCustomer(array $value)
    {
        $this->customer = $value;
    }

    private function getBilling(): array
    {
        return $this->billing;
    }

    public function setBilling(array $value)
    {
        $this->billing = $value;
    }

    public function charge_amount($amount)
    {
        return $amount;
    }

    public function ipn_response(array $args = []): array
    {
        $transaction_id = request()->get('id');
        if (!empty($transaction_id)) {
            $response = $this->client()->get("checkouts/{$transaction_id}/payment?entityId={$this->getEntityID()}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->getAuthorizationToken()}",
                ]
            ]);

            $response = json_decode($response->getBody(), true);
            $order_id = $response['merchantTransactionId'] ?: null;
            $code = $response['result']['code'];
            $check_000_code = preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $code);
            $check_400_code = preg_match('/^(000\.400\.0[^3]|000\.400\.100)/', $code);

            if ($check_000_code || $check_400_code) {
                return $this->verified_data([
                    'transaction_id' => $transaction_id,
                    'order_id' => $order_id
                ]);
            }
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
        $form_params = [
            'entityId' => $this->getEntityID(),
            'amount' => number_format($args['amount'], 2, '.', ''),
            'currency' => $this->charge_currency(),
            'paymentType' => 'DB',
            'merchantTransactionId' => $args['order_id'],
            'createRegistration' => $args['payment_type'] === 'monthly' ? 'true' : 'false',
        ];

        if ($this->getCustomer()) {
            $form_params = array_merge($form_params, $this->getCustomer());
        }

        if ($this->getBilling()) {
            $form_params = array_merge($form_params, $this->getBilling());
        }

        if ($this->getEnv()) {
            $form_params['testMode'] = 'EXTERNAL';
        }

        $response = $this->client()->post('checkouts', [
            'form_params' => $form_params,
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
        if (in_array($this->getCurrency(), $this->supported_currency_list())) {
            return $this->getCurrency();
        }
        return $this->supported_currency_list()[0];
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
            'JOD',
        ];
    }

    private function client()
    {
        $environment = $this->getEnv() ? 'test' : 'prod';
        return new Client(['base_uri' => "https://eu-{$environment}.oppwa.com/v1/"]);
    }
}
