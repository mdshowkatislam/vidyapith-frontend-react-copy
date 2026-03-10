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
    <title>Transfer Certificate</title>
</head>

<body>
    <div class="container">
        {{-- <div class="header"> --}}
        <htmlpageheader name="page-header">
            <div style="text-align: center; font-size: 36px; line-height: 36px;">
                {{ @$student->classRoom->institute->institute_name_bn ?? @$student->classRoom->institute->institute_name }}
            </div>
            <div style="text-align: center; font-size: 14px; margin-top: 10px;">
                {{ @$student->classRoom->institute->board->board_name_bn ?? @$student->classRoom->institute->board->board_name_en }}
            </div>
            <div style="text-align: center; font-size: 14px;">
                উপজেলা:
                {{ @$student->classRoom->institute->upazila->upazila_name_bn ?? @$student->classRoom->institute->upazila->upazila_name_en }},
                জেলা:
                {{ @$student->classRoom->institute->district->district_name_bn ?? @$student->classRoom->institute->district->district_name_en }},
                বিভাগ:
                {{ @$student->classRoom->institute->division->division_name_bn ?? @$student->classRoom->institute->division->division_name_en }}
            </div>
            <div style="text-align: center; font-size: 14px;">EIIN: {{ @$student->classRoom->eiin }},
                Mobile: {{ @$student->classRoom->institute->phone ?? @$student->classRoom->institute->mobile }}
                @if (@$student->classRoom->institute->email)
                    , Email: {{ @$student->classRoom->institute->email }}
                @endif
            </div>

        </htmlpageheader>
        {{-- </div> --}}

        <div class="row" style="text-align: justify">
            <div>
                <div style="text-align: center; font-size: 36px;">বিদ্যালয় পরিত্যাগের ছাড়পত্র</div>
                <div style="font-size: 16px;">এই মর্মে প্রত্যয়ন করা যাইতেছে যে,
                    <span
                        style="text-decoration: underline; font-weight: bold !important;">{{ $student->student_info->student_name_bn ?? ($student->student_info->student_name_en ?? 'N/A') }}
                    </span> (নৈপূণ্য শিক্ষার্থী আইডিঃ
                    <span style="text-decoration: underline; font-weight: bold;">{{ en2bn($student->student_uid) }}),
                    </span>
                    পিতা: <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->student_info->father_name_bn ?? ($student->student_info->father_name_en ?? 'N/A') }}</span>,
                    মাতা: <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->student_info->mother_name_bn ?? ($student->student_info->mother_name_en ?? 'N/A') }}</span>

                    অত্র বিদ্যালয়ের
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->classRoom->branch->branch_name }}</span>
                    এর
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->classRoom->version->version_name }}</span>
                    ভার্সনের
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->classRoom->shift->shift_name }}</span>
                    শিফটের
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ en2bn($student->session_year) }}</span>
                    শিক্ষাবর্ষের
                    <span style="text-decoration: underline; font-weight: bold;">{{ $class }}</span> শ্রেণী
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->classRoom->section->section_name }}</span>
                    শাখার একজন নিয়মিত
                    {{ $student->student_info->gender == 2 ? 'ছাত্রী' : 'ছাত্র' }}
                    ছিল। তাহার শ্রেণী রোল
                    <span style="text-decoration: underline; font-weight: bold;">{{ en2bn($student->roll) }}</span>
                    এবং বিগত বার্ষিক পরীক্ষায় অংশগ্রহণ করে <span
                        style="text-decoration: underline; font-weight: bold;">{{ $class }}</span>
                    শ্রেণীতে উত্তীর্ণ হইয়াছে। সে
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ en2bn(date('d-m-Y', strtotime($student_transfer->issue_date))) }}</span>
                    খ্রিস্টাব্দ তারিখ পর্যন্ত অত্র বিদ্যালয়ে অধ্যয়ন করিয়াছে। তাহার জন্ম তারিখ
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->student_info->date_of_birth ? en2bn(date('d-m-Y', strtotime($student->student_info->date_of_birth))) : 'N/A' }}</span>
                    এবং জন্ম নিবন্ধন নম্বর
                    <span
                        style="text-decoration: underline; font-weight: bold;">{{ $student->student_info->brid ? en2bn($student->student_info->brid) : 'N/A' }}</span>।
                    <br><br>
                    আমার জানামতে সে উত্তম চরিত্রের অধিকারী। আমি তাহার সর্বাঙ্গীন সাফল্য কামনা করিতেছি।
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 16px;">
            <div>
                <div style="font-size: 20px;">বিদ্যালয় পরিত্যাগের কারণঃ
                </div>
                <div style="font-size: 16px; text-align: justify;">{{ $student_transfer->reason }}
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 50px;">
            <table>
                <tr>
                    <td>
                        <div style="font-size: 14px; margin-top: -50px">তারিখঃ {{ en2bn(date('d-m-Y')) }}</div>
                    </td>
                    <td style="text-align: right;">
                        <div style="text-align: right;">
                            {{-- <div style="width: 200px; height: 1px; background-color: #000;"></div> --}}
                            <div style="font-size: 14px; margin-top: 5px; border-top: 1px solid #000;">
                                প্রধান শিক্ষকের স্বাক্ষর
                            </div>
                            <div style="font-size: 14px;">
                                {{ $student->classRoom->institute->head_master->name_bn ?? @$student->classRoom->institute->head_master->name_en }}
                            </div>
                            <div style="font-size: 14px;">PDSID:
                                {{ en2bn(@$student->classRoom->institute->head_master->pdsid ?? (@$student->classRoom->institute->head_master->index_number ?? @$student->classRoom->institute->head_master->caid)) }}
                            </div>
                            <div style="font-size: 14px;">
                                {{ @$student->classRoom->institute->head_master->designations->designation_name ?? 'N/A' }}
                            </div>

                            <div style="font-size: 14px;">{{ @$student->classRoom->institute->institute_name_bn ?? @$student->classRoom->institute->institute_name }}</div>

                        </div>
                    </td>
                </tr>
            </table>

        </div>

        <htmlpagefooter name="page-footer">
            <div style="float:left; width: 80%; text-align: left;"><small style="font-size: 8px;"> এই প্রতিবেদনটি
                    সিস্টেম দ্বারা তৈরি করা হয়েছে
                </small></div>
            {{-- <div style="float:right; width: 20%; text-align: right; font-size: 8px;">Page {PAGENO} of {nb}</div> --}}
        </htmlpagefooter>
    </div>
</body>

</html>
