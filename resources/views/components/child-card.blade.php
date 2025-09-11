@forelse($children as $child)
    @php
        // $child is an array: ['id', 'name', 'email', 'grade_level']
        $fullName = $child['name'];
        $img = asset('images/user/default.jpg');
        // If you want to show student ID, use $child['id']
    @endphp
    <div class="profile-box" onclick="openViewChildModal('{{ $child['id'] }}')">
      {{--  <img src="{{ $img }}" alt="Profile Picture"> --}}
        <h2>{{ $fullName }}</h2>
        <p>Student ID: {{ $child['id'] }}</p>
        <p>Email: {{ $child['email'] }}</p>
        <p>Grade Level: {{ $child['grade_level'] }}</p>
    </div>
@empty
    <p>No children found.</p>
@endforelse
