<section class="staff-section" id="staff">
    <div class="staff-content">
        <h2>Meet Our Staff</h2>
        <p class=p>We’re more than just counselors—we’re here to support, listen,
            <br>and guide you every step of the way.
        </p>

        <div class="staff-list">
            @foreach ($staff as $staffMember)
                @include('components.staff-card', ['staff' => (object) $staffMember])
            @endforeach
        </div>

    </div>
</section>
