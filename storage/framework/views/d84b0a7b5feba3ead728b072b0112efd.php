<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['paginator']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if($paginator->hasPages()): ?>
    <div class="pagination">
        
        <?php if($paginator->onFirstPage()): ?>
            <span class="pagination-links page-item disabled">&laquo;</span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="pagination-links page-item" rel="prev">&laquo;</a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $paginator->links()->elements[0] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page == $paginator->currentPage()): ?>
                <span class="pagination-links page-item active" style="background-color:#1ea7ff;color:#fff;"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($url); ?>" class="pagination-links page-item"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="pagination-links page-item" rel="next">&raquo;</a>
        <?php else: ?>
            <span class="pagination-links page-item disabled">&raquo;</span>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\components\pagination.blade.php ENDPATH**/ ?>