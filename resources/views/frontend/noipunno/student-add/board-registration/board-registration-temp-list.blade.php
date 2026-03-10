@extends('frontend.layouts.noipunno')
<style>
    .np-table th,
    td {
        font-size: 11px;
    }

    span.error {
        color: red;
    }

    input[type="number"]:read-only {
        cursor: normal;
        background-color: rgb(240, 240, 240);
    }
</style>
@section('content')
    <div class="dashboard-section mb-5">
        <section class="np-breadcumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card np-breadcrumbs-card">
                            <div class="card-body">
                                <div class="title-section">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/linear-book.svg') }}"
                                            alt="">
                                    </div>
                                    <div class="content">
                                        <h2 class="title">শিক্ষার্থী ব্যবস্থাপনা</h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a
                                                        href="{{ route('student.index') }}">
                                                        শিক্ষার্থী ব্যবস্থাপনা
                                                    </a></li>
                                            </ol>
                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- @include('frontend.layouts.notice') --}}

        <div class="container mt-4 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-list">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">অস্থায়ী শিক্ষার্থী তালিকা</h2>
                            </div>
                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">শিক্ষার্থীর রোল</th>
                                                        <th scope="col">শিক্ষার্থীর নাম</th>
                                                        <th scope="col">পিতার নাম</th>
                                                        <th scope="col">মাতার নাম</th>
                                                        {{-- <th scope="col">পিতার নাম</th> --}}
                                                        <th scope="col">শ্রেণি</th>
                                                        <th scope="col">লিঙ্গ</th>
                                                        <th scope="col">ধর্ম</th>
                                                        <th scope="col">জন্ম তারিখ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($students as $student)
                                                        <tr>
                                                            <td scope="row"><span class="icon"><img
                                                                src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                alt=""></span>
                                                                {{ @$student->student_class_info->roll }}
                                                            </td>
                                                            <td scope="row">{{ @$student->student_name_en ?? @$student->student_name_bn }}
                                                            </td>
                                                            <td scope="row">{{ @$student->father_name_en ?? @$student->father_name_bn }}</td>
                                                            {{-- <td scope="row">
                                                                {{ @$student->father_name_en ?? @$student->father_name_bn }}
                                                            </td> --}}
                                                            <td scope="row">
                                                                {{ @$student->mother_name_en ?? @$student->mother_name_bn }}
                                                            </td>
                                                            <td scope="row">
                                                                @if (@$student->student_class_info->classRoom->class_id == 6)
                                                                    Six
                                                                @elseif(@$student->student_class_info->classRoom->class_id == 7)
                                                                    Seven
                                                                @elseif(@$student->student_class_info->classRoom->class_id == 8)
                                                                    Eight
                                                                @elseif(@$student->student_class_info->classRoom->class_id == 9)
                                                                    Nine
                                                                @elseif(@$student->student_class_info->classRoom->class_id == 10)
                                                                    Ten
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td scope="row">
                                                                @if (@$student->gender == 'Male')
                                                                    ছাত্র
                                                                @elseif(@$student->gender == 'Female')
                                                                    ছাত্রী
                                                                @else
                                                                    <span class="btn np-delete-btn-small bg-warning"
                                                                        style="font-size: 12px; width: 90px !important; color: #000;">আপডেট করুন</span>
                                                                @endif
                                                            </td>
                                                            <td scope="row">
                                                                @if (@$student->religion == 'Islam')
                                                                    ইসলাম
                                                                @elseif(@$student->religion == 'Hinduism')
                                                                    হিন্দু
                                                                @elseif(@$student->religion == 'Christianity')
                                                                    খ্রিষ্টান
                                                                @elseif(@$student->religion == 'Buddhism')
                                                                    বৌদ্ধ
                                                                @else
                                                                    <span class="btn np-delete-btn-small bg-warning"
                                                                        style="font-size: 12px; width: 90px !important; color: #000;">আপডেট করুন</span>
                                                                @endif
                                                            </td>

                                                            <td scope="row">
                                                                @if (isset($student->dob))
                                                                    {{ $student->dob }}
                                                                @else
                                                                    <span class="btn np-delete-btn-small bg-warning"
                                                                        style="font-size: 12px; width: 90px !important; color: #000;">আপডেট করুন</span>
                                                                @endif
                                                            </td>
                                                            {{-- <td scope="row">{{ @$student->session_year }}</td> --}}
                                                            <td scope="row">
                                                                <div class="action-content">
                                                                        <a href="{{ route('student.edit', $student->uid) }}"
                                                                            class="np-route">
                                                                            <button class="btn np-edit-btn-small">
                                                                                <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                                    alt="">
                                                                            </button>
                                                                        </a>
                                                                    </div>
                                                            </td>

                                                            {{-- <td scope="row">
                                                                <input style="border: 1px solid var(--primary-color-1);"
                                                                    type="number" class="form-control np-teacher-input"
                                                                    id="roll" name="roll[{{ @$student->uid }}]"
                                                                    value="{{ @$student->roll ?? old('roll') }}">
                                                            </td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="post" action="{{ route('student.board_registration.store') }}" id="copyForm">
                                @csrf
                                <input type="hidden" name="class" value="{{@$students[0]->student_class_info->classRoom->class_id}}">
                                @foreach ($students as $student)
                                <input type="hidden" name="students[]" value="{{$student->uid}}">
                                @endforeach
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="text-center ">
                                        <button type="submit"
                                            class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn">তথ্য সংযোজন করুন
                                            <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo">
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                            </form>
                        </div>
                    </section>

                    {{-- <div>
                        <div class="np-pagination-section d-flex justify-content-end align-items-center">
                            <div class="np-select-page-number d-flex align-items-center">
                                {{ $versionList->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#checkAll').click(function() {
            $(':checkbox.checkItem').prop('checked', this.checked);
        });

        $(document).on('change', '#branch_uid', function() {
            var branch_id = $('#branch_uid').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('branch_wise_version') }}",
                type: "GET",
                data: {
                    branch_id: branch_id,
                    eiin: eiin,
                },
                success: function(data) {
                    $('#version_uid').html('');
                    $('#shift_uid').html('');

                    var vselected = "";
                    var sselected = "";

                    if (data.versions) {
                        $.each(data.versions, function(index, category) {
                            $('#version_uid').append('<option value ="' + category.uid + '"' + (
                                    (
                                        category
                                        .uid == vselected) ? ('selected') : '') + '>' +
                                category
                                .version_name +
                                '</option>');
                        });
                    }
                    if (data.shifts) {
                        $.each(data.shifts, function(index, category) {
                            $('#shift_uid').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == sselected) ? ('selected') : '') + '>' + category
                                .shift_name +
                                '</option>');
                        });
                    }
                }

            });
        });

        $(document).on('change', '#class_uid', function() {
            var class_id = $('#class_uid').val();
            var branch_id = $('#branch_uid').val();
            var shift_id = $('#shift_uid').val();
            var version_id = $('#version_uid').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('class_wise_section') }}",
                type: "GET",
                data: {
                    class_id: class_id,
                    branch_id: branch_id,
                    shift_id: shift_id,
                    eiin: eiin,
                    version_id: version_id
                },
                success: function(data) {
                    // $('#section_id').html('<option value ="">Please Select</option>');
                    $('#section_uid').html('');
                    var selected = "";

                    if (data.sections) {
                        $.each(data.sections, function(index, category) {
                            $('#section_uid').append('<option value ="' + category
                                .uid + '"' + ((category.uid == selected) ? ('selected') :
                                    '') + '>' + category.section_name +
                                '</option>');
                        });
                        $('#section_uid').trigger('change');
                    }
                }
            });
        });
        $(function() {
            $('#branch_uid').trigger('change');
        });
    </script>

@endsection
