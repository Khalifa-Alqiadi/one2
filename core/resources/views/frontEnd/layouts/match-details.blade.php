<script>
    (function() {
        const URL = "{{ route('fixture.live.details', ['id' => $fixtureId]) }}";
        const intervalMs = 1000; // كل 8 ثواني (يمكنك تعديلها حسب الحاجة)
        let timer = null;
        let inflight = false;

        const $ = (q) => document.querySelector(q);
        const show = (el, yes) => {
            if (el) el.style.display = yes ? 'inline-block' : 'none';
        };
        const showBlock = (el, yes) => {
            if (el) el.style.display = yes ? 'block' : 'none';
        };
        const setText = (sel, val) => {
            const el = $(sel);
            if (el) el.textContent = (val ?? '');
        };

        function setHeader(fx) {
            const status = fx.status ?? 'NS';
            const state_code = fx.state_code ?? 'NS';

            showBlock($('.js-scorebox'), status !== 'NS');
            showBlock($('.js-kickoffbox'), status === 'NS');

            if (status === 'NS') {
                setText('.js-kickoff', formatKickoff(fx.starting_at));
            }

            setText('.js-home', fx.score?.home ?? '-');
            setText('.js-away', fx.score?.away ?? '-');

            show($('.js-status'), status === 'LIVE');
            show($('.js-ns'), status === 'NS');
            show($('.js-ht'), state_code === 'HT');
            show($('.js-ft'), state_code === 'FT');

            setText('.js-minute', status === 'LIVE' && fx.minute ? (fx.minute + "'") : '');

            // NS probability block
            showBlock($('#js-ns-prob'), status === 'NS');
        }

        function formatKickoff(startingAt) {
            if (!startingAt) return '';

            // 1) طَبِّع التاريخ: لو جاك "YYYY-MM-DD HH:mm:ss" حوله لـ ISO واعتبره UTC
            // (إذا كان عندك بالفعل ISO فيه Z أو +03:00 بيشتغل كما هو)
            let iso = startingAt;
            if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/.test(startingAt)) {
                iso = startingAt.replace(' ', 'T') + 'Z'; // اعتبره UTC
            }

            const d = new Date(iso);

            const fmt = new Intl.DateTimeFormat('ar-YE-u-nu-latn', {
                timeZone: 'Asia/Aden',
                weekday: 'long',
                day: 'numeric',
                month: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });

            const parts = fmt.formatToParts(d);
            const get = (t) => parts.find(p => p.type === t)?.value || '';

            // 2) ركّبها بالشكل اللي تريده حرفيًا
            return `${get('weekday')}، ${get('day')}‏/${get('month')}، ${get('hour')}:${get('minute')} ${get('dayPeriod')}`;
        }

        // الاستخدام


        function updateProbabilities(p) {
            if (!p) return;
            const home = Number(p.home ?? 0);
            const draw = Number(p.draw ?? 0);
            const away = Number(p.away ?? 0);

            setText('.js-p-home', home + '%');
            setText('.js-p-draw', draw + '%');
            setText('.js-p-away', away + '%');

            const bh = $('.js-bar-home'),
                bd = $('.js-bar-draw'),
                ba = $('.js-bar-away');
            if (bh) bh.style.width = home + '%';
            if (bd) bd.style.width = draw + '%';
            if (ba) ba.style.width = away + '%';
        }

        // نفس renderers اللي عندك (Events/Stats/Lineups) — استخدمهم كما هم
        function renderEvents(fx) {
            /* ... خلي كودك الحالي ... */
        }

        function renderStats(fx) {
            /* ... خلي كودك الحالي ... */
        }

        function renderLineups(fx) {
            /* ... خلي كودك الحالي ... */
        }

        async function tick() {
            if (inflight) return;
            inflight = true;

            try {
                const res = await fetch(URL, {
                    cache: 'no-store'
                });

                if (!res.ok) return;
                const json = await res.json();
                if (!json?.ok || !json.data) return;

                const fx = json.data;

                setHeader(fx);
                if (fx.probabilities) updateProbabilities(fx.probabilities);

                renderEvents(fx);
                renderStats(fx);
                renderLineups(fx);

                if ((fx.status || '') === 'FT') {
                    clearInterval(timer);
                    timer = null;
                }

            } finally {
                inflight = false;
            }
        }

        tick();
        timer = setInterval(tick, intervalMs);
    })();
    function setHeader(fx) {
  const status = fx.status ?? 'NS';
  const state_code = fx.state_code ?? 'NS';

  // Score vs kickoff
  showBlock(document.querySelector('.js-scorebox'), status !== 'NS');
  showBlock(document.querySelector('.js-kickoffbox'), status === 'NS');

  // kickoff time
  if (status === 'NS') {
    setText('.js-kickoff', fx.starting_at ? new Date(fx.starting_at).toLocaleString() : '');
  }

  // score
  setText('.js-home', fx.score?.home ?? '-');
  setText('.js-away', fx.score?.away ?? '-');

  // badges
  show(document.querySelector('.js-status'), status === 'LIVE');
  show(document.querySelector('.js-ht'), state_code === 'HT');
  show(document.querySelector('.js-ft'), status === 'FT' && state_code === 'FT');
  show(document.querySelector('.js-ns'), status === 'NS');

  // minute: يظهر في LIVE فقط
  setText('.js-minute', (status === 'LIVE' && fx.minute) ? (fx.minute + "'") : '');

  // NS probability block
  showBlock(document.getElementById('js-ns-prob'), status === 'NS');
}
</script>
