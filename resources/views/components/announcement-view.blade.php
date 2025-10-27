<div class="view-announcement-container">
    <div class="view-announcement-item">
        <div class="view-announcement-card">
            <div class="view-announcement-image">
                <img src="{{ asset('images/announcements/' . $announcement->image) }}" alt="image">
            </div>
            <div class="view-announcement-content">

                <div class="view-announcement-title-section">
                    <div class="view-announcement-logo">
                        <img src="{{ asset('images/logo/school-logo.png') }}" alt="School Logo">
                    </div>
                    <div class="view-announcement-text">
                        <h3>{{ $announcement->title }}</h3>
                        <p class="view-announcement-date">
                            {{ \Carbon\Carbon::parse($announcement->created_at)->format('F d, Y \a\t h:i A') }}
                        </p>

                    </div>
                </div>


                <div class="view-announcement-description">
                    <p>{{ $announcement->description }}</p>
                </div>
                @if ($announcement->link)
                    <div class="announcement-btn-link">
                        <a href="{{ $announcement->link }}" class="announcement-btn-link" target="_blank">See more
                            information</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
