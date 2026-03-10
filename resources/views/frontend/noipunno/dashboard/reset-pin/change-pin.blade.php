@extends('frontend.layouts.noipunno')
@section('content')
    <div class="dashboard-section">
        {{-- Top section starts --}}
        <section class="np-breadcumb-section">
            <div class="container">
                <div class="row">


                    @if (Session::has('success'))
                        <div class="col-md-12 mt-2">
                            <div class="alert alert-success" role="alert">
                                <p><strong>{{ Session::get('success') }}</strong></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div class="card np-breadcrumbs-card">
                            <div class="card-body">
                                <div class="title-section">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/linear-book.svg') }}"
                                            alt="">
                                    </div>
                                    <div class="content">
                                        <h2 class="title">পিন পরিবর্তন</h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page">পিন পরিবর্তন</li>
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

        <section class="mt-4 mb-5">
            <div class="container">
                <div class="row">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="p-2 m-2 text-white rounded-2">
                            <div class="card shadow" style="border: none; min-width: 450px;">
                                <div id="alertContainer"></div>
                                <div style="background-color: #e4feff;border-bottom:none" class="card-header">
                                    <h4 class="bn text-center p-2">পিন পরিবর্তন</h4>
                                </div>
                                <div class="card-body">
                                    <form id="resetPinForm">

                                        <div class="mb-3">
                                            <label for="new_pin" class="form-label">নতুন পিনটি দিন</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="new_pin" name="password" oninput="validatePasswordsMatch()"
                                                    minlength="6" maxlength="6" pattern="[0-9]{6}"
                                                    onkeypress="return onlyNumbers(event)">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePasswordVisibility('new_pin')">
                                                    <i id="new_pin_visibility" class="fa-solid fa-eye"></i>
                                                </button>
                                            </div>
                                            <div id="newPasswordAlert" style="color: red;display: none; margin-top:10px">
                                                নতুন পিনটি দিন
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="conf_pin" class="form-label">
                                                নতুন পিনটি পুনরায় দিন</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="conf_pin"
                                                    name="password_confirmation" minlength="6" maxlength="6"
                                                    pattern="[0-9]{6}" onkeypress="return onlyNumbers(event)"
                                                    oninput="validatePasswordsMatch()">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePasswordVisibility('conf_pin')">
                                                    <i id="conf_pin_visibility" class="fa-solid fa-eye"></i>
                                                </button>
                                            </div>
                                            <div id="confirmPasswordAlert"class="alert"
                                                style="display: none; color: red;">
                                                নতুন পিনটি দিন
                                            </div>
                                            <div id="pinMatchError" style="display: none; color: red; margin-top:10px">নতুন পিনটি এবং পুনরায়
                                                পিনটি মিলছে না।</div>
                                        </div>

                                        <input type="hidden" name="user_type_id"
                                            value="{{ auth()->user()->user_type_id }}">
                                        <input type="hidden" name="caid" value="{{ auth()->user()->caid }}">
                                        <button id="pinSubBtn" type="submit" class="btn btn-primary">জমা দিন</button>

                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>



    <script>
        const apiUrl = "{{ env('API_GATEWAY_URL') }}";
        const accessToken = "{{ request()->bearerToken() ?? App\Helper\UtilsCookie::getCookie() }}";

        function onlyNumbers(event) {

            var newPassword = document.getElementById('new_pin').value;

            const newPasswordAlert = document.getElementById('newPasswordAlert');
            const confirmPasswordAlert = document.getElementById('confirmPasswordAlert');

            if (newPassword === '') {
                newPasswordAlert.style.display = 'block';
                confirmPasswordAlert.style.display = 'none';
            } else {
                newPasswordAlert.style.display = 'none';
                confirmPasswordAlert.style.display = 'none';
            }

            var keyCode = event.which || event.keyCode;
            if (keyCode >= 48 && keyCode <= 57 || keyCode === 8 || keyCode === 37 || keyCode === 39) {
                return true;
            } else {
                return false;
            }

        }

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const visibilityIcon = document.getElementById(inputId + '_visibility');

            if (input.type === 'password') {
                input.type = 'text';
                visibilityIcon.classList.remove('fa-eye');
                visibilityIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                visibilityIcon.classList.remove('fa-eye-slash');
                visibilityIcon.classList.add('fa-eye');
            }
        }

        var pinSubBtn = document.getElementById('pinSubBtn');
        pinSubBtn.disabled = true;

        function validatePasswordsMatch() {
            var newPassword = document.getElementById('new_pin').value;
            var confirmPassword = document.getElementById('conf_pin').value;
            var errorDiv = document.getElementById('pinMatchError');

            if (newPassword !== confirmPassword) {
                errorDiv.style.display = 'block';
                pinSubBtn.disabled = true;
            } else {
                errorDiv.style.display = 'none';
                pinSubBtn.disabled = false;
            }
        }

        
        document.getElementById('resetPinForm').addEventListener('submit', function(event) {
            event.preventDefault(); 

            const form = event.target;
            const formData = new FormData(form);

            fetch(apiUrl + "v2/account-change-pin", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "Authorization": "Bearer " + accessToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("সার্ভারে সমস্যা ! আবার চেষ্টা করুন ");
                    }
                    return response.json();
                })
                .then(data => {
                    //console.log(data);
                    if (data.status === true) {
                        window.location.href = "{{ route('home') }}";
                    } else {
                        console.log("Response is not true");
                    }
                })
                .catch(error => {
                    console.error("সার্ভারে সমস্যা ! আবার চেষ্টা করুন :", error);
                });
        });
    </script>
@endsection
