@extends('layouts.parent')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="profiles-container">
                <!-- Add new profile box -->
                <div class="profile-box add-box" onclick="openLinkChildModal()">
                    <i class='bx bx-plus add-profile-icon'></i>
                    <h2>Link Children</h2>
                </div>

                @include('components.child-card', ['children' => $children])

                <!-- Modal for sending request to link children -->
                <div id="linkChildModal" class="modal" style="display:none;">
                    <div class="modal-content"
                        style="background:#fff; padding:2rem; border-radius:10px; max-width:500px; margin:auto;">
                        <h2>Link Children</h2>
                        <form id="linkChildForm" method="POST" action="{{ route('Parent.link.request') }}">
                            @csrf
                            <div class="form-group">
                                <label for="student_ids">Select Students</label>
                                <select name="student_ids[]" id="student_ids" class="form-control" multiple
                                    required></select>
                            </div>

                            <div class="form-group">
                                <label for="parent_email">Your Email</label>
                                <input type="email" name="parent_email" id="parent_email"
                                    value="{{ Auth::user()->email }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="parent_contact">Your Contact Number</label>
                                <input type="text" name="parent_contact" id="parent_contact"
                                    value="{{ Auth::user()->contact_num }}" readonly>
                            </div>
                            <button type="submit" class="btn-primary">Send Request</button>
                            <button type="button" onclick="closeLinkChildModal()" class="btn-secondary"
                                style="margin-left:10px;">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <script>
        function openLinkChildModal() {
            document.getElementById('linkChildModal').style.display = 'flex';
        }

        function closeLinkChildModal() {
            document.getElementById('linkChildModal').style.display = 'none';
        }

        $(document).ready(function() {
            $('#student_ids').select2({
                placeholder: 'Search students by ID or name',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('Parent.search.students') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(student => ({
                                id: student.s_id,
                                text: student.s_id + ' - ' + student.name
                            }))
                        };
                    }
                }
            });
        });
    </script>
