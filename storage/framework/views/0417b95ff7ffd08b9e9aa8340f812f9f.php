<!-- Add/Edit Counselor Modal -->
<div id="formModal" class="counselor-modal">
    <div class="counselor-modal-content">
        <span class="counselor-close">&times;</span>
        <h2 class="counselor-modal-title">Add Counselor</h2>

        <!-- Display success and error messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="counselorForm" method="POST" action="<?php echo e(url('Head/counselors')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="form-row image-name-row">
                <!-- Left Column -->
                <div class="image-col">
                    <div class="counsel-image-wrapper">
                        <img 
                            id="counselorImage" 
                            src="<?php echo e(asset('images/user/default.jpg')); ?>" 
                            data-default="<?php echo e(asset('images/user/default.jpg')); ?>" 
                            alt="Counselor Image" 
                            class="counselor-image-box"
                        >
                        <button type="button" id="remove-counselor-image" class="counselor-delete-btn" style="display:none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <label class="counsel-upload-btn" for="counselor_profile_image">
                        <i class="fas fa-image"></i> Choose Image
                    </label>
                    <input type="file" id="counselor_profile_image" name="profile_image" accept="image/*" style="display:none;">
                    <span id="counselor-file-chosen" class="file-chosen-text">No file chosen</span>

                    <div class="counselor-id-row">
                        <label class="counselor-id-label">Counselor ID:</label>
                        <span id="counselor_id_display" class="counselor-id-value">Loading...</span>
                        <input type="hidden" id="counselor_id" name="c_id">
                        <div style="margin-top:8px;">
                            <button type="button" id="activateCounselorBtn" class="counsel-save-btn" style="display:none; width:100%;" onclick="window.activateCounselorStatus()">Activate</button>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="name-fields-col">
                    <!-- Row 1: Names -->
                    <div class="form-row">
                        <div class="counsel-field-col">
                            <label for="fname" class="counsel-label">First Name</label>
                            <input type="text" id="fname" name="fname" class="counsel-input" required>
                        </div>
                        <div class="counsel-field-col">
                            <label for="mname" class="counsel-label">Middle Name</label>
                            <input type="text" id="mname" name="mname" class="counsel-input">
                        </div>
                        <div class="counsel-field-col">
                            <label for="lname" class="counsel-label">Last Name</label>
                            <input type="text" id="lname" name="lname" class="counsel-input" required>
                        </div>
                    </div>

                    <!-- Row 2: Sex and Contact -->
                    <div class="form-row">
                        <div class="counsel-field-col">
                            <label class="counsel-label">Sex</label>
                            <div class="counsel-radio-group">
                                <label>
                                    <input type="radio" name="sex" value="Male" required> Male
                                </label>
                                <label>
                                    <input type="radio" name="sex" value="Female" required> Female
                                </label>
                            </div>
                        </div>
                        <div class="counsel-field-col">
                            <label for="contact_num" class="counsel-label">Contact</label>
                            <input type="text" id="contact_num" name="contact_num" class="counsel-input">
                        </div>
                    </div>

                    <!-- Row 3: Email and Password -->
                    <div class="form-row">
                        <div class="counsel-field-col">
                            <label for="email" class="counsel-label">Email</label>
                            <input type="email" id="email" name="email" class="counsel-input" required>
                        </div>
                        <div class="counsel-field-col">
                            <label for="password" class="counsel-label">Password</label>
                            <input type="password" id="password" name="password" class="counsel-input">
                        </div>
                    </div>

                    <div class="counselor-buttons">
                        <button type="submit" class="counsel-save-btn">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-------------------------------------------------------------------------------->

<!-- Crop Image Modal for Counselor --> 
<div id="cropCounselorImageModal" class="modal" style="display:none; z-index:2200;"> 
    <div class="modal-content" style="max-width:600px; text-align:center; z-index:2210;"> 
        <h3>Crop Profile Image</h3> 
        <div style="max-height:400px; overflow:hidden;"> 
            <img id="counselorCropperPreview" style="max-width:100%;"> 
        </div> 
        <div style="margin-top:16px;"> 
            <button type="button" id="applyCropCounselorBtn" class="counsel-save-btn">Apply</button> 
            <button type="button" id="cancelCropCounselorBtn" class="counsel-save-btn">Cancel</button> 
        </div> 
    </div> 
</div> 

<!-- Hidden input for cropped image --> 
<input type="hidden" id="counselorCroppedImageData" name="cropped_image_data">


<!-------------------------------------------------------------------------------->

<!-- View Counselor Modal -->
<div id="viewCounselorModal" class="counselor-modal">
    <div class="counselor-modal-content">
        <span class="counselor-close">&times;</span>
        <h2 class="counselor-modal-title">Counselor Details</h2>

        <div class="form-row image-name-row">
            <div class="image-col">
                <div class="counsel-image-wrapper">
                    <img id="viewCounselorImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Counselor Image" class="counselor-image-box">
                </div>
                <div class="counselor-id-row">
                    <label class="counselor-id-label">Counselor ID:</label>
                    <span id="view_c_id_display" class="counselor-id-value">Loading...</span>
                </div>
            </div>

            <div class="name-fields-col">
                <div class="form-row">
                    <div class="counsel-field-col">
                        <label class="counsel-label">First Name</label>
                        <span id="view_counselor_fname" class="counsel-view-text"></span>
                    </div>
                    <div class="counsel-field-col">
                        <label class="counsel-label">Middle Name</label>
                        <span id="view_counselor_mname" class="counsel-view-text"></span>
                    </div>
                    <div class="counsel-field-col">
                        <label class="counsel-label">Last Name</label>
                        <span id="view_counselor_lname" class="counsel-view-text"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="counsel-field-col">
                        <label class="counsel-label">Sex</label>
                        <span id="view_counselor_sex" class="counsel-view-text"></span>
                    </div>
                    <div class="counsel-field-col">
                        <label class="counsel-label">Contact</label>
                        <span id="view_counselor_contact_num" class="counsel-view-text"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="counsel-field-col">
                        <label class="counsel-label">Email</label>
                        <span id="view_counselor_email" class="counsel-view-text"></span>
                    </div>
                </div>

                <div class="counselor-buttons">
                    <button type="button" id="editCounselorBtn" class="counsel-save-btn">Edit</button>
                    <button type="button" id="archiveCounselorBtn" class="counsel-archive-btn" onclick="window.showArchiveConfirmModal(document.getElementById('view_c_id_display').textContent)">Deactivate</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-------------------------------------------------------------------------------->

<!-- Deactivate Confirmation Modal -->
<div id="archiveConfirmModal" class="counselor-modal" style="display:none; z-index:2300;">
    <div class="counselor-modal-content" style="max-width:400px; text-align:center;">
        <h3 style="margin-bottom:16px;">Deactivate Counselor</h3>

        <!-- Warning Icon -->
        <div style="font-size:48px; color:#eab308; margin-bottom:12px;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <div style="margin-bottom:16px;">
            <p>
                Are you certain you want to <strong>deactivate the account</strong> of 
                <span id="archiveCounselorName" style="font-weight:bold; color:#2563eb;"></span>? 
                This action will restrict access until the account is reactivated.
            </p>
        </div>

        <div class="archive-error-msg" style="color:#e11d48; margin-bottom:8px; display:none;"></div>

        <div style="display:flex; justify-content:center; gap:16px;">
            <button type="button" class="counsel-save-btn" onclick="window.confirmArchiveCounselor()">Yes, Deactivate</button>
            <button type="button" class="counsel-save-btn" onclick="window.closeArchiveConfirmModal()">Cancel</button>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/Modal/counselModal.blade.php ENDPATH**/ ?>