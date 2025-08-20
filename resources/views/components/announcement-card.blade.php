<div class="announcement-box">
    <div class="a-arrow">
        <i class="fi fi-rr-arrow-small-right"></i>
    </div>
    <div class="announcement-img">
        <img src="{{ asset('images/announcements/' . $announcement->image) }}" class="img-fluid" alt="image">
    </div>
    <div class="announcement-content">
        <div class="an-header">
            <h3>{{ $announcement->title }}</h3>
            <p>{{ \Carbon\Carbon::parse($announcement->date_posted)->format('F d, Y') }}</p>
        </div>
        <div class="an-body">
            <p>
                {{ \Illuminate\Support\Str::limit($announcement->description, 180, '...') }}
                @if (strlen($announcement->description) > 180)
                    <a href="">Read more</a>
                @endif
            </p>
        </div>
    </div>
</div>
