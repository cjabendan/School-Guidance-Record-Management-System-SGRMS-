    <div class="settings-flex-row">
        <div class="card shadow-sm p-4 w-100">
            <h5 class="mb-3">📄 Upload Policy Document</h5>

            <!--[if BLOCK]><![endif]--><?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <form action="<?php echo e(route('rag.store')); ?>" method="POST" enctype="multipart/form-data"
                class="rag-upload-form">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="id" class="form-label fw-semibold">Document ID</label>
                    <input type="text" name="id" id="id" class="form-control"
                        placeholder="e.g. school_policy_2025" required>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label fw-semibold">Select File</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".txt,.pdf"
                        required>
                    <small class="text-muted">Accepted formats: .txt or .pdf</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    🚀 Upload & Index to Pinecone
                </button>
            </form>
        </div>
    </div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/system-chatbot.blade.php ENDPATH**/ ?>