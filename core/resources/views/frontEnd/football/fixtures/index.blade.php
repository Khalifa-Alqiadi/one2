@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp


@extends('frontEnd.layouts.master')
@push('before-styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #0b0d10;
            --bg-elev: #14171c;
            --bg-elev-2: #1a1f26;
            --border: #252b34;
            --border-strong: #3a4250;
            --text: #e8ebf0;
            --text-muted: #8a94a5;
            --text-dim: #5a6473;
            --accent: #d4ff3f;
            /* ليمون كهربائي */
            --accent-ink: #0b0d10;
            --live: #ff4757;
            --win: #2ed573;
            --loss: #ff6b81;
            --draw: #ffa502;
            --radius: 6px;
            --radius-lg: 12px;
            --font-sans: 'IBM Plex Sans Arabic', -apple-system, system-ui, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-sans);
            font-size: 14px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        body {
            background-image:
                radial-gradient(circle at 20% 0%, rgba(212, 255, 63, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 100%, rgba(212, 255, 63, 0.02) 0%, transparent 50%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
            background: none;
            color: inherit;
        }

        input,
        select {
            font-family: inherit;
        }

        /* ─── Header ─── */
        .site-header {
            border-bottom: 1px solid var(--border);
            background: rgba(11, 13, 16, 0.85);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .site-header__inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .brand__mark {
            width: 28px;
            height: 28px;
            background: var(--accent);
            color: var(--accent-ink);
            display: grid;
            place-items: center;
            font-weight: 700;
            border-radius: 4px;
            font-family: var(--font-mono);
            font-size: 13px;
        }

        .brand__text {
            font-size: 15px;
        }

        .brand__dim {
            color: var(--text-muted);
            font-weight: 400;
            margin-inline-start: 6px;
        }

        .date-range {
            font-family: var(--font-mono);
            color: var(--text-muted);
            font-size: 12px;
            letter-spacing: 0.02em;
        }

        .date-range strong {
            color: var(--accent);
            font-weight: 600;
        }

        /* ─── Main ─── */
        main {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px 80px;
        }

        /* ─── Components ─── */
        .card {
            background: var(--bg-elev);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
        }

        .btn {
            padding: 8px 14px;
            border-radius: var(--radius);
            background: var(--bg-elev-2);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .btn:hover {
            border-color: var(--border-strong);
            background: var(--border);
        }

        .btn--accent {
            background: var(--accent);
            color: var(--accent-ink);
            border-color: var(--accent);
        }

        .btn--accent:hover {
            background: #b8e835;
            border-color: #b8e835;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            font-family: var(--font-mono);
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .badge--live {
            background: rgba(255, 71, 87, 0.12);
            color: var(--live);
            border: 1px solid rgba(255, 71, 87, 0.3);
        }

        .badge--live::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--live);
            animation: pulse 1.4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.3);
            }
        }

        .badge--ft {
            background: var(--border);
            color: var(--text-muted);
        }

        .badge--ns {
            background: rgba(212, 255, 63, 0.08);
            color: var(--accent);
            border: 1px solid rgba(212, 255, 63, 0.2);
        }

        /* ─── Utilities ─── */
        .mono {
            font-family: var(--font-mono);
        }

        .muted {
            color: var(--text-muted);
        }

        .dim {
            color: var(--text-dim);
        }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--border-strong);
        }

        /* ─── Page head ─── */
        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .page-title__hint {
            display: block;
            font-size: 13px;
            font-weight: 400;
            color: var(--text-muted);
            margin-top: 6px;
            letter-spacing: 0;
        }

        .stats {
            display: flex;
            gap: 24px;
            align-items: baseline;
        }

        .stat__num {
            font-family: var(--font-mono);
            font-size: 28px;
            font-weight: 600;
            color: var(--accent);
            line-height: 1;
        }

        .stat__label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 4px;
        }

        /* ─── View switcher ─── */
        .view-bar {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .view-tabs {
            display: inline-flex;
            background: var(--bg-elev);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 3px;
        }

        .view-tabs button {
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 4px;
            color: var(--text-muted);
            transition: all 0.15s;
        }

        .view-tabs button.is-active {
            background: var(--accent);
            color: var(--accent-ink);
        }

        /* ─── Filters ─── */
        .filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filters input,
        .filters select {
            background: var(--bg-elev);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 8px 12px;
            border-radius: var(--radius);
            font-size: 13px;
            outline: none;
            transition: border-color 0.15s;
        }

        .filters input:focus,
        .filters select:focus {
            border-color: var(--accent);
        }

        .filters input {
            min-width: 220px;
        }

        /* ─── Day group (List view) ─── */
        .day-group {
            margin-bottom: 32px;
        }

        .day-group__head {
            display: flex;
            align-items: baseline;
            gap: 12px;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px dashed var(--border);
        }

        .day-group__date {
            font-size: 17px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .day-group__count {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--text-muted);
        }

        /* ─── Match card (List view) ─── */
        .match {
            display: grid;
            grid-template-columns: 60px 1fr auto 1fr 120px;
            align-items: center;
            gap: 16px;
            padding: 14px 18px;
            background: var(--bg-elev);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .match:hover {
            border-color: var(--border-strong);
            background: var(--bg-elev-2);
            transform: translateX(-2px);
        }

        .match__time {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--text-muted);
        }

        .match__team {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .match__team--away {
            justify-content: flex-start;
        }

        .match__team--home {
            justify-content: flex-end;
            text-align: right;
        }

        .match__team img {
            width: 28px;
            height: 28px;
            object-fit: contain;
            border-radius: 4px;
        }

        .match__team-placeholder {
            width: 28px;
            height: 28px;
            border-radius: 4px;
            background: var(--border);
            display: grid;
            place-items: center;
            font-size: 10px;
            color: var(--text-dim);
            font-family: var(--font-mono);
        }

        .match__score {
            font-family: var(--font-mono);
            font-size: 20px;
            font-weight: 600;
            padding: 4px 12px;
            background: var(--bg);
            border-radius: 6px;
            min-width: 68px;
            text-align: center;
            letter-spacing: 0.04em;
        }

        .match__score--no-score {
            font-size: 13px;
            color: var(--text-dim);
            font-weight: 400;
        }

        .match__state {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .match__league {
            font-size: 11px;
            color: var(--text-dim);
        }

        /* ─── Table view ─── */
        .table-wrap {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--bg-elev);
        }

        table.fixtures-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .fixtures-table th {
            background: var(--bg-elev-2);
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: right;
            border-bottom: 1px solid var(--border);
        }

        .fixtures-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .fixtures-table tbody tr {
            cursor: pointer;
            transition: background 0.12s;
        }

        .fixtures-table tbody tr:hover {
            background: var(--bg-elev-2);
        }

        .fixtures-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ─── Card grid view ─── */
        .grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
        }

        .gcard {
            background: var(--bg-elev);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .gcard:hover {
            border-color: var(--border-strong);
            transform: translateY(-2px);
        }

        .gcard__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            font-size: 11px;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }

        .gcard__matchup {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .gcard__row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .gcard__team {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .gcard__team img,
        .gcard__team .match__team-placeholder {
            width: 22px;
            height: 22px;
        }

        .gcard__team span {
            font-weight: 500;
            font-size: 13px;
        }

        .gcard__goal {
            font-family: var(--font-mono);
            font-size: 15px;
            font-weight: 600;
        }

        .gcard__foot {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed var(--border);
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-dim);
        }

        /* ─── Detail panel (overlay) ─── */
        .panel-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .panel-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        .panel {
            position: fixed;
            top: 0;
            left: 0;
            /* RTL: slides in from the left */
            height: 100vh;
            width: min(640px, 94vw);
            background: var(--bg);
            border-inline-end: 1px solid var(--border);
            z-index: 101;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .panel.is-open {
            transform: translateX(0);
        }

        .panel__head {
            position: sticky;
            top: 0;
            background: var(--bg);
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
        }

        .panel__close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--bg-elev);
            display: grid;
            place-items: center;
            font-size: 18px;
            transition: all 0.15s;
        }

        .panel__close:hover {
            background: var(--border);
            transform: rotate(90deg);
        }

        .panel__hero {
            padding: 32px 24px;
            border-bottom: 1px solid var(--border);
            background:
                radial-gradient(circle at 50% 0%, rgba(212, 255, 63, 0.06) 0%, transparent 70%);
        }

        .panel__teams {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 20px;
        }

        .panel__team {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .panel__team img,
        .panel__team .match__team-placeholder {
            width: 56px;
            height: 56px;
        }

        .panel__team-name {
            font-size: 15px;
            font-weight: 600;
            text-align: center;
        }

        .panel__score-box {
            font-family: var(--font-mono);
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 0.04em;
            background: var(--bg-elev);
            padding: 14px 22px;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .panel__meta {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 14px;
            font-size: 12px;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }

        .panel__meta span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* ─── Tabs ─── */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            gap: 4px;
            overflow-x: auto;
            background: var(--bg);
            position: sticky;
            top: 72px;
            z-index: 1;
        }

        .tabs button {
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            border-bottom: 2px solid transparent;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .tabs button:hover {
            color: var(--text);
        }

        .tabs button.is-active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }

        .tab-panel {
            padding: 24px;
            display: none;
        }

        .tab-panel.is-active {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* ─── Detail content blocks ─── */
        .section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .event-row {
            display: grid;
            grid-template-columns: 48px 1fr;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 4px;
            font-size: 13px;
            background: var(--bg-elev);
            border: 1px solid var(--border);
        }

        .event-row__minute {
            font-family: var(--font-mono);
            color: var(--accent);
            font-weight: 600;
        }

        .event-row__text {
            color: var(--text);
        }

        .event-row__player {
            font-weight: 500;
        }

        .event-row__type {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .lineup-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .lineup-col h4 {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .lineup-player {
            display: grid;
            grid-template-columns: 28px 1fr auto;
            gap: 10px;
            padding: 7px 0;
            font-size: 13px;
            align-items: center;
        }

        .lineup-player__num {
            font-family: var(--font-mono);
            color: var(--text-muted);
            font-size: 11px;
            background: var(--bg-elev);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2px 5px;
            text-align: center;
        }

        .lineup-player__pos {
            font-size: 10px;
            color: var(--text-dim);
            font-family: var(--font-mono);
        }

        .stats-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-row {
            display: grid;
            grid-template-columns: 60px 1fr 60px;
            align-items: center;
            gap: 10px;
        }

        .stat-row__name {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-row__val {
            font-family: var(--font-mono);
            font-weight: 600;
            text-align: center;
        }

        .stat-bar {
            height: 6px;
            background: var(--bg-elev);
            border-radius: 3px;
            overflow: hidden;
            display: flex;
        }

        .stat-bar__home {
            background: var(--accent);
        }

        .stat-bar__away {
            background: var(--live);
        }

        .tv-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .tv-item {
            padding: 10px 12px;
            background: var(--bg-elev);
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
        }

        .tv-item__country {
            font-size: 11px;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }

        .empty {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-dim);
            font-size: 13px;
        }

        /* ─── Loading & misc ─── */
        .skeleton {
            background: linear-gradient(90deg, var(--bg-elev) 0%, var(--border) 50%, var(--bg-elev) 100%);
            background-size: 200% 100%;
            animation: shimmer 1.3s infinite;
            border-radius: 6px;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .is-hidden {
            display: none !important;
        }

        @media (max-width: 680px) {
            .match {
                grid-template-columns: 1fr;
                gap: 8px;
                text-align: right;
            }

            .match__team--home,
            .match__team--away {
                justify-content: flex-start;
            }

            .match__state {
                align-items: flex-start;
            }

            .lineup-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
@section('content')

    <section class="page-head">
        <div>
            <h1 class="page-title">
                مباريات الأسبوع
                <span class="page-title__hint">نتائج مباريات آخر ٧ أيام • بيانات من Sportmonks</span>
            </h1>
        </div>
        <div class="stats">
            <div>
                <div class="stat__num mono">{{ count($fixtures) }}</div>
                <div class="stat__label">مباراة</div>
            </div>
            <div>
                <div class="stat__num mono">{{ $leagues->count() }}</div>
                <div class="stat__label">دوري</div>
            </div>
            <div>
                <div class="stat__num mono">{{ count($grouped) }}</div>
                <div class="stat__label">يوم</div>
            </div>
        </div>
    </section>

    <section class="view-bar">
        <div class="view-tabs" role="tablist">
            <button data-view="list" class="is-active">قائمة</button>
            <button data-view="table">جدول</button>
            <button data-view="grid">بطاقات</button>
        </div>

        <div class="filters">
            <input type="text" id="f-search" placeholder="ابحث بفريق، دوري، ملعب…">
            <select id="f-league">
                <option value="">كل الدوريات</option>
                @foreach ($leagues as $lg)
                    <option value="{{ $lg }}">{{ $lg }}</option>
                @endforeach
            </select>
            <select id="f-state">
                <option value="">كل الحالات</option>
                @foreach ($states as $st)
                    <option value="{{ $st }}">{{ $st }}</option>
                @endforeach
            </select>
        </div>
    </section>

    {{-- ═══════════════ List view ═══════════════ --}}
    <div id="view-list" class="view">
        @forelse ($grouped as $date => $items)
            <div class="day-group" data-date="{{ $date }}">
                <div class="day-group__head">
                    <div class="day-group__date">{{ \Carbon\Carbon::parse($date)->locale('ar')->isoFormat('dddd، D MMMM') }}
                    </div>
                    <div class="day-group__count mono">{{ count($items) }} مباراة</div>
                </div>

                @foreach ($items as $fx)
                    @php
                        $short = strtolower($fx['state_short'] ?? '');
                        $stateClass = match (true) {
                            in_array($short, ['live', 'ht', 'inplay_1st_half', 'inplay_2nd_half', 'pen_live', 'et'])
                                => 'badge--live',
                            in_array($short, ['ft', 'aet', 'ft_pen', 'pen']) => 'badge--ft',
                            in_array($short, ['ns', 'tba']) => 'badge--ns',
                            default => 'badge--ft',
                        };
                    @endphp
                    <div class="match" data-id="{{ $fx['id'] }}" data-league="{{ $fx['league'] }}"
                        data-state="{{ $fx['state'] }}"
                        data-search="{{ strtolower(($fx['home']['name'] ?? '') . ' ' . ($fx['away']['name'] ?? '') . ' ' . ($fx['league'] ?? '') . ' ' . ($fx['venue'] ?? '')) }}">

                        <div class="match__time mono">{{ $fx['time'] ?? '--:--' }}</div>

                        <div class="match__team match__team--home">
                            <span>{{ $fx['home']['name'] }}</span>
                            @if ($fx['home']['image'])
                                <img src="{{ $fx['home']['image'] }}" alt="" loading="lazy">
                            @else
                                <div class="match__team-placeholder">{{ mb_substr($fx['home']['name'] ?? '?', 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="match__score {{ is_null($fx['home']['score']) ? 'match__score--no-score' : '' }}">
                            @if (!is_null($fx['home']['score']))
                                {{ $fx['home']['score'] }} - {{ $fx['away']['score'] ?? 0 }}
                            @else
                                —
                            @endif
                        </div>

                        <div class="match__team match__team--away">
                            @if ($fx['away']['image'])
                                <img src="{{ $fx['away']['image'] }}" alt="" loading="lazy">
                            @else
                                <div class="match__team-placeholder">{{ mb_substr($fx['away']['name'] ?? '?', 0, 1) }}
                                </div>
                            @endif
                            <span>{{ $fx['away']['name'] }}</span>
                        </div>

                        <div class="match__state">
                            <span class="badge {{ $stateClass }}">{{ $fx['state'] }}</span>
                            <span class="match__league">{{ $fx['league'] ?? '—' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="empty">ما فيه مباريات في هذي الفترة.</div>
        @endforelse
    </div>

    {{-- ═══════════════ Table view ═══════════════ --}}
    <div id="view-table" class="view is-hidden">
        <div class="table-wrap">
            <table class="fixtures-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>الدوري</th>
                        <th>المضيف</th>
                        <th>النتيجة</th>
                        <th>الضيف</th>
                        <th>الحالة</th>
                        <th>الملعب</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fixtures as $fx)
                        <tr data-id="{{ $fx['id'] }}" data-league="{{ $fx['league'] }}"
                            data-state="{{ $fx['state'] }}"
                            data-search="{{ strtolower(($fx['home']['name'] ?? '') . ' ' . ($fx['away']['name'] ?? '') . ' ' . ($fx['league'] ?? '') . ' ' . ($fx['venue'] ?? '')) }}">
                            <td class="mono dim">{{ $fx['date'] }}</td>
                            <td class="mono">{{ $fx['time'] }}</td>
                            <td class="muted">{{ $fx['league'] ?? '—' }}</td>
                            <td>{{ $fx['home']['name'] }}</td>
                            <td class="mono" style="font-weight: 600;">
                                {{ is_null($fx['home']['score']) ? '—' : $fx['home']['score'] . ' - ' . $fx['away']['score'] }}
                            </td>
                            <td>{{ $fx['away']['name'] }}</td>
                            <td><span class="badge badge--ft">{{ $fx['state'] }}</span></td>
                            <td class="muted">{{ $fx['venue'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════ Grid view ═══════════════ --}}
    <div id="view-grid" class="view is-hidden">
        <div class="grid-view">
            @foreach ($fixtures as $fx)
                <div class="gcard" data-id="{{ $fx['id'] }}" data-league="{{ $fx['league'] }}"
                    data-state="{{ $fx['state'] }}"
                    data-search="{{ strtolower(($fx['home']['name'] ?? '') . ' ' . ($fx['away']['name'] ?? '') . ' ' . ($fx['league'] ?? '') . ' ' . ($fx['venue'] ?? '')) }}">
                    <div class="gcard__head">
                        <span>{{ $fx['date'] }} • {{ $fx['time'] }}</span>
                        <span class="badge badge--ft">{{ $fx['state'] }}</span>
                    </div>
                    <div class="gcard__matchup">
                        <div class="gcard__row">
                            <div class="gcard__team">
                                @if ($fx['home']['image'])
                                    <img src="{{ $fx['home']['image'] }}" alt="">
                                @else
                                    <div class="match__team-placeholder">{{ mb_substr($fx['home']['name'] ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ $fx['home']['name'] }}</span>
                            </div>
                            <span class="gcard__goal">{{ $fx['home']['score'] ?? '—' }}</span>
                        </div>
                        <div class="gcard__row">
                            <div class="gcard__team">
                                @if ($fx['away']['image'])
                                    <img src="{{ $fx['away']['image'] }}" alt="">
                                @else
                                    <div class="match__team-placeholder">{{ mb_substr($fx['away']['name'] ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ $fx['away']['name'] }}</span>
                            </div>
                            <span class="gcard__goal">{{ $fx['away']['score'] ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="gcard__foot">
                        <span>{{ $fx['league'] ?? '—' }}</span>
                        <span>{{ $fx['venue'] ?? '' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════ Detail panel ═══════════════ --}}
    <div class="panel-overlay" id="panel-overlay"></div>
    <aside class="panel" id="panel" aria-hidden="true">
        <div class="panel__head">
            <div class="mono muted" id="panel-league">—</div>
            <button class="panel__close" id="panel-close" aria-label="إغلاق">×</button>
        </div>
        <div id="panel-body">
            {{-- يُحقن ديناميكياً --}}
        </div>
    </aside>
@endsection
@push('after-scripts')
    <script>
        (() => {
            // ═════ View switcher ═════
            const views = {
                list: document.getElementById('view-list'),
                table: document.getElementById('view-table'),
                grid: document.getElementById('view-grid'),
            };
            const tabButtons = document.querySelectorAll('.view-tabs button');
            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabButtons.forEach(b => b.classList.remove('is-active'));
                    btn.classList.add('is-active');
                    Object.values(views).forEach(v => v.classList.add('is-hidden'));
                    views[btn.dataset.view].classList.remove('is-hidden');
                });
            });

            // ═════ Filters (تشتغل على كل الـ rows في كل الـ views) ═════
            const fSearch = document.getElementById('f-search');
            const fLeague = document.getElementById('f-league');
            const fState = document.getElementById('f-state');

            const applyFilters = () => {
                const q = (fSearch.value || '').trim().toLowerCase();
                const lg = fLeague.value;
                const st = fState.value;

                document.querySelectorAll('[data-search]').forEach(el => {
                    const matchSearch = !q || (el.dataset.search || '').includes(q);
                    const matchLeague = !lg || el.dataset.league === lg;
                    const matchState = !st || el.dataset.state === st;
                    const visible = matchSearch && matchLeague && matchState;
                    el.classList.toggle('is-hidden', !visible);
                });

                // إخفاء المجموعات الفارغة في وضع القائمة
                document.querySelectorAll('.day-group').forEach(group => {
                    const anyVisible = group.querySelectorAll('.match:not(.is-hidden)').length > 0;
                    group.classList.toggle('is-hidden', !anyVisible);
                });
            };

            [fSearch, fLeague, fState].forEach(input => {
                input.addEventListener('input', applyFilters);
            });

            // ═════ Detail panel ═════
            const panel = document.getElementById('panel');
            const overlay = document.getElementById('panel-overlay');
            const closeBtn = document.getElementById('panel-close');
            const panelLeague = document.getElementById('panel-league');
            const panelBody = document.getElementById('panel-body');

            const openPanel = () => {
                panel.classList.add('is-open');
                overlay.classList.add('is-open');
                panel.setAttribute('aria-hidden', 'false');
            };
            const closePanel = () => {
                panel.classList.remove('is-open');
                overlay.classList.remove('is-open');
                panel.setAttribute('aria-hidden', 'true');
            };
            overlay.addEventListener('click', closePanel);
            closeBtn.addEventListener('click', closePanel);
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closePanel();
            });

            // ═════ اختيار صف لعرض التفاصيل ═════
            document.querySelectorAll('[data-id]').forEach(el => {
                el.addEventListener('click', async () => {
                    const id = el.dataset.id;
                    panelLeague.textContent = el.dataset.league || '—';
                    panelBody.innerHTML = renderSkeleton();
                    openPanel();

                    try {
                        const res = await fetch(`/fixtures/${id}`);
                        const json = await res.json();
                        if (json.fixture) {
                            panelBody.innerHTML = renderFixture(json.fixture);
                            bindTabs();
                        } else {
                            panelBody.innerHTML =
                            `<div class="empty">ما قدرنا نجيب التفاصيل.</div>`;
                        }
                    } catch (e) {
                        panelBody.innerHTML =
                            `<div class="empty">خطأ بالاتصال. حاول مرة ثانية.</div>`;
                    }
                });
            });

            const renderSkeleton = () => `
        <div class="panel__hero">
            <div class="skeleton" style="height: 120px;"></div>
        </div>
        <div style="padding: 24px;">
            <div class="skeleton" style="height: 16px; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 16px; width: 70%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 16px; width: 90%;"></div>
        </div>
    `;

            const esc = (s) => String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));

            const renderFixture = (fx) => {
                const raw = fx.raw || {};

                // ── الأحداث ──
                const events = (raw.events || []).slice().sort((a, b) => (a.minute ?? 0) - (b.minute ?? 0));
                const eventsHtml = events.length ? events.map(ev => {
                    const player = ev.player?.name || ev.player_name || '—';
                    const relPlayer = ev.related_player?.name;
                    const type = ev.type?.name || ev.type_id || '—';
                    const minute = ev.minute != null ? `${ev.minute}'` : '—';
                    const extra = ev.addition ? ` • ${esc(ev.addition)}` : '';
                    return `
                <div class="event-row">
                    <div class="event-row__minute">${esc(minute)}</div>
                    <div>
                        <div class="event-row__text event-row__player">${esc(player)}</div>
                        <div class="event-row__type">${esc(type)}${relPlayer ? ` ← ${esc(relPlayer)}` : ''}${extra}</div>
                    </div>
                </div>
            `;
                }).join('') : `<div class="empty">ما فيه أحداث مسجّلة.</div>`;

                // ── التشكيلات ──
                const lineupsByTeam = {};
                (raw.lineups || []).forEach(l => {
                    const tid = l.team_id;
                    if (!lineupsByTeam[tid]) lineupsByTeam[tid] = [];
                    lineupsByTeam[tid].push(l);
                });

                const participants = raw.participants || [];
                const home = participants.find(p => p.meta?.location === 'home') || {};
                const away = participants.find(p => p.meta?.location === 'away') || {};

                const renderLineupCol = (team) => {
                    const list = lineupsByTeam[team.id] || [];
                    if (!list.length) return `<div class="empty">ما فيه تشكيلة.</div>`;
                    return list.map(l => `
                <div class="lineup-player">
                    <div class="lineup-player__num">${esc(l.jersey_number ?? '–')}</div>
                    <div>${esc(l.player?.name ?? '—')}</div>
                    <div class="lineup-player__pos">${esc(l.position?.code ?? l.position?.name ?? '')}</div>
                </div>
            `).join('');
                };

                const lineupsHtml = (!lineupsByTeam[home.id] && !lineupsByTeam[away.id]) ?
                    `<div class="empty">ما فيه تشكيلات متاحة.</div>` :
                    `
                <div class="lineup-grid">
                    <div class="lineup-col">
                        <h4>${esc(home.name ?? 'المضيف')}</h4>
                        ${renderLineupCol(home)}
                    </div>
                    <div class="lineup-col">
                        <h4>${esc(away.name ?? 'الضيف')}</h4>
                        ${renderLineupCol(away)}
                    </div>
                </div>
            `;

                // ── الإحصائيات ──
                const statsByType = {};
                (raw.statistics || []).forEach(s => {
                    const name = s.type?.name || `#${s.type_id}`;
                    if (!statsByType[name]) statsByType[name] = {
                        home: 0,
                        away: 0
                    };
                    const val = Number(s.data?.value ?? 0);
                    if (s.location === 'home') statsByType[name].home = val;
                    else if (s.location === 'away') statsByType[name].away = val;
                });

                const statsHtml = Object.keys(statsByType).length ? `
            <div class="stats-grid">
                ${Object.entries(statsByType).map(([name, v]) => {
                    const total = (v.home + v.away) || 1;
                    const hp = Math.round((v.home / total) * 100);
                    const ap = 100 - hp;
                    return `
                            <div class="stat-row">
                                <div class="stat-row__val">${esc(v.home)}</div>
                                <div class="stat-row__name">${esc(name)}</div>
                                <div class="stat-row__val">${esc(v.away)}</div>
                            </div>
                            <div class="stat-bar">
                                <div class="stat-bar__home" style="width: ${hp}%;"></div>
                                <div class="stat-bar__away" style="width: ${ap}%;"></div>
                            </div>
                        `;
                }).join('')}
            </div>
        ` : `<div class="empty">ما فيه إحصائيات.</div>`;

                // ── قنوات البث ──
                const tv = raw.tvStations || [];
                const tvHtml = tv.length ? `
            <div class="tv-list">
                ${tv.map(s => `
                        <div class="tv-item">
                            <span>${esc(s.tvStation?.name ?? '—')}</span>
                            <span class="tv-item__country">${esc(s.country?.name ?? '')}</span>
                        </div>
                    `).join('')}
            </div>
        ` : `<div class="empty">ما فيه قنوات مسجّلة.</div>`;

                // ── Hero ──
                const hs = fx.home?.score,
                    as_ = fx.away?.score;
                const scoreText = (hs != null && as_ != null) ? `${hs} : ${as_}` : 'VS';

                return `
            <div class="panel__hero">
                <div class="panel__teams">
                    <div class="panel__team">
                        ${fx.home?.image
                            ? `<img src="${esc(fx.home.image)}" alt="">`
                            : `<div class="match__team-placeholder">${esc((fx.home?.name || '?').charAt(0))}</div>`}
                        <div class="panel__team-name">${esc(fx.home?.name)}</div>
                    </div>
                    <div class="panel__score-box">${esc(scoreText)}</div>
                    <div class="panel__team">
                        ${fx.away?.image
                            ? `<img src="${esc(fx.away.image)}" alt="">`
                            : `<div class="match__team-placeholder">${esc((fx.away?.name || '?').charAt(0))}</div>`}
                        <div class="panel__team-name">${esc(fx.away?.name)}</div>
                    </div>
                </div>
                <div class="panel__meta">
                    <span>📅 ${esc(fx.date_full ?? fx.date ?? '')}</span>
                    <span>⏰ ${esc(fx.time ?? '')}</span>
                    ${fx.venue ? `<span>📍 ${esc(fx.venue)}</span>` : ''}
                    <span>• ${esc(fx.state ?? '')}</span>
                </div>
            </div>

            <div class="tabs">
                <button class="is-active" data-tab="events">الأحداث</button>
                <button data-tab="lineups">التشكيلات</button>
                <button data-tab="stats">الإحصائيات</button>
                <button data-tab="tv">البث</button>
            </div>

            <div class="tab-panel is-active" data-panel="events">${eventsHtml}</div>
            <div class="tab-panel" data-panel="lineups">${lineupsHtml}</div>
            <div class="tab-panel" data-panel="stats">${statsHtml}</div>
            <div class="tab-panel" data-panel="tv">${tvHtml}</div>
        `;
            };

            const bindTabs = () => {
                const tabs = panelBody.querySelectorAll('.tabs button');
                const panels = panelBody.querySelectorAll('.tab-panel');
                tabs.forEach(btn => {
                    btn.addEventListener('click', () => {
                        tabs.forEach(b => b.classList.remove('is-active'));
                        panels.forEach(p => p.classList.remove('is-active'));
                        btn.classList.add('is-active');
                        panelBody.querySelector(`[data-panel="${btn.dataset.tab}"]`)?.classList.add(
                            'is-active');
                    });
                });
            };
        })();
    </script>
@endpush
