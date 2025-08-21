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
                <form action="{{ url('Head/updatecounsel') }}" method="POST">
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


        <!-- View Counselor Modal -->
        <div id="viewCounselorModal" class="modal" style="display:none;">
            <div class="modal-content" style="max-width: 420px; width: 100%; border-radius: 18px; box-shadow: 0 8px 32px rgba(44,82,130,0.18); padding: 32px 24px;">
                <span class="close" onclick="closeViewCounselorModal()">&times;</span>
                <!-- Profile Image -->
                <div style="display: flex; justify-content: center; margin-bottom: 24px;">
                    <img id="view_c_image"
                         src="{{ $counselor->profile_image ? asset('uploads/users/' . $counselor->profile_image) : asset('images/user.img/people.png') }}"
                         alt="Counselor Profile"
                         style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 12px #b6c7e3; border: 3px solid #2563eb; background: #f1f5f9;">
                </div>
                <!-- Header Title -->
                <h2 style="text-align:center; font-size: 1.5rem; font-weight: bold; color: #1e3a8a; margin-bottom: 8px; user-select: none;">
                    Counselor Details
                </h2>
                <!-- Counselor Details -->
                <dl style="color: #1e293b; margin-bottom: 32px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 18px;">
                        <dt style="font-weight: 600;">Full Name</dt>
                        <dd id="view_fullname" style="text-align: right; font-weight: 400;"></dd>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 18px;">
                        <dt style="font-weight: 600;">Email Address</dt>
                        <dd id="view_email" style="text-align: right; font-weight: 400;"></dd>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 18px;">
                        <dt style="font-weight: 600;">Contact Number</dt>
                        <dd id="view_contact" style="text-align: right; font-weight: 400;"></dd>
                    </div>
                </dl>
                <!-- Action Buttons -->
                <div class="modal-buttons" style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button aria-label="Edit counselor details" class="btn edit" id="editBtn" type="button">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button aria-label="Archive counselor profile" class="btn archive" id="archiveBtn" type="button">
                        <i class="fas fa-archive"></i>
                    </button>
                    <button class="btn close-blue" id="closeModalBtn" type="button">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
