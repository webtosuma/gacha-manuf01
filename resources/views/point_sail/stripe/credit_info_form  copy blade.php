<div class="h-100">
    @if (session('flash_alert'))
        <div class="alert alert-danger">{{ session('flash_alert') }}</div>
    @endif


    <div class="d-flex align-items-center h-100">
        <div class="w-100">
            <h5 class="mb-3">カードで支払い</h5>

            <div id="payment-form">
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>

                <button id="submit-payment">Submit Payment</button>
            </div>


        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe_public_key = "{{ config('stripe.public_key') }}"
    const stripe = Stripe(stripe_public_key);
    var elements = stripe.elements();

    var style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            '::placeholder': {
                color: '#aab7c4',
            },
        },
    };

    var card = elements.create('card', {style: style});
    card.mount('#card-element');

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    document.getElementById('submit-payment').addEventListener('click', function(event) {
        event.preventDefault();

        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // PaymentMethodの処理はここで行います。通常、サーバーサイドに送信し、支払いを完了させます。
                console.log(result.paymentMethod);
            }
        });
    });
</script>
