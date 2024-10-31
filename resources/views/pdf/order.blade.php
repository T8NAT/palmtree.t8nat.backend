<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale" />
        <title></title>
    </head>
    <body>
        <table CellPadding="0px" style="border-radius: 5px 5px 5px 5px;">
            <tr>
                <td colspan="2">
                    <table>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">
                                <b>Shipment start date : {{ date('Y-m-d',strtotime($order->created_at)) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;font-size:10.5pt">
                               <strong>Seller Name : {{ $order->seller_name }}  </strong>
                               <strong>Client Name : {{ $order->customer_name }} </strong>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1" style="text-align: center">
                    <img width="200" style="margin: auto" src="{{ "https://palmtree.sadualshar.com/assets/img/favicon/fav.png" /*asset('logo.png')*/ }}" />
                </td>
            </tr>
        </table>
        <hr height="5px" style="margin-bottom:10px;color:(red 20%);background: linear-gradient(109.6deg, rgb(255, 230, 109) 11.2%, rgb(87, 232, 107) 100.2%);"/>
        <table>
            <tr>
                <td></td>
            </tr>
        </table>
        <table CellPadding="5px" style="border-radius: 10px 10px 10px 10px;">
            <tr>
                <td style="border:1px solid black;text-align: center;">
                  {{ $order->location_full_address }}
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                From
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    {{ $order->destination_full_address }}
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                   To
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    <strong style="font-weight:600">{{ $order->unique_id }}</strong>
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                   Token
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    <strong style="font-weight:600">{{ $order->seller_name }}</strong>
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                   Seller Name
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    <strong style="font-weight:600">{{ $order->customer_name }}</strong>
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                   Client Name
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    @php 
                        $invoice_url = url('show-invoice-pdf/'.$order->unique_id);
                        //print_r(DNS1D::getBarcodePNG($invoice_url, 'C39+'))
                    @endphp                    
                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($invoice_url,'QRCODE') }}" alt="barcode"   />
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                barcode
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    {{ $order->customer_notes }}
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                    Notes
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                Weight
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;text-align: center;">
                    {{ date('Y-m-d',strtotime($order->created_at)) }}
                </td>
                <td style="text-align: left;font-weight:600px;border:1px solid black">
                Time
                </td>
            </tr>
        </table>
    </body>
</html>