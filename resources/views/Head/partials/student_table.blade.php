<div class="table">
    @foreach ($students as $row)
        @php
            // Case Severity Color
            $cases = DB::table('case_students')
                ->join('cases', 'cases.case_id', '=', 'case_students.case_id')
                ->where('case_students.student_id', $row->s_id)
                ->where('cases.archived', 0)
                ->pluck('cases.severity');

            $severityColor = '#9ca3af'; // default gray
            if ($cases->count() > 0) {
                $minorCount = 0;
                $majorCount = 0;
                $hasGrave = false;
                foreach ($cases as $sev) {
                    $sev = strtolower(trim($sev));
                    if ($sev === 'grave') $hasGrave = true;
                    elseif ($sev === 'major') $majorCount++;
                    elseif ($sev === 'minor') $minorCount++;
                }
                if ($hasGrave) {
                    $severityColor = '#dc2626'; // red
                } elseif ($majorCount >= 2 || $minorCount >= 2) {
                    $severityColor = '#f59e0b'; // orange
                } elseif ($majorCount === 1 || $minorCount === 1) {
                    $severityColor = '#16a34a'; // green
                }
            }

            // Name Formatting
            $suffix = $row->suffix !== 'N/A' ? $row->suffix : '';
            $mname = trim($row->mname);
            $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
            $name = trim($row->lname . ', ' . $row->fname . ' ' . $mname . ' ' . $suffix);

            // Enrollment Status Color
            $status = $row->enrollment_status ?? 'N/A';
            $enrollColor = [
                'Enrolled'    => '#16a34a',
                'Incoming'    => '#f97316',
                'Probation'   => '#eab308',
                'Suspended'   => '#dc2626',
                'Dropped'     => '#475569',
                'Transferred' => '#3b82f6',
                'Graduated'   => '#8b5cf6',
                'Deceased'    => '#94a3b8',
                'Expelled'    => '#b91c1c',
            ][$status] ?? '#64748b';
        @endphp

        <div class="table-card" data-id="{{ $row->s_id }}">
            <div class="table-col title">
                <span class="status-circle" style="background: {{ $severityColor }}; vertical-align: middle; margin-right: 6px; border-radius:50%; width:12px; height:12px; display:inline-block;"></span>
                {{ $row->s_id }}
            </div>
            <div class="table-col tittle">
                <img src="{{ asset ('images/user/'. $row->profile_image )}}" alt="student-pfp" class="profile-thumb">
                {{ $name }}</div>
          <!--  <div class="table-col">{{ $row->sex }}</div> -->
            <div class="table-col">{{ $row->educ_level }}</div>
            <div class="table-col">{{ $row->year_level }}</div>
            <div class="table-col">
                <span style="display:inline-block;padding:4px 14px;border-radius:16px;font-weight:600;
                    background: {{ $enrollColor }}20; color: {{ $enrollColor }};
                    border: 1px solid {{ $enrollColor }}; min-width:90px;text-align:center;">
                    {{ $status }}
                </span>

            </div>
            <div class="table-col actions">
                <a href="javascript:void(0);" class="view-btn" title="View" onclick="openViewStudentModal('{{ $row->s_id }}')">
                    <i class='bx bx-show'></i>
                </a>
                <a href="javascript:void(0);" class="edit-btn" title="Edit" onclick="openAddEditModal('edit', { s_id: '{{ $row->s_id }}' })">
                    <i class='bx bx-edit'></i>
                </a>
                <button type="button" class="archive-btn" title="Archive" onclick="openArchiveStudentModal('{{ $row->s_id }}')">
                    <i class='bx bx-archive'></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

@if ($students instanceof \Illuminate\Pagination\LengthAwarePaginator)
    @component('components.student-pagination', ['paginator' => $students]) @endcomponent
@endif
