<div class="view-announcement-container">
    <div class="view-announcement-item">
        <div class="view-announcement-card">
            <div class="view-announcement-image">
                <img src="{{ asset('images/announcements/' . $announcement->image) }}" alt="image">
            </div>
            <div class="view-announcement-content">
                <h3 class="view-announcement-title">{{ $announcement->title }}</h3>
                <p class="view-announcement-date">{{ \Carbon\Carbon::parse($announcement->date_posted)->format('F d, Y') }}</p>
                <div class="view-announcement-description">
                    <p>{{ $announcement->description }}</p>
                </div>
                @if ($announcement->link)
                    <a href="{{ $announcement->link }}" class="btn btn-primary" target="_blank">View More</a>
                @endif
            </div>
        </div>
    </div>
</div>


