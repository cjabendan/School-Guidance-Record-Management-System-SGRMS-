@extends('layouts.main')
@section('title', 'Parent Accounts')
@section('content')

<section id="content">
    @include('partials.navbar')
    <div class="wrapper">
        <div class="table-container">
            <div class="table-management">
                <div class="table-nav">
                    <div class="table-filter">
                        <button class="add-btn" id="addParentBtn">
                            <i class="fi fi-br-plus"></i>Add Parent
                        </button>
                    </div>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search parents..." id="parent-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col title">Parent Name</div>
                <div class="table-col">Contact</div>
                <div class="table-col">Email</div>
                <div class="table-col">Status</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="parent-list">
                @include('Head.partials.parent_table')
            </div>
        </div>
    </div>
</section>

@include('Head.Modal.parentModal')
<script src="{{ asset('js/Modal/parentModal.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('parent-search-input');
    const tableList = document.getElementById('parent-list');
    let searchTimeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            const query = searchInput.value;
            fetch(`/Head/parents?search=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                tableList.innerHTML = html;
            });
        }, 300);
    });
});
</script>
@endsection
