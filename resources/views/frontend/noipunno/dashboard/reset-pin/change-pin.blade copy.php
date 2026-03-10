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
                                        <div class="input-container">
                                            <input type="password" name="password" id="password" minlength="6" maxlength="6"
                                                onkeypress="return onlyNumbers(event)" aria-describedby="requirements"
                                                required />
                                            <label for="password">নতুন পিনটি দিন</label>
                                            <button class="show-password" id="show-password" type="button" role="switch"
                                                aria-label="Show password" aria-checked="false">Show</button>
                                        </div>

                                        <div id="requirements" class="password-requirements">
                                            <p class="requirement" id="length">কমপক্ষে ৬ টি সংখ্যা</p>
                                            <p class="requirement" id="number">শুধু নাম্বার দিন</p>

                                        </div>

                                        <div class="input-container">
                                            <input type="password" name="password_confirmation" id="confirm-password" minlength="6" maxlength="6"
                                                onkeypress="return onlyNumbers(event)" required />
                                            <label for="confirm-password">নতুন পিনটি পুনরায় দিন</label>
                                        </div>

                                        <div class="password-requirements">
                                            <p class="requirement hidden error" id="match">পিনটি অবশ্যই মিলতে হবে</p>
                                        </div>

                                        <input type="hidden" name="user_type_id"
                                            value="{{ auth()->user()->user_type_id }}">
                                        <input type="hidden" name="caid" value="{{ auth()->user()->caid }}">

                                        <div class="submit-container">
                                            <input type="submit" id="submit" value="জমা দিন" disabled />
                                        </div>
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

    <style>
        form {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            width: 98%;
            max-width: 600px;
            /* background-color: white; */
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 0.5rem;
            /* box-shadow: 0 0 8px 0 rgb(0 0 0 / 8%), 0 0 15px 0 rgb(0 0 0 / 2%), 0 0 20px 4px rgb(0 0 0 / 6%); */
        }

        .input-container {
            background-color: #f5f5f5;
            position: relative;
            border-radius: 4px 4px 0 0;
            height: 56px;
            transition: background-color 500ms;
        }

        .input-container:hover {
            background-color: #ececec;
        }

        .input-container:focus-within {
            background-color: #dcdcdc;
        }

        label {
            display: block;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 16px;
            color: rgba(0, 0, 0, 0.5);
            transform-origin: left top;
            user-select: none;
            transition: transform 150ms cubic-bezier(0.4, 0, 0.2, 1), color 150ms cubic-bezier(0.4, 0, 0.2, 1), top 500ms;
        }

        input {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            background: transparent;
            caret-color: var(--accent-color);
            border: 1px solid transparent;
            border-bottom-color: rgba(0, 0, 0, 0.42);
            color: rgba(0, 0, 0, 0.87);
            transition: border 500ms;
            padding: 20px 16px 6px;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-bottom-width: 2px;
            border-bottom-color: var(--accent-color);
        }

        input:focus+label {
            color: var(--accent-color);
        }

        input:focus+label,
        input.is-valid+label {
            transform: translateY(-100%) scale(0.75);
        }

        input[type=submit] {
            transition: .25s;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            padding: 16px;
            background-color: white;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 14px;
        }

        input[type=submit]:disabled {
            color: #808080;
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        input[type=submit]:not(:disabled):hover {
            border-color: blueviolet;
            background-color: var(--accent-color-opaque);
            color: var(--accent-color);
        }

        .submit-container {
            border-radius: 4px;
            margin-top: 1rem;
            height: 56px;
        }

        .show-password {
            transition: opacity .25s;
            position: absolute;
            background-color: transparent;
            right: 0;
            margin: auto;
            top: 0;
            bottom: 0;
            height: fit-content;
            border: none;
            font-size: 10px;
            color: grey;
            cursor: pointer;
            outline: none;
            text-transform: uppercase;
        }

        .show-password:hover,
        .show-password:focus {
            color: black;
        }

        .input-container:not(:hover, :focus-within) .show-password {
            opacity: 0;
        }

        .password-requirements {
            display: flex;
            flex-wrap: wrap;
            margin-top: -1rem;
            padding: 0 16px;
        }

        .requirement {
            font-size: 14px;
            flex: 1 0 50%;
            min-width: max-content;
            margin: 5px 0;
        }

        .requirement:before {
            content: '\2639';
            padding-right: 5px;
            font-size: 1.6em;
            position: relative;
            top: .15em;
        }

        .requirement:not(.valid) {
            color: #808080;
        }

        .requirement.valid {
            color: #4CAF50;
        }

        .requirement.valid:before {
            content: '\263A';
        }

        .requirement.error {
            color: red;
        }

        .hidden {
            display: none;
        }
    </style>

    <script>
        const apiUrl = "{{ env('API_GATEWAY_URL') }}";
        const accessToken = "{{ request()->bearerToken() ?? App\Helper\UtilsCookie::getCookie() }}";

        function onlyNumbers(event) {
            var keyCode = event.which || event.keyCode;
            if (keyCode >= 48 && keyCode <= 57 || keyCode === 8 || keyCode === 37 || keyCode === 39) {
                return true;
            } else {
                return false;
            }

        }
        const inputs = document.querySelectorAll("input");
        const form = document.getElementById("resetPinForm");
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirm-password");
        const showPassword = document.getElementById("show-password");
        const matchPassword = document.getElementById("match");
        const submit = document.getElementById("submit");

        inputs.forEach((input) => {
            input.addEventListener("blur", (event) => {
                if (event.target.value) {
                    input.classList.add("is-valid");
                } else {
                    input.classList.remove("is-valid");
                }
            });
        });

        showPassword.addEventListener("click", (event) => {
            if (password.type == "password") {
                password.type = "text";
                confirmPassword.type = "text";
                showPassword.innerText = "hide";
                showPassword.setAttribute("aria-label", "hide password");
                showPassword.setAttribute("aria-checked", "true");
            } else {
                password.type = "password";
                confirmPassword.type = "password";
                showPassword.innerText = "show";
                showPassword.setAttribute("aria-label", "show password");
                showPassword.setAttribute("aria-checked", "false");
            }
        });

        const updateRequirement = (id, valid) => {
            const requirement = document.getElementById(id);

            if (valid) {
                requirement.classList.add("valid");
            } else {
                requirement.classList.remove("valid");
            }
        };

        password.addEventListener("input", (event) => {
            const value = event.target.value;

            updateRequirement("length", value.length >= 6);
            updateRequirement("number", /\d/.test(value));

        });

        confirmPassword.addEventListener("blur", (event) => {
            const value = event.target.value;

            if (value.length && value != password.value) {
                matchPassword.classList.remove("hidden");
            } else {
                matchPassword.classList.add("hidden");
            }
        });

        confirmPassword.addEventListener("focus", (event) => {
            matchPassword.classList.add("hidden");
        });

        const handleFormValidation = () => {
            const value = password.value;
            const confirmValue = confirmPassword.value;

            if (
                value.length >= 6 &&
                /\d/.test(value) &&
                value == confirmValue
            ) {
                submit.removeAttribute("disabled",false);
                return true;
            }

            submit.setAttribute("disabled", true);
            return false;
        };

        form.addEventListener("change", () => {
            handleFormValidation();
        });

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            const validForm = handleFormValidation();

            if (!validForm) {
                return false;
            }

            console.log("Form submitted");
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
