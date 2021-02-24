<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Great E-commerce Site</title>
</head>
<body>
    <table width="700px">
        <tr><td>&nbsp;</td></tr>
        <tr><td><img src="{{ asset('images/frontend_images/home/logo.png') }}" alt=""></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Hello {{ $name }},</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Thank you for shopping with us. Your order details are as below: </td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Order no: #{{ $order_id }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>
            <table width="95%" cellpading="5" cellspacing="5" bgcolor="#f7f4f4">
                <tr bgcolor="#cccccc">
                    <th>Product Name</th>
                    <th>Product Code</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                </tr>
                @foreach ($productDetails['orders'] as $product)
                  <tr>
                      <td align="center">{{ $product['product_name'] }}</td>
                      <td align="center">{{ $product['product_code'] }}</td>
                      <td align="center">{{ $product['product_size'] }}</td>
                      <td align="center">{{ $product['product_color'] }}</td>
                      <td align="center">{{ $product['product_qty'] }}</td>
                      <td align="center">{{ $product['product_price'] }}/Tk</td>
                  </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right">Shipping Charges</td>
                    <td>{{ $productDetails['shipping_charges'] }}/Tk</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Coupon Discount</td>
                    <td>{{ $productDetails['coupon_amount'] }}/Tk</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Grand Total</td>
                    <td>{{ $productDetails['grand_total'] }}/Tk</td>
                </tr>
            </table>
        </td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>
            <table width="100%">
                <tr>
                    <td width="50%">
                        <table>
                            <tr>
                                <td><strong>Bill To :-</strong></td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['name'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['address'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['city'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['state'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['country'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['pincode'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $userDetails['mobile'] }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table>
                            <tr>
                                <td><strong>Ship To :-</strong></td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['name'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['address'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['city'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['state'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['country'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['pincode'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $productDetails['mobile'] }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>For any enquiries, you can contact us at <a href="mailto:wuddin73@gmail.com">wuddin73@gmail.com</a></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Regards, <br> Team Great E-commerce Site</td></tr>
        <tr><td>&nbsp;</td></tr>
    </table>
</body>
</html>