@extends('errors.minimal')

@section('code', '500 😭')

@section('title', __('Server error'))

@section('image')

@endsection

@section('message', __($e))