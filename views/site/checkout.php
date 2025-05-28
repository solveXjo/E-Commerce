<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cart $cart */
/** @var app\models\Order $orderModel */
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
    <!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Fresh and Organic</p>
                        <h1>Check Out Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- check out section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <?php if ($cart && !$cart->isEmpty()): ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="checkout-accordion-wrap">
                            <?php $form = ActiveForm::begin([
                                'id' => 'checkout-form',
                                'action' => ['site/process-checkout'],
                                'options' => ['class' => 'checkout-form']
                            ]); ?>

                            <div class="accordion" id="accordionExample">
                                <!-- Billing Address -->
                                <div class="card single-accordion">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Billing Address
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="billing-address-form">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?= $form->field($orderModel, 'billing_first_name')->textInput([
                                                            'placeholder' => 'First Name',
                                                            'class' => 'form-control',
                                                            'required' => true
                                                        ])->label(false) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $form->field($orderModel, 'billing_last_name')->textInput([
                                                            'placeholder' => 'Last Name',
                                                            'class' => 'form-control',
                                                            'required' => true
                                                        ])->label(false) ?>
                                                    </div>
                                                </div>

                                                <?= $form->field($orderModel, 'billing_email')->textInput([
                                                    'type' => 'email',
                                                    'placeholder' => 'Email Address',
                                                    'class' => 'form-control',
                                                    'required' => true
                                                ])->label(false) ?>

                                                <?= $form->field($orderModel, 'billing_phone')->textInput([
                                                    'type' => 'tel',
                                                    'placeholder' => 'Phone Number',
                                                    'class' => 'form-control',
                                                    'required' => true
                                                ])->label(false) ?>

                                                <?= $form->field($orderModel, 'billing_address')->textInput([
                                                    'placeholder' => 'Street Address',
                                                    'class' => 'form-control',
                                                    'required' => true
                                                ])->label(false) ?>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?= $form->field($orderModel, 'billing_city')->textInput([
                                                            'placeholder' => 'City',
                                                            'class' => 'form-control',
                                                            'required' => true
                                                        ])->label(false) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $form->field($orderModel, 'billing_postal_code')->textInput([
                                                            'placeholder' => 'Postal Code',
                                                            'class' => 'form-control',
                                                            'required' => true
                                                        ])->label(false) ?>
                                                    </div>
                                                </div>

                                                <?= $form->field($orderModel, 'order_notes')->textarea([
                                                    'placeholder' => 'Order Notes (Optional)',
                                                    'class' => 'form-control',
                                                    'rows' => 4
                                                ])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Address -->
                                <div class="card single-accordion">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Shipping Address
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shipping-address-form">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="same-as-billing" checked>
                                                    <label class="form-check-label" for="same-as-billing">
                                                        Same as billing address
                                                    </label>
                                                </div>

                                                <div id="shipping-fields" style="display: none;">
                                                    <?= $form->field($orderModel, 'shipping_address')->textInput([
                                                        'placeholder' => 'Shipping Address',
                                                        'class' => 'form-control'
                                                    ])->label(false) ?>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <?= $form->field($orderModel, 'shipping_city')->textInput([
                                                                'placeholder' => 'City',
                                                                'class' => 'form-control'
                                                            ])->label(false) ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?= $form->field($orderModel, 'shipping_postal_code')->textInput([
                                                                'placeholder' => 'Postal Code',
                                                                'class' => 'form-control'
                                                            ])->label(false) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div class="card single-accordion">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Payment Method
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="payment-method">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="credit-card" value="credit_card" checked>
                                                    <label class="form-check-label" for="credit-card">
                                                        Credit Card
                                                    </label>
                                                </div>

                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                                    <label class="form-check-label" for="paypal">
                                                        PayPal
                                                    </label>
                                                </div>

                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="cash-on-delivery" value="cod">
                                                    <label class="form-check-label" for="cash-on-delivery">
                                                        Cash on Delivery
                                                    </label>
                                                </div>

                                                <!-- Credit Card Fields -->
                                                <div id="credit-card-fields">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control mb-3" placeholder="Card Number" id="card-number">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" placeholder="MM/YY" id="expiry-date">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" placeholder="CVV" id="cvv">
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control mb-3" placeholder="Cardholder Name" id="cardholder-name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="order-details-wrap">
                            <table class="order-details">
                                <thead>
                                    <tr>
                                        <th>Your Order Details</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody class="order-details-body">
                                    <tr>
                                        <td><strong>Product</strong></td>
                                        <td><strong>Total</strong></td>
                                    </tr>
                                    <?php foreach ($cart->cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <?= Html::encode($item->product->Name) ?>
                                                <span class="product-quantity">× <?= $item->Quantity ?></span>
                                            </td>
                                            <td>$<?= number_format($item->Price * $item->Quantity, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tbody class="checkout-details">
                                    <tr>
                                        <td>Subtotal</td>
                                        <td id="order-subtotal">$<?= number_format($cart->getSubtotal(), 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tax (10%)</td>
                                        <td id="order-tax">$<?= number_format($cart->getTaxAmount(), 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Shipping</td>
                                        <td id="shipping-cost">$15</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td><strong>Total</strong></td>
                                        <td><strong id="order-total">$<?= number_format($cart->getTotalWithTax() + 15.00, 2) ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Hidden fields for order totals -->
                            <input type="hidden" id="hidden-subtotal" value="<?= $cart->getSubtotal() ?>">
                            <input type="hidden" id="hidden-tax" value="<?= $cart->getTaxAmount() ?>">
                            <input type="hidden" id="hidden-shipping" value="15.00">
                            <input type="hidden" id="hidden-total" value="<?= $cart->getTotalWithTax() + 15.00 ?>">

                            <button type="button" class="boxed-btn btn-primary mt-3" id="place-order-btn" onclick="processOrder()">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty Cart Message -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="empty-cart-message">
                            <h3>Your cart is empty!</h3>
                            <p>Add some products to your cart before proceeding to checkout.</p>
                            <a href="<?= Url::to(['site/products']) ?>" class="boxed-btn">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- end check out section -->

    <script>
        // Handle shipping address toggle
        $('#same-as-billing').change(function() {
            if ($(this).is(':checked')) {
                $('#shipping-fields').hide();
            } else {
                $('#shipping-fields').show();
            }
        });

        // Handle payment method changes
        $('input[name="payment_method"]').change(function() {
            if ($(this).val() === 'credit_card') {
                $('#credit-card-fields').show();
            } else {
                $('#credit-card-fields').hide();
            }
        });

        // Process order function
        function processOrder() {
            // Validate required fields
            if (!validateCheckoutForm()) {
                return;
            }

            // Show loading state
            $('#place-order-btn').html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);

            // Collect form data
            const formData = {
                // Billing information
                billing_first_name: $('input[name="Order[billing_first_name]"]').val(),
                billing_last_name: $('input[name="Order[billing_last_name]"]').val(),
                billing_email: $('input[name="Order[billing_email]"]').val(),
                billing_phone: $('input[name="Order[billing_phone]"]').val(),
                billing_address: $('input[name="Order[billing_address]"]').val(),
                billing_city: $('input[name="Order[billing_city]"]').val(),
                billing_postal_code: $('input[name="Order[billing_postal_code]"]').val(),

                // Shipping information
                same_as_billing: $('#same-as-billing').is(':checked'),
                shipping_address: $('input[name="Order[shipping_address]"]').val(),
                shipping_city: $('input[name="Order[shipping_city]"]').val(),
                shipping_postal_code: $('input[name="Order[shipping_postal_code]"]').val(),

                // Order details
                order_notes: $('textarea[name="Order[order_notes]"]').val(),
                payment_method: $('input[name="payment_method"]:checked').val(),

                // Totals
                subtotal: $('#hidden-subtotal').val(),
                tax: $('#hidden-tax').val(),
                shipping: $('#hidden-shipping').val(),
                total: $('#hidden-total').val(),

                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            };

            // Submit order
            $.ajax({
                url: '/site/process-checkout',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Redirect to success page
                        window.location.href = '/site/order-success?order=' + response.orderId;
                    } else {
                        alert(response.message || 'Failed to process order. Please try again.');
                        $('#place-order-btn').html('Place Order').prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                    $('#place-order-btn').html('Place Order').prop('disabled', false);
                }
            });
        }

        // Form validation
        function validateCheckoutForm() {
            const requiredFields = [
                'input[name="Order[billing_first_name]"]',
                'input[name="Order[billing_last_name]"]',
                'input[name="Order[billing_email]"]',
                'input[name="Order[billing_phone]"]',
                'input[name="Order[billing_address]"]',
                'input[name="Order[billing_city]"]',
                'input[name="Order[billing_postal_code]"]'
            ];

            let isValid = true;

            requiredFields.forEach(function(selector) {
                const field = $(selector);
                if (!field.val().trim()) {
                    field.addClass('is-invalid');
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            });

            // Validate email format
            const email = $('input[name="Order[billing_email]"]').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                $('input[name="Order[billing_email]"]').addClass('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                alert('Please fill in all required fields correctly.');
            }

            return isValid;
        }

        // Auto-calculate totals if needed
        function recalculateTotals() {
            // This function can be used if you need to recalculate based on shipping options
            const subtotal = parseFloat($('#hidden-subtotal').val());
            const tax = subtotal * 0.1;
            const shipping = 15.00; // You can make this dynamic based on location
            const total = subtotal + tax + shipping;

            $('#order-subtotal').text('$' + subtotal.toFixed(2));
            $('#order-tax').text('$' + tax.toFixed(2));
            $('#shipping-cost').text('$' + shipping.toFixed(2));
            $('#order-total').text('$' + total.toFixed(2));

            // Update hidden fields
            $('#hidden-tax').val(tax.toFixed(2));
            $('#hidden-total').val(total.toFixed(2));
        }
    </script>

    <style>
        .product-quantity {
            color: #888;
            font-size: 0.9em;
        }

        .total-row {
            border-top: 2px solid #ddd;
            font-weight: bold;
        }

        .empty-cart-message {
            padding: 50px 0;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .form-control {
            margin-bottom: 15px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .checkout-form .form-group {
            margin-bottom: 0;
        }

        #credit-card-fields {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
    </style>

    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- count down -->
    <script src="assets/js/jquery.countdown.js"></script>
    <!-- isotope -->
    <script src="assets/js/jquery.isotope-3.0.6.min.js"></script>
    <!-- waypoints -->
    <script src="assets/js/waypoints.js"></script>
    <!-- owl carousel -->
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- magnific popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- mean menu -->
    <script src="assets/js/jquery.meanmenu.min.js"></script>
    <!-- sticker js -->
    <script src="assets/js/sticker.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>

</body>

</html>