@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message')
{{'SERVER_ADDR: '.request()->server('SERVER_ADDR')}}
@endsection
@section('message', __('Server Error'))
