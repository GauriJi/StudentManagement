@extends('layouts.master')
@section('page_title', 'Student Information — '.$my_class->name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ── Scoped to .sl-scope only — no global overrides ── */
.sl-scope { font-family: 'Inter', sans-serif; }

/* Card wrapper */
.sl-scope .sl-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 8px 24px rgba(0,0,0,.05);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

/* Page header */
.sl-scope .sl-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.sl-scope .sl-subtitle {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0;
}

/* Tabs */
.sl-scope .nav-tabs-modern {
    border-bottom: 2px solid #e9ecef;
    padding: 0 20px;
    background: #f8fafc;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    align-items: flex-end;
}
.sl-scope .nav-tabs-modern .nav-item .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 0;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    background: transparent;
    margin-bottom: -2px;
    transition: color .18s, border-color .18s;
}
.sl-scope .nav-tabs-modern .nav-item .nav-link:hover {
    color: #3b82f6;
    border-bottom-color: #93c5fd;
    background: transparent;
}
.sl-scope .nav-tabs-modern .nav-item .nav-link.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    background: transparent;
}
.sl-scope .tab-count {
    display: inline-block;
    padding: 1px 7px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    margin-left: 5px;
}
.sl-scope .nav-link.active .tab-count { background: #dbeafe; color: #1d4ed8; }
.sl-scope .nav-link:not(.active) .tab-count { background: #f1f5f9; color: #64748b; }

/* Toolbar */
.sl-scope .sl-toolbar {
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.sl-scope .sl-search-box {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 320px;
}
.sl-scope .sl-search-box .input-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
    pointer-events: none;
}
.sl-scope .sl-search-box input.form-control {
    padding-left: 33px;
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    font-size: 13px;
    height: 38px;
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    transition: border-color .2s, box-shadow .2s;
}
.sl-scope .sl-search-box input.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    background: #fff;
}
.sl-scope .sl-tools {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.sl-scope .sl-tools select.form-control {
    height: 38px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 13px;
    border: 1.5px solid #e2e8f0;
    font-family: 'Inter', sans-serif;
    min-width: 120px;
    cursor: pointer;
}
.sl-scope .btn-tool {
    height: 38px;
    padding: 0 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all .18s;
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
}
.sl-scope .btn-tool-outline {
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #64748b;
}
.sl-scope .btn-tool-outline:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: #eff6ff;
}
.sl-scope .btn-add-student {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    border: none;
}
.sl-scope .btn-add-student:hover { opacity: .88; color: #fff; }

/* Table */
.sl-scope .sl-table-responsive { overflow-x: auto; }
.sl-scope table.sl-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    font-family: 'Inter', sans-serif;
}
.sl-scope table.sl-table thead th {
    background: #f8fafc;
    padding: 11px 16px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    border-bottom: 2px solid #e9ecef;
    border-top: none;
    white-space: nowrap;
}
.sl-scope table.sl-table tbody td {
    padding: 13px 16px;
    vertical-align: middle;
    border-top: 1px solid #f1f5f9;
    color: #1e293b;
}
.sl-scope table.sl-table tbody tr:nth-child(even) td { background: #fafbfc; }
.sl-scope table.sl-table tbody tr:hover td { background: #eff6ff !important; transition: background .15s; }
.sl-scope table.sl-table tbody tr:last-child td { border-bottom: none; }

/* Avatar */
.sl-scope .sl-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
    flex-shrink: 0;
}
.sl-scope .sl-avatar-init {
    width: 38px; height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.sl-scope .sl-student-name { font-weight: 600; color: #1e293b; line-height: 1.3; }
.sl-scope .sl-student-email { font-size: 11.5px; color: #94a3b8; margin-top: 1px; }
.sl-scope .sl-adm-badge {
    font-family: 'SFMono-Regular', Consolas, monospace;
    font-size: 12px;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    padding: 3px 8px;
    border-radius: 5px;
    border: 1px solid #e2e8f0;
}
.sl-scope .sl-section-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    background: #ede9fe;
    color: #6d28d9;
}

/* Action buttons */
.sl-scope .sl-actions { display: flex; align-items: center; gap: 5px; }
.sl-scope .sl-btn-action {
    width: 32px; height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    font-size: 13px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    transition: all .18s;
    text-decoration: none;
    cursor: pointer;
}
.sl-scope .sl-btn-action.view:hover  { background: #dbeafe; border-color: #93c5fd; color: #1d4ed8; }
.sl-scope .sl-btn-action.edit:hover  { background: #fef9c3; border-color: #fde047; color: #b45309; }
.sl-scope .sl-btn-action.lock:hover  { background: #dcfce7; border-color: #86efac; color: #15803d; }
.sl-scope .sl-btn-action.mark:hover  { background: #f3e8ff; border-color: #d8b4fe; color: #7c3aed; }
.sl-scope .sl-btn-action.del:hover   { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }

/* Footer */
.sl-scope .sl-footer {
    padding: 12px 20px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    background: #fafbfc;
}
.sl-scope .sl-entry-count { font-size: 13px; color: #64748b; }
.sl-scope .sl-entry-count strong { color: #1e293b; font-weight: 700; }

/* Empty state */
.sl-scope .sl-empty {
    padding: 50px 24px;
    text-align: center;
    color: #94a3b8;
}
.sl-scope .sl-empty i { font-size: 3rem; opacity: .3; display: block; margin-bottom: 12px; }
.sl-scope .sl-empty p { margin: 0; font-size: 14px; color: #94a3b8; }

/* Avatar colours */
.sl-scope .av-blue   { background: #3b82f6; }
.sl-scope .av-purple { background: #8b5cf6; }
.sl-scope .av-green  { background: #10b981; }
.sl-scope .av-amber  { background: #f59e0b; }
.sl-scope .av-red    { background: #ef4444; }
.sl-scope .av-cyan   { background: #06b6d4; }
.sl-scope .av-pink   { background: #ec4899; }
.sl-scope .av-teal   { background: #14b8a6; }
</style>
@endpush

@section('content')
<div class="sl-scope">

    {{-- ── Page Header ───────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="sl-title"><i class="icon-users4 mr-2" style="color:#3b82f6"></i>{{ $my_class->name }} — Student List</h5>
            <p class="sl-subtitle mb-0">Student Information &rsaquo; {{ $my_class->name }}</p>
        </div>
        @if(Qs::userIsTeamSA())
        <a href="{{ route('students.create') }}" class="btn btn-tool btn-add-student">
            <i class="icon-plus3"></i> Add Student
        </a>
        @endif
    </div>

    {{-- ── Card ──────────────────────────────────────── --}}
    <div class="sl-card">

        {{-- Section Tabs --}}
        <ul class="nav nav-tabs-modern mb-0">
            <li class="nav-item">
                <a href="#tab-all" class="nav-link active" data-toggle="tab" id="tab-all-btn">
                    All Students
                    <span class="tab-count">{{ $students->count() }}</span>
                </a>
            </li>
            @foreach($sections as $se)
            <li class="nav-item">
                <a href="#tab-sec-{{ $se->id }}" class="nav-link" data-toggle="tab">
                    {{ $my_class->name }} {{ $se->name }}
                    <span class="tab-count">{{ $students->where('section_id',$se->id)->count() }}</span>
                </a>
            </li>
            @endforeach
        </ul>

        {{-- Toolbar --}}
        <div class="sl-toolbar">
            {{-- Search --}}
            <div class="sl-search-box">
                <i class="icon-search4 input-icon"></i>
                <input type="text" id="sl-search" class="form-control"
                       placeholder="Search name, ADM no, email…"
                       oninput="slSearch()">
            </div>

            {{-- Right tools --}}
            <div class="sl-tools">
                <select class="form-control" id="sl-per-page" onchange="slPerPage()">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                    <option value="100">100 / page</option>
                </select>
                <button type="button" class="btn btn-tool btn-tool-outline" onclick="slExportCSV()" title="Export to CSV">
                    <i class="icon-file-excel"></i> Export
                </button>
                <button type="button" class="btn btn-tool btn-tool-outline" onclick="window.print()" title="Print">
                    <i class="icon-printer"></i> Print
                </button>
            </div>
        </div>

        {{-- Tab content --}}
        <div class="tab-content">

            {{-- ── ALL STUDENTS ─────────────────────── --}}
            <div class="tab-pane fade show active" id="tab-all">
                <div class="sl-table-responsive">
                    <table class="sl-table" id="sl-tbl-all">
                        <thead>
                            <tr>
                                <th style="width:46px">#</th>
                                <th style="width:52px">Photo</th>
                                <th>Student</th>
                                <th>ADM No</th>
                                @if(count($sections) > 1)
                                <th>Section</th>
                                @endif
                                <th>Email</th>
                                <th class="text-center" style="width:150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sl-body-all">
                        @php $avCols = ['av-blue','av-purple','av-green','av-amber','av-red','av-cyan','av-pink','av-teal']; @endphp
                        @forelse($students as $s)
                        @php
                            $name    = $s->user->name  ?? '—';
                            $email   = $s->user->email ?? '';
                            $photo   = $s->user->photo ?? '';
                            $words   = explode(' ', trim($name));
                            $initials = strtoupper(substr($words[0],0,1)) . (isset($words[1]) ? strtoupper(substr($words[1],0,1)) : '');
                            $avCol   = $avCols[$loop->index % 8];
                            $noPhoto = empty($photo) || str_contains($photo,'no_image');
                        @endphp
                        <tr>
                            <td class="text-muted" style="font-size:12px;font-weight:600">{{ $loop->iteration }}</td>
                            <td>
                                @if(!$noPhoto)
                                    <img src="{{ $photo }}" alt="{{ $name }}"
                                         class="sl-avatar"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                    <span class="sl-avatar-init {{ $avCol }}" style="display:none">{{ $initials }}</span>
                                @else
                                    <span class="sl-avatar-init {{ $avCol }}">{{ $initials }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="sl-student-name">{{ $name }}</div>
                                <div class="sl-student-email">{{ $email }}</div>
                            </td>
                            <td><span class="sl-adm-badge">{{ $s->adm_no }}</span></td>
                            @if(count($sections) > 1)
                            <td><span class="sl-section-badge">{{ $my_class->name }} {{ $s->section->name }}</span></td>
                            @endif
                            <td class="text-muted" style="font-size:12.5px">{{ $email }}</td>
                            <td>
                                <div class="sl-actions justify-content-center">
                                    <a href="{{ route('students.show', Qs::hash($s->id)) }}"
                                       class="sl-btn-action view" title="View Profile" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-eye"></i>
                                    </a>
                                    @if(Qs::userIsTeamSA())
                                    <a href="{{ route('students.edit', Qs::hash($s->id)) }}"
                                       class="sl-btn-action edit" title="Edit Student" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                    <a href="{{ route('st.reset_pass', Qs::hash($s->user->id)) }}"
                                       class="sl-btn-action lock" title="Reset Password" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-lock2"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" target="_blank"
                                       class="sl-btn-action mark" title="Marksheet" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-file-text2"></i>
                                    </a>
                                    @if(Qs::userIsSuperAdmin())
                                    <button type="button"
                                            id="{{ Qs::hash($s->user->id) }}"
                                            onclick="confirmDelete(this.id)"
                                            class="sl-btn-action del" title="Delete" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <form method="post" id="item-delete-{{ Qs::hash($s->user->id) }}"
                                          action="{{ route('students.destroy', Qs::hash($s->user->id)) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="sl-empty">
                                    <i class="icon-users4"></i>
                                    <p class="font-weight-semibold mb-1" style="color:#475569">No students enrolled</p>
                                    <p>No students are currently in <strong>{{ $my_class->name }}</strong>.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="sl-footer">
                    <div class="sl-entry-count" id="sl-count-all">
                        <strong>{{ $students->count() }}</strong> {{ $students->count() == 1 ? 'student' : 'students' }} found
                    </div>
                </div>
            </div>{{-- /tab-all --}}

            {{-- ── SECTION TABS ──────────────────────── --}}
            @foreach($sections as $se)
            @php $secSts = $students->where('section_id', $se->id); @endphp
            <div class="tab-pane fade" id="tab-sec-{{ $se->id }}">
                <div class="sl-table-responsive">
                    <table class="sl-table" id="sl-tbl-sec-{{ $se->id }}">
                        <thead>
                            <tr>
                                <th style="width:46px">#</th>
                                <th style="width:52px">Photo</th>
                                <th>Student</th>
                                <th>ADM No</th>
                                <th>Email</th>
                                <th class="text-center" style="width:150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($secSts as $sr)
                        @php
                            $n2   = $sr->user->name  ?? '—';
                            $e2   = $sr->user->email ?? '';
                            $p2   = $sr->user->photo ?? '';
                            $w2   = explode(' ', trim($n2));
                            $i2   = strtoupper(substr($w2[0],0,1)) . (isset($w2[1]) ? strtoupper(substr($w2[1],0,1)) : '');
                            $c2   = $avCols[$loop->index % 8];
                            $nd2  = empty($p2) || str_contains($p2,'no_image');
                        @endphp
                        <tr>
                            <td class="text-muted" style="font-size:12px;font-weight:600">{{ $loop->iteration }}</td>
                            <td>
                                @if(!$nd2)
                                    <img src="{{ $p2 }}" alt="{{ $n2 }}" class="sl-avatar"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                    <span class="sl-avatar-init {{ $c2 }}" style="display:none">{{ $i2 }}</span>
                                @else
                                    <span class="sl-avatar-init {{ $c2 }}">{{ $i2 }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="sl-student-name">{{ $n2 }}</div>
                                <div class="sl-student-email">{{ $e2 }}</div>
                            </td>
                            <td><span class="sl-adm-badge">{{ $sr->adm_no }}</span></td>
                            <td class="text-muted" style="font-size:12.5px">{{ $e2 }}</td>
                            <td>
                                <div class="sl-actions justify-content-center">
                                    <a href="{{ route('students.show', Qs::hash($sr->id)) }}"
                                       class="sl-btn-action view" title="View Profile" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-eye"></i>
                                    </a>
                                    @if(Qs::userIsTeamSA())
                                    <a href="{{ route('students.edit', Qs::hash($sr->id)) }}"
                                       class="sl-btn-action edit" title="Edit" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                    <a href="{{ route('st.reset_pass', Qs::hash($sr->user->id)) }}"
                                       class="sl-btn-action lock" title="Reset Password" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-lock2"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('marks.year_selector', Qs::hash($sr->user->id)) }}" target="_blank"
                                       class="sl-btn-action mark" title="Marksheet" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-file-text2"></i>
                                    </a>
                                    @if(Qs::userIsSuperAdmin())
                                    <button type="button"
                                            id="{{ Qs::hash($sr->user->id) }}"
                                            onclick="confirmDelete(this.id)"
                                            class="sl-btn-action del" title="Delete" data-toggle="tooltip" data-placement="top">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <form method="post" id="item-delete-{{ Qs::hash($sr->user->id) }}"
                                          action="{{ route('students.destroy', Qs::hash($sr->user->id)) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="sl-empty">
                                    <i class="icon-users4"></i>
                                    <p class="font-weight-semibold mb-1" style="color:#475569">No students in this section</p>
                                    <p>{{ $my_class->name }} {{ $se->name }} has no enrolled students yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="sl-footer">
                    <div class="sl-entry-count">
                        <strong>{{ $secSts->count() }}</strong> {{ $secSts->count() == 1 ? 'student' : 'students' }} in this section
                    </div>
                </div>
            </div>
            @endforeach

        </div>{{-- /tab-content --}}
    </div>{{-- /sl-card --}}
</div>{{-- /sl-scope --}}
@endsection

@push('scripts')
<script>
$(function () {
    // Tooltips
    $('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });

    // Keep active tab state on reload
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        localStorage.setItem('sl_active_tab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('sl_active_tab');
    if (activeTab) {
        $('a[href="' + activeTab + '"]').tab('show');
    }
});

/* ── Live search (current visible tab) ─────────────── */
function slSearch() {
    var q     = document.getElementById('sl-search').value.toLowerCase().trim();
    var panel = document.querySelector('.tab-pane.active');
    var rows  = panel.querySelectorAll('tbody tr');
    var count = 0;
    rows.forEach(function(row) {
        var match = !q || row.textContent.toLowerCase().indexOf(q) > -1;
        row.style.display = match ? '' : 'none';
        if (match) count++;
    });
    var counter = panel.querySelector('.sl-entry-count');
    if (counter) counter.innerHTML = '<strong>' + count + '</strong> ' + (count === 1 ? 'student' : 'students') + ' found';
}

/* ── Export active tab as CSV ───────────────────────── */
function slExportCSV() {
    var panel = document.querySelector('.tab-pane.active');
    var rows  = panel.querySelectorAll('table tr');
    var csv   = [];
    rows.forEach(function(row) {
        var cells = row.querySelectorAll('th, td');
        var cols  = [];
        cells.forEach(function(cell, idx) {
            if (idx === cells.length - 1) return; // skip actions
            cols.push('"' + cell.textContent.trim().replace(/"/g, '""') + '"');
        });
        if (cols.length) csv.push(cols.join(','));
    });
    var blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'students_{{ $my_class->name }}.csv';
    link.click();
}

/* ── Per-page (placeholder for server-side) ─────────── */
function slPerPage() {
    // Hook to server-side pagination if implemented
}
</script>
@endpush
