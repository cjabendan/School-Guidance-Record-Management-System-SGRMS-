<section class="staff-section" id="staff">
    <div class="staff-content">
        <h2>Meet Our Staff</h2>
        <p class="p">We’re more than just counselors—we’re here to support, listen,
            <br>and guide you every step of the way.
        </p>

        <div class="staff-carousel">
            <button class="staff-arrow left" id="prevStaff" aria-label="Previous"><i class="fi fi-rr-angle-small-left"></i></button>

            <div class="staff-list-wrapper">
                <div class="staff-list" id="staffList" data-carousel="managed">
                    @foreach ($staff as $staffMember)
                        @include('components.staff-card', ['staff' => (object) $staffMember])
                    @endforeach
                </div>
            </div>

            <button class="staff-arrow right" id="nextStaff" aria-label="Next"><i class="fi fi-rr-angle-small-right"></i></button>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('staffList');
    const prev = document.getElementById('prevStaff');
    const next = document.getElementById('nextStaff');
    if (!list) return;
    const items = Array.from(list.children);
    let index = 0; // index of first visible item

    function visibleCount() {
        const ww = window.innerWidth;
        if (ww <= 600) return 1;
        if (ww <= 900) return 2;
        return 3;
    }

    // Pager-style carousel: show exactly `v` items and hide others
    function update() {
        const v = visibleCount();
        index = Math.max(0, Math.min(index, Math.max(0, items.length - v)));
        items.forEach((it, i) => {
            if (i >= index && i < index + v) {
                it.style.display = '';
            } else {
                it.style.display = 'none';
            }
        });

        // disable/hide arrows when not needed
        if (items.length <= v) {
            if (prev) prev.style.display = 'none';
            if (next) next.style.display = 'none';
        } else {
            if (prev) { prev.style.display = ''; prev.disabled = index === 0; }
            if (next) { next.style.display = ''; next.disabled = index >= items.length - v; }
        }
    }

    if (prev) prev.addEventListener('click', function() {
        index = Math.max(0, index - 1);
        update();
    });
    if (next) next.addEventListener('click', function() {
        index = Math.min(items.length - visibleCount(), index + 1);
        update();
    });

    // Update on resize
    window.addEventListener('resize', function() { update(); });

    // initial update after load (wait for images to settle)
    window.addEventListener('load', function() { setTimeout(update, 80); });
    // also run initial update on DOMContentLoaded in case load already fired
    setTimeout(update, 100);
});
</script>
@endpush
