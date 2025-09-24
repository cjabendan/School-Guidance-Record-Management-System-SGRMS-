@extends('layouts.parent')
@section('title', 'SGRMS - Chat')
@section('content')

    <section id="content">
        @include('partials.navbar')

        @livewire('chat')
     
    </section>
@endsection
