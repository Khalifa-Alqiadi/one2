<div class="no-scroll col-md-12">

    <div class="topbar">
        <div class="title">Shorts</div>
        <div class="hint">↑ ↓ للتنقّل • Space تشغيل/إيقاف • M كتم</div>
    </div>

    <div class="swiper shortsSwiper">
        <div class="swiper-wrapper">
            @foreach ($Topics as $Topic)
                @php
                    $url = Helper::getThumbnail($Topic->video_file);
                    $id = $url['id'];
                    if (!$id) {
                        continue;
                    }
                    $t = $Topic->$title_var ?: $Topic->$title_var2;
                @endphp

                <div class="swiper-slide" data-title="{{ e($t ?? '') }}">
                    <div class="player-wrap">
                        <div class="yt" data-video-id="{{ $id }}"></div>

                        <div class="actions d-none">
                            <div class="action" title="Like">👍</div>
                            <div class="action" title="Comment">💬</div>
                            <div class="action" title="Share">↗️</div>
                            <div class="action" title="More">⋯</div>
                        </div>

                        <div class="meta">
                            <div class="title">{{ $t }}</div>
                        </div>
                        <div class="touch-layer"></div>
                        <div class="overlay">
                            <button class="ov-btn ov-play" type="button" aria-label="Play/Pause">
                                <span class="ov-icon">⏸</span>
                            </button>

                            <button class="ov-btn ov-sound" type="button" aria-label="Mute/Unmute">
                                <span class="ov-icon">🔇</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="toast" id="toast"></div>
</div>

@push('after-scripts')
    <script src="https://www.youtube.com/iframe_api"></script>

    <script>
        let swiper;
        const players = []; // index => YT.Player
        const playerState = {
            muted: true
        };
        let audioUnlocked = false; // يصير true بعد أول تفاعل
        let apiReady = false;

        const toast = document.getElementById('toast');

        function showToast(msg) {
            toast.textContent = msg;
            toast.classList.add('show');
            clearTimeout(showToast._t);
            showToast._t = setTimeout(() => toast.classList.remove('show'), 1300);
        }

        // أي تفاعل واحد "يفتح" الصوت
        function unlockAudioOnce() {
            audioUnlocked = true;
            // إذا في فيديو شغال الآن، نفك كتمه فورًا
            const i = swiper?.activeIndex ?? 0;
            const p = players[i];
            if (p) {
                try {
                    p.unMute();
                    p.setVolume(100);
                    playerState.muted = false;
                } catch (e) {}
            }
        }

        // اعتبر هذه تفاعلات
        window.addEventListener('pointerdown', unlockAudioOnce, {
            once: true
        });
        window.addEventListener('touchstart', unlockAudioOnce, {
            once: true
        });
        window.addEventListener('wheel', unlockAudioOnce, {
            once: true
        });

        // على الكيبورد: الأسهم/المسافة/م الخ…
        document.addEventListener('keydown', (e) => {
            if (['ArrowUp', 'ArrowDown', ' ', 'Spacebar', 'm', 'M'].includes(e.key)) {
                unlockAudioOnce();
            }
        }, {
            once: true
        });

        // YouTube API Ready
        function onYouTubeIframeAPIReady() {
            apiReady = true;

            swiper = new Swiper('.shortsSwiper', {
                direction: 'vertical',
                slidesPerView: 1,
                mousewheel: true,
                spaceBetween: 20,
                resistanceRatio: 0,
                // مهم للجوال
                simulateTouch: true,
                touchStartPreventDefault: false,
                passiveListeners: false,

                keyboard: {
                    enabled: true,
                    onlyInViewport: true,
                },

                on: {
                    slideChange: () => {
                        playOnlyActive();      // اللي عندك: يشغل النشط ويوقف الباقي
                        applySoundToActive();  // الجديد: يطبق حالة الصوت ويظهر overlay
                    }
                }
            });

            function togglePlay(activeIndex) {
                const p = players[activeIndex];
                if (!p) return;

                try {
                    const st = p.getPlayerState(); // 1 playing, 2 paused
                    if (st === 1) p.pauseVideo();
                    else p.playVideo();
                } catch (e) {}
            }

            // Tap على الفيديو (على طبقة اللمس)
            document.querySelectorAll('.shortsSwiper .swiper-slide .touch-layer').forEach(layer => {
                layer.addEventListener('click', () => {
                    if (!swiper) return;
                    togglePlay(swiper.activeIndex);
                });
            });


            // أنشئ Players لكل سلايد
            const els = document.querySelectorAll('.shortsSwiper .swiper-slide .yt');
            els.forEach((el, i) => {
                const videoId = el.dataset.videoId;

                players[i] = new YT.Player(el, {
                    videoId,
                    playerVars: {
                        autoplay: 0, // نشغله نحن
                        mute: 1, // مهم لـ autoplay
                        playsinline: 1,
                        controls: 0,
                        rel: 0,
                        modestbranding: 1,
                        enablejsapi: 1
                    },
                    events: {
                        onReady: () => {
                            // شغّل أول فيديو تلقائيًا (Muted)
                            if (i === 0) {
                                safePlay(i, true);
                            }
                        }
                    }
                });
            });

            // اختصارات إضافية (Space / M)
            document.addEventListener('keydown', (e) => {
                if (!swiper) return;

                // امنع سلوك الصفحة الافتراضي
                if (e.key === ' ' || e.key === 'Spacebar') e.preventDefault();
                if (e.key === 'ArrowUp' || e.key === 'ArrowDown') e.preventDefault();

                const active = swiper.activeIndex;
                const p = players[active];
                if (!p) return;

                // Space: play/pause
                if (e.key === ' ' || e.key === 'Spacebar') {
                    try {
                        const st = p.getPlayerState(); // 1 playing, 2 paused
                        if (st === 1) {
                            p.pauseVideo();
                            showToast('إيقاف');
                        } else {
                            safePlay(active);
                            showToast('تشغيل');
                        }
                    } catch (e) {}
                }

                // M: mute/unmute
                if (e.key === 'm' || e.key === 'M') {
                    try {
                        if (playerState.muted) {
                            // لازم يكون audioUnlocked true على بعض المتصفحات ليطلع الصوت
                            if (!audioUnlocked) unlockAudioOnce();
                            p.unMute();
                            p.setVolume(100);
                            playerState.muted = false;
                            showToast('الصوت شغال');
                        } else {
                            p.mute();
                            playerState.muted = true;
                            showToast('تم الكتم');
                        }
                    } catch (e) {}
                }
            });
        }

        function playOnlyActive() {
            const active = swiper.activeIndex;

            players.forEach((p, i) => {
                if (!p) return;
                try {
                    if (i === active) safePlay(i);
                    else p.pauseVideo(); // أو stopVideo() لو تبي يرجع من البداية
                } catch (e) {}
            });
        }

        function safePlay(i, forceMuted = false) {
            const p = players[i];
            if (!p) return;

            try {
                if (forceMuted) {
                    p.mute();
                    playerState.muted = true;
                } else {
                    // بعد أول تفاعل: شغّل بالصوت
                    if (audioUnlocked) {
                        p.unMute();
                        p.setVolume(100);
                        playerState.muted = false;
                    } else {
                        p.mute();
                        playerState.muted = true;
                    }
                }
                p.playVideo();
            } catch (e) {}
        }

        // حالة عامة للصوت (مثل يوتيوب: قرارك يطبق على كل الفيديوهات)
        let globalMuted = true;

        // أي تفاعل يفتح الصوت (سياسات المتصفح)
        function unlockAudio() {
            audioUnlocked = true;
        }
        window.addEventListener('pointerdown', unlockAudio, {
            once: true
        });
        window.addEventListener('touchstart', unlockAudio, {
            once: true
        });
        window.addEventListener('keydown', unlockAudio, {
            once: true
        });

        function getActivePlayer() {
            const i = swiper?.activeIndex ?? 0;
            return {
                p: players[i],
                i
            };
        }

        function showOverlay(slideEl) {
            const ov = slideEl.querySelector('.overlay');
            if (!ov) return;
            ov.classList.add('show');
            clearTimeout(ov._t);
            ov._t = setTimeout(() => ov.classList.remove('show'), 1800);
        }

        function setPlayIcon(slideEl, isPlaying) {
            const icon = slideEl.querySelector('.ov-play .ov-icon');
            if (!icon) return;
            icon.textContent = isPlaying ? '⏸' : '▶️';
        }

        function setSoundIcon(slideEl, muted) {
            const icon = slideEl.querySelector('.ov-sound .ov-icon');
            if (!icon) return;
            icon.textContent = muted ? '🔇' : '🔊';
        }

        // Tap على الفيديو = Play/Pause
        document.querySelectorAll('.shortsSwiper .swiper-slide').forEach((slideEl, idx) => {
            const layer = slideEl.querySelector('.touch-layer');
            const playBtn = slideEl.querySelector('.ov-play');
            const soundBtn = slideEl.querySelector('.ov-sound');

            if (!layer) return;

            // تحديث أيقونات أوليًا
            setSoundIcon(slideEl, globalMuted);
            setPlayIcon(slideEl, true);

            function togglePlay() {
                if (!swiper) return;
                const active = swiper.activeIndex;
                const p = players[active];
                if (!p) return;

                try {
                    const st = p.getPlayerState(); // 1 playing, 2 paused
                    if (st === 1) {
                        p.pauseVideo();
                        setPlayIcon(slideEl, false);
                    } else {
                        p.playVideo();
                        setPlayIcon(slideEl, true);
                    }
                } catch (e) {}
                showOverlay(slideEl);
            }

            function toggleSound() {
                if (!swiper) return;
                const active = swiper.activeIndex;
                const p = players[active];
                if (!p) return;

                // لازم يكون فيه تفاعل (على الأقل مرة) عشان unmute يكون مقبول
                if (!audioUnlocked) audioUnlocked = true;

                globalMuted = !globalMuted;

                try {
                    if (globalMuted) {
                        p.mute();
                    } else {
                        p.unMute();
                        p.setVolume(100);
                    }
                } catch (e) {}

                // حدث الأيقونة للسلايد الحالي
                setSoundIcon(slideEl, globalMuted);
                showOverlay(slideEl);
            }

            // لمس على الفيديو (الطبقة) -> play/pause
            layer.addEventListener('click', () => {
                // نفذ فقط إذا السلايد هو النشط (حتى لو ضغطت على سلايد مش نشط بالخطأ)
                if (swiper && swiper.activeIndex !== idx) return;
                togglePlay();
            });

            // أزرار الأوفرلاي
            playBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                if (swiper && swiper.activeIndex !== idx) return;
                togglePlay();
            });

            soundBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                if (swiper && swiper.activeIndex !== idx) return;
                toggleSound();
            });
        });

        // عند تغيير السلايد: طبق حالة الصوت على الفيديو الجديد + أظهر overlay لحظة
        function applySoundToActive() {
            const activeSlide = document.querySelectorAll('.shortsSwiper .swiper-slide')[swiper.activeIndex];
            const p = players[swiper.activeIndex];
            if (!activeSlide || !p) return;

            try {
                if (globalMuted || !audioUnlocked) {
                    p.mute();
                    globalMuted = true;
                } else {
                    p.unMute();
                    p.setVolume(100);
                }
            } catch (e) {}

            setSoundIcon(activeSlide, globalMuted);
            showOverlay(activeSlide);
        }

        // نادِ applySoundToActive داخل slideChange عندك:
        /// on: { slideChange: () => { playOnlyActive(); applySoundToActive(); } }
    </script>
@endpush
@push('after-styles')
    <style>
        :root {
            --bg: #0f0f0f;
            --text: #fff;
            --muted: rgba(255, 255, 255, .75);
            --radius: 18px;
            --maxW: 360px;
            --shadow: 0 10px 30px rgba(0, 0, 0, .45);
        }

        .shortsSwiper {
            height: 100vh;
            width: 100%;
        }

        .swiper-slide {
            height: 100vh;
            display: grid;
            place-items: center;
            padding: 0 12px 18px;
        }

        /* Simple topbar */
        .topbar {
            position: absolute;
            inset: 0 0 auto 0;
            height: 56px;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 14px;
            pointer-events: none;
        }

        .topbar * {
            pointer-events: auto
        }

        .topbar .title {
            font-weight: 900;
            letter-spacing: .2px;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            opacity: .85;
        }

        /* Player card */
        .player-wrap {
            width: min(var(--maxW), 100%);
            aspect-ratio: 9 / 16;
            border-radius: var(--radius);
            overflow: hidden;
            background: #000;
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: var(--shadow);
            position: relative;
        }

        .player-wrap iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Right actions (UI only) */
        .actions {
            position: absolute;
            left: 10px;
            bottom: 90px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 12px;
            user-select: none;
        }

        .action {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(0, 0, 0, .35);
            color: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            backdrop-filter: blur(6px);
        }

        /* Bottom meta */
        .meta {
            position: absolute;
            inset: auto 12px 12px 12px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 6px;
            pointer-events: none;
        }

        .meta .title {
            font-size: 14px;
            font-weight: 900;
            line-height: 1.35;
            max-width: 100%;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-shadow: 0 2px 10px rgba(0, 0, 0, .55);
        }

        .meta .sub {
            font-size: 12px;
            color: var(--muted);
            text-shadow: 0 2px 10px rgba(0, 0, 0, .55);
        }

        /* Small status toast */
        .toast {
            position: fixed;
            bottom: 18px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, .75);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #fff;
            padding: 10px 12px;
            border-radius: 999px;
            font-size: 13px;
            opacity: 0;
            pointer-events: none;
            transition: .2s ease;
            z-index: 999;
        }

        .toast.show {
            opacity: 1;
        }

        /* Helpful: prevent accidental page scroll on desktop */
        .no-scroll {
            overscroll-behavior: none;
            position: relative;
        }

        #header .container {
            margin: 0 !important;
            padding-top: 0;
            border: 0;
        }

        .breadcrumbs {
            display: none !important;
        }

        .swiper-backface-hidden .swiper-slide {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
        .overlay{
            display: none;
        }

        @media (max-width: 768px) {
            #header {
                display: none;
            }

            .container {
                max-width: 100%;
                width: 100%;
                padding: 0 !important;
                overflow: hidden;
            }

            .topbar {
                display: none
            }

            #content {
                padding: 0;
            }

            .shortsSwiper {
                height: 100dvh;
                touch-action: pan-y;
            }

            .swiper-slide {
                padding: 0 !important;
                margin: 0 !important;
                height: 100dvh;
            }

            .player-wrap {
                width: 100%;
                border-radius: 0 !important;
                height: 100%;
                position: relative;
            }

            /* خلّي iframe ما يمسك اللمس */
            .player-wrap iframe {
                pointer-events: none !important;
            }

            /* طبقة اللمس فوق الفيديو تستقبل السحب */
            .touch-layer {
                position: absolute;
                inset: 0;
                z-index: 50;
                background: transparent;
                touch-action: pan-y;
            }

            .overlay {
                position: absolute;
                inset: 0;
                display: block;
                z-index: 60;
                pointer-events: none;
                /* نخليها ما تعطل السحب */
                opacity: 0;
                transition: opacity .18s ease;
            }

            /* نخلي الأزرار نفسها قابلة للضغط */
            .overlay .ov-btn {
                pointer-events: auto;
            }

            /* إظهار الأوفرلاي */
            .overlay.show {
                opacity: 1;
            }

            /* زر play في الوسط */
            .ov-play {
                position: absolute;
                inset: 0;
                margin: auto;
                width: 74px;
                height: 74px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, .18);
                background: rgba(0, 0, 0, .35);
                color: #fff;
                display: grid;
                place-items: center;
                cursor: pointer;
                backdrop-filter: blur(6px);
            }

            /* زر الصوت في الأعلى */
            .ov-sound {
                position: absolute;
                top: 14px;
                right: 14px;
                width: 44px;
                height: 44px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, .18);
                background: rgba(0, 0, 0, .35);
                color: #fff;
                display: grid;
                place-items: center;
                cursor: pointer;
                backdrop-filter: blur(6px);
            }

            /* RTL */
            [dir="rtl"] .ov-sound {
                right: auto;
                left: 14px;
            }

            .ov-icon {
                font-size: 20px;
                line-height: 1;
            }
        }
    </style>
@endpush
