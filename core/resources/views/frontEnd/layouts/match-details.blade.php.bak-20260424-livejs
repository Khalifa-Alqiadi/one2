<script>
    (function() {
        const URL = "{{ route('fixture.live.details', ['id' => $fixtureId]) }}";
        const intervalMs = 10000;
        let timer = null;
        let inflight = false;

        const $ = (q, root = document) => root.querySelector(q);

        function setText(sel, val) {
            const el = $(sel);
            if (el) el.textContent = val ?? '';
        }

        function show(el, yes, displayType = 'inline-block') {
            if (el) el.style.display = yes ? displayType : 'none';
        }

        function showBlock(el, yes) {
            if (el) el.style.display = yes ? 'block' : 'none';
        }

        function escapeHtml(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function setHeader(fx) {
            const status = String(fx.status ?? 'NS').toUpperCase();
            const stateCode = String(fx.state_code ?? 'NS').toUpperCase();


            showBlock($('.js-scorebox'), status !== 'NS');
            showBlock($('.js-kickoffbox'), status === 'NS');

            setText('.js-home', fx.score?.home ?? '-');
            setText('.js-away', fx.score?.away ?? '-');

            show($('.js-status'), status === 'LIVE');
            show($('.js-ns'), stateCode === 'NS');
            show($('.js-ht'), stateCode === 'HT');
            show($('.js-ft'), status === 'FT');
            show($('.js-stop'), stateCode === 'POSTP');

            setText('.js-minute', status === 'LIVE' && fx.minute ? (fx.minute + "'") : '');

            const tabItem = $('.js-events-tab-item');
            if (tabItem) {
                tabItem.style.display = status === 'NS' ? 'none' : 'block';
            }

            showBlock(document.getElementById('js-ns-prob'), status === 'NS');

            const statsSection = $('.js-stats-section');
            if (statsSection) {
                statsSection.style.display = status === 'NS' ? 'none' : 'block';
            }
        }

        function updateProbabilities(p) {
            const home = Number(p?.home ?? 0);
            const draw = Number(p?.draw ?? 0);
            const away = Number(p?.away ?? 0);

            setText('.js-p-home', home + '%');
            setText('.js-p-draw', draw + '%');
            setText('.js-p-away', away + '%');

            const bh = $('.js-bar-home');
            const bd = $('.js-bar-draw');
            const ba = $('.js-bar-away');
            if (bh) bh.style.width = home + '%';
            if (bd) bd.style.width = draw + '%';
            if (ba) ba.style.width = away + '%';
        }

        function updateStatistics(p) {
            const home2 = Number(p?.home ?? 0);
            const away2 = Number(p?.away ?? 0);

            const total = home2 + away2;

            let homePercent = 0;
            let awayPercent = 0;

            if (total > 0) {
                homePercent = Math.round((home2 / total) * 100);
                awayPercent = Math.round((away2 / total) * 100);
            }

            setText('.js-p-home2', homePercent + '%');
            setText('.js-p-away2', awayPercent + '%');

            const bh = $('.js-bar-home2');
            const ba = $('.js-bar-away2');

            if (bh) bh.style.width = homePercent + '%';
            if (ba) ba.style.width = awayPercent + '%';
        }

        function renderEvents(fx) {
            const wrap = $('.js-events-wrapper');
            if (!wrap) return;

            let events = Array.isArray(fx.events?.data) ? fx.events.data : (Array.isArray(fx.events) ? fx.events :
            []);

            if (!events.length) {
                wrap.innerHTML = '<div class="gx-empty">لا توجد أحداث</div>';
                return;
            }

            let html = '';

            events.forEach(e => {
                const kind = e.kind ?? 'other';
                const minute = escapeHtml(e.minute_label ?? '');

                if (kind === 'goal') {
                    const goal = e.goal ?? {};
                    const scorerName = escapeHtml(goal.scorer_name ?? e.player_name ?? '-');
                    const scorerImg = goal.scorer_image ?? '';
                    const assistName = escapeHtml(goal.assist_name ?? '');
                    const scoreLine = escapeHtml(goal.scoreline ?? '');
                    const fallback = scorerName ? scorerName.charAt(0) : '?';

                    html += `
                    <div class="gx-card gx-goal-card">
                        <div class="gx-goal-top">
                            <div class="gx-goal-chip">
                                <span class="gx-goal-icon">⚽</span>
                                هدف
                                <div class="gx-goal-minute">${minute}</div>
                            </div>
                        </div>
                        <div class="gx-goal-scoreline">${scoreLine}</div>
                        <div class="gx-goal-body">
                            <div class="gx-goal-player">
                                <div class="gx-avatar gx-goal-ring">
                                    ${scorerImg ? `<img src="${scorerImg}" alt="" loading="lazy" onerror="this.remove();">` : ''}
                                    <div class="gx-fallback">${fallback}</div>
                                </div>
                                <div class="gx-goal-info">
                                    <div class="gx-name">${scorerName}</div>
                                    ${assistName ? `<div class="gx-meta">أسيست: ${assistName}</div>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    return;
                }

                if (kind === 'sub') {
                    const sub = e.sub ?? {};
                    const inn = sub.in ?? {};
                    const out = sub.out ?? {};

                    const inName = escapeHtml(inn.name ?? '-');
                    const outName = escapeHtml(out.name ?? '-');
                    const inImg = inn.image ?? '';
                    const outImg = out.image ?? '';
                    const inNum = escapeHtml(inn.number ?? '');
                    const outNum = escapeHtml(out.number ?? '');
                    const inPos = escapeHtml(inn.pos ?? '');
                    const outPos = escapeHtml(out.pos ?? '');
                    const inInitial = inName ? inName.charAt(0) : '?';
                    const outInitial = outName ? outName.charAt(0) : '?';

                    html += `
                    <div class="gx-card gx-sub-card">
                        <div class="gx-card-head">
                            <div class="gx-minute">${minute}</div>
                            <div class="gx-title">
                                <span class="gx-icon">🔁</span>
                                تبديل لاعب
                            </div>
                        </div>

                        <div class="gx-card-body">
                            <div class="gx-row">
                                <div class="gx-tag gx-in">دخل</div>
                                <div class="gx-player">
                                    <div class="gx-name">${inName}</div>
                                    <div class="gx-meta">
                                        ${inPos ? `<span>${inPos}</span>` : ''}
                                        ${inNum ? `<span>• #${inNum}</span>` : ''}
                                    </div>
                                </div>
                                <div class="gx-avatar gx-in-ring" title="${inName}">
                                    ${inImg ? `<img src="${inImg}" alt="" loading="lazy" onerror="this.remove();">` : ''}
                                    <div class="gx-fallback">${inInitial}</div>
                                </div>
                            </div>

                            <div class="gx-row mt">
                                <div class="gx-tag gx-out">خرج</div>
                                <div class="gx-player">
                                    <div class="gx-name">${outName}</div>
                                    <div class="gx-meta">
                                        ${outPos ? `<span>${outPos}</span>` : ''}
                                        ${outNum ? `<span>• #${outNum}</span>` : ''}
                                    </div>
                                </div>
                                <div class="gx-avatar gx-out-ring" title="${outName}">
                                    ${outImg ? `<img src="${outImg}" alt="" loading="lazy" onerror="this.remove();">` : ''}
                                    <div class="gx-fallback">${outInitial}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    return;
                }
            });

            wrap.innerHTML = html || '<div class="gx-empty">لا توجد أحداث</div>';
        }

        function renderStats(fx) {
            const wrap = $('.js-stats-wrapper');
            if (!wrap) return;

            const stats = Array.isArray(fx.statistics_rows) ? fx.statistics_rows : [];

            if (!stats.length) {
                wrap.innerHTML = '<div class="text-muted">لا توجد إحصائيات</div>';
                return;
            }

            let html = '';

            stats.forEach(row => {
                html += `
                <div class="gx-stat-row">
                    <div class="gx-val gx-left">
                        <span class="gx-pill gx-pill-home">${escapeHtml(row.home ?? '-')}</span>
                    </div>
                    <div class="gx-label">${escapeHtml(row.label ?? '-')}</div>
                    <div class="gx-val gx-right">
                        <span class="gx-pill gx-pill-away">${escapeHtml(row.away ?? '-')}</span>
                    </div>
                </div>
            `;
            });

            wrap.innerHTML = html;
        }

        function groupPlayersByRows(players) {
            const map = {};

            (players || []).forEach(p => {
                const field = String(p.formation_field ?? '');
                let r = 0,
                    c = 0;

                if (field.includes(':')) {
                    const parts = field.split(':');
                    r = parseInt(parts[0] || '0', 10);
                    c = parseInt(parts[1] || '0', 10);
                }

                if (!map[r]) map[r] = [];
                map[r].push({
                    ...p,
                    _r: r,
                    _c: c
                });
            });

            return Object.keys(map)
                .map(k => Number(k))
                .sort((a, b) => a - b)
                .map(k => ({
                    row: k,
                    players: map[k].sort((a, b) => (a._c || 0) - (b._c || 0))
                }));
        }

        function formationLabel(grouped) {
            const out = [];
            grouped.forEach(g => {
                if ((g.players || []).length === 1) return;
                out.push(g.players.length);
            });
            return out.join('-');
        }

        function playerName(p) {
            return p?.player?.display_name || p?.player?.name || p?.player_name || '-';
        }

        function playerNum(p) {
            return p?.jersey_number ?? '';
        }

        function renderLineups(fx) {
            const wrap = $('.js-lineups-wrapper');
            if (!wrap) return;

            const lineups = Array.isArray(fx.lineups) ? fx.lineups : [];
            if (!lineups.length) {
                wrap.innerHTML = '<div class="text-muted">لا توجد تشكيلات</div>';
                return;
            }

            const homeId = fx.home?.id ?? null;
            const awayId = fx.away?.id ?? null;

            const homeAll = lineups.filter(p => Number(p.team_id) === Number(homeId));
            const awayAll = lineups.filter(p => Number(p.team_id) === Number(awayId));

            let homeXI = homeAll.filter(p => Number(p.type_id) === 11);
            let awayXI = awayAll.filter(p => Number(p.type_id) === 11);

            if (!homeXI.length) homeXI = homeAll.slice(0, 11);
            if (!awayXI.length) awayXI = awayAll.slice(0, 11);

            const homeBench = homeAll.filter(p => Number(p.type_id) !== 11);
            const awayBench = awayAll.filter(p => Number(p.type_id) !== 11);

            const homeRows = groupPlayersByRows(homeXI);
            const awayRows = groupPlayersByRows(awayXI);

            const homeFormation = formationLabel(homeRows);
            const awayFormation = formationLabel(awayRows);

            let html = `
            <div class="gx-topline">
                <div class="gx-formation">${escapeHtml(awayFormation)}</div>
                <div class="gx-team">${escapeHtml(fx.away?.name ?? '')}</div>
            </div>

            <div class="gx-field">
        `;

            awayRows.forEach(g => {
                html += `<div class="gx-line">`;
                g.players.forEach(p => {
                    html += `
                    <div class="gx-player">
                        <div class="gx-badge gx-away">
                            <span class="gx-num">${escapeHtml(playerNum(p))}</span>
                        </div>
                        <div class="gx-name">${escapeHtml(playerName(p))}</div>
                    </div>
                `;
                });
                html += `</div>`;
            });

            html += `<div class="gx-mid"></div>`;

            homeRows.forEach(g => {
                html += `<div class="gx-line">`;
                g.players.forEach(p => {
                    html += `
                    <div class="gx-player">
                        <div class="gx-badge gx-home">
                            <span class="gx-num">${escapeHtml(playerNum(p))}</span>
                        </div>
                        <div class="gx-name">${escapeHtml(playerName(p))}</div>
                    </div>
                `;
                });
                html += `</div>`;
            });

            html += `
            </div>

            <div class="gx-bottomline">
                <div class="gx-team">${escapeHtml(fx.home?.name ?? '')}</div>
                <div class="gx-formation">${escapeHtml(homeFormation)}</div>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-lg-6">
                    <div class="fw-bold mb-2">${escapeHtml(fx.home?.name ?? '')} - البدلاء</div>
                    <ul class="list-group list-group-flush">
                        ${homeBench.length ? homeBench.map(p => `
                            <li class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between">
                                <span>${escapeHtml(playerName(p))}</span>
                                <span class="text-muted small">#${escapeHtml(playerNum(p))}</span>
                            </li>
                        `).join('') : `
                            <li class="list-group-item bg-dark text-muted border-secondary">لا يوجد بدلاء</li>
                        `}
                    </ul>
                </div>

                <div class="col-lg-6">
                    <div class="fw-bold mb-2">${escapeHtml(fx.away?.name ?? '')} - البدلاء</div>
                    <ul class="list-group list-group-flush">
                        ${awayBench.length ? awayBench.map(p => `
                            <li class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between">
                                <span>${escapeHtml(playerName(p))}</span>
                                <span class="text-muted small">#${escapeHtml(playerNum(p))}</span>
                            </li>
                        `).join('') : `
                            <li class="list-group-item bg-dark text-muted border-secondary">لا يوجد بدلاء</li>
                        `}
                    </ul>
                </div>
            </div>
        `;

            wrap.innerHTML = html;
        }

        async function tick() {
            if (inflight) return;
            inflight = true;

            try {
                const res = await fetch(URL, {
                    method: 'GET',
                    cache: 'no-store',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) return;

                const json = await res.json();
                if (!json || !json.ok || !json.data) return;

                const fx = json.data;

                setHeader(fx);

                if (fx.probabilities) {
                    updateProbabilities(fx.probabilities);
                }
                // if (fx.statistics_rows) {
                //     updateStatistics(fx.statistics_rows);
                // }

                renderEvents(fx);
                renderStats(fx);
                renderLineups(fx);


                const status = String(fx.status ?? '').toUpperCase();
                if (status === 'FT') {
                    clearInterval(timer);
                    timer = null;
                }

            } catch (e) {
                console.error('Live match details polling failed:', e);
            } finally {
                inflight = false;
            }
        }

        tick();
        timer = setInterval(tick, intervalMs);
        startLiveCommentary(true)


//         async function loadCommentary(fixtureId) {
//             const resCommentary = await fetch("{{ route('commentary', ['id' => $fixtureId]) }}");
//             const data = await resCommentary.json();
//
//             // if (!resCommentary.ok) return;
//             renderCommentary(data.data);
//         }

        function renderCommentary(events) {
            const container = document.querySelector('.js-commentary');
            if (!container) return;

            container.innerHTML = '';

            events.forEach(e => {

                const minute = e.extra_minute ?
                    `${e.minute}+${e.extra_minute}` :
                    e.minute;

                const row = document.createElement('div');
                row.className = 'commentary-row';

                row.innerHTML = `
            <div class="minute">${minute}'</div>
            <div class="text">${e.comment}</div>
        `;

                container.appendChild(row);
            });
        }

        function startLiveCommentary(isLive) {
            var fixtureId = `{{$fixtureId}}`;
            if (!isLive) return;

//             loadCommentary(fixtureId);
//
//             setInterval(() => {
//                 loadCommentary(fixtureId);
//             }, 10000); // كل 10 ثواني
        }
    })();
</script>
