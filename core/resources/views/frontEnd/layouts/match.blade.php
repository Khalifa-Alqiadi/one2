<script>
    (function() {
        const URL = "{{ route('fixtures.live.proxy') }}";
        let inflight = false;

        function liveEls() {
            return Array.from(
                document.querySelectorAll('[id^="fixture-"][data-live="1"]'),
            );
        }

        function liveIds() {
            return liveEls()
                .map((el) => parseInt(el.id.replace("fixture-", ""), 10))
                .filter((n) => !Number.isNaN(n));
        }

        function fmtMinute(m, stateCode = "") {
            if (m === null || m === undefined) return "";
            m = parseInt(m, 10);
            if (Number.isNaN(m)) return "";
            if (m > 90) return `90+${m - 90}'`;
            if (m > 45 && stateCode === "INPLAY_1ST_FT") return `45+${m - 45}'`;
            return `${m}'`;
        }

        function setText(el, sel, val, allowEmpty = false) {
            const node = el.querySelector(sel);
            if (!node) return;
            if (val === null || val === undefined) {
                if (allowEmpty) node.textContent = "";
                return;
            }
            // لا تكتب "-" فوق قيمة موجودة إلا إذا allowEmpty
            if (!allowEmpty && (val === "-" || val === "")) return;
            node.textContent = val;
        }

        async function tick() {
            if (inflight) return;
            const ids = liveIds();
            if (!ids.length) return;

            inflight = true;
            try {
                const res = await fetch(
                    URL + "?ids=" + encodeURIComponent(ids.join(",")), {
                        cache: "no-store",
                    },
                );
                if (!res.ok) return;

                const json = await res.json();
                if (!json || !json.ok) return;

                (json.fixtures || []).forEach((fx) => {
                    console.log(fx);
                    const el = document.getElementById("fixture-" + fx.id);
                    if (!el) return;

                    // ✅ خزّن آخر قيم معروفة داخل dataset (عشان ما تنمسح)
                    const lastHome = el.dataset.lastHome ?? "";
                    const lastAway = el.dataset.lastAway ?? "";
                    const lastMin = el.dataset.lastMin ?? "";

                    // scores: حدّث فقط إذا في قيمة رقمية/واضحة
                    if (fx.home_score !== null && fx.home_score !== undefined) {
                        setText(el, ".js-home-score", fx.home_score);
                        el.dataset.lastHome = fx.home_score;
                    } else if (lastHome !== "") {
                        setText(el, ".js-home-score", lastHome);
                    }

                    if (fx.away_score !== null && fx.away_score !== undefined) {
                        setText(el, ".js-away-score", fx.away_score);
                        el.dataset.lastAway = fx.away_score;
                    } else if (lastAway !== "") {
                        setText(el, ".js-away-score", lastAway);
                    }

                    // ✅ إذا انتهت المباراة: فقط هنا نطفي live ونمسح الدقيقة
                    if (fx.is_finished && fx.state_code == "FT") {

                        el.dataset.live = "0";
                        setText(
                            el,
                            ".js-live-badge",
                            "{{ $locale == 'ar' ? 'النهائية' : 'FT' }}",
                            true,
                        );
                        setText(el, ".js-minute", "", true);
                        el.dataset.lastMin = "";
                        return;
                    }

                    // منتصف المباراة


                    // ✅ ما دام مش منتهية: لا تطفي live حتى لو fx.is_live رجع false لحظة
                    // فقط حدّث النص والدقيقة إذا متاحة
                    el.dataset.live = "1";
                    if(fx.state_code == "NS"){
                        setText(
                            el,
                            ".js-live-badge",
                            "{{ $locale == 'ar' ? __('frontend.coming_soon') : 'LIVE' }}",
                            false,
                        );
                    }else{
                        if (fx.state_code === 'HT') {
                            // el.dataset.live = "1";
                            // lb.textContent = "منتصف المباراة";
                            // mn.textContent = "";
                            // return;
                            setText(
                                el,
                                ".js-live-badge",
                                "{{ $locale == 'ar' ? 'منتصف المباراة' : 'LIVE' }}",
                                false,
                            );

                        }else{
                            setText(
                                el,
                                ".js-live-badge",
                                "{{ $locale == 'ar' ? 'مباشر' : 'LIVE' }}",
                                true,
                            );
                        }

                    }

                    if (fx.minute !== null && fx.minute !== undefined && fx.minute !== "") {
                        const m = fmtMinute(fx.minute, fx.state_code);
                        if (m) {
                            setText(el, ".js-minute", m, true);
                            el.dataset.lastMin = m;
                        }
                    } else if (lastMin !== "") {
                        // ✅ احتفظ بآخر دقيقة بدل تمسح
                        setText(el, ".js-minute", lastMin, true);
                    } else {
                        setText(el, ".js-minute", "—", true);
                    }

                    if (fx.state_code === 'HT') {
                        setText(el, ".js-minute", "", true);
                        el.dataset.lastMin = "";
                    }
                });
            } catch (e) {
                // لا تسوي شي
            } finally {
                inflight = false;
            }
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
