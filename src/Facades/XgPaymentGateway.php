<?php

namespace Xgenious\Paymentgateway\Facades;

use Illuminate\Support\Facades\Facade;
use Xgenious\Paymentgateway\Base\GlobalCurrency;
use Xgenious\Paymentgateway\Base\Gateways\ZitoPay;
use Xgenious\Paymentgateway\Base\Gateways\PaytmPay;
use Xgenious\Paymentgateway\Base\Gateways\RazorPay;
use Xgenious\Paymentgateway\Base\Gateways\CinetPay;
use Xgenious\Paymentgateway\Base\Gateways\HyperPay;
use Xgenious\Paymentgateway\Base\Gateways\StripePay;
use Xgenious\Paymentgateway\Base\Gateways\PaypalPay;
use Xgenious\Paymentgateway\Base\Gateways\MolliePay;
use Xgenious\Paymentgateway\Base\Gateways\SquarePay;
use Xgenious\Paymentgateway\Base\Gateways\PayFastPay;
use Xgenious\Paymentgateway\Base\Gateways\PayTabsPay;
use Xgenious\Paymentgateway\Base\Gateways\MidtransPay;
use Xgenious\Paymentgateway\Base\Gateways\PaystackPay;
use Xgenious\Paymentgateway\Base\Gateways\CashFreePay;
use Xgenious\Paymentgateway\Base\PaymentGatewayHelpers;
use Xgenious\Paymentgateway\Base\Gateways\InstamojoPay;
use Xgenious\Paymentgateway\Base\Gateways\PayUmoneyPay;
use Xgenious\Paymentgateway\Base\Gateways\FlutterwavePay;

/**
 * @see GlobalCurrency
 * @method static GlobalCurrency script_currency_list()
 *
 * @see PaymentGatewayHelpers
 * @method static StripePay stripe()
 * @method static PaypalPay paypal()
 * @method static MidtransPay midtrans()
 * @method static PaytmPay paytm()
 * @method static RazorPay razorpay()
 * @method static MolliePay mollie()
 * @method static FlutterwavePay flutterwave()
 * @method static PaystackPay paystack()
 * @method static PayFastPay payfast()
 * @method static CashFreePay cashfree()
 * @method static InstamojoPay instamojo()
 * @method static PayUmoneyPay payumoney()
 * @method static SquarePay squareup()
 * @method static CinetPay cinetpay()
 * @method static PayTabsPay paytabs()
 * @method static ZitoPay zitopay()
 * @method static HyperPay hyperpay()
 *
 * @method setClientId($client_id)
 */
class XgPaymentGateway extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'XgPaymentGateway';
    }
}
