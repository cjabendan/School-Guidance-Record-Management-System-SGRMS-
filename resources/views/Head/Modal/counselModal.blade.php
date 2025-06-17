<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
</head>
<body>

<!-- Add Modal -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeFormModal()">&times;</span>
        <h2>Add Counselor</h2>
        <form action="{{ route('addcounsel') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" name="lname">
            </div>
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" name="fname">
            </div>
            <div class="form-group">
                <label for="mname">Middle Name:</label>
                <input type="text" name="mname">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label for="contact_num">Phone Number:</label>
                <input type="text" name="contact_num">
            </div>
            <div class="form-group">
                <label for="c_level">Department:</label>
                <select name="c_level">
                    <option value="">Select Department</option>
                    <option value="Elementary">Elementary</option>
                    <option value="High School">Highschool</option>
                    <option value="College">College</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password">
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn save">Save</button>
                <button type="button" class="btn cancel" onclick="closeFormModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Counselor</h2>
        <form action="{{ url('updatecounsel') }}" method="POST">
            @csrf
            <input type="hidden" name="c_id" id="edit_id">

            <div class="form-group">
                <label for="edit_lname">Last Name:</label>
                <input type="text" name="lname" id="edit_lname">
            </div>
            <div class="form-group">
                <label for="edit_fname">First Name:</label>
                <input type="text" name="fname" id="edit_fname">
            </div>
            <div class="form-group">
                <label for="edit_mname">Middle Name:</label>
                <input type="text" name="mname" id="edit_mname">
            </div>
            <div class="form-group">
                <label for="edit_email">Email:</label>
                <input type="email" name="email" id="edit_email">
            </div>
            <div class="form-group">
                <label for="edit_contact">Phone Number:</label>
                <input type="text" name="contact_num" id="edit_contact">
            </div>
            <div class="form-group">
                <label for="edit_c_level">Department:</label>
                <select name="c_level" id="edit_c_level">
                    <option value="">Select Department</option>
                    <option value="Elementary">Elementary</option>
                    <option value="High School">High School</option>
                    <option value="College">College</option>
                </select>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn save">Update</button>
                <button type="button" class="btn cancel" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>



<!-- View Modal -->
<div id="viewCounselorModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeViewCounselModal()">&times;</span>
        <h2>View Counselor Details</h2>
        <div class="form-group">
            <label>Full Name:</label>
            <p id="view_fullname"></p>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <p id="view_email"></p>
        </div>
        <div class="form-group">
            <label>Phone:</label>
            <p id="view_contact"></p>
        </div>
        <div class="form-group">
            <label>Department:</label>
            <p id="view_department"></p>
        </div>
        <div class="modal-buttons">
            <button type="button" class="btn cancel" onclick="closeViewCounselModal()">Close</button>
        </div>
    </div>
</div>


</body>
</html>
