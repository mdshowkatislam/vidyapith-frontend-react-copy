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
                    <div id="alertOtp"></div>
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="p-2 m-2 text-white rounded-2">
                            <div class="card shadow" style="border: none; width: 450px;">
                                <div style="background-color:#e4feff" class="card-header">
                                    <h4 class="bn text-center p-2">পিন পরিবর্তন</h4>
                                </div>
                                <div class="card-body" id="bodyResetPin">
                                    <form id="resetPin">
                                        <div class="mb-3" style="font-size: 16px;">
                                            <label class="form-label">ইউজার আইডি</label>
                                            <div class="input-group"><input type="eiin" id="eiin"
                                                    class="form-control" readonly="" name="eiin"
                                                    value="{{ auth()->user()->eiin }}"></div>
                                        </div>

                                        <div class="mb-3" style="font-size: 16px;">
                                            <label class="form-label">ইউজার ইমেইল</label>
                                            <div class="input-group"><input type="email" class="form-control"
                                                    readonly="" value="{{ auth()->user()->email }}"></div>
                                        </div>
                                        <div class="mb-3" style="font-size: 16px;">
                                            <label class="form-label">ইউজার মোবাইল</label>
                                            <div class="input-group"><input type="mobile" class="form-control"
                                                    readonly="" value="{{ auth()->user()->phone_no }}"></div>
                                        </div>

                                        <div class="mb-3" style="font-size: 16px;">
                                            <label class="form-label">কোন মাধ্যমে ওটিপি পেতে চান?</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="1"
                                                    name="otp_send_option" id="inlineRadio1" value="option1" checked>
                                                <label class="form-check-label" for="inlineRadio1">মোবাইল</label>
                                            </div>
                                            <div class="form-check form-check-inline">

                                                <input class="form-check-input" value="2" type="radio"
                                                    name="otp_send_option" id="inlineRadio2" value="option2">
                                                <label class="form-check-label" for="inlineRadio2">ইমেইল</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_type_id"
                                            value="{{ auth()->user()->user_type_id }}">
                                        <input type="hidden" name="caid" value="{{ auth()->user()->caid }}">
                                        <button type="submit" class="btn login-button px-5"
                                            style="background-color: rgb(66, 143, 146); color: rgb(255, 255, 255); width: 200px;">ওটিপি
                                            পাঠান</button>
                                    </form>
                                </div>

                                <div class="card-body d-none" id="bodyOtpPin">
                                    <div id="alertContainer"></div>
                                    <form id="otpBox">
                                        <input type="hidden" name="user_type_id"
                                            value="{{ auth()->user()->user_type_id }}">
                                        <input type="hidden" name="caid" value="{{ auth()->user()->caid }}">

                                        <div class="text-center mx-auto otp-card">
                                            <p class="text-center p-2 mb-2">ওটিপি</p>

                                            <div class="row">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-2">
                                                    <input type="text" id="otpInput1" name="code1"
                                                        class="np-otp-form form-control" required maxlength="1"
                                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                                        pattern="[0-9]{1}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" id="otpInput2" name="code2"
                                                        class="np-otp-form form-control" required maxlength="1"
                                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                                        pattern="[0-9]{1}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" id="otpInput3" name="code3"
                                                        class="np-otp-form form-control" required maxlength="1"
                                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                                        pattern="[0-9]{1}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" id="otpInput4" name="code4"
                                                        class="np-otp-form form-control" required maxlength="1"
                                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                                        pattern="[0-9]{1}">
                                                </div>
                                            </div>

                                        </div>
                                        <input type="hidden" id="pinOtp" name="pin" value="">
                                        <button type="submit" class="btn login-button px-5 mt-3"
                                            style="background-color: rgb(66, 143, 146); color: rgb(255, 255, 255); width:60%;">নিশ্চিত</button>
                                            <button type="button" id="resend-otp" style="display: none;" class="btn btn-outline-secondary btn-sm">ওটিপিটি
                                                পুনরায় পাঠান</button>    
                                    </form>

                                    <div class="d-flex justify-content-center mt-2 mb-2">
                                        <div class="progress-container">
                                            <div class="progress-bar">
                                                <div class="progress"></div>
                                                <div class="timer"></div>
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


    <style>
        .progress-container {
            position: relative;
            width: 70px;
            height: 70px;
        }

        .progress-bar {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #ccc;
            overflow: hidden;
        }

        .progress {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 6px solid green;
            border-top-color: transparent;
            animation: progress 120s linear forwards;
        }

        .timer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: bold;
        }
    </style>

    <script>
        function startTimer(duration) {
            let timerDisplay = document.querySelector('.timer');
            let progress = document.querySelector('.progress');
            const resendButton = document.getElementById('resend-otp');

            let timer = duration;
            let minutes, seconds;

            let timerInterval = setInterval(updateTimer, 1000);

            function updateTimer() {
                minutes = Math.floor(timer / 60);
                seconds = timer % 60;

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.textContent = minutes + ':' + seconds;

                let progressPercent = (duration - timer) / duration * 100;
                progress.style.transform = `rotate(${360 * (progressPercent / 100)}deg)`;

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    timerDisplay.textContent = '00:00';
                    // Timer finished, handle logic here
                    //resendButton.style.display = 'block';

                }
            }
        }
        

        const apiUrl = "{{ env('API_GATEWAY_URL') }}";
        const accessToken = "{{ request()->bearerToken() ?? App\Helper\UtilsCookie::getCookie() }}";
        document.getElementById("resetPin").addEventListener("submit", function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch(apiUrl + "v2/account-otp", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "Authorization": "Bearer " + accessToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("সার্ভারে সমস্যা ! আবার চেষ্টা করুন");
                    }
                    return response.json();
                })
                .then(data => {
                    //console.log(data);
                    if (data.status === true) {
                        document.getElementById("bodyResetPin").style.display = "none";
                        document.getElementById("bodyOtpPin").classList.remove("d-none");
                        const alertElement = document.createElement("div");
                        alertElement.classList.add("alert", "alert-success");
                        alertElement.textContent = data.message;
                        document.getElementById("alertContainer").appendChild(alertElement);
                        startTimer(120);
                    } else {
                        // Handle other cases if needed
                        console.log("Response is not true");
                    }
                })
                .catch(error => {
                    console.error("সার্ভার সমস্যা হয়েছে !!  আবার চেষ্টা করুন :", error);
                });
        });

        $(document).ready(function() {
            const $pinInput = $('#pinOtp');
            const $otpInputs = $('input[type="text"]');

            $otpInputs.on('input', function(event) {
                const $input = $(this);
                const inputValue = $input.val();

                if (inputValue.length === 1) {
                    const index = $otpInputs.index($input);
                    if (index < $otpInputs.length - 1) {
                        $otpInputs.eq(index + 1).focus();
                    }
                }

                const pinValue = $otpInputs.map(function() {
                    return $(this).val();
                }).get().join('');

                $pinInput.val(pinValue);
                //console.log(pinValue);
            });


            $otpInputs.on('keydown', function(event) {
                const $input = $(this);
                const index = $otpInputs.index($input);

                if (event.key === 'Backspace') {
                    if (index > 0 && !$input.val()) {
                        $otpInputs.eq(index - 1).focus();
                    }
                }
            });
        });



        document.getElementById("otpBox").addEventListener("submit", function(event) {
            event.preventDefault();
            const formData2 = new FormData(this);
            fetch(apiUrl + "v2/account-otp-verify-change-pin", {
                    method: "POST",
                    body: formData2,
                    headers: {
                        "Authorization": "Bearer " + accessToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        const alertElement = document.createElement("div");
                        alertElement.classList.add("alert", "alert-danger");
                        alertElement.textContent = "আপনার ওটিপিটি সঠিক নয়।";
                        document.getElementById("alertOtp").appendChild(alertElement);
                    }
                    return response.json();
                })
                .then(data => {
                    //console.log(data);
                    if (data.status === true) {
                        document.getElementById("otpBox").reset();
                        window.location.href = "{{ route('change_new_pin') }}";
                    } else {
                        // Handle other cases if needed
                        console.log("Response is not true");
                    }

                })
                .catch(error => {
                    //console.log(error);
                    const alertElement = document.createElement("div");
                    alertElement.classList.add("alert", "alert-danger");
                    alertElement.textContent = "আপনার ওটিপিটি সঠিক নয়।";
                    document.getElementById("alertOtp").appendChild(alertElement);
                });
        });
    </script>
@endsection
