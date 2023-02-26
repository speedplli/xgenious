<html>
<head>
    <title>{{__('HyperPay Payment Gateway')}}</title>
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{!! $checkout_id !!}}"></script>
</head>
<body>
<form action="{{ $url }}" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>
</body>
</html>
