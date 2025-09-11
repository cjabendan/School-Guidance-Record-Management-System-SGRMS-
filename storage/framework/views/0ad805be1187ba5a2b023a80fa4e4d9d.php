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
    <nav class="pagination">
        <ul class="pagination">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled"><span class="pagination-links">&laquo;</span></li>
            <?php else: ?>
                <li class="page-item"><a class="pagination-links" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&laquo;</a></li>
            <?php endif; ?>

            
            <?php for($page = 1; $page <= $paginator->lastPage(); $page++): ?>
                <?php if($page == $paginator->currentPage()): ?>
                    <li class="page-item active"><span class="pagination-links"><?php echo e($page); ?></span></li>
                <?php else: ?>
                    <li class="page-item"><a class="pagination-links" href="<?php echo e($paginator->url($page)); ?>"><?php echo e($page); ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item"><a class="pagination-links" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">&raquo;</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="pagination-links">&raquo;</span></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/components/student-pagination.blade.php ENDPATH**/ ?>