

                <?php $__empty_1 = true; $__currentLoopData = $counselors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counselor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if(strtolower($counselor->status ?? 'active') === 'active'): ?>
                        <?php
                            $fullName = trim("{$counselor->last_name}, {$counselor->first_name} {$counselor->middle_name}");
                            $img = asset('images/user/default.jpg');
                            if (!empty($counselor->profile_image) && $counselor->profile_image !== 'default.jpg' && $counselor->profile_image !== 'default.png') {
                                if (file_exists(public_path('images/user/' . $counselor->profile_image))) {
                                    $img = asset('images/user/' . $counselor->profile_image);
                                }
                            }
                        ?>
                        <div class="profile-box" onclick="openViewCounselModal('<?php echo e($counselor->c_id); ?>', false)">
                            <img src="<?php echo e($img); ?>" alt="Profile Picture">
                            <h2><?php echo e($fullName); ?></h2>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No counselors found.</p>
                <?php endif; ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/components/counselor-card.blade.php ENDPATH**/ ?>