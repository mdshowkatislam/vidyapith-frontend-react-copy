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
            table td { font-size: 13px; text-align:left; padding:3px}
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
                                <span style="font-size:20px; font-weight:bold;">Registration Session 2024<br> (Student Infomation) </span>
                                <!-- <button onclick="PrintDiv()"> Print </button> -->
                            </div>
                        </center>
                    </div>
                    <div style="clear:both"></div>
                    <div style="margin:5px">
                        <fieldset>
                            <legend>Personal Information:</legend>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="18%">শিক্ষার্থীর নাম (ইংরেজি)</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="3">{{ $student->student_name_en ?? '-' }}</td>
                                        <td width="30%" rowspan="6" style="text-align: center">
                                            @if($student->image)
                                            <img src="{{ Storage::url(@$student->image) }}" class="img-fluid"
                                                                    alt="Main logo" style="height: 80px;">
                                            @else
                                                <img src="{{ asset('assets/images/demo-user-img.jpg') }}" class="img-fluid"
                                                                    alt="Main logo" style="height: 150px;">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">শিক্ষার্থীর নাম (বাংলা)</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="3">{{ $student->student_name_bn ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">পিতার নাম (ইংরেজি)</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="3">{{  $student->father_name_en ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">পিতার নাম (বাংলা)</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="3">{{ $student->father_name_bn ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">মাতার নাম (ইংরেজি)</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="3">{{  $student->mother_name_en ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">মাতার নাম (বাংলা)</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="3">{{ $student->mother_name_bn ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">জন্ম নিবন্ধন নং</td>
                                        <td width="1%">:</td>
                                        <td width="30%" colspan="4">{{ $student->brid ?? '-'}}</td>
                                    </tr>

                                    <tr>
                                        <td width="20%">জন্ম তারিখ</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{  $student->date_of_birth ?? '-'}}</td>
                                        <td width="20%">লিঙ্গ</td>
                                        <td width="1%">:</td>
                                        <td width="30%">
                                            @if($student->gender == 'Male')ছাত্র
                                            @elseif($student->gender == 'Female')ছাত্রী
                                            @elseif($student->gender == 'Other')অন্যান্য
                                            @else -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">ধর্ম</td>
                                        <td width="1%">:</td>
                                        <td width="30%">
                                            @if($student->religion == 'Islam')ইসলাম
                                            @elseif($student->religion == 'Hinduism')হিন্দু
                                            @elseif($student->religion == 'Christianity')খ্রিষ্টান
                                            @elseif($student->religion == 'Buddhism')বৌদ্ধ
                                            @elseif($student->religion == 'Other')Other
                                            @else -
                                            @endif
                                        </td>
                                        <td width="20%">মোবাইল নম্বর</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{ $student->student_mobile_no ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">পিতার মোবাইল</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{  $student->father_mobile_no ?? '-'}}</td>
                                        <td width="20%">মাতার মোবাইল নম্বর</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{ $student->mother_mobile_no ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">অভিভাবকের নাম</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{  $student->guardian_name_bn ?? '-'}}</td>
                                        <td width="20%">অভিভাবকের মোবাইল</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{ $student->guardian_mobile_no ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>

                        <fieldset>
                            <legend>Academic Information:</legend>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="18%">ব্রাঞ্চ</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{ $student_class_info->classRoom->branch->branch_name ?? '-' }}</td>
                                        <td width="20%">শিফট</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{ $student_class_info->classRoom->shift->shift_name ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">ভার্সন</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{ $student_class_info->classRoom->version->version_name?? '-' }}</td>
                                        <td width="20%">শ্রেণি</td>
                                        <td width="1%">:</td>
                                        <td width="30%">
                                            @if($student->class == 6) ষষ্ঠ  
                                            @elseif($student->class == 7) সপ্তম 
                                            @elseif($student->class == 8) অষ্টম 
                                            @elseif($student->class == 9) নবম 
                                            @else -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">রোল নম্বর </td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{  $student_class_info->roll ?? '-' }}</td>
                                        <td width="20%">বোর্ড রেজিস্ট্রেশন নম্বর</td>
                                        <td width="1%">:</td>
                                        <td width="30%">{{  $student->board_reg_no ?? '-'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>

                        <fieldset>
                            <legend>Address:</legend>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="10%">উপজেলা </td>
                                        <td width="1%">:</td>
                                        <td width="20%">{{  $student->upazilla->upazila_name_bn ?? '-' }}</td>
                                        <td width="10%">জেলা </td>
                                        <td width="1%">:</td>
                                        <td width="20%">{{ $student->district->district_name_bn ?? '-' }}</td>
                                        <td width="10%">বিভাগ </td>
                                        <td width="1%">:</td>
                                        <td width="20%">{{  $student->division->division_name_bn ?? '-' }}</td>
                                    </tr>
                                    {{-- <tr>
                                    </tr> --}}
                                    {{-- <tr>
                                        <td width="20%">জন্ম নিবন্ধন ফাইল</td>
                                        <td width="30%">
                                            @if($student->br_file)
                                            <img src="{{ Storage::url(@$student->br_file) }}" class="img-fluid"
                                                                    alt="Main logo" style="height: 80px;">
                                            @endif
                                        </td>
                                        <td width="20%">ডিসএবিলিটি ফাইল</td>
                                        <td width="30%">
                                            @if($student->disability_file)
                                            <img src="{{ Storage::url(@$student->disability_file) }}" class="img-fluid"
                                                                    alt="Main logo" style="height: 80px;">
                                            @endif
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </fieldset>
                            
                       
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