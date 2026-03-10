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
                        <h5>{{ $class }} শ্রেণির নিবন্ধিত শিক্ষার্থী তালিকা</h5>
                        @if(count($students)>0) <a href="{{ route('student.board_registration.temp.list_print',[$class_id,1]) }}" target="_blanck" class="btn btn-primary nav-right-dorpdown" > <i class="fas fa-print"></i> Print Final List</a>@endif
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
                                            <th scope="col">শ্রেণি</th>
                                            <th scope="col">লিঙ্গ</th>
                                            <th scope="col">ধর্ম</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $student)
                                            <tr>
                                                <td scope="row"><span class="icon"><img
                                                    src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                    alt=""></span>
                                                    {{ @$student->student_class_info->roll }}
                                                </td>
                                                <td scope="row">{{ @$student->student_name_en ?? @$student->student_name_bn }}
                                                </td>
                                                <td scope="row">{{ @$student->father_name_en ?? @$student->father_name_bn }}</td>
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
                                                    @endif
                                                </td>

                                                <td scope="row">
                                                        {{ $student->dob }}
                                                </td>
                                                <td scope="row">
                                                    <div class="action-content">
                                                        <a target="_blanck" href="{{ route('student.print', $student->uid) }}"
                                                            class="np-route">
                                                            <button class="btn np-edit-btn-small">
                                                                <i class="fas fa-print text-light"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <p>কোনো নিবন্ধিত শিক্ষার্থী পাওয়া যায়নি</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
{{-- <style>
    .btn-primary{
        background-color: #428f92;
    }
</style> --}}

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
