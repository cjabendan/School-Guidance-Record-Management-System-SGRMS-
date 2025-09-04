

                @forelse($counselors as $counselor)
                    @php
                        $fullName = trim("{$counselor->last_name}, {$counselor->first_name} {$counselor->middle_name}");
                        $img = asset('images/user/default.jpg');
                        if (!empty($counselor->profile_image) && $counselor->profile_image !== 'default.jpg' && $counselor->profile_image !== 'default.png') {
                            if (file_exists(public_path('images/user/' . $counselor->profile_image))) {
                                $img = asset('images/user/' . $counselor->profile_image);
                            }
                        }
                    @endphp
                    <div class="profile-box" onclick="openViewCounselModal('{{ $counselor->c_id }}')">
                        <img src="{{ $img }}" alt="Profile Picture">
                        <h2>{{ $fullName }}</h2>
                    </div>
                @empty
                    <p>No counselors found.</p>
                @endforelse