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

   <script>
    document.addEventListener("DOMContentLoaded", async () => {
        try {
            await fetch('/sanctum/csrf-cookie', {
                credentials: 'same-origin',
            });
        } catch (error) {
            console.error('CSRF refresh failed:', error);
        }
    });
</script>

    
@endsection
