@extends('layouts.main')
@section('title', 'SGRMS - Chat')
@section('content')

    <section id="content">
        @include('partials.navbar')

        @livewire('chat', ['selectedUserId' => $selectedUserId ?? null])
        
    </section>
@endsection
