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

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-list mt-4">
                        {{-- <div class="container"> --}}
                        <div class="row">
                            <div class="col-md-12 mb-2 d-flex justify-content-between">
                                <h2 class="np-form-title">শিক্ষার্থী প্রতিষ্ঠানে যুক্ত করার আবেদনের তালিকা</h2>
                                {{-- @if (count($payments) > 0) <a href="{{ route('student.board_registration.payment.list_print') }}" target="_blanck" class="btn nav-right-dorpdown" > <i class="fas fa-print"></i> Print Payment List</a>@endif --}}
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Eiin</th>
                                                        <th scope="col">Class</th>
                                                        <th scope="col">Count</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($requests as $eiin => $classes)
                                                        @php
                                                            $institute = \App\Models\Institute::where('eiin', $eiin)->first();
                                                        @endphp
                                                        @foreach ($classes as $class => $students)
                                                            <tr>
                                                                <td scope="row"><span class="icon"> <img
                                                                            src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                            alt=""></span>{{ $institute->institute_name ?? $institute->institute_name_bn }}
                                                                    ({{ @$eiin }})</td>
                                                                <td scope="row">
                                                                    {{ @$class }}
                                                                </td>
                                                                <td scope="row">
                                                                    {{ @$students->count() }}
                                                                </td>
                                                                <td scope="row">
                                                                    <a href="{{ route('student.attached_institute_request_student.list', [$eiin, $class]) }}"
                                                                        class="np-route">
                                                                        <button class="btn np-edit-btn-small"
                                                                            style="font-size: 12px; width: 200px !important; color: #fff;">Click
                                                                            here to see students list
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </section>
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
