<html>
    <head>
        <style>
            .read{ color: red; font-weight: bold;}
            .green{ color: green;font-weight: bold;}
            .navy{ color: navy; font-weight: bold;}
            .blue{ color: blue; font-weight: bold;}
            .headtable{ background-color: grey;}
            .validate{  text-decoration: none;
            }
            .inputboxset{ height: 33px !important; width: 150px}
            table td { font-size: 13px; text-align:center; padding:3px}
            .tr_head{ font-size: 15px; background-color: green; color: white;}
            .customBtn{ width:116px;}
        </style>
    </head>
    <body onload="winPrint()">
    {{-- <body> --}}
        <div class="container" style=" min-height: 600px;">
            <div class="span13">
                <img style="position: absolute; width: 300px; height: 300px; display: none;" src="img/loading.gif" id="load">
                <!-- Mozila A4 paper size height:1234px; and width:794px  -->
                <!-- Crome A4 paper size height:1050px; and width:794px  -->
                <div id="printDiv" style="width:820px; min-height:1050px;  margin:0 auto">
                    <!-- hide div for print   -->
                    <div style="clear:both"></div>
                    <div style="width:70px; height:70px;  float:left;margin-left: 17px;"><img src="{{ asset('/assets/images/dhaka-board.png') }}" style=" width:70px; height:70px;"></div>
                    <div style="width:600px; float:right; margin-right: 99px;">
                        <center>
                            <span style="font-size:22px; font-weight:bold;color: #008000;">{{ getBoardData()->board_name_en }} </span>
                        </center>
                        <center>
                            <span style="font-size:18px; font-weight:bold;color: #008000;"> {{ auth()->user()->name}} ( {{ auth()->user()->eiin }} ) </span>
                        </center>
                        <div style="clear:both"></div>
                        <center>
                            <div>
                                @php
                                    $class_name = '-';
                                    if (@$class_id == 6){ $class_name = 'Six'; }
                                    elseif(@$class_id == 7){$class_name = 'Seven';}
                                    elseif(@$class_id == 8){$class_name = 'Eight';}
                                    elseif(@$class_id == 9){$class_name = 'Nine';}
                                    elseif(@$class_id == 10){$class_name = 'Ten';}
                                @endphp
                                <span style="font-size:20px; font-weight:bold;">Registration Session 2024<br> (Payment List) </span>
                                <!-- <button onclick="PrintDiv()"> Print </button> -->
                            </div>
                        </center>
                    </div>
                    <div style="clear:both"></div>
                    <div style="margin:5px">
                        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td scope="col">Depositor Name</td>
                                    <td scope="col">Depositor Mobile</td>
                                    <td scope="col">Class</td>
                                    <td scope="col">No of Students</td>
                                    <td scope="col">Amount</td>
                                </tr>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td scope="row">{{ @$payment->depositor_name }}</td>
                                        <td scope="row">{{ @$payment->depositor_mobile }}</td>
                                        <td scope="row">{{ @$payment->class }}</td>
                                        <td scope="row">{{ @$payment->no_of_students }}</td>
                                        <td scope="row" style="text-align: right">{{ number_format($payment->amount,2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td scope="col" colspan="3">Total</td>
                                    <td scope="col">{{ $payments->sum('no_of_students') }}</td>
                                    <td scope="col" style="text-align: right"><b>{{ number_format($payments->sum('amount'), 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- <div style="float:left; margin-top:60px; font-size:13px; color: #808080;">Page No: 1   {{ now()->format('d-M-Y') }}</div> --}}
                        <div style="float:left; margin-top:60px; font-size:13px; color: #808080;"> {{ now()->format('d-M-Y') }}</div>
                        <div style="float:right; margin-top:60px; font-size:13px; color: #808080;"> Seal &amp; Signature of Head of the Institute</div>
                    </div>
                </div>
                <p style="page-break-after:always ;"> </p>
                <center>
                    <div style="width:811px;">
                        <table width="100%" border="1" cellpadding="0" cellspacing="0">
                        </table>
                    </div>
                </center>
                <script type="text/javascript">
                    function winPrint()
                    {
                    
                     document.getElementById('load').style.display = 'none';
                    	window.print();
                    }
                    
                    
                    
                </script>
            </div>
        </div>
    </body>
</html>