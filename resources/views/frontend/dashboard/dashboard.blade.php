@extends('frontend.layouts.app')

@section('content')

<style>
    .sub-navbar-section{
        background: #FFF;
        padding: 10px 25px;
    }
</style>

<div class="dashboard-section">
    <div class="sub-navbar-section">
        <div class="container">
            <div class="tabs">
                <div class="tab-list">
                    <div class="tab">
                        <div class="start-icon">
                            <img src="{{ asset('') }}" alt="">
                        </div>
                        <p class="tab-name">প্রথম পাতা</p>
                        <div class="end-icon"></div>
                    </div>
                </div>
            </div>
            <div class="create-section">
                <button>Create</button>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <h1>dashboard</h1>
        </div>
    </div>
</div>

@endsection