@extends('layouts.app')

@section('content')
    @include('auth.login')
    <script src="{{ asset('js/landing.js') }}"></script>
@endsection
