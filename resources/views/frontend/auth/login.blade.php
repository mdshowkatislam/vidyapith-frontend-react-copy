@extends('frontend.layouts.app')

@section('content')
<div class="login-bg">
    <div class="container">
        <div class="">
            <div class="row d-flex justify-content-center align-items-center login-container">
                <div class="col-md-7 cols-sm-12">
                    <img src="{{ asset('frontend/images/noipunno-new-logo.svg') }}" alt="logo">
                    <p class="teacher-login-title">বিষয়ভিত্তিক মূল্যায়ন অ্যাপ্লিকেশন</p>
                    <p class="np-login-subtitle">অনুগ্রহ করে আপনার অ্যাকাউন্টে সাইন ইন করুন এবং অ্যাডভেঞ্চার শুরু করুন</p>

                </div>
                <div class="col-md-5 cols-sm-12">
                    <div class="card login-form-card">
                        <p class="teacher-login-title text-center">লগ ইন</p>
                        <!-- Form Start -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="id" class="login-field-title"> শিক্ষকের আইডি </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span>  <img src="{{ asset('frontend/noipunno/images/icons/user-square.svg') }}" class="np-login-field-icon" alt="logo"></span>
                                    </div>
                                    <input type="text" id="id" name="id" class="form-control np-login-form-field" placeholder="৯১৩১৫০৩০৩০৪০১" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pin" class="login-field-title"> পিন নম্বর </label>
                                <div class="input-group"><img src="{{ asset('frontend/noipunno/images/icons/lock.svg') }}" class="np-login-field-icon" alt="logo">
                                    <input type="password" id="pin" name="pin" class="form-control np-login-form-field" placeholder="•••••••••" required>
                                    <div class="input-group-append password-toggle">
                                        <span>
                                            <i id="password-toggle" class="fa fa-eye-slash" onclick="togglePassword()"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-check my-4">
                                <input type="checkbox" class="form-check-input np-login-checkbox" id="remember" name="remember" required>
                                <label class="form-check-label np-login-checbox-text" for="remember">পিন সংরক্ষণ করুণ</label>
                            </div>

                            <button type="submit" class="btn login-button"> <a href="{{ route('noipunno.dashboard') }}" class="login-next-page">লগ ইন করুন</a></button>
                        </form>
                        <!-- Form End -->
                    </div>
                </div>
            </div>

        </div>
        <div class="switch-container">
            <input type="checkbox" id="switch" class="language-switch">
            <label for="switch" class="switch-label">
                <small class="login-language">আপনি কি বাংলাকে আপনার ডিফল্ট ভাষা হিসেবে রাখতে চান ?</small>
                <div class="switch-rail">
                    <div class="switch-slider"></div>
                </div>

            </label>
        </div>
    </div>

</div>

<script>
    function togglePassword() {
        var passwordInput = document.getElementById("pin");
        var passwordToggle = document.getElementById("password-toggle");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordToggle.classList.remove("fa-eye-slash");
            passwordToggle.classList.add("fa-eye");
            passwordInput.setAttribute("placeholder", ""); // Clear placeholder
        } else {
            passwordInput.type = "password";
            passwordToggle.classList.remove("fa-eye");
            passwordToggle.classList.add("fa-eye-slash");
            passwordInput.setAttribute("placeholder", "•••••••••"); // Restore placeholder
        }
    }
</script>
@endsection