@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="eiin" class="col-md-4 col-form-label text-md-end">eiin </label>
                            <div class="col-md-6">
                                <input id="eiin" type="text" class="form-control" name="eiin" value="{{ old('eiin') }}">
                                @if ($errors->has('eiin'))
                                <small class="help-block form-text text-danger">{{ $errors->first('eiin') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="pdsid" class="col-md-4 col-form-label text-md-end">pdsid </label>
                            <div class="col-md-6">
                                <input id="pdsid" type="text" class="form-control" name="pdsid" value="{{ old('pdsid') }}">
                                @if ($errors->has('eiin'))
                                <small class="help-block form-text text-danger">{{ $errors->first('eiin') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="institute_type" class="col-md-4 col-form-label text-md-end">institute_type</label>
                            <div class="col-md-6">
                                <input id="institute_type" type="text" class="form-control" name="institute_type" value="{{ old('institute_type') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="index_number" class="col-md-4 col-form-label text-md-end">index_number</label>
                            <div class="col-md-6">
                                <input id="index_number" type="text" class="form-control" name="index_number">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="institute_name" class="col-md-4 col-form-label text-md-end">institute_name</label>
                            <div class="col-md-6">
                                <input id="institute_name" type="text" class="form-control" name="institute_name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="workstation_name" class="col-md-4 col-form-label text-md-end">workstation_name</label>

                            <div class="col-md-6">
                                <input id="workstation_name" type="text" class="form-control" name="workstation_name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="branch_institute_name" class="col-md-4 col-form-label text-md-end">branch_institute_name</label>

                            <div class="col-md-6">
                                <input id="branch_institute_name" type="text" class="form-control" name="branch_institute_name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="mobile_no" class="col-md-4 col-form-label text-md-end">mobile_no</label>

                            <div class="col-md-6">
                                <input id="mobile_no" type="text" class="form-control" name="mobile_no">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="branch_institute_category" class="col-md-4 col-form-label text-md-end">branch_institute_category</label>

                            <div class="col-md-6">
                                <input id="branch_institute_category" type="text" class="form-control" name="branch_institute_category">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="institute_category" class="col-md-4 col-form-label text-md-end">institute_category</label>

                            <div class="col-md-6">
                                <input id="institute_category" type="text" class="form-control" name="institute_category">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="service_break_institute" class="col-md-4 col-form-label text-md-end">service_break_institute</label>

                            <div class="col-md-6">
                                <input id="service_break_institute" type="text" class="form-control" name="service_break_institute">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="designation" class="col-md-4 col-form-label text-md-end">designation</label>

                            <div class="col-md-6">
                                <input id="designation" type="text" class="form-control" name="designation">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="subject" class="col-md-4 col-form-label text-md-end">subject</label>

                            <div class="col-md-6">
                                <input id="subject" type="text" class="form-control" name="subject">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="date_of_birth" class="col-md-4 col-form-label text-md-end">date_of_birth</label>

                            <div class="col-md-6">
                                <input id="date_of_birth" type="text" class="form-control" name="date_of_birth">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="mpo_code" class="col-md-4 col-form-label text-md-end">mpo_code</label>

                            <div class="col-md-6">
                                <input id="mpo_code" type="text" class="form-control" name="mpo_code">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nid" class="col-md-4 col-form-label text-md-end">nid</label>

                            <div class="col-md-6">
                                <input id="nid" type="text" class="form-control" name="nid">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ismpo" class="col-md-4 col-form-label text-md-end">ismpo</label>

                            <div class="col-md-6">
                                <input id="ismpo" type="text" class="form-control" name="ismpo">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="data_source" class="col-md-4 col-form-label text-md-end">data_source</label>

                            <div class="col-md-6">
                                <input id="data_source" type="text" class="form-control" name="data_source">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection