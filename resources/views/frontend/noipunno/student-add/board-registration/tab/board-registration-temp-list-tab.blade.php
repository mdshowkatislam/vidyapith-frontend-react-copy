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

<div class="dashboard-section">
    <section class="np-breadcumb-section">
        <div class="container mt-4 mb-5">
            <div class="row">
                <div class="col-lg-3 col-md-5 col-sm-12">
                    @include('/frontend/noipunno/student-add/board-registration/tab/sidebare')
                </div>

                <div class="col-lg-9 col-md-7 col-sm-12">
                    <div class="d-flex mb-2 justify-content-between">
                        <h5>বোর্ড রেজিস্ট্রেশনের জন্য {{ $class }} শ্রেণির অস্থায়ী শিক্ষার্থী তালিকা</h5>
                        @if(count($students)>0) <a href="{{ route('student.board_registration.temp.list_print', [$class_id, 0]) }}" target="_blanck" class="btn btn-primary nav-right-dorpdown" > <i class="fas fa-print"></i> Print Temporary List</a>@endif
                    </div>

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
                                        @forelse($students as $student)
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
                                                        <a target="_blanck" href="{{ route('student.print', $student->uid) }}"
                                                            class="np-route">
                                                            <button class="btn np-edit-btn-small">
                                                                <i class="fas fa-print text-light"></i>
                                                            </button>
                                                        </a>

                                                        <a href="{{ route('student.edit_board_reg', $student->uid) }}"
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
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <p>কোনো অস্থায়ী শিক্ষার্থী পাওয়া যায়নি</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(count($students)>0)
                            <form method="post" action="{{ route('student.board_registration.store') }}" id="finalSubmitForm">
                                @csrf
                                <input type="hidden" name="class" value="{{@$students[0]->student_class_info->classRoom->class_id}}">
                                @foreach ($students as $student)
                                <input type="hidden" name="students[]" value="{{$student->uid}}">
                                @endforeach
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="text-center ">
                                            <button type="button" class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn" id="finalSubmitBtn">Final Submit
                                                <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo">
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </form>
                            
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .btn-primary{
        background-color: #428f92;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    document.getElementById('finalSubmitBtn').addEventListener('click', function() {
        const total = @json(count($students));
        Swal.fire({
            icon: "warning",
            // title: `আপনি কি ${total} জন শিক্ষার্থী কে ফাইনাল নিবন্ধন করতে চাচ্ছেন?`,
            title : `Are you sure you want to finalize the registration for <b style="color:red">${total}</b> students?`,
            showDenyButton: true,
            showCancelButton: true,
            cancelButtonText: `Print`,
            denyButtonText: `Cancle`,
            confirmButtonText: "Save",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("Thank You!", "", "success");
                document.getElementById('finalSubmitForm').submit()
            } else if (result.isDenied) {
                Swal.fire("Cancle!", "", "success");
            }else if (result.dismiss === Swal.DismissReason.cancel) {
                const url = "{{ route('student.board_registration.temp.list_print', [$class_id, 0]) }}";
                window.open(url, '_blank');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var remainingStudent = {{ @$remainingStudent }};
        $('input[name="checkedStudents[]"]').on('change', function() {
            if ($('input[name="checkedStudents[]"]:checked').length > remainingStudent) {
                $(this).prop('checked', false);
                alert("You can only select a maximum of " + remainingStudent + " options.");
            }
        });
    });

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

    $(document).on('change', '#shift', function() {
        $('#class').trigger('change');
    });

    $(document).on('change', '#branch', function() {
        var branch_id = $('#branch').val();
        var eiin = {{ auth()->user()->eiin }};
        $.ajax({
            url: "{{ route('branch_wise_version') }}",
            type: "GET",
            data: {
                branch_id: branch_id,
                eiin: eiin,
            },
            success: function(data) {
                $('#version').html('');
                $('#shift').html('');

                var vselected = "{{ @$request_data['version'] }}";
                var sselected = "{{ @$request_data['shift'] }}";

                if (data.versions) {
                    $.each(data.versions, function(index, category) {
                        $('#version').append('<option value ="' + category.uid + '"' + ((
                                category
                                .uid == vselected) ? ('selected') : '') + '>' + category
                            .version_name +
                            '</option>');
                    });
                    $('#version').trigger('change');
                }
                if (data.shifts) {
                    $.each(data.shifts, function(index, category) {
                        $('#shift').append('<option value ="' + category.uid + '"' + ((
                                category
                                .uid == sselected) ? ('selected') : '') + '>' + category
                            .shift_name +
                            '</option>');
                    });
                    $('#shift').trigger('change');
                }
            }

        });
    });

    $(function() {
        $('#branch').trigger('change');
    });
</script>
<script>
    $(document).on('change', '#class', function() {
        var class_id = $('#class').val();
        var branch_id = $('#branch').val();
        var shift_id = $('#shift').val();
        var version_id = $('#version').val();
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
                $('#section').html('');
                var selected = "{{ @$request_data['section'] }}";

                if (data.sections) {
                    $.each(data.sections, function(index, category) {
                        $('#section').append('<option value ="' + category
                            .uid + '"' + ((category.uid == selected) ? ('selected') :
                                '') + '>' + category.section_name +
                            '</option>');
                    });
                    $('#section').trigger('change');
                }
            }
        });
    });
</script>
<script>
    $(document).on('change', '#section', function() {
        $('#s_branch').html($('#branch').find("option:selected").text());
        $('#s_shift').html($('#shift').find("option:selected").text());
        $('#s_version').html($('#version').find("option:selected").text());
        $('#s_class').html($('#class').find("option:selected").text());
        $('#s_section').html($('#section').find("option:selected").text());
    })
</script>
@endsection
