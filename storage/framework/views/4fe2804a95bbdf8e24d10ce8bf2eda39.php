


        <!-- Add Counselor Modal -->
        <div id="formModal" class="modal">
            <div class="modal-content add-modal-content pro-add-modal">
                <span class="close add-modal-close pro-add-close" onclick="closeFormModal()">&times;</span>
                <h2 class="add-modal-title pro-add-title" style="text-align:center; font-size: 1.5rem; font-weight: bold; color: #1e3a8a; margin-bottom: 18px;">Add Counselor</h2>
                <form id="addCounselorForm" method="POST" action="<?php echo e(route('Head.counselors.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-row image-name-row">
                        <div class="image-col">
                            <img id="counselorImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" data-default="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Counselor Image" class="student-image-box pro-add-image">
                            <input type="file" id="counselor_profile_image" name="profile_image" accept="image/*" class="pro-add-image-input">
                            <div class="student-id-row pro-add-id-row">
                                <label for="c_id_display" class="add-label" style="margin-bottom:0;">ID No.:</label>
                                <span id="c_id_display" class="pro-add-id-value"><?php echo e($nextCounselorId ?? 'Auto'); ?></span>
                            </div>
                            <input type="hidden" id="c_id" name="c_id" value="">
                        </div>
                        <div class="name-fields-col">
                            <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                                <div class="add-field-col">
                                    <label for="counselor_fname" class="add-label">First Name:</label>
                                    <input type="text" id="counselor_fname" name="fname" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="counselor_mname" class="add-label">Middle Name:</label>
                                    <input type="text" id="counselor_mname" name="mname" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label for="counselor_lname" class="add-label">Last Name:</label>
                                    <input type="text" id="counselor_lname" name="lname" class="add-input" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="counselor_email" class="add-label">Email:</label>
                                    <input type="email" id="counselor_email" name="email" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="counselor_contact_num" class="add-label">Contact Number:</label>
                                    <input type="text" id="counselor_contact_num" name="contact_num" class="add-input" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="add-field-col" id="passwordFieldWrapper">
                                    <label for="counselor_password" class="add-label">Password:</label>
                                    <input type="password" id="counselor_password" name="password" class="add-input" required>
                                </div>
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
        <div id="viewCounselorModal" class="modal" style="display:none;">
            <div class="modal-content add-modal-content pro-add-modal">
                <span class="close add-modal-close pro-add-close" onclick="window.closeViewCounselorModal()">&times;</span>
                <h2 class="add-modal-title pro-add-title" style="text-align:center; font-size: 1.5rem; font-weight: bold; color: #1e3a8a; margin-bottom: 18px;">View Counselor</h2>
                <div class="form-row image-name-row">
                    <div class="image-col">
                        <img id="viewCounselorImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" data-default="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Counselor Image" class="student-image-box pro-add-image">
                        <div class="student-id-row pro-add-id-row">
                            <label class="add-label" style="margin-bottom:0; font-weight:600; color:#1e3a8a; letter-spacing:1px;">Counselor ID:</label>
                            <span id="view_c_id_display" class="pro-add-id-value" style="font-family:monospace; font-size:1.1em; color:#0a2540; background:#f3f6fa; padding:2px 8px; border-radius:6px;">Loading...</span>
                        </div>
                    </div>
                    <div class="name-fields-col">
                        <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                            <div class="add-field-col">
                                <label class="add-label">First Name:</label>
                                <span id="view_counselor_fname" class="view-field"></span>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Middle Name:</label>
                                <span id="view_counselor_mname" class="view-field"></span>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Last Name:</label>
                                <span id="view_counselor_lname" class="view-field"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Email:</label>
                                <span id="view_counselor_email" class="view-field"></span>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Contact Number:</label>
                                <span id="view_counselor_contact_num" class="view-field"></span>
                            </div>
                        </div>


        <!-- Edit Counselor Modal -->
        <div id="formModal" class="modal">
            <div class="modal-content add-modal-content pro-add-modal">
                <span class="close add-modal-close pro-add-close" onclick="closeFormModal()">&times;</span>
                <h2 class="add-modal-title pro-add-title" style="text-align:center; font-size: 1.5rem; font-weight: bold; color: #1e3a8a; margin-bottom: 18px;">Add Counselor</h2>
                <form id="addCounselorForm" method="POST" action="<?php echo e(route('Head.counselors.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-row image-name-row">
                        <div class="image-col">
                            <img id="counselorImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" data-default="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Counselor Image" class="student-image-box pro-add-image">
                            <input type="file" id="counselor_profile_image" name="profile_image" accept="image/*" class="pro-add-image-input">
                            <div class="student-id-row pro-add-id-row">
                                <label for="c_id_display" class="add-label" style="margin-bottom:0;">ID No.:</label>
                                <span id="c_id_display" class="pro-add-id-value"><?php echo e($nextCounselorId ?? 'Auto'); ?></span>
                            </div>
                            <input type="hidden" id="c_id" name="c_id" value="">
                        </div>
                        <div class="name-fields-col">
                            <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                                <div class="add-field-col">
                                    <label for="counselor_fname" class="add-label">First Name:</label>
                                    <input type="text" id="counselor_fname" name="fname" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="counselor_mname" class="add-label">Middle Name:</label>
                                    <input type="text" id="counselor_mname" name="mname" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label for="counselor_lname" class="add-label">Last Name:</label>
                                    <input type="text" id="counselor_lname" name="lname" class="add-input" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="counselor_email" class="add-label">Email:</label>
                                    <input type="email" id="counselor_email" name="email" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="counselor_contact_num" class="add-label">Contact Number:</label>
                                    <input type="text" id="counselor_contact_num" name="contact_num" class="add-input" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="add-field-col" id="passwordFieldWrapper">
                                    <label for="counselor_password" class="add-label">Password:</label>
                                    <input type="password" id="counselor_password" name="password" class="add-input" required>
                                </div>
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
s<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/modal/counselModal.blade.php ENDPATH**/ ?>