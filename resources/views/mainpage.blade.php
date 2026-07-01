<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Kajian</title>
    <meta name="description" content="Jadwal Kajian Islam – Informasi jadwal kajian, narasumber, dan tempat pelaksanaan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           BASE RESET & VARIABLES
        ============================================================ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --black: #000000;
            --dark: #0a0a0a;
            --dark-2: #111111;
            --gold: #c9a227;
            --gold-light: #e8c04a;
            --gold-dim: rgba(201, 162, 39, 0.18);
            --green: #16a34a;
            --green-light: #22c55e;
            --red: #dc2626;
            --text-primary: #f5f5f5;
            --text-dim: rgba(245, 245, 245, 0.55);
            --ticker-h: 64px;
            --banner-h: 90px;
            --bar-h: 38px;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            background: var(--black);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        /* ============================================================
           FULL-SCREEN LAYOUT
           Top: main area (black)
           Bottom: ticker strip
        ============================================================ */
        .screen {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100vw;
            position: relative;
        }

        /* ============================================================
           MAIN CONTENT AREA (top black area)
        ============================================================ */
        .main-area {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Subtle radial gradient centre glow */
        .main-area::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 50% at 50% 50%,
                    rgba(201, 162, 39, 0.07) 0%,
                    transparent 70%);
            pointer-events: none;
        }

        /* Islamic geometric watermark */
        .watermark {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            opacity: 0.035;
        }

        .watermark svg {
            width: min(60vw, 460px);
            height: auto;
        }

        /* Brand / Logo area */
        .brand-block {
            position: relative;
            z-index: 2;
            text-align: center;
            animation: fadeIn 1.2s ease both;
        }

        .brand-ornament {
            width: 56px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 0 auto 1.2rem;
            border-radius: 2px;
        }

        .brand-title {
            font-family: 'Amiri', serif;
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 700;
            color: var(--gold-light);
            letter-spacing: 0.04em;
            line-height: 1.15;
            text-shadow: 0 0 40px rgba(201, 162, 39, 0.35);
        }

        .brand-subtitle {
            margin-top: 0.5rem;
            font-size: clamp(0.75rem, 1.4vw, 1rem);
            font-weight: 400;
            color: var(--text-dim);
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .brand-ornament.bottom {
            margin: 1.2rem auto 0;
        }

        /* Live clock */
        .live-clock {
            position: absolute;
            top: 20px;
            right: 28px;
            z-index: 10;
            text-align: right;
            animation: fadeIn 0.8s ease both;
        }

        .clock-time {
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--gold-light);
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .clock-date {
            font-size: 0.7rem;
            color: var(--text-dim);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* Channel badge top-left */
        .channel-badge {
            position: absolute;
            top: 20px;
            left: 28px;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.8s ease both;
        }

        .live-dot {
            width: 10px;
            height: 10px;
            background: var(--red);
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.6);
            animation: livePulse 1.4s ease infinite;
            flex-shrink: 0;
        }

        @keyframes livePulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.6);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(220, 38, 38, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
            }
        }

        .channel-name {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        /* ============================================================
           NEXT KAJIAN SPOTLIGHT (centre card)
        ============================================================ */
        .spotlight {
            position: relative;
            z-index: 2;
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            animation: fadeIn 1.4s ease 0.3s both;
        }

        .spotlight-label {
            font-size: 0.65rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--text-dim);
            font-weight: 600;
        }

        .spotlight-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(201, 162, 39, 0.2);
            border-radius: 16px;
            padding: 1.5rem 2.5rem;
            text-align: center;
            max-width: min(680px, 90vw);
            backdrop-filter: blur(8px);
            box-shadow: 0 0 40px rgba(201, 162, 39, 0.06), inset 0 1px 0 rgba(201, 162, 39, 0.1);
        }

        .spotlight-judul {
            font-family: 'Amiri', serif;
            font-size: clamp(1.1rem, 2.5vw, 1.9rem);
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        .spotlight-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.2rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--text-dim);
        }

        .meta-item svg {
            width: 14px;
            height: 14px;
            color: var(--gold);
            flex-shrink: 0;
        }

        .meta-item strong {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* ============================================================
           BOTTOM TICKER AREA
        ============================================================ */
        .ticker-area {
            flex-shrink: 0;
            position: relative;
        }

        /* Info bar — slim bar above the main ticker */
        .info-bar {
            height: var(--bar-h);
            background: var(--gold);
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .info-bar-label {
            flex-shrink: 0;
            background: #8a6b0a;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 18px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            color: #fff;
            text-transform: uppercase;
            gap: 8px;
            white-space: nowrap;
        }

        .info-bar-label::after {
            content: '';
            position: absolute;
            left: calc(var(--label-w, 120px));
            top: 0;
            border-top: calc(var(--bar-h) / 2) solid transparent;
            border-bottom: calc(var(--bar-h) / 2) solid transparent;
            border-left: 14px solid #8a6b0a;
        }

        .info-bar-scroll {
            flex: 1;
            overflow: hidden;
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .info-bar-inner {
            display: flex;
            gap: 0;
            white-space: nowrap;
            will-change: transform;
            /* transform is set entirely by JS — no CSS animation */
        }

        .info-item {
            display: inline-flex;
            align-items: center;
            padding: 0 28px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #1a0e00;
            gap: 8px;
        }

        .info-sep {
            color: #8a6b0a;
            font-weight: 900;
            font-size: 1rem;
        }

        /* Main ticker strip */
        .ticker-strip {
            height: var(--ticker-h);
            background: #0d0d0d;
            border-top: 2px solid var(--gold);
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /* Green "KAJIAN" label on left */
        .ticker-label {
            flex-shrink: 0;
            background: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 22px;
            gap: 8px;
            position: relative;
            z-index: 2;
        }

        .ticker-label-text {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            color: #fff;
            text-transform: uppercase;
            writing-mode: vertical-lr;
            /* vertical text */
            transform: rotate(180deg);
        }

        /* Arrow chevron */
        .ticker-label::after {
            content: '';
            position: absolute;
            right: -14px;
            top: 0;
            border-top: calc(var(--ticker-h) / 2) solid transparent;
            border-bottom: calc(var(--ticker-h) / 2) solid transparent;
            border-left: 14px solid var(--green);
            z-index: 3;
        }

        /* Scroll track */
        .ticker-track {
            flex: 1;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding-left: 22px;
        }

        .ticker-inner {
            display: flex;
            align-items: center;
            white-space: nowrap;
            gap: 0;
            will-change: transform;
            /* transform is set entirely by JS — no CSS animation */
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            padding: 0 32px;
            border-right: 1px solid rgba(201, 162, 39, 0.2);
        }

        .ticker-item:last-child {
            border-right: none;
        }

        .ticker-date {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 0.06em;
            white-space: nowrap;
        }

        .ticker-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .ticker-narasumber {
            font-size: 0.78rem;
            color: var(--text-dim);
            font-weight: 400;
        }

        .ticker-narasumber::before {
            content: '— ';
        }

        .ticker-tempat {
            font-size: 0.72rem;
            background: var(--gold-dim);
            border: 1px solid rgba(201, 162, 39, 0.3);
            color: var(--gold-light);
            border-radius: 4px;
            padding: 2px 8px;
            white-space: nowrap;
        }

        /* Logo thumbnail inside ticker */
        .ticker-logo {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(201, 162, 39, 0.25);
            padding: 3px;
            flex-shrink: 0;
        }

        .ticker-logo-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: rgba(201, 162, 39, 0.1);
            border: 1px solid rgba(201, 162, 39, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.6rem;
            color: var(--gold);
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .ticker-bullet {
            font-size: 1.4rem;
            color: var(--gold);
            opacity: 0.6;
            margin: 0 8px;
        }

        /* ── Scroll animation: driven entirely by JS requestAnimationFrame ──
           No CSS keyframes needed. JS moves the element in pixel steps each
           frame and resets seamlessly when one content copy scrolls off-left. */

        /* Fade-in utility */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Empty state */
        .empty-state {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0 32px;
            font-size: 0.88rem;
            color: var(--text-dim);
            font-style: italic;
        }

        /* No-animation pause on hover for accessibility */
        @media (prefers-reduced-motion: reduce) {

            .ticker-inner,
            .info-bar-inner {
                animation-play-state: paused;
            }
        }
    </style>
</head>

<body>

    <div class="screen">

        <!-- ============================================================
         MAIN BLACK AREA
    ============================================================ -->
        <div class="main-area">

            <!-- Islamic geometric watermark -->
            <div class="watermark">
                <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="100,10 190,50 190,150 100,190 10,150 10,50" stroke="white" stroke-width="1.5" fill="none" />
                    <polygon points="100,30 170,60 170,140 100,170 30,140 30,60" stroke="white" stroke-width="1" fill="none" />
                    <polygon points="100,50 150,70 150,130 100,150 50,130 50,70" stroke="white" stroke-width="0.8" fill="none" />
                    <circle cx="100" cy="100" r="40" stroke="white" stroke-width="1" />
                    <circle cx="100" cy="100" r="25" stroke="white" stroke-width="0.7" />
                    <line x1="100" y1="10" x2="100" y2="190" stroke="white" stroke-width="0.5" />
                    <line x1="10" y1="100" x2="190" y2="100" stroke="white" stroke-width="0.5" />
                    <line x1="29" y1="29" x2="171" y2="171" stroke="white" stroke-width="0.4" />
                    <line x1="171" y1="29" x2="29" y2="171" stroke="white" stroke-width="0.4" />
                </svg>
            </div>

            <!-- Channel badge -->
            <div class="channel-badge">
                <span class="live-dot"></span>
                <span class="channel-name">Jadwal Kajian Islam</span>
            </div>

            <!-- Live clock -->
            <div class="live-clock">
                <div class="clock-time" id="clockTime">00:00:00</div>
                <div class="clock-date" id="clockDate"></div>
            </div>

            <!-- Brand -->
            <div class="brand-block">
                <div class="brand-ornament"></div>
                <div class="brand-title">Jadwal Kajian</div>
                <div class="brand-subtitle">Informasi Jadwal &amp; Kegiatan Islam</div>
                <div class="brand-ornament bottom"></div>
            </div>

            <!-- Spotlight: next upcoming kajian -->
            @if ($kajian->isNotEmpty())
            @php $next = $kajian->first(); @endphp
            <div class="spotlight">
                <div class="spotlight-label">▶ &nbsp;Kajian Berikutnya</div>
                <div class="spotlight-card">
                    <div class="spotlight-judul">{{ $next->Judul }}</div>
                    <div class="spotlight-meta">
                        <div class="meta-item">
                            <!-- Calendar icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <strong>{{ \Carbon\Carbon::parse($next->Tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
                        </div>
                        <div class="meta-item">
                            <!-- Mic icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                                <line x1="12" y1="19" x2="12" y2="23" />
                                <line x1="8" y1="23" x2="16" y2="23" />
                            </svg>
                            <span>{{ $next->Narasumber }}</span>
                        </div>
                        <div class="meta-item">
                            <!-- Location pin -->
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>{{ $next->Tempat }}</span>
                        </div>
                        @if($next->Kontak)
                        <div class="meta-item">
                            <!-- Phone icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <span>{{ $next->Kontak }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div><!-- /main-area -->

        <!-- ============================================================
         BOTTOM TICKER AREA
    ============================================================ -->
        <div class="ticker-area">

            <!-- ── Main ticker strip ── -->
            <div class="ticker-strip">
                <div class="ticker-label">
                    <span class="ticker-label-text">Kajian</span>
                </div>
                <div class="ticker-track">
                    <div class="ticker-inner" id="tickerInner">
                        @if ($kajian->isNotEmpty())
                        @foreach ($kajian as $item)
                        <div class="ticker-item">

                            <span class="ticker-date">
                                {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}
                            </span>
                            <span class="ticker-title">{{ $item->Judul }}</span>
                            <span class="ticker-narasumber">{{ $item->Narasumber }}</span>
                            <span class="ticker-tempat">📍 {{ $item->Tempat }}</span>
                            @if ($item->TampilkanLogo)
                                @if ($item->Logo)
                                <img src="{{ asset('logo/' . $item->Logo) }}"
                                    alt="{{ $item->Judul }}"
                                    class="ticker-logo"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                <span class="ticker-logo-placeholder" style="display:none">LOGO</span>
                                @else
                                <span class="ticker-logo-placeholder">LOGO</span>
                                @endif
                            @endif
                        </div>
                        <span class="ticker-bullet">◆</span>
                        @endforeach
                        {{-- Duplicate for seamless loop --}}
                        @foreach ($kajian as $item)
                        <div class="ticker-item">

                            <span class="ticker-date">
                                {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}</span>
                            <span class="ticker-title">{{ $item->Judul }}</span>
                            <span class="ticker-narasumber">{{ $item->Narasumber }}</span>
                            <span class="ticker-tempat">📍 {{ $item->Tempat }}</span>
                            @if ($item->TampilkanLogo)
                                @if ($item->Logo)
                                <img src="{{ asset('logo/' . $item->Logo) }}"
                                    alt="{{ $item->Judul }}"
                                    class="ticker-logo"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                <span class="ticker-logo-placeholder" style="display:none">LOGO</span>
                                @else
                                <span class="ticker-logo-placeholder">LOGO</span>
                                @endif
                            @endif
                        </div>
                        <span class="ticker-bullet">◆</span>
                        @endforeach
                        @else
                        <div class="empty-state">Belum ada jadwal kajian yang ditampilkan.</div>
                        @endif
                    </div>
                </div>
            </div>

        </div><!-- /ticker-area -->

    </div><!-- /screen -->

    <script>
        // ── Live clock ──────────────────────────────────────────────
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        function updateClock() {
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clockTime').textContent = `${hh}:${mm}:${ss}`;

            const day = days[now.getDay()];
            const date = now.getDate();
            const mon = months[now.getMonth()];
            const yr = now.getFullYear();
            document.getElementById('clockDate').textContent = `${day}, ${date} ${mon} ${yr}`;
        }

        updateClock();
        setInterval(updateClock, 1000);

        // ── Seamless marquee via requestAnimationFrame ───────────────
        //
        // How it works:
        //   • The HTML has 2 pre-rendered copies: [A][A]
        //   • JS measures one copy's width and clones more until
        //     the total content fills (trackWidth + oneCopyWidth) px.
        //     This guarantees the screen is NEVER empty during scroll.
        //   • x starts at +trackWidth (off-screen right → enters cleanly)
        //   • Each frame: x -= speed × deltaSeconds
        //   • When x <= -oneCopyWidth, reset x += oneCopyWidth
        //     (invisible because cloned copies are identical)
        //
        function startMarquee(elementId, pixelsPerSecond) {
            const el = document.getElementById(elementId);
            if (!el) return;

            el.style.animation = 'none';

            const trackWidth = el.parentElement.offsetWidth;

            // HTML has exactly 2 copies — measure one copy's width
            const oneCopyWidth = el.scrollWidth / 2;

            // Grab children of the FIRST copy (half of all children)
            const allChildren    = [...el.children];
            const halfCount      = Math.floor(allChildren.length / 2);
            const firstCopyKids  = allChildren.slice(0, halfCount);

            // How many total copies we need so the screen is never empty:
            //   totalWidth must be >= trackWidth + oneCopyWidth
            //   i.e., copies >= ceil((trackWidth + oneCopyWidth) / oneCopyWidth)
            const copiesNeeded = Math.ceil((trackWidth + oneCopyWidth) / oneCopyWidth);

            // We already have 2 copies; clone more if required
            for (let i = 2; i < copiesNeeded + 1; i++) {
                firstCopyKids.forEach(child => el.appendChild(child.cloneNode(true)));
            }

            // Loop reset point: after one copy scrolls off, jump back by oneCopyWidth
            const loopWidth = oneCopyWidth;

            let x        = trackWidth; // start fully off-screen right
            let lastTime = null;

            function frame(timestamp) {
                if (lastTime === null) lastTime = timestamp;
                const delta = (timestamp - lastTime) / 1000; // seconds
                lastTime    = timestamp;

                x -= pixelsPerSecond * delta;

                // One copy scrolled past → reset invisibly
                if (x <= -loopWidth) {
                    x += loopWidth;
                }

                el.style.transform = `translateX(${x}px)`;
                requestAnimationFrame(frame);
            }

            requestAnimationFrame(frame);
        }

        window.addEventListener('load', () => {
            startMarquee('tickerInner', 110); // px/s — main Kajian strip
            startMarquee('infoBarInner', 80); // px/s — gold JADWAL bar
        });
    </script>

</body>

</html>