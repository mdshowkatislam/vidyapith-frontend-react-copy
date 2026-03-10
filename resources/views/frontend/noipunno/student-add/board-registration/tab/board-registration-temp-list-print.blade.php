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
                                <span style="font-size:20px; font-weight:bold;">List of Students applied for Class {{ $class_name }} Registration Session 2024<br> (Temporary List) </span>
                                <!-- <button onclick="PrintDiv()"> Print </button> -->
                            </div>
                        </center>
                    </div>
                    <table style="width:100%" class="table table-bordered table-hover dataTable">
                        <tbody>
                            <tr style="background-color: #00c0ef;">
                                <td class="text-right">Male:</td>
                                <td>{{ $maleCount }}</td>
                                <td class="text-right">Female:</td>
                                <td>{{ $femaleCount }}</td>
                                <td class="text-right">TOTAL:</td>
                                <td>{{ count($students) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="clear:both"></div>
                    <div style="margin:5px">
                        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="40px">eSIF SL.No.</td>
                                    <td>Candidate's Name/Father's Name/Mothers Name</td>
                                    <td>Gender<br>Dete of Birth</td>
                                    <td>Section <br> Roll<br>Religion</td>
                                    <td width="83px">Subjects</td>
                                    <td width="30px">Opt. <br>Sub</td>
                                    <td>D.O.B cert<br>Mobile number</td>
                                    <td>Photo</td>
                                    <td width="100px">Student Signature</td>
                                </tr>
                                @forelse($students as $student)
                                    <tr>
                                        <td scope="row">
                                            {{ $student->scroll_num }}
                                            <br>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td scope="row">
                                            {{ @$student->student_name_en ?? '-' }}
                                            <br>
                                            {{ @$student->father_name_en ?? '-' }}
                                            <br>
                                            {{ @$student->mother_name_en ?? '-'}}
                                        </td>
                                        <td scope="row">
                                            {{ @$student->gender ?? '-'}}
                                            <br>
                                            {{ @$student->date_of_birth ?? '-'}}
                                        </td>
                                        <td scope="row">
                                            {{ $student->student_class_info->classRoom->section->section_name }}
                                            <br>
                                            {{ $student->roll }}
                                            <br>
                                            {{ $student->religion }}
                                        </td>
                                        <td scope="row">
                                            -
                                        </td>
                                        <td scope="row">
                                            -
                                        </td>
                                        <td scope="row">
                                            {{ $student->brid }}
                                            <br>
                                            {{ $student->student_mobile_no }}
                                        </td>
                                        <td scope="row">
                                            @if($student->image)
                                                <img style="width:70px; height:70px;" src="{{ Storage::url(@$student->image) }}" alt="no img ">
                                            @endif
                                        </td>
                                        <td scope="row">
                                            @if($student->signature)
                                                <img style="width:70px; height:70px;" src="{{ Storage::url(@$student->signature) }}" alt="no img ">
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <p>কোনো অস্থায়ী শিক্ষার্থী পাওয়া যায়নি</p>
                                        </td>
                                    </tr>
                                @endforelse
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