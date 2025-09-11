<!-- Add Counselor Modal -->
        <div id="formModal" class="counselor-modal">
            <div class="counselor-modal-content">
                <span class="close add-modal-close pro-add-close" onclick="closeFormModal()">&times;</span>
                <h2 class="add-modal-title pro-add-title">Counselor</h2>
                <form id="addCounselorForm" method="POST" action="<?php echo e(route('Head.counselors.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="counselor-row">
                        <div class="counselor-image-col">
                            <img id="counselorImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" data-default="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Counselor Image" class="pro-add-image">
                            <input type="file" id="counselor_profile_image" name="profile_image" accept="image/*" class="pro-add-image-input">
                        </div>
                        <div class="counselor-fields-col">
                            <div class="counselor-field-row">
                                 <label for="c_id_display" class="add-label">Counselor ID:</label>
                                 <span id="c_id_display" class="pro-add-id-value"><?php echo e($nextCounselorId ?? 'Auto'); ?></span>
                                 <input type="hidden" id="c_id" name="c_id" value="">
                                 <button type="button" id="activateCounselorBtn" class="pro-add-save" style="margin-left:12px; display:none;" onclick="activateCounselorStatus()">Activate</button>
                            </div>
                            <div class="counselor-field-row">
                                <label for="counselor_lname" class="add-label">Last Name:</label>
                                <input type="text" id="counselor_lname" name="lname" class="add-input" required>
                            </div>
                            <div class="counselor-field-row">
                                <label for="counselor_fname" class="add-label">First Name:</label>
                                <input type="text" id="counselor_fname" name="fname" class="add-input" required>
                            </div>
                            <div class="counselor-field-row">
                                <label for="counselor_mname" class="add-label">Middle Name:</label>
                                <input type="text" id="counselor_mname" name="mname" class="add-input">
                            </div>
                            <div class="counselor-field-row">
                                <label class="add-label">Sex:</label>
                                <div class="add-radio-group">
                                    <label class="add-radio-label">
                                        <input type="radio" name="sex" id="counselor_sex_male" value="Male" required>
                                        Male
                                    </label>
                                    <label class="add-radio-label" style="margin-left:16px;">
                                        <input type="radio" name="sex" id="counselor_sex_female" value="Female" required>
                                        Female
                                    </label>
                                </div>
                            </div>
                            <div class="counselor-field-row">
                                <label for="counselor_email" class="add-label">Email:</label>
                                <input type="email" id="counselor_email" name="email" class="add-input" required>
                            </div>
                            <div class="counselor-field-row">
                                <label for="counselor_contact_num" class="add-label">Contact Number:</label>
                                <input type="text" id="counselor_contact_num" name="contact_num" class="add-input" required>
                            </div>
                            <div class="counselor-field-row" id="passwordFieldWrapper">
                                <label for="counselor_password" class="add-label">Password:</label>
                                <input type="password" id="counselor_password" name="password" class="add-input" required>
                            </div>
                        </div>
                    </div>
                    <div class="pro-add-buttons">
                        <button type="submit" class="pro-add-save">Save</button>
                    </div>
                </form>
            </div>
        </div>

<!------------------------------------------------------------------------>

<!-- View Counselor Modal -->
<div id="viewCounselorModal" class="counselor-modal" style="display:none;">
    <div class="counselor-modal-content">
        <span class="close add-modal-close pro-add-close" onclick="window.closeViewCounselorModal()">&times;</span>
        <h2 class="add-modal-title pro-add-title">view Counselor Profile</h2>
        <div class="counselor-row">
            <div class="counselor-image-col">
                <img id="viewCounselorImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" data-default="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Counselor Image" class="pro-add-image">
            </div>
            <div class="counselor-fields-col">
                <div class="counselor-field-row">
                    <label class="add-label">Counselor ID:</label>
                    <span id="view_c_id_display" class="pro-add-id-value">Loading...</span>
                </div>
                <div class="counselor-field-row">
                    <label class="add-label">Full Name:</label>
                    <span class="view-field"><span id="view_counselor_lname"></span>, <span id="view_counselor_fname"></span> <span id="view_counselor_mname"></span></span>
                </div>
                <div class="counselor-field-row">
                    <label class="add-label">Sex:</label>
                    <span class="view-field" id="view_counselor_sex"></span>
                </div>
                <div class="counselor-field-row">
                    <label class="add-label">Email:</label>
                    <span class="view-field" id="view_counselor_email"></span>
                </div>
                <div class="counselor-field-row">
                    <label class="add-label">Contact No.:</label>
                    <span class="view-field" id="view_counselor_contact_num"></span>
                </div>
            </div>
        </div>
        <div class="pro-add-buttons">
            <button type="button" class="pro-add-save" id="editCounselorBtn"
                onclick="editCounselorFromView(document.getElementById('view_c_id_display').textContent)" style="margin-right:8px;">
                <i class='bx bx-edit' style="vertical-align:middle;"></i> Edit
            </button>
            <button type="button" class="pro-add-archive" id="archiveCounselorBtn"
                onclick="showArchiveConfirmModal(document.getElementById('view_c_id_display').textContent)">
                <i class='bx bx-archive' style="vertical-align:middle;"></i> Archive
            </button>
        </div>
    </div>
</div>

<!------------------------------------------------------------------------>



<!------------------------------------------------------------------------>

<!-- Archive Confirm Modal -->
<div id="archiveConfirmModal" class="counselor-modal" style="display:none;">
    <div class="archive-confirm-content">
        <h3>Confirm Archive</h3>
        <p >Are you sure you want to archive this counselor?</p>
        <div">
            <button type="button" class="archive-confirm-btn" onclick="confirmArchiveCounselor()">Confirm</button>
            <button type="button" class="archive-cancel-btn" onclick="closeArchiveConfirmModal()">Cancel</button>
        </div>
    </div>
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/Modal/counselModal.blade.php ENDPATH**/ ?>