<div class="staff-card">
    <img src="{{ asset('images/user/' . $staff->image) }}" alt="{{ $staff->name }}">
    <div class="staff-name">
        <h3>{{ $staff->name }}</h3>
        <i class="fi fi-sr-badge-check"></i>
    </div>
    <p>{{ $staff->role }}</p>
    <div class="staff-btn">
        <button class="mail-btn"><i class="fi fi-sr-envelope"></i></button>
        <button class="book-btn">Book An Appointment</button>
    </div>
</div>
