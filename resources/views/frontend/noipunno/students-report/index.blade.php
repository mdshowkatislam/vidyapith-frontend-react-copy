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
                  <img src="{{ asset('frontend/noipunno/images/icons/linear-book.svg') }}" alt="">
                </div>
                <div class="content">
                  <h2 class="title">শিক্ষার্থীদের মূল্যায়ন প্রতিবেদন </h2>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb np-breadcrumb">
                      <li class="breadcrumb-item"><a href="#">
                          <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}" alt="">
                          Dashboard
                        </a></li>
                      <li class="breadcrumb-item active" aria-current="page">মূল্যায়ন প্রতিবেদন </li>
                    </ol>
                  </nav>

                </div>
              </div>
              <div class="option-section">
                <div class="fav-icon">
                  <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}" alt="">
                </div>
                <div class="dots-icon">
                  <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}" alt="">
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
        <a class="nav-link active np-student-tab-link" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab"
          aria-controls="tab1" aria-selected="true">
          <img src="{{ asset('frontend/noipunno/images/icons/book.svg') }}" alt=""> পারদর্শিতার মূল্যায়ন
          প্রতিবেদন (PI)
        </a>
      </li>
      <li class="nav-item np-student-tab" role="presentation">
        <a class="nav-link np-student-tab-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab"
          aria-controls="tab2" aria-selected="false">
          <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"> আচরণগত মূল্যায়ন প্রতিবেদন (BI)
        </a>
      </li>
    </ul>

    <div class="tab-content" id="myTabsContent">
      <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
        <!-- Content for Tab 1 -->

        <div class="row">
          <div class="col-md-12 col-sm-12">
            <!-- student add -->
            <section class="np-teacher-add-form">
              <div class="np-input-form-bg">
                <div class="container">
                  <form action="{{ route('noipunno.dashboard.shift.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="row-gap: 20px;">

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">শ্রেণী নির্বাচন করুন </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">সেশন নির্বাচন করুন </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">শাখা নির্বাচন করুন  </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">বিষয় নির্বাচন করুন  </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">মূল্যায়ন শিরোনাম নির্বাচন করুন  </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">যোগ্যতা নির্বাচন করুন  </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div>
                          <label for="beiin-psid" class="form-label">পারদর্শিতার সূচক নির্বাচন করুন  </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example"
                              name="branch_id">
                              <option value="">Please Class</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center"
                          style="width: fit-content;border: unset;column-gap: 10px;">নিম্নে মূল্যায়ন প্রতিবেদন দেখুন
                          <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></button>
                      </div>
                    </div>

                  </form>

                  <div class="row mt-5">
                    <h2 class="assesment-report-title">শিখনকালীন মূল্যায়ন প্রতিবেদন  (PI)</h2>

                    <div class="col-md-12">
                      <div class="accordion np-accordion" id="accordionExample">
                        <div class="accordion-item np-card">
                          <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              <div class="text-content-items">
                                <h2 class="text-content-title">শিক্ষার্থীর নাম: ইনতিশার পারভেজ </h2>
                                <h2 class="text-content-sub-title">রোল নম্বর  #৩২১০০ </h2>
                              </div>
                              <div class="button-section">
                                <a href="#" class="download-btn" style="z-index: 999;">
                                  <img src="{{ asset('frontend/noipunno/images/icons/document-download.svg') }}" alt="">
                                  ডাউনলোড করুন
                                </a>
                                <span class="np-accordion-icon">
                                  <img src="{{ asset('frontend/noipunno/images/icons/arrow-down.svg') }}" alt="">
                                </span>
                              </div>
                            </button>
                          </h2>

                          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                              <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                    <img src="{{ asset('frontend/noipunno/images/icons/book.svg') }}" alt="">
                                    বাংলা
                                  </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                                    <img src="{{ asset('frontend/noipunno/images/icons/book.svg') }}" alt="">
                                    গনিত
                                  </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                  <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                                    <img src="{{ asset('frontend/noipunno/images/icons/book.svg') }}" alt="">
                                    ইংরেজি
                                  </button>
                                </li>
                              </ul>
                              <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <div class="pi-list">
                                    <div class="pi">
                                      <div class="row">
                                        <div class="col-md-3">
                                          <div class="pi-title">
                                            <h2 class="title">পারদর্শিতা সূচক ৬.১.১ </h2>
                                            <h2 class="sub-title">নিজের এবং অন্যের প্রয়োজন ও  আবেগ বিবেচনায় নিয়ে যোগাযোগ করতে পারছে।</h2>
                                          </div>
                                        </div>

                                        <div class="col-md-9">
                                          <div class="pi-items">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="pi-item np-card">
                                                  <div class="content">
                                                    <h2>অন্যের সাথে যোগাযোগের সময়ে নিজের চাহিদা প্রকাশ করতে পারছে।</h2>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="pi-item np-card pi-item-active">
                                                  <div class="content d-flex align-items-start">
                                                    <div class="check-icon">
                                                      <i class="fas fa-check"></i>
                                                    </div>
                                                    <h2>অন্যের কাছে নিজের চাহিদা প্রকাশ করার সময় ঐ ব্যক্তির আগ্রহ, চাহিদা ও আবেগ বিবেচনায় নিতে পারছে।</h2>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="pi-item np-card">
                                                  অন্যের কাছে নিজের চাহিদা প্রকাশ করার সময় পরিবেশ - পরিস্থিতির ভিন্নতা অনুযায়ী ব্যক্তির আগ্রহ, চাহিদা ও আবেগ বিবেচনায় নিয়ে যোগাযোগ করতে পারছে।
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      
                                      
                                    </div>
                                  </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                  ..
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                              </div>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
        <!-- Content for Tab 2 -->
        <div class="">
          <div class="row">
            <div class="col-md-12">

              <section class="np-teacher-add-form">
                <div class="np-input-form-bg">

                </div>
              </section>
            </div>
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
      url: '{{ route("student.getBranchData") }}',
      type: 'GET',
      data: {
        id: id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function (data) {
        $.each(data.versions, function (key, value) {
          $('#version1').append('<option value="' + value.id + '">' + value.version_name + '</option>');
        });

        $.each(data.shifts, function (key, value) {
          $('#shift1').append('<option value="' + value.id + '">' + value.shift_name + '</option>');
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
      url: '{{ route("student.getSectionData") }}',
      type: 'GET',
      data: {
        branch_id,
        version_id,
        shift_id,
        class_id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function (data) {
        $.each(data, function (key, value) {
          $('#section1').append('<option value="' + value.id + '">' + value.section_name + '</option>');
        });
      }
    }, "json");
  }

  function fetchDataForBranch2(id) {
    $.ajax({
      url: '{{ route("student.getBranchData") }}',
      type: 'GET',
      data: {
        id: id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function (data) {
        $.each(data.versions, function (key, value) {
          $('#version2').append('<option value="' + value.id + '">' + value.version_name + '</option>');
        });

        $.each(data.shifts, function (key, value) {
          $('#shift2').append('<option value="' + value.id + '">' + value.shift_name + '</option>');
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
      url: '{{ route("student.getSectionData") }}',
      type: 'GET',
      data: {
        branch_id,
        version_id,
        shift_id,
        class_id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function (data) {
        $.each(data, function (key, value) {
          $('#section2').append('<option value="' + value.id + '">' + value.section_name + '</option>');
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

  (function () {
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
          xhr.onreadystatechange = function (e) {
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