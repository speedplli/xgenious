<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{__('HyperPay Payment Gateway')}}</title>
    <script src="https://eu-{{$environment}}.oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkout_id}}"></script>
</head>
<body>
<form action="{{ $url }}" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>
<script type="text/javascript">
    var wpwlOptions = {
        paymentTarget: "_top",
        billingAddress: {
            country: "JO"
        },
        mandatoryBillingFields: {
            country: true,
            state: true,
            city: true,
            postcode: true,
            street1: true,
            street2: false
        }
    }
</script>
</body>
</html>
