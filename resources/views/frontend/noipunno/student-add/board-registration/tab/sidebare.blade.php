


<div class="card">
    <div class="card-body">
        <div class="stepper d-flex flex-column mt-2 ml-2">
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pr-4 align-items-center">
                    <div class="rounded py-2 px-3 mb-1 {{ $tab == 'payment_tab' ? 'bg-color text-white' : 'border border-color text-color' }}">
                        @if(!empty($existsPayment))
                            &#10003; <!-- HTML entity for a check mark -->
                        @else
                            1
                        @endif
                    </div>
                    <div class="line h-100"></div>
                </div>

                <div>
                    <p class="lead text-muted pb-5 pt-2">
                        <a href="{{ route('student.board_registration.payment_tab', $class_id) }}" class="nav-link">&nbsp;&nbsp; Payment</a>
                    </p>
                </div>
            </div>


            <div class="d-flex mb-1">
                <div class="d-flex flex-column pr-4 align-items-center">
                    <div class="rounded py-2 px-3 mb-1 {{ $tab == 'student_tab' ? 'bg-color text-white' : 'border border-color text-color' }}">
                        @if(!empty($remainingStudentExists) && ($remainingStudentCount == 0))
                            &#10003; <!-- HTML entity for a check mark -->
                        @else
                            2
                        @endif
                    </div>
                    <div class="line h-100"></div>
                </div>
                <div>
                    <p class="lead text-muted pb-5 pt-2">
                        <a href="{{ route('student.board_registration.list_tab', $class_id) }}" class="nav-link">&nbsp;&nbsp; Student</a>
                    </p>
                </div>
            </div>

            <div class="d-flex mb-1">
                <div class="d-flex flex-column pr-4 align-items-center">
                    <div class="rounded py-2 px-3 mb-1 {{ $tab == 'tem_student_tab' ? 'bg-color text-white' : 'border border-color text-color' }}">3 </div>
                    <div class="line h-100"></div>
                </div>
                <div>
                    <p class="lead text-muted pb-5 pt-2">
                        <a href="{{ route('student.board_registration.temp.list_tab', $class_id) }}" class="nav-link">&nbsp;&nbsp; Temporary List</a>
                    </p>
                </div>
            </div>
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pr-4 align-items-center">
                    <div class="rounded py-2 px-3 mb-1 {{ $tab == 'registered_tab' ? 'bg-color text-white' : 'border border-color text-color' }}">4 </div>
                    <div class="line h-100"></div>
                </div>
                <div>
                    <p class="lead text-muted pt-2">
                        <a href="{{ route('student.board_registration.registered.list_tab', $class_id) }}" class="nav-link">&nbsp;&nbsp; Final</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stepper {
        .line {
            width: 2px;
            background-color: lightgrey !important;
        }
        .lead {
            font-size: 1.1rem;
        }
    }
    .bg-color{
        background-color: #428f92 !important;
    }
    .text-color{
        color: #428f92 !important;
    }
    .border-color{
        border-color: #428f92 !important;
    }

</style>
