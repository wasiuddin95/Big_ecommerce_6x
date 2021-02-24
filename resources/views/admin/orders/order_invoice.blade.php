<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <title>Great E-commerce Site</title>

<style>
    .invoice-title h2, .invoice-title h3 {
    display: inline-block;
    }

    .table > tbody > tr > .no-line {
    border-top: none;
    }

    .table > thead > tr > .no-line {
    border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
    border-top: 2px solid;
    }
</style>

</head>
<body>
    <div class="container">
        <div class="text-center">
            <img src="{{ asset('images/backend_images/logo3.png') }}" class="img-fluid" 
            style="height: 90px; width: 400px;" alt="">
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="invoice-title">
                    <h2>Invoice</h2><h3 class="pull-right">Order # {{ $orderDetails->id }}
                        <?php echo DNS1D::getBarcodeHTML($orderDetails->id, 'C39'); ?>
                    </h3>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                        <strong>Billed To:</strong><br>
                        {{ $userDetails->name }} <br>
                        {{ $userDetails->address }} <br>
                        {{ $userDetails->city }} <br>
                        {{ $userDetails->state }} <br>
                        {{ $userDetails->country }} <br>
                        {{ $userDetails->pincode }} <br>
                        {{ $userDetails->mobile }} <br>
                        </address>
                    </div>
                    <div class="col-xs-6 text-right">
                        <address>
                        <strong>Shipped To:</strong><br>
                        {{ $orderDetails->name }} <br>
                        {{ $orderDetails->address }} <br>
                        {{ $orderDetails->city }} <br>
                        {{ $orderDetails->state }} <br>
                        {{ $orderDetails->country }} <br>
                        {{ $orderDetails->pincode }} <br>
                        {{ $orderDetails->mobile }} <br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                            <strong>Payment Method:</strong><br>
                            @if ($orderDetails->payment_method=="COD")
                                <p>Cash On Delivery</p>
                            @elseif ($orderDetails->payment_method=="Paypal")
                                <p>Paypal Payment</p>
                            @elseif ($orderDetails->payment_method=="Bkash")
                                <p>Bkash Payment</p>
                            @endif
                        </address>
                    </div>
                    <div class="col-xs-6 text-right">
                        <address>
                            <strong>Order Date:</strong><br>
                            {{ $orderDetails->created_at }}<br><br>
                        </address>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Order summary</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <td><strong>Product Name &<br> Product Code</strong></td>
                                        <td class="text-center"><strong>Product Size &<br> Product Color</strong></td>
                                        <td class="text-center"><strong>Product Price &<br> Product Qty</strong></td>
                                        <td class="text-right"><strong>Totals</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                    <?php $subtotal = 0; ?>
                                    @foreach ($orderDetails->orders as $pro)
                                    <tr>
                                        <td class="text-left">{{ $pro->product_name }} <br>
                                          ({{ $pro->product_code }}) <br>
                                           <?php echo DNS1D::getBarcodeHTML($pro->product_code, 'C39'); ?>
                                        </td>
                                        <td class="text-center">{{ $pro->product_size }} <br>
                                        ({{ $pro->product_color }})</td>
                                        <td class="text-center">{{ $pro->product_price }}/Tk <br>
                                        ({{ $pro->product_qty }})</td>
                                        <td class="text-right">{{ $pro->product_price * $pro->product_qty }}/Tk</td>
                                    </tr>
                                    <?php $subtotal = $subtotal + ($pro->product_price * $pro->product_qty); ?>
                                    @endforeach
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line"></td>
                                        <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                        <td class="thick-line text-right">{{ $subtotal }}/Tk</td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Shipping Charges (+)</strong></td>
                                        <td class="no-line text-right">0/Tk</td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Coupon Discount (-)</strong></td>
                                        <td class="no-line text-right">{{ $orderDetails->coupon_amount }}/Tk</td>
                                    </tr>
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line"></td>
                                        <td class="thick-line text-center"><strong>Grand Total</strong></td>
                                        <td class="thick-line text-right">{{ $orderDetails->grand_total }}/Tk</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>