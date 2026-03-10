@extends('frontend.layouts.noipunno')

@section('content')
    <div class="dashboard-section">
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
                                        <h2 class="title">শিক্ষার্থীদের মূল্যায়ন প্রতিবেদন </h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="#">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        Dashboard
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page">মূল্যায়ন প্রতিবেদন
                                                </li>
                                            </ol>
                                        </nav>

                                    </div>
                                </div>
                                <div class="option-section">
                                    <div class="fav-icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}"
                                            alt="">
                                    </div>
                                    <div class="dots-icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- tab -->
        <div class="container mt-5">
            <ul class="nav nav-tabs np-student-tab-container" id="myTabs" role="tablist">
                <li class="nav-item np-student-tab" role="presentation">
                    <a class="nav-link active np-student-tab-link" id="tab1-tab" data-bs-toggle="tab" href="#tab1"
                        role="tab" aria-controls="tab1" aria-selected="true">
                        <img src="{{ asset('frontend/noipunno/images/icons/book.svg') }}" alt=""> বিদ্যালয় পরিচিতি
                    </a>
                </li>
                <li class="nav-item np-student-tab" role="presentation">
                    <a class="nav-link np-student-tab-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2"
                        role="tab" aria-controls="tab2" aria-selected="false">
                        <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"> শিক্ষক-শিক্ষিকা তথ্য
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <!-- Content for Tab 1 -->
                    <section class="section-teacher-add-form np-input-form-bg">
                        <div class="container">
                            <form action="{{ route('noipunno.dashboard.section.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">বিদ্যালয়ের EIIN</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                name="column_name">
                                                <option>123456</option>
                                                <option>123456</option>
                                                <option>123456</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">বিদ্যালয়ের নাম</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                name="column_name">
                                                <option>পাবনা জিলা স্কুল</option>
                                                <option>পাবনা জিলা স্কুল</option>
                                                <option>পাবনা জিলা স্কুল</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">SCHOOL NAME </label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                name="column_name">
                                                <option>PABNA ZILLA SCHOOL PABNA</option>
                                                <option>PABNA ZILLA SCHOOL PABNA</option>
                                                <option>PABNA ZILLA SCHOOL PABNA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label"> গ্রাম/বাড়ী ও সড়কের বিবরণ</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                name="column_name">
                                                <option>আব্দুল হামিদ রোড, গোপালপুর, পাবনা।</option>
                                                <option>আব্দুল হামিদ রোড, গোপালপুর, পাবনা।</option>
                                                <option>আব্দুল হামিদ রোড, গোপালপুর, পাবনা।</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">পোস্ট অফিস</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                name="column_name">
                                                <option>পাবনা</option>
                                                <option>পাবনা</option>
                                                <option>পাবনা</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">জেলা</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>পাবনা </option>
                                                <option>পাবনা </option>
                                                <option>পাবনা</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">Website </label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>www.pzs.edu.bd</option>
                                                <option>www.pzs.edu.bd</option>
                                                <option>www.pzs.edu.bd</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label"> E-Mai</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>pabnazilaschool.gmail.com</option>
                                                <option>pabnazilaschool.gmail.com</option>
                                                <option>pabnazilaschool.gmail.com</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">প্রধান শিক্ষকের নাম</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>তুষার কুমার দাশ</option>
                                                <option>তুষার কুমার দাশ</option>
                                                <option>তুষার কুমার দাশ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">ফোন নম্বর</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>১১১-২২২-৩৩৩ </option>
                                                <option>১১১-২২২-৩৩৩ </option>
                                                <option>১১১-২২২-৩৩৩</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">বিদ্যালয়ের ধরণ </label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>বালক</option>
                                                <option>বালক</option>
                                                <option>বালক</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <label for="beiin-psid" class="form-label">শিক্ষার্থীর সংখ্যা</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>2050</option>
                                                <option>2050</option>
                                                <option>2050</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="beiin-psid" class="form-label">বিদ্যালয়ের শিফট</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>দুই শিফট</option>
                                                <option>দুই শিফট</option>
                                                <option>দুই শিফট</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label for="beiin-psid" class="form-label">প্রভাতি শিফট</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>তৃতীয়-দশম </option>
                                                <option>তৃতীয়-দশম </option>
                                                <option>তৃতীয়-দশম</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label for="beiin-psid" class="form-label">দিবা শিফট </label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="column_name">
                                                <option>তৃতীয়-দশম</option>
                                                <option>তৃতীয়-দশম</option>
                                                <option>তৃতীয়-দশম</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                    <!-- Content for Tab 2 -->
                    <div class="row">
                        <div class="col-md-12">
                            <section class="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card np-card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table np-table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">ক্রমিক </th>
                                                                <th scope="col">নাম <span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                            alt=""></span></th>
                                                                <th scope="col">পিডিএস আইডি <span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                            alt=""></span></th>
                                                                <th scope="col">মূলপদ<span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                            alt=""></span></th>
                                                                <th scope="col">পদবী <span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                            alt=""></span></th>
                                                                <th scope="col">ফোন নম্বর <span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                            alt=""></span></th>
                                                                <th scope="col">যোগদানের তারিখ <span
                                                                        class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                            alt=""></span></th>
                                                                <th scope="col">অপারেশন</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td scope="row">1
                                                                </td>
                                                                <td scope="row">তুষার কুমার দাশ
                                                                </td>
                                                                <td scope="row">
                                                                    2016705265
                                                                </td>
                                                                <td scope="row">সিনিয়র শিক্ষক (ভৌত বিজ্ঞান)</td>
                                                                <td scope="row">প্রধান শিক্ষক (ভারঃ)</th>
                                                                <td scope="row">xxxxx-xxxxxx</td>
                                                                <td scope="row">xxxxx-xxxxxx</td>
                                                                <td scope="row">
                                                                    <div class="action-content">
                                                                        <h2 class="created-date">

                                                                        </h2>
                                                                        <a href=""#" class="np-route">
                                                                            <button class="btn np-edit-btn-small">
                                                                                <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                                    alt="">
                                                                            </button>
                                                                        </a>
                                                                        <img src="{{ asset('frontend/noipunno/images/icons/3-dots-horizontal.svg') }}"
                                                                            alt="">
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end tab -->

    </div>
    <style>
        .np-table th,
        td {
            font-size: 11px;
        }

        span.error {
            color: red;
        }
    </style>

    <script>
        function fetchDataForBranch(id) {
            $.ajax({
                url: '{{ route('student.getBranchData') }}',
                type: 'GET',
                data: {
                    id: id,
                    '_token': $('input[name="_token"]').val(),
                },
                success: function(data) {
                    $.each(data.versions, function(key, value) {
                        $('#version1').append('<option value="' + value.id + '">' + value.version_name +
                            '</option>');
                    });

                    $.each(data.shifts, function(key, value) {
                        $('#shift1').append('<option value="' + value.id + '">' + value.shift_name +
                            '</option>');
                    });
                }
            }, "json");
        }

        function fetchDataForSection(id) {
            var branch_id = document.getElementById('branch1').value,
                version_id = document.getElementById('version1').value,
                shift_id = document.getElementById('shift1').value,
                class_id = id;
            $.ajax({
                url: '{{ route('student.getSectionData') }}',
                type: 'GET',
                data: {
                    branch_id,
                    version_id,
                    shift_id,
                    class_id,
                    '_token': $('input[name="_token"]').val(),
                },
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#section1').append('<option value="' + value.id + '">' + value.section_name +
                            '</option>');
                    });
                }
            }, "json");
        }

        function fetchDataForBranch2(id) {
            $.ajax({
                url: '{{ route('student.getBranchData') }}',
                type: 'GET',
                data: {
                    id: id,
                    '_token': $('input[name="_token"]').val(),
                },
                success: function(data) {
                    $.each(data.versions, function(key, value) {
                        $('#version2').append('<option value="' + value.id + '">' + value.version_name +
                            '</option>');
                    });

                    $.each(data.shifts, function(key, value) {
                        $('#shift2').append('<option value="' + value.id + '">' + value.shift_name +
                            '</option>');
                    });
                }
            }, "json");
        }

        function fetchDataForSection2(id) {
            var branch_id = document.getElementById('branch2').value,
                version_id = document.getElementById('version2').value,
                shift_id = document.getElementById('shift2').value,
                class_id = id;
            $.ajax({
                url: '{{ route('student.getSectionData') }}',
                type: 'GET',
                data: {
                    branch_id,
                    version_id,
                    shift_id,
                    class_id,
                    '_token': $('input[name="_token"]').val(),
                },
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#section2').append('<option value="' + value.id + '">' + value.section_name +
                            '</option>');
                    });
                }
            }, "json");
        }

        function addEventListeners() {
            $('#branch1').on('change', (event) => {
                fetchDataForBranch(event.target.value);
            });

            $('#class1').on('change', (event) => {
                fetchDataForSection(event.target.value);
            });

            $('#branch2').on('change', (event) => {
                fetchDataForBranch2(event.target.value);
            });

            $('#class2').on('change', (event) => {
                fetchDataForSection2(event.target.value);
            });
        }

        addEventListeners();

        (function() {
            function Init() {
                var fileSelect = document.getElementById('file-upload'),
                    fileDrag = document.getElementById('file-drag'),
                    submitButton = document.getElementById('submit-button');

                fileSelect.addEventListener('change', fileSelectHandler, false);

                // Is XHR2 available?
                var xhr = new XMLHttpRequest();
                if (xhr.upload) {
                    // File Drop
                    fileDrag.addEventListener('dragover', fileDragHover, false);
                    fileDrag.addEventListener('dragleave', fileDragHover, false);
                    fileDrag.addEventListener('drop', fileSelectHandler, false);
                }
            }

            function fileDragHover(e) {
                var fileDrag = document.getElementById('file-drag');

                e.stopPropagation();
                e.preventDefault();

                fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
            }

            function fileSelectHandler(e) {
                // Fetch FileList object
                var files = e.target.files || e.dataTransfer.files;

                // Cancel event and hover styling
                fileDragHover(e);

                // Process all File objects
                for (var i = 0, f; f = files[i]; i++) {
                    parseFile(f);
                    uploadFile(f);
                }
            }

            function output(msg) {
                var m = document.getElementById('messages');
                m.innerHTML = msg;
            }

            function parseFile(file) {
                output(
                    '<ul>' +
                    '<li>Name: <strong>' + encodeURI(file.name) + '</strong></li>' +
                    '<li>Type: <strong>' + file.type + '</strong></li>' +
                    '<li>Size: <strong>' + (file.size / (1024 * 1024)).toFixed(2) + ' MB</strong></li>' +
                    '</ul>'
                );
            }

            function setProgressMaxValue(e) {
                var pBar = document.getElementById('file-progress');

                if (e.lengthComputable) {
                    pBar.max = e.total;
                }
            }

            function updateFileProgress(e) {
                var pBar = document.getElementById('file-progress');

                if (e.lengthComputable) {
                    pBar.value = e.loaded;
                }
            }

            function uploadFile(file) {

                var xhr = new XMLHttpRequest(),
                    fileInput = document.getElementById('class-roster-file'),
                    pBar = document.getElementById('file-progress'),
                    fileSizeLimit = 1024; // In MB
                if (xhr.upload) {
                    // Check if file is less than x MB
                    if (file.size <= fileSizeLimit * 1024 * 1024) {
                        // Progress bar
                        pBar.style.display = 'inline';
                        xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
                        xhr.upload.addEventListener('progress', updateFileProgress, false);

                        // File received / failed
                        xhr.onreadystatechange = function(e) {
                            if (xhr.readyState == 4) {
                                // Everything is good!

                                // progress.className = (xhr.status == 200 ? "success" : "failure");
                                // document.location.reload(true);
                            }
                        };

                        // Start upload
                        xhr.open('POST', document.getElementById('file-upload-form').action, true);
                        xhr.setRequestHeader('X-File-Name', file.name);
                        xhr.setRequestHeader('X-File-Size', file.size);
                        xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                        xhr.send(file);
                    } else {
                        output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
                    }
                }
            }

            // Check for the various File API support.
            if (window.File && window.FileList && window.FileReader) {
                Init();
            } else {
                document.getElementById('file-drag').style.display = 'none';
            }
        })();
    </script>
@endsection
