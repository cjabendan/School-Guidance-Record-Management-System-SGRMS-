<?php $__env->startSection('content'); ?>
    <div class="announcement-container">
        <div class="announcement-header">
            <h2>Announcement Board</h2>
            <p>Stay informed with the latest news, updates, and important announcements from your school. </p>
        </div>
        <div class="announcement-board">
            <div class="announcement-nav">
                <div class="announcement-filter" id="announcement-filters">
                    <a href="#"
                        class="a-nav <?php echo e(request('category') == 'recent' || !request()->has('category') ? 'active' : ''); ?>"
                        data-category="recent">Recent</a>
                    <a href="#" class="a-nav <?php echo e(request('category') == 'announcement' ? 'active' : ''); ?>"
                        data-category="announcement">Announcements</a>
                    <a href="#" class="a-nav <?php echo e(request('category') == 'event' ? 'active' : ''); ?>"
                        data-category="event">Events</a>
                    <a href="#" class="a-nav <?php echo e(request('category') == 'news' ? 'active' : ''); ?>"
                        data-category="news">News</a>
                </div>
                <div class="announcement-search">
                    <form method="GET" action="<?php echo e(route('announcements.index')); ?>">
                        <i class="fi fi-br-search"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Search announcements..." id="announcement-search-input">
                        <?php if(request('category')): ?>
                            <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                        <?php endif; ?>
                        <button type="submit" style="display:none"></button>
                    </form>
                </div>
            </div>
            <div class="announcements-list" id="announcements-list">
                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal27614b8e9c0b331869e6d1efc4911ebe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27614b8e9c0b331869e6d1efc4911ebe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.announcement-card','data' => ['announcement' => $announcement]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('announcement-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['announcement' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($announcement)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27614b8e9c0b331869e6d1efc4911ebe)): ?>
<?php $attributes = $__attributesOriginal27614b8e9c0b331869e6d1efc4911ebe; ?>
<?php unset($__attributesOriginal27614b8e9c0b331869e6d1efc4911ebe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27614b8e9c0b331869e6d1efc4911ebe)): ?>
<?php $component = $__componentOriginal27614b8e9c0b331869e6d1efc4911ebe; ?>
<?php unset($__componentOriginal27614b8e9c0b331869e6d1efc4911ebe); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if($announcements->hasPages()): ?>
                <?php if (isset($component)) { $__componentOriginal41032d87daf360242eb88dbda6c75ed1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41032d87daf360242eb88dbda6c75ed1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pagination','data' => ['paginator' => $announcements]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($announcements)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41032d87daf360242eb88dbda6c75ed1)): ?>
<?php $attributes = $__attributesOriginal41032d87daf360242eb88dbda6c75ed1; ?>
<?php unset($__attributesOriginal41032d87daf360242eb88dbda6c75ed1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41032d87daf360242eb88dbda6c75ed1)): ?>
<?php $component = $__componentOriginal41032d87daf360242eb88dbda6c75ed1; ?>
<?php unset($__componentOriginal41032d87daf360242eb88dbda6c75ed1); ?>
<?php endif; ?>
            <?php endif; ?>
            <!-- Announcement View Modal -->
            <div class="view-modal-announcement" id="announcement-view-modal">
                <div class="view-modal-content-announcement" id="announcement-view-modal-content">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('announcement-search-input');
            const announcementsList = document.getElementById('announcements-list');
            const filters = document.getElementById('announcement-filters');
            let timeout = null;
            let currentCategory = '<?php echo e(request('category') ?? 'recent'); ?>';

            // Modal logic
            window.openAnnouncementModal = function(id) {
                console.log("Opening announcement with id:", id);
                const modal = document.getElementById('announcement-view-modal');
                const modalContent = document.getElementById('announcement-view-modal-content');
                modal.style.display = 'flex';

                fetch(`<?php echo e(url('/announcements/view')); ?>/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load announcement');
                        return response.text();
                    })
                    .then(html => {
                        console.log("HTML:", html);
                        modalContent.innerHTML = html;
                        const closeBtn = modalContent.querySelector('.close-modal-btn');
                        if (closeBtn) {
                            closeBtn.onclick = function() {
                                modal.style.display = 'none';
                                modalContent.innerHTML = '';
                            };
                        }
                    })
                    .catch(err => {
                        modalContent.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
                    });
            };


            // Close modal when clicking outside content
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('announcement-view-modal');
                const modalContent = document.getElementById('announcement-view-modal-content');
                if (modal.style.display === 'flex' && !modalContent.contains(e.target) && !e.target.closest(
                        '.announcement-box')) {
                    modal.style.display = 'none';
                    modalContent.innerHTML = '';
                }
            });

            // AJAX for search
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        fetchAnnouncements(currentCategory, searchInput.value);
                    }, 400);
                });
            }

            // AJAX for filters
            filters.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    filters.querySelectorAll('a').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    currentCategory = this.dataset.category;
                    fetchAnnouncements(currentCategory, searchInput.value);
                });
            });

            function fetchAnnouncements(category = 'recent', search = '') {
                let params = new URLSearchParams();
                if (category && category !== 'recent') params.append('category', category);
                if (search && search.trim() !== '') params.append('search', search.trim());
                let url = `<?php echo e(route('announcements.index')); ?>?` + params.toString();

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newList = doc.getElementById('announcements-list');
                        if (newList) {
                            announcementsList.innerHTML = newList.innerHTML;
                        }
                        // pagination
                        const newPagination = doc.querySelector('.pagination');
                        const oldPagination = document.querySelector('.pagination');
                        if (newPagination && oldPagination) {
                            oldPagination.innerHTML = newPagination.innerHTML;
                        } else if (oldPagination) {
                            oldPagination.innerHTML = '';
                        }
                    });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/announcement.blade.php ENDPATH**/ ?>