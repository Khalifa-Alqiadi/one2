<script>
    (function() {
        const URL = "{{ route('fixture.live.details', ['id' => $fixtureId]) }}";
        const intervalMs = 10000;
        let timer = null;
        let inflight = false;

        const $ = (q, root = document) => root.querySelector(q);

        function setText(sel, val) {
            const el = $(sel);
            if (el) {
                el.textContent = val ?? '';
            }
        }

        function show(el, yes, displayType = 'inline-block') {
            if (el) {
                el.style.display = yes ? displayType : 'none';
            }
        }

        function showBlock(el, yes, displayType = 'block') {
            if (el) {
                el.style.display = yes ? displayType : 'none';
            }
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
            const isNotStarted = stateCode === 'NS' || status === 'NS';
            const isLiveNow = ['LIVE', 'INPLAY_1ST', 'INPLAY_2ND'].includes(stateCode) || status === 'LIVE';

            showBlock($('.js-scorebox'), !isNotStarted, 'inline-flex');
            showBlock($('.js-kickoffbox'), isNotStarted, 'inline-flex');

            setText('.js-home', fx.score?.home ?? '-');
            setText('.js-away', fx.score?.away ?? '-');

            show($('.js-status'), isLiveNow);
            show($('.js-ns'), isNotStarted);
            show($('.js-ht'), stateCode === 'HT');
            show($('.js-ft'), stateCode === 'FT' && status === 'FT');
            show($('.js-stop'), stateCode === 'POSTP');

            setText('.js-minute', isLiveNow && fx.minute ? (fx.minute + "'") : '');

            const tabItem = $('.js-events-tab-item');
            if (tabItem) {
                tabItem.style.display = isNotStarted ? 'none' : 'block';
            }

            showBlock(document.getElementById('js-ns-prob'), isNotStarted);

            const statsSection = $('.js-stats-section');
            if (statsSection) {
                statsSection.style.display = isNotStarted ? 'none' : 'block';
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

        function renderEvents(fx) {
            const wrap = $('.js-events-wrapper');
            if (!wrap) return;

            let events = Array.isArray(fx.events?.data) ? fx.events.data : (Array.isArray(fx.events) ? fx.events : []);

            if (!events.length) {
                wrap.innerHTML = '<div class="soft-empty">لا توجد أحداث</div>';
                return;
            }

            const html = events.map((e) => {
                const kind = String(e.kind ?? 'other');
                const minute = escapeHtml(e.minute_label ?? '-');
                const playerName = escapeHtml(e.player_name ?? '');
                const playerImage = e.player_image ?? '';
                const eventTypeName = String(e.type_name ?? e.event_name ?? e.name ?? '').toLowerCase();
                const isYellowCard = kind === 'yellow_card' || eventTypeName.includes('yellow');
                const isRedCard = kind === 'red_card' || eventTypeName.includes('red');

                const sub = e.sub ?? {};
                const inPlayer = sub.in ?? {};
                const outPlayer = sub.out ?? {};
                const inName = escapeHtml(inPlayer.name ?? '-');
                const outName = escapeHtml(outPlayer.name ?? '-');
                const inImg = inPlayer.image ?? '';
                const outImg = outPlayer.image ?? '';

                const goal = e.goal ?? {};
                const scorerName = escapeHtml(goal.scorer_name ?? e.player_name ?? '-');
                const scorerImg = goal.scorer_image ?? '';
                const assistName = escapeHtml(goal.assist_name ?? '');
                const scoreLine = escapeHtml(goal.scoreline ?? '');

                const teamName = escapeHtml(e.team_name ?? '');

                let title = 'حدث';
                let body = '';
                let media = '';

                if (kind === 'goal') {
                    title = 'هدف';
                    body = `
                        <div class="fw-bold">${scorerName}</div>
                        <div class="text-muted small">
                            ${scoreLine || 'تحديث النتيجة'}
                            ${assistName ? ` - أسيست: ${assistName}` : ''}
                        </div>
                    `;
                    if (scorerImg) {
                        media = `<img src="${escapeHtml(scorerImg)}" alt="" style="width: 44px; height: 44px; border-radius: 14px; object-fit: cover;">`;
                    }
                } else if (kind === 'sub') {
                    title = 'تبديل لاعب';
                    body = `
                        <div class="fw-bold">تبديل لاعب</div>
                        <div class="text-muted small">${outName} - ${inName}</div>
                    `;
                    media = `
                        <div class="d-flex gap-2">
                            ${outImg ? `<img src="${escapeHtml(outImg)}" alt="" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">` : ''}
                            ${inImg ? `<img src="${escapeHtml(inImg)}" alt="" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">` : ''}
                        </div>
                    `;
                } else if (isYellowCard) {
                    title = 'بطاقة صفراء';
                    body = `
                        <div class="fw-bold">${playerName || '-'}</div>
                        <div class="text-muted small">تم إنذاره ببطاقة صفراء</div>
                    `;
                    if (playerImage) {
                        media = `<img src="${escapeHtml(playerImage)}" alt="" style="width: 44px; height: 44px; border-radius: 14px; object-fit: cover;">`;
                    }
                } else if (isRedCard) {
                    title = 'بطاقة حمراء';
                    body = `
                        <div class="fw-bold">${playerName || '-'}</div>
                        <div class="text-muted small">تم طرده ببطاقة حمراء</div>
                    `;
                    if (playerImage) {
                        media = `<img src="${escapeHtml(playerImage)}" alt="" style="width: 44px; height: 44px; border-radius: 14px; object-fit: cover;">`;
                    }
                } else {
                    title = 'حدث';
                    body = `
                        <div class="fw-bold">${playerName || teamName || 'حدث'}</div>
                        <div class="text-muted small">${escapeHtml(e.type_name ?? e.event_name ?? e.name ?? 'تفاصيل المباراة')}</div>
                    `;
                }

                return `
                    <div class="info-item">
                        <div class="info-icon">${minute}</div>
                        <div class="w-100">
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-2">
                                <div class="fw-bold">${teamName || 'المباراة'}</div>
                                <div class="section-kicker">${title}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div>${body}</div>
                            </div>
                            ${media}
                        </div>
                    </div>
                `;
            }).join('');

            wrap.innerHTML = html;
        }

        function renderStats(fx) {
            const wrap = $('.js-stats-wrapper');
            if (!wrap) return;

            const stats = Array.isArray(fx.statistics_rows) ? fx.statistics_rows : [];

            if (!stats.length) {
                wrap.innerHTML = '<div class="soft-empty">لا توجد إحصائيات</div>';
                return;
            }

            const html = `
                <div class="d-flex flex-column gap-3 gx-stats-list">
                    ${stats.map((row) => {
                        const homeValue = Number(row.home ?? 0);
                        const awayValue = Number(row.away ?? 0);
                        const total = homeValue + awayValue;
                        const homePercent = total > 0 ? Math.round((homeValue / total) * 100) : 0;
                        const awayPercent = total > 0 ? Math.round((awayValue / total) * 100) : 0;

                        return `
                            <div class="info-item d-block">
                                <div class="gx-label text-center mb-3 fw-bold">${escapeHtml(row.label ?? '-')}</div>
                                <div class="row align-items-center g-3">
                                    <div class="col-6">
                                        <div class="fw-bold text-start gx-ns-perc-val">${escapeHtml(row.home ?? 0)}</div>
                                        <div class="gx-ns-bar" dir="ltr">
                                            <div class="gx-ns-bar-home2" style="width: ${homePercent}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold text-end gx-ns-perc-val">${escapeHtml(row.away ?? 0)}</div>
                                        <div class="gx-ns-bar">
                                            <div class="gx-ns-bar-away2" style="width: ${awayPercent}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;

            wrap.innerHTML = html;
        }

        function playerName(player) {
            return player?.player?.display_name || player?.player?.name || player?.player_name || '-';
        }

        function playerNum(player) {
            return player?.jersey_number ?? '';
        }

        function playerImg(player) {
            return player?.player?.image_path || '';
        }

        function renderLineups(fx) {
            const wrap = $('.js-lineups-wrapper');
            if (!wrap) return;

            const lineups = Array.isArray(fx.lineups) ? fx.lineups : [];
            if (!lineups.length) {
                wrap.innerHTML = '<div class="soft-empty">لا توجد تشكيلات</div>';
                return;
            }

            const homeId = fx.home?.id ?? null;
            const awayId = fx.away?.id ?? null;

            const homeAll = lineups.filter((p) => Number(p.team_id) === Number(homeId));
            const awayAll = lineups.filter((p) => Number(p.team_id) === Number(awayId));

            let homeXI = homeAll.filter((p) => Number(p.type_id) === 11);
            let awayXI = awayAll.filter((p) => Number(p.type_id) === 11);

            if (!homeXI.length) homeXI = homeAll.slice(0, 11);
            if (!awayXI.length) awayXI = awayAll.slice(0, 11);

            const homeBench = homeAll.filter((p) => Number(p.type_id) !== 11);
            const awayBench = awayAll.filter((p) => Number(p.type_id) !== 11);

            const renderRoster = (players) => players.map((p) => `
                <div class="lineup-roster-card d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        ${playerImg(p)
                            ? `<img src="${escapeHtml(playerImg(p))}" alt="" style="width: 42px; height: 42px; border-radius: 12px; object-fit: cover;">`
                            : `<div class="info-icon" style="width: 42px; height: 42px; border-radius: 12px;">${escapeHtml(String(playerName(p)).charAt(0) || '?')}</div>`}
                        <div>
                            <div class="fw-bold">${escapeHtml(playerName(p))}</div>
                            <div class="text-muted small">${escapeHtml(p?.position?.name ?? p?.position?.code ?? '-')}</div>
                        </div>
                    </div>
                    <span class="section-kicker">#${escapeHtml(playerNum(p) || '-')}</span>
                </div>
            `).join('');

            const renderBench = (players) => {
                if (!players.length) {
                    return '<div class="soft-empty">لا يوجد بدلاء</div>';
                }

                return players.map((p) => `
                    <div class="bench-row">
                        <span>${escapeHtml(playerName(p))}</span>
                        <span class="text-muted small">#${escapeHtml(playerNum(p) || '-')}</span>
                    </div>
                `).join('');
            };

            wrap.innerHTML = `
                <div class="row g-4">
                    <div class="col-12">
                        <div class="info-item d-block">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="section-title-row mb-3">
                                        <h5>${escapeHtml(fx.home?.name ?? 'المضيف')}</h5>
                                        <span class="section-kicker">التشكيل الأساسي</span>
                                    </div>
                                    <div class="d-flex flex-column gap-2">${renderRoster(homeXI)}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="section-title-row mb-3">
                                        <h5>${escapeHtml(fx.away?.name ?? 'الضيف')}</h5>
                                        <span class="section-kicker">التشكيل الأساسي</span>
                                    </div>
                                    <div class="d-flex flex-column gap-2">${renderRoster(awayXI)}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item d-block">
                            <div class="section-title-row">
                                <h5>${escapeHtml(fx.home?.name ?? 'المضيف')} - البدلاء</h5>
                                <span class="section-kicker">${homeBench.length}</span>
                            </div>
                            ${renderBench(homeBench)}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item d-block">
                            <div class="section-title-row">
                                <h5>${escapeHtml(fx.away?.name ?? 'الضيف')} - البدلاء</h5>
                                <span class="section-kicker">${awayBench.length}</span>
                            </div>
                            ${renderBench(awayBench)}
                        </div>
                    </div>
                </div>
            `;
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

                renderEvents(fx);
                renderStats(fx);
                renderLineups(fx);

                const status = String(fx.status ?? '').toUpperCase();
                const stateCode = String(fx.state_code ?? '').toUpperCase();
                const stillLive = status === 'LIVE' || ['LIVE', 'INPLAY_1ST', 'INPLAY_2ND', 'HT'].includes(stateCode);

                if (!stillLive && timer) {
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
    })();
</script>
