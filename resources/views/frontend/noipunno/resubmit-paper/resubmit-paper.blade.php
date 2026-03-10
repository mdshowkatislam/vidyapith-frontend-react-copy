@extends('frontend.layouts.noipunno')
@section('content')
    <div class="dashboard-section" style="min-height: 70vh">

        <section class="mt-4 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-center" style="background: #baeded14">
                            <div class="card-body w-100">

                                    @if(request()->status_code == 0)

                                    <div class="title-section">
                                        <div class="content">
                                            <div class="text-center">
                                                <img src="{{ asset('/assets/icons/warning.svg') }}" width="36px">
                                            </div>
                                            <h6 class="title text-center" style="color: #000; text-align: center !important;">
                                                একইসাথে অতিরিক্ত ব্যবহারকারী ষাণ্মাসিক সামষ্টিক প্রশ্নপত্র ও মূল্যায়ন নির্দেশিকা ডাউনলোডের চেষ্টা করার কারনে সেবাটি তাৎক্ষনিকভাবে নিশ্চিত করা সম্ভব হচ্ছে না। দয়া করে পূনরায় চেষ্টা করুন।
                                            </h6>
                                        </div>
                                    </div>
                                    @elseif (request()->status_code == 1)
                                    <div class="title-section">
                                        <div class="content">
                                            <div class="text-center">
                                                <img src="{{ asset('/assets/icons/warning.svg') }}" width="36px">
                                            </div>
                                            <h6 class="title text-center" style="color: #000; text-align: center !important;">
                                                আপনি যে পৃষ্ঠাটি এ্যাক্সেস করার চেষ্টা করছেন প্রয়োজনীয় রক্ষানাবেক্ষন কাজের জন্য সেটি সাময়িকভাবে নিষ্ক্রিয় রয়েছে। অনুগ্রহ করে পরবর্তিতে চেষ্টা করুন।
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                        <a type="submit" class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;" href="{{route('institute.paper')}}"> ষাণ্মাসিক সামষ্টিক প্রশ্নপত্র ডাউনলোড করুন
                            <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></a>
                    </div>
                </div>

            </div>
    </div>
    </section>
    </div>



    {{-- <script>
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
    </script> --}}
@endsection
