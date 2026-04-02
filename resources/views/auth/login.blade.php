<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ config('app.name', 'Bitgenius') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: #111827;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ──────────────────────────────────────────
           BRAND BAR — centred top
        ────────────────────────────────────────── */
        .brand-bar {
            width: 100%;
            padding: 20px 0 14px;
            text-align: center;
            background: #111827;
            flex-shrink: 0;
        }
        .brand-bar a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .brand-text {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #a855f7 0%, #c084fc 50%, #e879f9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .brand-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a855f7, #e879f9);
            flex-shrink: 0;
            margin-bottom: 2px;
        }

        /* ──────────────────────────────────────────
           MAIN CARD WRAPPER
        ────────────────────────────────────────── */
        .page-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px 20px 32px;
        }

        .login-card {
            display: flex;
            width: 100%;
            max-width: 980px;
            min-height: 580px;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
        }

        /* ──────────────────────────────────────────
           LEFT PANEL — dark form side
        ────────────────────────────────────────── */
        .left-panel {
            flex: 0 0 42%;
            background: #0f172a;
            padding: 52px 46px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 5px;
        }
        .form-subtitle {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 28px;
        }

        /* Error alert */
        .err-box {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.35);
            border-radius: 10px;
            padding: 11px 14px;
            color: #fca5a5;
            font-size: 0.83rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }


        /* ── Custom Role Dropdown ── */
        .custom-select-wrap {
            position: relative;
        }
        .select-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #0d1117;
            border: 1.5px solid #1f2937;
            border-radius: 9px;
            padding: 11px 14px;
            color: #e2e8f0;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            gap: 8px;
        }
        .select-trigger:hover,
        .custom-select-wrap.open .select-trigger {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
        }
        .select-trigger .role-icon { font-size: 1rem; }
        .select-trigger .chevron {
            flex-shrink: 0;
            color: #6b7280;
            transition: transform 0.2s;
        }
        .custom-select-wrap.open .chevron { transform: rotate(180deg); }

        .select-list {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0; right: 0;
            background: #0d1117;
            border: 1.5px solid #1f2937;
            border-radius: 10px;
            list-style: none;
            padding: 6px;
            z-index: 100;
            box-shadow: 0 16px 40px rgba(0,0,0,0.5);
            animation: dropIn 0.18s ease;
        }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .custom-select-wrap.open .select-list { display: block; }

        .select-opt {
            padding: 10px 12px;
            border-radius: 7px;
            font-size: 0.875rem;
            color: #9ca3af;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .select-opt:hover { background: #1f2937; color: #e2e8f0; }
        .select-opt.active {
            background: rgba(124,58,237,0.2);
            color: #c4b5fd;
            font-weight: 600;
        }
        .select-opt.active::after {
            content: '✓';
            margin-left: auto;
            color: #a855f7;
            font-size: 0.85rem;
        }

        /* Input fields */
        .field {
            margin-bottom: 20px;
        }
        .field-lbl {
            display: block;
            font-size: 0.76rem;
            color: #6b7280;
            margin-bottom: 7px;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        .field-inp {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #1f2937;
            padding: 10px 0;
            font-size: 0.9rem;
            color: #f1f5f9;
            outline: none;
            transition: border-color 0.25s;
            font-family: 'Inter', sans-serif;
        }
        .field-inp::placeholder { color: #374151; }
        .field-inp:focus { border-bottom-color: #7c3aed; }

        .pw-wrap { position: relative; }
        .pw-toggle {
            position: absolute; right: 0; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; padding: 4px;
            color: #4b5563;
            transition: color 0.2s;
            line-height: 1;
        }
        .pw-toggle:hover { color: #a855f7; }

        .forgot-lnk {
            display: inline-block;
            font-size: 0.78rem;
            color: #6b7280;
            text-decoration: none;
            margin-top: 4px;
            transition: color 0.2s;
        }
        .forgot-lnk:hover { color: #a855f7; }

        /* Submit button */
        .btn-login {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            border-radius: 9px;
            border: none;
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 60%, #c026d3 100%);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.2px;
            font-family: 'Inter', sans-serif;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(124,58,237,0.4);
        }
        .btn-login:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(124,58,237,0.5);
        }
        .btn-login:active { transform: translateY(0); }

        /* ──────────────────────────────────────────
           RIGHT PANEL — purple hero side
        ────────────────────────────────────────── */
        .right-panel {
            flex: 0 0 58%;
            background: linear-gradient(150deg, #6d28d9 0%, #7c3aed 30%, #9333ea 65%, #a855f7 100%);
            position: relative;
            display: flex;
            padding: 40px 36px 0;
            overflow: hidden;
        }

        /* Blob decorations */
        .blob { position: absolute; border-radius: 50%; }
        .b1 { width: 260px; height: 260px; top: -80px; right: -80px; background: rgba(255,255,255,0.07); }
        .b2 { width: 180px; height: 180px; top: 60px; left: -50px;  background: rgba(255,255,255,0.06); }
        .b3 { width: 350px; height: 350px; bottom: -120px; right: -100px; background: rgba(255,255,255,0.05); }
        .b4 { width: 120px; height: 120px; bottom: 140px; left: 30px; background: rgba(255,255,255,0.04); }

        /* Inner frosted card */
        .right-inner {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.11);
            border-radius: 18px;
            padding: 38px 38px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            overflow: hidden;
        }

        .welcome-line1 {
            font-size: 2.7rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.1;
        }
        .welcome-line2 {
            font-size: 2.3rem;
            font-weight: 400;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 12px;
        }
        .welcome-sub {
            font-size: 0.84rem;
            color: rgba(255,255,255,0.72);
            margin-bottom: 20px;
        }

        /* SVG illustration */
        .hero-svg {
            display: block;
            width: 100%;
            margin-top: auto;
            flex-shrink: 0;
        }

        /* ──────────────────────────────────────────
           RESPONSIVE
        ────────────────────────────────────────── */
        @media (max-width: 800px) {
            .login-card { flex-direction: column; max-width: 480px; }
            .left-panel, .right-panel { flex: none; width: 100%; }
            .left-panel { padding: 40px 32px; }
            .right-panel { padding: 32px 24px 0; min-height: 340px; }
            .welcome-line1 { font-size: 2rem; }
            .welcome-line2 { font-size: 1.7rem; }
        }
    </style>
</head>
<body>

    <!-- ── BRAND / HEADER ── -->
    <header class="brand-bar">
        <a href="{{ url('/') }}">
            <span class="brand-text">Bitgenius</span>
            <span class="brand-dot"></span>
        </a>
    </header>

    <!-- ── PAGE BODY ── -->
    <main class="page-body">
        <div class="login-card">

            {{-- ── LEFT: FORM PANEL ── --}}
            <div class="left-panel">
                <h1 class="form-title">Login</h1>
                <p class="form-subtitle">Enter your account details</p>

                @if ($errors->any())
                    <div class="err-box">
                        {{ implode(' ', $errors->all()) }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    {{-- Hidden role field submitted with form --}}
                    <input type="hidden" name="role" id="roleInput" value="student">

                    {{-- Role dropdown --}}
                    <div class="field" style="margin-bottom:26px;">
                        <label class="field-lbl">Login As</label>
                        <div class="custom-select-wrap" id="roleDropdown">
                            <button type="button" class="select-trigger" id="selectTrigger" aria-haspopup="listbox" aria-expanded="false">
                                <span id="selectedLabel">
                                    <span class="role-icon">🎓</span> Student
                                </span>
                                <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>
                            <ul class="select-list" id="selectList" role="listbox" aria-label="Select role">
                                <li class="select-opt active" data-value="student"   data-l1="Welcome to" data-l2="Student portal"    data-icon="🎓">🎓 &nbsp;Student</li>
                                <li class="select-opt"        data-value="teacher"   data-l1="Welcome to" data-l2="Teacher portal"    data-icon="📚">📚 &nbsp;Teacher</li>
                                <li class="select-opt"        data-value="parent"    data-l1="Welcome to" data-l2="Parent portal"     data-icon="👨‍👩‍👧">👨‍👩‍👧 &nbsp;Parent</li>
                                <li class="select-opt"        data-value="admin"     data-l1="Welcome to" data-l2="Admin portal"      data-icon="🛡️">🛡️ &nbsp;Admin</li>
                                <li class="select-opt"        data-value="super_admin" data-l1="Welcome to" data-l2="Super Admin portal" data-icon="⚡">⚡ &nbsp;Super Admin</li>
                            </ul>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-lbl">Username</label>
                        <input type="text"
                               class="field-inp"
                               name="identity"
                               id="identity"
                               value="{{ old('identity') }}"
                               placeholder="Login ID or Email"
                               autocomplete="username">
                    </div>

                    <div class="field">
                        <label class="field-lbl">Password</label>
                        <div class="pw-wrap">
                            <input type="password"
                                   class="field-inp"
                                   name="password"
                                   id="passwordField"
                                   placeholder="••••••••"
                                   autocomplete="current-password"
                                   required>
                            <button type="button" class="pw-toggle" id="pwToggle" aria-label="Toggle password visibility">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeClosed" style="display:none" xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7a9.97 9.97 0 012.074-3.13M6.343 6.343A9.97 9.97 0 0112 5c5 0 9 4 9 7a9.97 9.97 0 01-4.222 5.344M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <a href="{{ route('password.request') }}" class="forgot-lnk">Forgot Password?</a>

                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>

            {{-- ── RIGHT: HERO PANEL ── --}}
            <div class="right-panel">
                <div class="blob b1"></div>
                <div class="blob b2"></div>
                <div class="blob b3"></div>
                <div class="blob b4"></div>

                <div class="right-inner">
                    <div class="welcome-line1" id="wLine1">Welcome to</div>
                    <div class="welcome-line2" id="wLine2">Student portal</div>
                    <p class="welcome-sub">Login to access your account</p>

                    {{-- Inline SVG illustration (two students with resume/document) --}}
                    <svg class="hero-svg" viewBox="0 0 520 340" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                        {{-- ── Document / Resume ── --}}
                        <rect x="130" y="80" width="200" height="240" rx="12" fill="white" fill-opacity="0.92" stroke="white" stroke-opacity="0.3" stroke-width="1"/>
                        {{-- Document header bar --}}
                        <rect x="130" y="80" width="200" height="30" rx="12" fill="white" fill-opacity="0.15"/>
                        <rect x="130" y="98" width="200" height="12" fill="white" fill-opacity="0.15"/>
                        {{-- "APPLICATION" text --}}
                        <text x="195" y="101" font-family="Inter,sans-serif" font-size="11" font-weight="700" fill="#7c3aed" letter-spacing="0.5">APPLICATION</text>
                        <line x1="150" y1="108" x2="310" y2="108" stroke="#e5e7eb" stroke-width="1"/>
                        {{-- Name row --}}
                        <text x="148" y="125" font-family="Inter,sans-serif" font-size="7" font-weight="600" fill="#374151">NAME</text>
                        <line x1="148" y1="129" x2="220" y2="129" stroke="#d1d5db" stroke-width="1.5"/>
                        {{-- Contact row --}}
                        <text x="148" y="143" font-family="Inter,sans-serif" font-size="7" font-weight="600" fill="#374151">CONTACT</text>
                        <line x1="148" y1="147" x2="210" y2="147" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="148" y1="153" x2="215" y2="153" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="148" y1="159" x2="205" y2="159" stroke="#d1d5db" stroke-width="1"/>
                        {{-- Skills --}}
                        <text x="148" y="175" font-family="Inter,sans-serif" font-size="7" font-weight="600" fill="#374151">SKILLS</text>
                        <line x1="148" y1="178" x2="218" y2="178" stroke="#a855f7" stroke-width="2" stroke-linecap="round"/>
                        <line x1="148" y1="185" x2="210" y2="185" stroke="#a855f7" stroke-width="2" stroke-linecap="round"/>
                        <line x1="148" y1="192" x2="225" y2="192" stroke="#a855f7" stroke-width="2" stroke-linecap="round"/>
                        <line x1="148" y1="199" x2="200" y2="199" stroke="#a855f7" stroke-width="2" stroke-linecap="round"/>
                        {{-- Experience column (right side) --}}
                        <text x="240" y="125" font-family="Inter,sans-serif" font-size="7" font-weight="600" fill="#374151">EXPERIENCE</text>
                        <line x1="240" y1="129" x2="308" y2="129" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="240" y1="136" x2="300" y2="136" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="240" y1="143" x2="305" y2="143" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="240" y1="150" x2="295" y2="150" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="240" y1="167" x2="308" y2="167" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="240" y1="174" x2="302" y2="174" stroke="#d1d5db" stroke-width="1"/>
                        {{-- Education --}}
                        <line x1="148" y1="215" x2="310" y2="215" stroke="#e5e7eb" stroke-width="1"/>
                        <text x="240" y="230" font-family="Inter,sans-serif" font-size="7" font-weight="600" fill="#374151">EDUCATION</text>
                        <rect x="240" y="235" width="16" height="16" rx="2" stroke="#9ca3af" stroke-width="1.2" fill="none"/>
                        <line x1="243" y1="238" x2="253" y2="248" stroke="#9ca3af" stroke-width="1.2"/>
                        <line x1="253" y1="238" x2="243" y2="248" stroke="#9ca3af" stroke-width="1.2"/>
                        <line x1="262" y1="238" x2="305" y2="238" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="262" y1="244" x2="300" y2="244" stroke="#d1d5db" stroke-width="1"/>
                        <line x1="262" y1="250" x2="308" y2="250" stroke="#d1d5db" stroke-width="1"/>

                        {{-- ── Student 1: sitting on top of document with laptop ── --}}
                        {{-- Body --}}
                        <ellipse cx="210" cy="68" rx="14" ry="14" fill="white" fill-opacity="0.9"/>
                        {{-- head --}}
                        <circle cx="210" cy="54" r="11" fill="white" fill-opacity="0.9" stroke="white" stroke-opacity="0.2" stroke-width="0.5"/>
                        {{-- hair --}}
                        <path d="M200 50 Q205 42 215 43 Q222 44 219 52 Q210 46 200 50Z" fill="rgba(60,40,20,0.7)"/>
                        <path d="M218 51 Q222 55 219 60" stroke="rgba(60,40,20,0.7)" stroke-width="2" fill="none"/>
                        {{-- torso hoodie --}}
                        <path d="M196 68 Q196 80 200 86 L220 86 Q224 80 224 68 Q217 72 210 72 Q203 72 196 68Z" fill="white" fill-opacity="0.85"/>
                        {{-- Laptop on lap --}}
                        <rect x="196" y="82" width="34" height="22" rx="3" fill="#1f2937" stroke="white" stroke-opacity="0.3" stroke-width="0.8"/>
                        <rect x="198" y="84" width="30" height="18" rx="2" fill="#0f172a"/>
                        <rect x="199" y="85" width="28" height="16" rx="1" fill="#1e3a5f"/>
                        {{-- code on screen --}}
                        <line x1="201" y1="89" x2="213" y2="89" stroke="#a855f7" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="201" y1="93" x2="219" y2="93" stroke="#10b981" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="201" y1="97" x2="208" y2="97" stroke="#a855f7" stroke-width="1.5" stroke-linecap="round"/>
                        {{-- Legs sitting --}}
                        <path d="M200 86 Q192 100 185 108" stroke="white" stroke-opacity="0.9" stroke-width="7" stroke-linecap="round" fill="none"/>
                        <path d="M220 86 Q228 100 236 106" stroke="white" stroke-opacity="0.9" stroke-width="7" stroke-linecap="round" fill="none"/>
                        {{-- Shoes --}}
                        <ellipse cx="183" cy="110" rx="7" ry="4" fill="#1f2937"/>
                        <ellipse cx="237" cy="108" rx="7" ry="4" fill="#1f2937"/>

                        {{-- ── Student 2: standing beside document, phone ── --}}
                        {{-- Head --}}
                        <circle cx="348" cy="136" r="13" fill="white" fill-opacity="0.9"/>
                        {{-- hair --}}
                        <path d="M336 131 Q338 123 348 122 Q358 122 361 130 Q355 126 348 127 Q341 127 336 131Z" fill="rgba(60,40,20,0.65)"/>
                        {{-- neck --}}
                        <rect x="344" y="148" width="8" height="8" rx="2" fill="white" fill-opacity="0.85"/>
                        {{-- torso / jacket --}}
                        <path d="M332 156 Q332 200 335 218 L362 218 Q365 200 365 156 Q356 162 348 162 Q340 162 332 156Z" fill="white" fill-opacity="0.85"/>
                        {{-- backpack --}}
                        <rect x="342" y="162" width="18" height="26" rx="4" fill="rgba(124,58,237,0.55)" stroke="white" stroke-opacity="0.4" stroke-width="0.8"/>
                        <line x1="351" y1="162" x2="351" y2="188" stroke="white" stroke-opacity="0.4" stroke-width="1"/>
                        {{-- Phone in hand --}}
                        <rect x="362" y="170" width="16" height="26" rx="3" fill="#1f2937" stroke="white" stroke-opacity="0.4" stroke-width="1"/>
                        <rect x="364" y="172" width="12" height="20" rx="2" fill="#0f172a"/>
                        {{-- Screen glow --}}
                        <rect x="364" y="172" width="12" height="20" rx="2" fill="#a855f7" fill-opacity="0.3"/>
                        <line x1="366" y1="177" x2="374" y2="177" stroke="white" stroke-opacity="0.7" stroke-width="1" stroke-linecap="round"/>
                        <line x1="366" y1="181" x2="372" y2="181" stroke="white" stroke-opacity="0.5" stroke-width="1" stroke-linecap="round"/>
                        {{-- arm holding phone --}}
                        <path d="M362 168 Q368 165 366 170" stroke="white" stroke-opacity="0.8" stroke-width="5" stroke-linecap="round" fill="none"/>
                        {{-- Legs --}}
                        <path d="M335 218 Q334 252 334 270" stroke="white" stroke-opacity="0.9" stroke-width="9" stroke-linecap="round" fill="none"/>
                        <path d="M362 218 Q363 252 364 270" stroke="white" stroke-opacity="0.9" stroke-width="9" stroke-linecap="round" fill="none"/>
                        {{-- Shoes --}}
                        <ellipse cx="333" cy="273" rx="10" ry="5" fill="#1f2937"/>
                        <ellipse cx="365" cy="273" rx="10" ry="5" fill="#1f2937"/>

                        {{-- ── Decorative plants (bottom right) ── --}}
                        {{-- Stem 1 --}}
                        <path d="M430 320 Q432 290 440 270" stroke="rgba(255,255,255,0.55)" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <ellipse cx="438" cy="265" rx="10" ry="6" fill="rgba(255,255,255,0.45)" transform="rotate(-20 438 265)"/>
                        <ellipse cx="445" cy="278" rx="9" ry="5" fill="rgba(255,255,255,0.38)" transform="rotate(15 445 278)"/>
                        <ellipse cx="432" cy="280" rx="8" ry="5" fill="rgba(255,255,255,0.38)" transform="rotate(-30 432 280)"/>
                        {{-- Stem 2 --}}
                        <path d="M460 320 Q462 285 472 260" stroke="rgba(255,255,255,0.45)" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <ellipse cx="471" cy="255" rx="9" ry="5" fill="rgba(255,255,255,0.38)" transform="rotate(-10 471 255)"/>
                        <ellipse cx="478" cy="270" rx="8" ry="5" fill="rgba(255,255,255,0.32)" transform="rotate(20 478 270)"/>
                        <ellipse cx="463" cy="272" rx="7" ry="4" fill="rgba(255,255,255,0.32)" transform="rotate(-25 463 272)"/>
                        {{-- Stem 3 --}}
                        <path d="M490 320 Q493 295 500 278" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <ellipse cx="499" cy="274" rx="8" ry="5" fill="rgba(255,255,255,0.35)" transform="rotate(5 499 274)"/>
                        <ellipse cx="506" cy="285" rx="7" ry="4" fill="rgba(255,255,255,0.28)" transform="rotate(25 506 285)"/>
                        {{-- Small berries --}}
                        <circle cx="445" cy="258" r="3" fill="rgba(255,255,255,0.6)"/>
                        <circle cx="450" cy="253" r="2.5" fill="rgba(255,255,255,0.5)"/>
                        <circle cx="478" cy="262" r="2.5" fill="rgba(255,255,255,0.5)"/>
                        <circle cx="484" cy="258" r="2" fill="rgba(255,255,255,0.45)"/>
                        {{-- Ground line --}}
                        <line x1="415" y1="320" x2="520" y2="320" stroke="rgba(255,255,255,0.2)" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

        </div>
    </main>

    <script>
        // ── Role Dropdown Logic ──
        const trigger       = document.getElementById('selectTrigger');
        const dropdown      = document.getElementById('roleDropdown');
        const selectList    = document.getElementById('selectList');
        const selectedLabel = document.getElementById('selectedLabel');
        const roleInput     = document.getElementById('roleInput');
        const wLine1        = document.getElementById('wLine1');
        const wLine2        = document.getElementById('wLine2');

        // Toggle open/close
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('open');
            trigger.setAttribute('aria-expanded', dropdown.classList.contains('open'));
        });

        // Option selection
        selectList.querySelectorAll('.select-opt').forEach(opt => {
            opt.addEventListener('click', () => {
                // Deactivate all
                selectList.querySelectorAll('.select-opt').forEach(o => o.classList.remove('active'));
                opt.classList.add('active');

                // Update hidden input
                roleInput.value = opt.dataset.value;

                // Update trigger label
                selectedLabel.innerHTML = `<span class="role-icon">${opt.dataset.icon}</span> ${opt.textContent.trim()}`;

                // Update right-panel welcome text
                wLine1.textContent = opt.dataset.l1;
                wLine2.textContent = opt.dataset.l2;

                // Close dropdown
                dropdown.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            });
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // ── Password toggle ──
        const pwField   = document.getElementById('passwordField');
        const pwToggle  = document.getElementById('pwToggle');
        const eyeOpen   = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        pwToggle.addEventListener('click', () => {
            const show = pwField.type === 'password';
            pwField.type = show ? 'text' : 'password';
            eyeOpen.style.display   = show ? 'none'   : 'block';
            eyeClosed.style.display = show ? 'block'  : 'none';
        });
    </script>
</body>
</html>
