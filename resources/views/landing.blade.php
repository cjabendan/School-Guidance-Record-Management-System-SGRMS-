@extends('layouts.app')

@section('content')
    {{-- Home Section --}}
    @include('landing-sections.home')

    {{-- Highlight Section --}}

    {{-- About Section --}}
    @include('landing-sections.about')

    {{-- Services Section --}}
    @include('landing-sections.services')

    {{-- Meet Our Staff --}}
    @include('landing-sections.staff')

    {{-- FAQ Section --}}
    @include('landing-sections.faq')

@endsection
