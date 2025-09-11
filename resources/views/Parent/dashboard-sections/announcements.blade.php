<div class="announcement">
      @if (isset($announcements) && count($announcements))
          <div id="announcement-slideshow">
              @foreach ($announcements as $i => $announcement)
                  <div class="slide announcement-slide @if ($i === 0) active @endif"
                      style="background: {{ $announcement->image ? 'url(' . asset('images/announcements/' . $announcement->image) . ') center center/cover no-repeat' : '#eaf6ff' }};">
                      <div class="announcement-overlay"></div>
                      <div class="announcement-content">
                          <h5 class="title">{{ $announcement->title }}</h5>
                          <div class="description">{!! nl2br(e($announcement->description)) !!}</div>
                          <div class="posted-date">
                              {{ date('M d, Y', strtotime($announcement->date_posted)) }}</div>
                      </div>
                  </div>
              @endforeach
              <div class="announcement-dots">
                  @foreach ($announcements as $i => $announcement)
                      <span class="dot @if ($i === 0) active @endif"
                          data-slide="{{ $i }}"></span>
                  @endforeach
              </div>
          </div>
      @else
          <div class="text-muted p-3">No announcements at this time.</div>
      @endif
</div>
