@extends('layouts.app')

@section('content')
    {{-- Home Section --}}
    @include('sections.home')

    {{-- Highlight Section --}}

    {{-- About Section --}}
    @include('sections.about')

    {{-- Services Section --}}
    @include('sections.services')

    {{-- Meet Our Staff --}}
    @include('sections.staff')

    {{-- FAQ Section --}}
    @include('sections.faq')

@endsection
