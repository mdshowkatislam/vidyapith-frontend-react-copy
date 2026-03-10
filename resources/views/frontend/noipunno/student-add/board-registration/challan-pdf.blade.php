<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'kalpurush';
        }

        h2 h3 {
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 25px;
        }

        .table tr td {
            vertical-align: top;
            padding: 5px;
            font-size: 12px;
        }

        .table.border tr td {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .table-bordered th,
        .table-bordered td,
        .table-bordered tr {
            vertical-align: middle;
            padding: 0.75rem;
            border: .5px solid #a9a8a8;
            font-size: 12px;
        }

        .table-bordered tr td {
            font-size: 11px !important;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }
    </style>
    <title>Sonali Seba Challan</title>
</head>

<body>
    <div class="container">
        {{-- <div class="header"> --}}
        <htmlpageheader name="page-header">
            <div style="float:left; width: 50%; text-align: left;">
                <small style="font-size: 8px;"> {{ $time }} </small>
            </div>
            <div style="float:right; width: 50%; text-align: right;">
                <small style="font-size: 8px;"> সোনালী সেবা ফরম </small>
            </div>
            <table style="margin-top: 5px;">
                <tr>
                    <td width="33.33%"></td>
                    <td width="33.33%">
                        <div style="text-align: center; font-size: 20px;">
                            সোনালী ব্যাংক লিমিটেড
                        </div>
                        <div style="text-align: center; font-size: 12px;">
                            _____________________________ শাখা
                        </div>
                    </td>
                    <td width="33.33%" style="text-align: right">
                        <div style="text-align: right; font-size: 12px;">
                            নগদ/হস্তান্তর
                        </div>
                        <div style="text-align: right; font-size: 12px;">
                            ________________ জমা তারিখ
                        </div>
                        <div style="text-align: right; font-size: 12px;">
                            {{ en2bn($date_format) }} প্রিন্ট তারিখ
                        </div>
                    </td>
                </tr>
            </table>
            <div style="text-align: center; font-size: 12px;">
                মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, ঢাকা থেকে সেবা পেতে সোনালী সেবা সফটওয়্যার এর মাধ্যমে ফিস প্রেরণের আবেদন ফরম
            </div>
            <hr>
        </htmlpageheader>
        {{-- </div> --}}

        <div class="row" style="text-align: justify">
            <div>
                <div style="font-size: 12px;">
                    অনুগ্রহপূর্বক নিম্নলিখিত বর্ণনা অনুযায়ী জমাকৃত ফি সোনালী সেবা সফটওয়্যার এর মাধ্যমে প্রেরণ করিলে বাধিত হইব। এই জন্য আমি {{ @$payment->depositor_name}} নগদ/চেকের মাধ্যমে {{ @$payment->amount}} টাকা প্রদান করিলাম। উক্ত ফি আমি আমার নিজ খরচে ও ঝুঁকিতে সোনালী সেবা সফটওয়্যার এর মাধ্যমে প্রেরণের অনুরোধ জানাইতেছি। আমি এই মর্ম ঘোষণা করিতেছে যে, নেটওয়ার্ক কানেক্টিভিটি বা কারিগরি কারণে উক্ত ফি প্রেরণের বার্তা গন্তব্যে (প্রদানকারী শাখায়) পৌঁছানোর ক্ষেত্রে বিলম্ব বা ভুল, ত্রুটি  অথবা বার্তার ভুল ব্যাখ্যা ইত্যাদি কারণে পরিশোধে বিলম্বের জন্য প্রেরণকারী ব্যাংককে দায়ী করা হইবে না।
                </div>

                <table style="margin-top: 10px; margin-left: 0px;">
                    <tr>
                        <td width="66.66%" style="font-size: 12px;">
                            স্কুল/কলেজের নামঃ {{ @$payment->institute->institute_name ?? @$payment->institute->institute_name_bn }}

                        </td>
                        <td width="33.33%" style="font-size: 12px;">
                            EIIN: {{ @$payment->eiin }}
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 3px; margin-left: 0px;">
                    <tr>
                        <td width="33.33%" style="font-size: 12px;">
                            জমাকারীর নামঃ {{ @$payment->depositor_name}}
                        </td>
                        <td width="33.33%" style="font-size: 12px;">
                            মোবাইল নম্বরঃ {{ @$payment->depositor_mobile}}
                        </td>
                        <td width="33.33%" style="font-size: 12px;">
                            হিসাব নম্বরঃ 4408249000143029
                        </td>
                    </tr>
                </table>

                <table style="margin-top: 3px; margin-left: 0px;">
                    <tr>
                        <td width="33.33%" style="font-size: 12px;">
                            খাতের নামঃ REG
                        </td>
                        <td width="33.33%" style="font-size: 12px;">
                            পরীক্ষার নামঃ {{ @$payment->class}}
                        </td>
                        <td width="33.33%" style="font-size: 12px;">
                        </td>
                    </tr>
                </table>
                <div style="font-size: 12px;">
                    বি.দ্রঃ একাধিক খাতের ফি একই ফর্মে জমা দেয়া যাবে না।
                </div>
                <div style="font-size: 12px; text-align: right;">
                    প্রতিষ্ঠান প্রধানের স্বাক্ষর/জমাকারীর স্বাক্ষর
                </div>

                <table style="margin-top: 5px; margin-left: 0px;" border="1">
                    <tr style="text-align: center;">
                        <td style="text-align: center; font-size: 12px;">
                                নগদ টাকা বা চেকের বিবরণ
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                                ইস্যুকৃত সোনালী সেবা নম্বর
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                                প্রাপকের নাম ও ঠিকানা এবং ব্যাংক হিসাব নম্বর
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                                প্রাপক শাখার নাম
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                                প্রেরণের জন্য জমাকৃত টাকার পরিমান
                        </td>
                    </tr>
                    <tr style="text-align: center;">
                        <td style="text-align: center; font-size: 12px;">
                            ১
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                            ২
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                            ৩
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                            ৪
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                            ৫
                        </td>
                    </tr>

                    <tr style="text-align: center;">
                        <td style="text-align: left; font-size: 12px;">
                        </td>
                        <td style="text-align: left; font-size: 12px;">
                            {{ @$payment->transaction_id}}
                        </td>
                        <td style="text-align: left; font-size: 12px;">
                            সচিব, মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, ঢাকা। <br>
                            হিসাব নম্বরঃ 4408249000143029
                        </td>
                        <td style="text-align: left; font-size: 12px;">
                            সোনালী ব্যাংক লিমিটেড, <br> বিআইএসই, ঢাকা শাখা
                        </td>
                        <td style="text-align: right; font-size: 12px;">
                            {{ @$payment->amount}}
                        </td>
                    </tr>

                    <tr style="text-align: center;">
                        <td colspan="4" style="text-align: right; font-size: 12px;">
                            মোট টাকা
                        </td>
                        <td style="text-align: right; font-size: 12px;">
                            {{ @$payment->amount}}
                        </td>
                    </tr>
                </table>

                <table style="margin-top: 5px; margin-left: 0px;">
                    <tr style="text-align: center;">
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            ক্যাশিয়ার স্ক্রল নং
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            অফিসার (ক্যাশ)
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            অফিসার স্ক্রল নং
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            ট্রান্সফার স্ক্রল নং
                        </td>
                        <td width="28%" style="text-align: center; font-size: 12px;">
                            <table style="margin-top: 0px; margin-left: 0px;" border="1">
                                <tr style="text-align: center;">
                                    <td style="text-align: center; font-size: 12px;">
                                        ক
                                    </td>
                                    <td style="text-align: left; font-size: 12px;">
                                        কমিশন
                                    </td>
                                    <td style="text-align: right; font-size: 12px;">
                                            30
                                    </td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td style="text-align: center; font-size: 12px;">
                                            খ
                                    </td>
                                    <td style="text-align: left; font-size: 12px;">
                                        অনলাইন সার্ভিস চার্জ
                                    </td>
                                    <td style="text-align: right; font-size: 12px;">
                                            20
                                    </td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td style="text-align: center; font-size: 12px;">
                                            গ
                                    </td>
                                    <td style="text-align: left; font-size: 12px;">
                                        ভ্যাট = (ক+খ)X১৫%
                                    </td>
                                    <td style="text-align: right; font-size: 12px;">
                                            8
                                    </td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td style="text-align: center; font-size: 12px;">
                                            ঘ
                                    </td>
                                    <td style="text-align: left; font-size: 12px;">
                                            মোট টাকা (ক+খ+গ)
                                    </td>
                                    <td style="text-align: right; font-size: 12px;">
                                            58
                                    </td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td style="text-align: center; font-size: 12px;">

                                    </td>
                                    <td style="text-align: left; font-size: 12px;">
                                        সর্বমোট টাকা (৫+ঘ)
                                    </td>
                                    <td style="text-align: right; font-size: 12px;">
                                        {{ @$payment->amount + 58}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <hr>

                <table style="margin-top: 0px;">
                    <tr>
                        <td width="33.33%">
                            <div style="text-align: right; font-size: 12px;">
                                সোনালী সেবা নম্বর
                            </div>
                            <div style="text-align: right; font-size: 12px;">
                                {{ @$payment->transaction_id}}
                            </div>
                        </td>
                        <td width="33.33%">
                            <div style="text-align: center; font-size: 20px;">
                                সোনালী ব্যাংক লিমিটেড
                            </div>
                            <div style="text-align: center; font-size: 12px;">
                                _____________________________ শাখা
                            </div>
                        </td>
                        <td width="33.33%" style="text-align: right">
                            <div style="text-align: right; font-size: 12px;">
                                বোর্ডের অংশ
                            </div>
                            <div style="text-align: right; font-size: 12px;">
                                ________________ জমা তারিখ
                            </div>
                            <div style="text-align: right; font-size: 12px;">
                                {{ en2bn($date_format) }} প্রিন্ট তারিখ
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="font-size: 12px; margin-top: 10px;">
                    স্কুল/কলেজের নামঃ {{ @$payment->institute->institute_name ?? @$payment->institute->institute_name_bn }},
                    EIIN: {{ @$payment->eiin }},
                    জমাকারীর নামঃ {{ @$payment->depositor_name}},
                    খাতঃ REG এর বিপরীতে "সচিব, মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, ঢাকা, বিআইএসই, ঢাকা শাখা, হিসাব নম্বরঃ 4408249000143029, সোনালী ব্যাংক লিমিটেড এর অনুকূলে সোনালী সেবা সফটওয়্যার এর মাধ্যমে প্রেরণের জন্য নগদ/চেক নং ____________________ এর তাং ___________________ ব্যাংক ও শাখার নাম ______________________________________ এর মাধ্যমে ফি বাবদ {{ @$payment->amount }} টাকা, কমিশন 30 টাকা, অনলাইন সার্ভিস চার্জ 20 টাকা, ভ্যাট 8 টাকা। সর্বমোট {{ @$payment->amount + 58}} টাকা জমা করা হলো।
                </div>
                <table style="margin-top: 40px; margin-left: 0px;">
                    <tr style="text-align: center;">
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            ব্যাংক শাখার সিল
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            ব্যাংক স্ক্রল নং
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            অফিসার (ক্যাশ)/ক্যাশিয়ার এর স্বাক্ষর
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            অনুমোদিত ব্যাংক কর্মকর্তার স্বাক্ষর
                        </td>
                    </tr>
                </table>

                <hr>

                <table style="margin-top: 0px;">
                    <tr>
                        <td width="33.33%">
                            <div style="text-align: right; font-size: 12px;">
                                সোনালী সেবা নম্বর
                            </div>
                            <div style="text-align: right; font-size: 12px;">
                                {{ @$payment->transaction_id}}
                            </div>
                        </td>
                        <td width="33.33%">
                            <div style="text-align: center; font-size: 20px;">
                                সোনালী ব্যাংক লিমিটেড
                            </div>
                            <div style="text-align: center; font-size: 12px;">
                                _____________________________ শাখা
                            </div>
                        </td>
                        <td width="33.33%" style="text-align: right">
                            <div style="text-align: right; font-size: 12px;">
                                জমা প্রদানকারীর অংশ
                            </div>
                            <div style="text-align: right; font-size: 12px;">
                                ________________ জমা তারিখ
                            </div>
                            <div style="text-align: right; font-size: 12px;">
                                {{ en2bn($date_format) }} প্রিন্ট তারিখ
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="font-size: 12px; margin-top: 10px;">
                    স্কুল/কলেজের নামঃ {{ @$payment->institute->institute_name ?? @$payment->institute->institute_name_bn }},
                    EIIN: {{ @$payment->eiin }},
                    জমাকারীর নামঃ {{ @$payment->depositor_name}},
                    খাতঃ REG এর বিপরীতে "সচিব, মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, ঢাকা, বিআইএসই, ঢাকা শাখা, হিসাব নম্বরঃ 4408249000143029, সোনালী ব্যাংক লিমিটেড এর অনুকূলে সোনালী সেবা সফটওয়্যার এর মাধ্যমে প্রেরণের জন্য নগদ/চেক নং ____________________ এর তাং ___________________ ব্যাংক ও শাখার নাম ______________________________________ এর মাধ্যমে ফি বাবদ {{ @$payment->amount }} টাকা, কমিশন 30 টাকা, অনলাইন সার্ভিস চার্জ 20 টাকা, ভ্যাট 8 টাকা। সর্বমোট {{ @$payment->amount + 58}} টাকা জমা করা হলো।
                </div>
                <table style="margin-top: 40px; margin-left: 0px;">
                    <tr style="text-align: center;">
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            ব্যাংক শাখার সিল
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            ব্যাংক স্ক্রল নং
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            অফিসার (ক্যাশ)/ক্যাশিয়ার এর স্বাক্ষর
                        </td>
                        <td width="18%" style="text-align: center; font-size: 12px; vertical-align: bottom;">
                            অনুমোদিত ব্যাংক কর্মকর্তার স্বাক্ষর
                        </td>
                    </tr>
                </table>

                <div style="font-size: 12px; margin-top: 10px; line-height: 15px;">
                    বি.দ্রঃ একই সোনালী সেবা নং এর একাধিকবার (অর্থাৎ ফটোকপি করে অথবা একই জমা রশিদ একাধিকবার প্রিন্ট করে) টাকা জমা দেয়া যাবে না, ভুল করে একাধিকবার জমা করলেও একবার ধরা হবে।
                </div>
            </div>
        </div>

        <htmlpagefooter name="page-footer">
            <div style="float:left; width: 80%; text-align: left;"><small style="font-size: 8px;"> এই প্রতিবেদনটি
                    সিস্টেম দ্বারা তৈরি করা হয়েছে
                </small></div>
            <div style="float:right; width: 20%; text-align: right; font-size: 8px;">Page {PAGENO} of {nb}</div>
        </htmlpagefooter>
    </div>
</body>

</html>
