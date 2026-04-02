@extends('layouts.master')
@section('page_title', 'Timetable Management')

@section('content')
{{-- ═══════════════════════════════════════════
     STYLES — embedded directly because the
     master layout has no @stack('styles') hook
═══════════════════════════════════════════ --}}
<style>
/* ── Inter font ── */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

/* ── Design tokens ── */
.tt-scope {
    --p:     #6366f1;
    --p-d:   #4f46e5;
    --p-lt:  #ede9fe;
    --p-gl:  rgba(99,102,241,.14);
    --acc:   #0ea5e9;
    --suc:   #10b981;
    --wrn:   #f59e0b;
    --dan:   #ef4444;
    --txt:   #0f172a;
    --txt2:  #475569;
    --txt3:  #94a3b8;
    --surf:  #ffffff;
    --bg2:   #f8fafc;
    --bdr:   #e2e8f0;
    --bdr2:  #cbd5e1;
    --r-s:   10px;
    --r-m:   16px;
    --r-l:   24px;
    --sh-s:  0 1px 3px rgba(0,0,0,.08),0 1px 2px rgba(0,0,0,.05);
    --sh-m:  0 4px 20px rgba(0,0,0,.09);
    --tr:    all .2s cubic-bezier(.4,0,.2,1);
    font-family: 'Inter', 'Roboto', system-ui, sans-serif;
    color: var(--txt);
}

/* ── Page header (gradient banner) ── */
.tt-header {
    background: linear-gradient(135deg, #6366f1, #8b5cf6 55%, #0ea5e9);
    border-radius: var(--r-m);
    padding: 26px 28px 22px;
    margin-bottom: 22px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(99,102,241,.28);
}
.tt-header::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
    pointer-events: none;
}
.tt-header::after {
    content: '';
    position: absolute;
    bottom: -80px; left: 35%;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
    pointer-events: none;
}
.tt-header-title {
    font-size: 23px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 5px;
    letter-spacing: -.4px;
    position: relative;
    z-index: 1;
}
.tt-header-sub {
    font-size: 13px;
    color: rgba(255,255,255,.78);
    position: relative;
    z-index: 1;
}
.tt-mode-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .2px;
    background: rgba(255,255,255,.18);
    border: 1.5px solid rgba(255,255,255,.32);
    color: #fff;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    position: relative;
    z-index: 1;
}

/* ── Card ── */
.tt-card {
    background: var(--surf);
    border: 1px solid var(--bdr);
    border-radius: var(--r-m);
    box-shadow: var(--sh-m);
    margin-bottom: 20px;
    overflow: hidden;
}
.tt-card-head {
    padding: 16px 22px;
    border-bottom: 1px solid var(--bdr);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: linear-gradient(90deg, #fafbff 0%, #fff 100%);
}
.tt-card-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--txt);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 9px;
}
.tt-card-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    background: var(--p-lt);
    color: var(--p);
    flex-shrink: 0;
}
.tt-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    background: var(--p-lt);
    color: var(--p);
}

/* ── Filter bar ── */
.tt-filter-bar {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
    padding: 20px 22px;
}
.tt-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 185px;
    flex: 1;
}
.tt-lbl {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: var(--txt2);
    font-family: 'Inter', system-ui, sans-serif;
}

/* ── Custom styled select ── */
.tt-sel-wrap { position: relative; }
.tt-sel-wrap::after {
    content: '';
    position: absolute;
    right: 14px; top: 50%;
    width: 7px; height: 7px;
    transform: translateY(-65%) rotate(45deg);
    border-right: 2px solid var(--txt3);
    border-bottom: 2px solid var(--txt3);
    pointer-events: none;
    transition: border-color .18s;
}
.tt-sel-wrap:focus-within::after { border-color: var(--p); }
.tt-sel {
    width: 100%;
    height: 44px;
    -webkit-appearance: none;
    appearance: none;
    background: #fff;
    border: 1.5px solid var(--bdr2);
    border-radius: var(--r-s);
    padding: 0 38px 0 13px;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', 'Roboto', system-ui, sans-serif;
    color: var(--txt);
    cursor: pointer;
    outline: none;
    transition: var(--tr);
    box-shadow: var(--sh-s);
}
.tt-sel:hover,
.tt-sel:focus { border-color: var(--p); box-shadow: 0 0 0 3px var(--p-gl); }

/* ── Buttons ── */
.tt-btn-row { display: flex; gap: 10px; align-items: flex-end; padding-bottom: 1px; flex-shrink: 0; }

.tt-btn {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    font-family: 'Inter', 'Roboto', system-ui, sans-serif;
    font-weight: 600;
    font-size: 13px;
    border-radius: 50px !important;
    padding: 0 22px;
    height: 44px;
    line-height: 1;
    transition: var(--tr);
    white-space: nowrap;
    text-decoration: none !important;
    outline: none !important;
    letter-spacing: .1px;
    position: relative;
    overflow: hidden;
}
.tt-btn i { font-size: 12px; }

/* Ripple overlay */
.tt-btn::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background .18s;
}
.tt-btn:hover::after { background: rgba(255,255,255,.1); }

.tt-primary {
    background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
    color: #fff !important;
    box-shadow: 0 4px 14px rgba(99,102,241,.32);
}
.tt-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(99,102,241,.38); }
.tt-primary:active { transform: none; }

.tt-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    color: #fff !important;
    box-shadow: 0 4px 14px rgba(16,185,129,.28);
}
.tt-success:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,185,129,.36); }

.tt-ghost {
    background: #fff !important;
    color: var(--txt2) !important;
    border: 1.5px solid var(--bdr2) !important;
    box-shadow: var(--sh-s);
}
.tt-ghost:hover { border-color: var(--p) !important; color: var(--p) !important; transform: translateY(-1px); }

.tt-btn-sm { height: 36px !important; padding: 0 18px !important; font-size: 12px !important; }

/* ── Stats strip ── */
.tt-stats {
    display: flex;
    border-bottom: 1px solid var(--bdr);
    background: #fafbff;
    overflow-x: auto;
}
.tt-stat {
    flex: 1;
    min-width: 90px;
    padding: 13px 18px;
    border-right: 1px solid var(--bdr);
    text-align: center;
}
.tt-stat:last-child { border-right: none; }
.tt-stat-n {
    font-size: 20px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 3px;
    font-family: 'Inter', system-ui, sans-serif;
}
.tt-stat-l {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--txt3);
}

/* ── Grid ── */
.tt-table-wrap { overflow-x: auto; }
.tt-table-wrap::-webkit-scrollbar { height: 5px; }
.tt-table-wrap::-webkit-scrollbar-track { background: var(--bg2); }
.tt-table-wrap::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 10px; }

.tt-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 700px;
}
.tt-table thead th {
    background: #f1f5f9;
    padding: 11px 8px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: var(--txt2);
    border-bottom: 2px solid var(--bdr2);
    border-right: 1px solid var(--bdr);
    text-align: center;
    font-family: 'Inter', system-ui, sans-serif;
}
.tt-table thead th:first-child {
    width: 88px;
    background: linear-gradient(135deg, #ede9fe, #f1f5f9);
}
.tt-table td.tt-day {
    background: linear-gradient(180deg, #fafbff, #f8fafc);
    text-align: center;
    vertical-align: middle;
    padding: 8px;
    border-right: 2px solid var(--bdr2);
    border-bottom: 1px solid var(--bdr);
    min-width: 86px;
}
.tt-day-abbr { font-size: 13px; font-weight: 800; color: var(--p); display: block; }
.tt-day-full { font-size: 9px; color: var(--txt3); text-transform: uppercase; letter-spacing: .5px; }

.tt-table td.tt-cell {
    vertical-align: top;
    padding: 5px;
    border-right: 1px solid var(--bdr);
    border-bottom: 1px solid var(--bdr);
    height: 90px;
    min-width: 115px;
    transition: background .15s;
}
.tt-table td.tt-cell:hover { background: #f8f9ff; }

/* ── Entry block ── */
.tt-entry {
    height: 80px;
    border-radius: var(--r-s);
    padding: 7px 9px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--tr);
    position: relative;
    overflow: hidden;
    border-left: 3px solid transparent;
}
.tt-entry:hover { transform: scale(1.02); box-shadow: 0 6px 20px rgba(0,0,0,.12); }
.tt-entry-subj { font-size: 11.5px; font-weight: 700; line-height: 1.25; margin-bottom: 1px; font-family: 'Inter', system-ui, sans-serif; }
.tt-entry-tchr { font-size: 10px; font-weight: 500; display: flex; align-items: center; gap: 3px; opacity: .8; }
.tt-entry-tchr i { font-size: 9px; }
.tt-entry-time { font-size: 9px; font-weight: 600; opacity: .65; letter-spacing: .2px; }
.tt-entry-acts { display: flex; gap: 4px; margin-top: 3px; }

.tt-ico-btn {
    width: 22px; height: 22px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 10px;
    transition: var(--tr);
    outline: none;
}
.tt-ico-edit { background: #fef3c7; color: #b45309; }
.tt-ico-edit:hover { background: #fde047; transform: scale(1.12); }
.tt-ico-del  { background: #fee2e2; color: #dc2626; }
.tt-ico-del:hover  { background: #fca5a5; transform: scale(1.12); }

/* 10 colour classes */
.tc0 { background:#ede9fe; border-left-color:#6366f1; color:#4338ca; }
.tc1 { background:#e0f2fe; border-left-color:#0ea5e9; color:#0369a1; }
.tc2 { background:#dcfce7; border-left-color:#22c55e; color:#15803d; }
.tc3 { background:#fef9c3; border-left-color:#eab308; color:#a16207; }
.tc4 { background:#fee2e2; border-left-color:#ef4444; color:#b91c1c; }
.tc5 { background:#f3e8ff; border-left-color:#a855f7; color:#7c3aed; }
.tc6 { background:#fff7ed; border-left-color:#f97316; color:#c2410c; }
.tc7 { background:#ecfdf5; border-left-color:#10b981; color:#065f46; }
.tc8 { background:#eff6ff; border-left-color:#3b82f6; color:#1d4ed8; }
.tc9 { background:#fdf4ff; border-left-color:#e879f9; color:#a21caf; }

/* ── Add placeholder ── */
.tt-add-ph {
    width: 100%; height: 80px;
    background: transparent;
    border: 2px dashed var(--bdr2);
    border-radius: var(--r-s);
    cursor: pointer;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 3px;
    color: var(--txt3);
    font-size: 10px; font-weight: 600; letter-spacing: .4px;
    font-family: 'Inter', system-ui, sans-serif;
    transition: var(--tr);
    padding: 0; outline: none;
}
.tt-add-ph i { font-size: 16px; transition: var(--tr); }
.tt-add-ph:hover { border-color: var(--p); color: var(--p); background: var(--p-lt); }
.tt-add-ph:hover i { transform: scale(1.2) rotate(90deg); }

/* ── Empty state ── */
.tt-empty { padding: 72px 20px; text-align: center; }
.tt-empty-icon {
    width: 76px; height: 76px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ede9fe, #e0f2fe);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px;
    font-size: 30px;
    color: var(--p);
}
.tt-empty-title { font-size: 17px; font-weight: 700; color: var(--txt2); margin-bottom: 6px; font-family: 'Inter', system-ui, sans-serif; }
.tt-empty-sub   { font-size: 13px; color: var(--txt3); }

/* ── Legend ── */
.tt-legend {
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    padding: 12px 22px;
    border-top: 1px solid var(--bdr);
    background: #fafbff;
    font-size: 11px; color: var(--txt2); font-weight: 500;
    font-family: 'Inter', system-ui, sans-serif;
}
.tt-leg-item { display: flex; align-items: center; gap: 6px; }
.tt-leg-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

/* Period header */
.p-chip { display: flex; flex-direction: column; align-items: center; gap: 1px; }
.p-num  { font-size: 13px; font-weight: 800; color: var(--txt); font-family: 'Inter', system-ui, sans-serif; }
.p-lbl  { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--txt3); }

/* ════════════════════════════════════
   MODAL — fixed overlay, starts hidden
═════════════════════════════════════ */
#tt-overlay {
    display: none;              /* JS will switch to flex */
    position: fixed !important;
    top: 0 !important; left: 0 !important;
    width: 100vw !important; height: 100vh !important;
    z-index: 999999 !important;
    background: rgba(15,23,42,.52) !important;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}
#tt-overlay.tt-show { display: flex !important; }

@keyframes ttSlideUp {
    from { opacity:0; transform:translateY(22px); }
    to   { opacity:1; transform:translateY(0); }
}
.tt-modal-box {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 490px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: 0 24px 80px rgba(0,0,0,.22);
    animation: ttSlideUp .24s cubic-bezier(.4,0,.2,1);
    font-family: 'Inter', 'Roboto', system-ui, sans-serif;
}
.tt-m-head {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(90deg, #fafbff, #fff);
    border-radius: 20px 20px 0 0;
}
.tt-m-head-l { display: flex; align-items: center; gap: 12px; }
.tt-m-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: #ede9fe; color: #6366f1;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
}
.tt-m-title { font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; }
.tt-m-close {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #f1f5f9; border: 1px solid #e2e8f0;
    color: #64748b; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; line-height: 1;
    transition: all .15s; outline: none;
}
.tt-m-close:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }
.tt-m-body { padding: 20px 24px; }
.tt-m-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.tt-fg { margin-bottom: 14px; }
.tt-fg:last-child { margin-bottom: 0; }
.tt-fl {
    display: block;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #475569; margin-bottom: 6px;
    font-family: 'Inter', system-ui, sans-serif;
}
.tt-fl .req { color: #ef4444; margin-left: 2px; }
.tt-fc {
    width: 100%; height: 42px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    padding: 0 13px;
    font-size: 13px; font-weight: 500;
    font-family: 'Inter', system-ui, sans-serif;
    color: #0f172a;
    transition: all .18s;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
    outline: none;
    box-sizing: border-box;
}
.tt-fc:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.tt-fs-wrap { position: relative; }
.tt-fs-wrap::after {
    content: '';
    position: absolute;
    right: 13px; top: 50%;
    width: 6px; height: 6px;
    transform: translateY(-68%) rotate(45deg);
    border-right: 2px solid #94a3b8; border-bottom: 2px solid #94a3b8;
    pointer-events: none;
}
.tt-fs {
    width: 100%; height: 42px;
    -webkit-appearance: none; appearance: none;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    padding: 0 34px 0 13px;
    font-size: 13px; font-weight: 500;
    font-family: 'Inter', system-ui, sans-serif;
    color: #0f172a; cursor: pointer;
    transition: all .18s;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
    outline: none;
    box-sizing: border-box;
}
.tt-fs:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.tt-err {
    display: none;
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 8px; padding: 10px 14px;
    font-size: 13px; color: #b91c1c; margin-bottom: 14px;
    align-items: center; gap: 8px;
    font-family: 'Inter', system-ui, sans-serif;
}
.tt-err.show { display: flex; }
.tt-m-foot {
    padding: 16px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex; justify-content: flex-end; gap: 10px;
    background: #fafbff;
    border-radius: 0 0 20px 20px;
}
</style>

<div class="tt-scope">

{{-- ── Header ── --}}
<div class="tt-header">
    <div>
        <h1 class="tt-header-title">
            <i class="icon-table2" style="font-size:20px;margin-right:8px;opacity:.9;vertical-align:middle;"></i>Timetable Management
        </h1>
        <div class="tt-header-sub">
            <i class="icon-calendar3" style="font-size:11px;margin-right:4px;"></i>
            Academic Session &mdash; <strong style="color:#fff;">{{ $session }}</strong>
        </div>
    </div>
    @if(Qs::userIsTeamSA())
    <span class="tt-mode-badge"><i class="icon-shield" style="font-size:12px;"></i> Admin — Edit Mode</span>
    @else
    <span class="tt-mode-badge"><i class="icon-eye" style="font-size:12px;"></i> View Only</span>
    @endif
</div>

{{-- ── Filter Card ── --}}
<div class="tt-card">
    <div class="tt-card-head">
        <span class="tt-card-label">
            <span class="tt-card-icon"><i class="icon-filter3"></i></span>
            Select Class &amp; Section
        </span>
        @if($selected_class)
        <span class="tt-chip">
            <i class="icon-checkmark3" style="font-size:11px;"></i>
            {{ $selected_class->name }}@if($selected_section) — {{ $selected_section->name }}@endif
        </span>
        @endif
    </div>
    <form method="GET" action="{{ route('admin.timetable') }}" id="tt-filter-form">
        <div class="tt-filter-bar">
            <div class="tt-field">
                <label class="tt-lbl" for="filter-class">Class</label>
                <div class="tt-sel-wrap">
                    <select name="class_id" id="filter-class" class="tt-sel"
                            onchange="ttLoadSections(this.value)" required>
                        <option value="">— Choose Class —</option>
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="tt-field">
                <label class="tt-lbl" for="filter-section">Section</label>
                <div class="tt-sel-wrap">
                    <select name="section_id" id="filter-section" class="tt-sel" required>
                        <option value="">— Choose Section —</option>
                        @if($selected_class)
                            @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="tt-btn-row">
                <button type="submit" class="tt-btn tt-primary">
                    <i class="icon-search4"></i> View Timetable
                </button>
                @if($selected_class)
                <a href="{{ route('admin.timetable') }}" class="tt-btn tt-ghost">
                    <i class="icon-x"></i> Clear
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- ── Timetable Grid ── --}}
@if($selected_class && $selected_section)

@php
    $subjectColors = [];
    $ci = 0;
    $filled = 0;
    foreach ($days as $day) {
        foreach ($periods as $p) {
            if (!empty($grid[$day][$p])) {
                $filled++;
                $sid = $grid[$day][$p]->subject_id;
                if ($sid && !isset($subjectColors[$sid])) $subjectColors[$sid] = $ci++ % 10;
            }
        }
    }
    $total = count($days) * count($periods);
    $pct   = $total ? round($filled / $total * 100) : 0;
@endphp

<div class="tt-card">
    <div class="tt-card-head">
        <span class="tt-card-label">
            <span class="tt-card-icon" style="background:#e0f2fe;color:#0ea5e9;"><i class="icon-calendar3"></i></span>
            {{ $selected_class->name }} &mdash; {{ $selected_section->name }}
            <span style="font-size:11px;font-weight:400;color:#94a3b8;margin-left:4px;">({{ $filled }}/{{ $total }} filled)</span>
        </span>
        @if(Qs::userIsTeamSA())
        <button type="button" class="tt-btn tt-success tt-btn-sm" onclick="ttOpenAdd(null,null)">
            <i class="icon-plus3"></i> Add Entry
        </button>
        @endif
    </div>

    {{-- Stats --}}
    <div class="tt-stats">
        <div class="tt-stat">
            <div class="tt-stat-n" style="color:#6366f1;">{{ $filled }}</div>
            <div class="tt-stat-l">Assigned</div>
        </div>
        <div class="tt-stat">
            <div class="tt-stat-n" style="color:#94a3b8;">{{ $total - $filled }}</div>
            <div class="tt-stat-l">Free</div>
        </div>
        <div class="tt-stat">
            <div class="tt-stat-n" style="color:#0ea5e9;">{{ $pct }}%</div>
            <div class="tt-stat-l">Used</div>
        </div>
        <div class="tt-stat">
            <div class="tt-stat-n" style="color:#10b981;">{{ count($days) }}</div>
            <div class="tt-stat-l">Days</div>
        </div>
        <div class="tt-stat">
            <div class="tt-stat-n" style="color:#f59e0b;">{{ count($periods) }}</div>
            <div class="tt-stat-l">Periods</div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="tt-table-wrap">
        <table class="tt-table">
            <thead>
                <tr>
                    <th>Day</th>
                    @foreach($periods as $p)
                    <th>
                        <div class="p-chip">
                            <span class="p-num">{{ $p }}</span>
                            <span class="p-lbl">Period</span>
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                <tr>
                    <td class="tt-day">
                        <span class="tt-day-abbr">{{ substr($day,0,3) }}</span>
                        <span class="tt-day-full">{{ $day }}</span>
                    </td>
                    @foreach($periods as $p)
                    <td class="tt-cell">
                        @if(!empty($grid[$day][$p]))
                            @php
                                $entry = $grid[$day][$p];
                                $sid   = $entry->subject_id;
                                $cc    = 'tc'.(isset($subjectColors[$sid]) ? $subjectColors[$sid] : 0);
                            @endphp
                            <div class="tt-entry {{ $cc }}">
                                <div>
                                    <div class="tt-entry-subj">{{ $entry->subject->name ?? '—' }}</div>
                                    <div class="tt-entry-tchr"><i class="icon-user"></i> {{ $entry->teacher->name ?? 'No teacher' }}</div>
                                </div>
                                <div>
                                    @if($entry->time_from)
                                    <div class="tt-entry-time"><i class="icon-clock3" style="font-size:8px;"></i> {{ $entry->time_from }}–{{ $entry->time_to }}</div>
                                    @endif
                                    @if(Qs::userIsTeamSA())
                                    <div class="tt-entry-acts">
                                        <button type="button" class="tt-ico-btn tt-ico-edit" title="Edit"
                                            onclick="ttOpenEdit({{ $entry->id }},'{{ addslashes($day) }}',{{ $p }},{{ $entry->subject_id ?? 'null' }},{{ $entry->teacher_id ?? 'null' }},'{{ $entry->time_from }}','{{ $entry->time_to }}')">
                                            <i class="icon-pencil7"></i>
                                        </button>
                                        <button type="button" class="tt-ico-btn tt-ico-del" title="Delete"
                                            onclick="ttDelete({{ $entry->id }})">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            @if(Qs::userIsTeamSA())
                            <button type="button" class="tt-add-ph"
                                    title="Add — {{ $day }}, Period {{ $p }}"
                                    onclick="ttOpenAdd('{{ addslashes($day) }}',{{ $p }})">
                                <i class="icon-plus3"></i>
                                <span>Add</span>
                            </button>
                            @else
                            <div style="height:80px;display:flex;align-items:center;justify-content:center;color:#e2e8f0;font-size:18px;">—</div>
                            @endif
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Legend --}}
    <div class="tt-legend">
        <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;">Legend:</span>
        @foreach($subjects->whereIn('id', array_keys($subjectColors)) as $s)
            @if(isset($subjectColors[$s->id]))
            <span class="tt-leg-item">
                <span class="tt-leg-dot tc{{ $subjectColors[$s->id] }}" style="opacity:.85;"></span>
                {{ $s->name }}
            </span>
            @endif
        @endforeach
        @if(Qs::userIsTeamSA())
        <span style="margin-left:auto;font-size:10px;color:#94a3b8;">
            <i class="icon-info22" style="margin-right:3px;"></i>Click + to add &bull; Pencil to edit &bull; Trash to delete
        </span>
        @endif
    </div>
</div>

@else
<div class="tt-card">
    <div class="tt-empty">
        <div class="tt-empty-icon"><i class="icon-table2"></i></div>
        <div class="tt-empty-title">No class selected</div>
        <div class="tt-empty-sub">Choose a class and section above to view the weekly timetable grid.</div>
    </div>
</div>
@endif

</div>{{-- /tt-scope --}}

{{-- ════════════ MODAL OVERLAY ════════════ --}}
{{-- Positioned outside .tt-scope so z-index resolves against document root --}}
<div id="tt-overlay">
    <div class="tt-modal-box" id="tt-modal-box">
        <div class="tt-m-head">
            <div class="tt-m-head-l">
                <span class="tt-m-icon" id="tt-m-icon"><i class="icon-plus3"></i></span>
                <h6 class="tt-m-title" id="tt-m-title">Add Timetable Entry</h6>
            </div>
            <button type="button" class="tt-m-close" onclick="ttCloseModal()" title="Close">&times;</button>
        </div>
        <div class="tt-m-body">
            <div class="tt-err" id="tt-err">
                <i class="icon-warning22" style="flex-shrink:0;font-size:14px;"></i>
                <span id="tt-err-msg"></span>
            </div>
            <form id="tt-form" onsubmit="return false;">
                <input type="hidden" id="e-id"  value="">
                <input type="hidden" id="e-cls" value="{{ request('class_id') }}">
                <input type="hidden" id="e-sec" value="{{ request('section_id') }}">

                <div class="tt-m-row">
                    <div class="tt-fg">
                        <label class="tt-fl" for="e-day">Day <span class="req">*</span></label>
                        <div class="tt-fs-wrap">
                            <select id="e-day" class="tt-fs" required>
                                @foreach($days as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tt-fg">
                        <label class="tt-fl" for="e-period">Period <span class="req">*</span></label>
                        <div class="tt-fs-wrap">
                            <select id="e-period" class="tt-fs" required>
                                @foreach($periods as $p)
                                <option value="{{ $p }}">Period {{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tt-fg">
                    <label class="tt-fl" for="e-subj">Subject</label>
                    <div class="tt-fs-wrap">
                        <select id="e-subj" class="tt-fs">
                            <option value="">— None / Free Period —</option>
                            @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tt-fg">
                    <label class="tt-fl" for="e-tchr">Teacher</label>
                    <div class="tt-fs-wrap">
                        <select id="e-tchr" class="tt-fs">
                            <option value="">— None —</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tt-m-row">
                    <div class="tt-fg" style="margin-bottom:0;">
                        <label class="tt-fl" for="e-from">Time From</label>
                        <input type="time" id="e-from" class="tt-fc">
                    </div>
                    <div class="tt-fg" style="margin-bottom:0;">
                        <label class="tt-fl" for="e-to">Time To</label>
                        <input type="time" id="e-to" class="tt-fc">
                    </div>
                </div>
            </form>
        </div>
        <div class="tt-m-foot">
            <button type="button" class="tt-btn tt-ghost tt-btn-sm" onclick="ttCloseModal()">Cancel</button>
            <button type="button" class="tt-btn tt-primary tt-btn-sm" id="tt-save-btn" onclick="ttSaveEntry()">
                <i class="icon-check2"></i> Save Entry
            </button>
        </div>
    </div>
</div>

{{-- ════════════ SCRIPTS ════════════ --}}
{{-- Embedded directly because master layout has no @stack('scripts') --}}
<script>
(function () {
    'use strict';

    var STORE_URL    = "{{ route('admin.timetable.store') }}";
    var DELETE_URL   = "{{ url('admin/timetable') }}/";
    var SECTIONS_URL = "{{ url('admin/timetable/sections') }}/";
    var CSRF         = "{{ csrf_token() }}";
    var FIRST_DAY    = "{{ $days[0] ?? 'Monday' }}";

    /* ── error helpers ── */
    function showErr(msg) {
        var el = document.getElementById('tt-err');
        document.getElementById('tt-err-msg').textContent = msg;
        el.classList.add('show');
    }
    function clearErr() {
        document.getElementById('tt-err').classList.remove('show');
    }

    /* ── section AJAX loader ── */
    window.ttLoadSections = function (classId) {
        var sel = document.getElementById('filter-section');
        if (!classId) { sel.innerHTML = '<option value="">— Choose Section —</option>'; return; }
        sel.innerHTML = '<option value="">Loading…</option>';
        fetch(SECTIONS_URL + classId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                sel.innerHTML = '<option value="">— Choose Section —</option>';
                data.forEach(function (s) {
                    sel.insertAdjacentHTML('beforeend',
                        '<option value="' + s.id + '">' + s.name + '</option>');
                });
            })
            .catch(function () {
                sel.innerHTML = '<option value="">Error — refresh page</option>';
            });
    };

    /* ── modal helpers ── */
    function openModal() {
        clearErr();
        document.getElementById('tt-overlay').classList.add('tt-show');
        document.body.style.overflow = 'hidden';
    }

    window.ttCloseModal = function () {
        document.getElementById('tt-overlay').classList.remove('tt-show');
        document.body.style.overflow = '';
    };

    window.ttOpenAdd = function (day, period) {
        document.getElementById('tt-m-title').textContent = 'Add Timetable Entry';
        document.getElementById('tt-m-icon').innerHTML    = '<i class="icon-plus3"></i>';
        document.getElementById('e-id').value     = '';
        document.getElementById('e-day').value    = day    || FIRST_DAY;
        document.getElementById('e-period').value = period || 1;
        document.getElementById('e-subj').value   = '';
        document.getElementById('e-tchr').value   = '';
        document.getElementById('e-from').value   = '';
        document.getElementById('e-to').value     = '';
        openModal();
    };

    window.ttOpenEdit = function (id, day, period, subjectId, teacherId, timeFrom, timeTo) {
        document.getElementById('tt-m-title').textContent = 'Edit Timetable Entry';
        document.getElementById('tt-m-icon').innerHTML    = '<i class="icon-pencil7"></i>';
        document.getElementById('e-id').value     = id;
        document.getElementById('e-day').value    = day;
        document.getElementById('e-period').value = period;
        document.getElementById('e-subj').value   = subjectId  || '';
        document.getElementById('e-tchr').value   = teacherId  || '';
        document.getElementById('e-from').value   = timeFrom   || '';
        document.getElementById('e-to').value     = timeTo     || '';
        openModal();
    };

    /* ── save ── */
    window.ttSaveEntry = function () {
        clearErr();
        var day    = document.getElementById('e-day').value;
        var period = document.getElementById('e-period').value;
        if (!day || !period) { showErr('Please select a day and period.'); return; }

        var btn = document.getElementById('tt-save-btn');
        btn.disabled = true;
        btn.innerHTML = 'Saving…';

        var payload = {
            _token:      CSRF,
            my_class_id: document.getElementById('e-cls').value,
            section_id:  document.getElementById('e-sec').value,
            day:         day,
            period_no:   period,
            subject_id:  document.getElementById('e-subj').value  || null,
            teacher_id:  document.getElementById('e-tchr').value  || null,
            time_from:   document.getElementById('e-from').value  || null,
            time_to:     document.getElementById('e-to').value    || null,
        };

        fetch(STORE_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify(payload)
        })
        .then(function (r) {
            return r.json().then(function (d) { return { status: r.status, data: d }; });
        })
        .then(function (res) {
            if (res.status === 422 || res.data.error) {
                showErr(res.data.error || res.data.message || 'Validation error.');
            } else {
                window.ttCloseModal();
                location.reload();
            }
        })
        .catch(function () { showErr('Network error — please try again.'); })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="icon-check2"></i> Save Entry';
        });
    };

    /* ── delete ── */
    window.ttDelete = function (id) {
        if (!confirm('Remove this period from the timetable?')) return;
        fetch(DELETE_URL + id, {
            method:  'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF }
        })
        .then(function (r) {
            if (r.ok) { location.reload(); }
            else { alert('Could not delete — please try again.'); }
        })
        .catch(function () { alert('Network error. Please try again.'); });
    };

    /* ── backdrop click & Escape ── */
    document.getElementById('tt-overlay').addEventListener('click', function (e) {
        if (e.target === this) window.ttCloseModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.ttCloseModal();
    });

})();
</script>
@endsection
