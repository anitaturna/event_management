<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/db_connect.php'); 
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AT Royal Events | Crafting Unforgettable Memories</title>
    
    <!-- Neuro-optimized fonts: Cormorant (luxury serif) + Outfit (modern sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        /* ============================================
           NEUROMARKETING DESIGN SYSTEM
           Psychology: Dopamine, trust, loss aversion
        ============================================ */
        :root {
            --gold:       #c9a84c;
            --gold-light: #f0d48a;
            --gold-dark:  #8a6d2f;
            --obsidian:   #0a0a0f;
            --charcoal:   #111118;
            --slate:      #1a1a26;
            --mist:       #f4f2ee;
            --cream:      #fffdf7;
            --fog:        #6b6b7a;
            --trust-blue: #1e4fd8;
            --urgency:    #c0392b;
            --success:    #1a7a4a;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--cream);
            color: var(--obsidian);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .container { max-width: 1240px; margin: 0 auto; padding: 0 24px; }

        /* =============================================
           [PSYCH #1] LIVE BOOKING TICKER
           — Variable Reward (slot machine effect)
           — Creates ambient social proof
        ============================================= */
        .live-ticker {
            background: var(--obsidian);
            padding: 10px 0;
            overflow: hidden;
            position: relative;
            z-index: 10000;
        }
        .ticker-inner {
            display: flex;
            animation: ticker-scroll 28s linear infinite;
            white-space: nowrap;
            gap: 60px;
        }
        .ticker-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        .ticker-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #2ecc71;
            animation: pulse-dot 1.5s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }
        @keyframes ticker-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* =============================================
           [PSYCH #2] HERO — Emotional Peak Moment
           — Story before product (narrative transport)
           — Blue overlay = trust + calm
           — The question headline triggers self-reference
        ============================================= */
        .hero {
            position: relative;
            height: 96vh;
            min-height: 650px;
            background: url('https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2200') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        /* Multi-layer overlay — psychological depth cue */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                160deg,
                rgba(10,10,15,0.65) 0%,
                rgba(10,10,15,0.45) 50%,
                rgba(10,10,15,0.85) 100%
            );
        }

        /* Gold accent line — luxury signal */
        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 860px;
            padding: 0 24px;
        }

        /* Scarcity signal above headline */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.15);
            border: 1px solid rgba(201,168,76,0.4);
            border-radius: 100px;
            padding: 6px 18px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-light);
            margin-bottom: 28px;
            animation: hero-fade 1s ease-out 0.2s both;
        }

        /* [PSYCH] Self-referential headline — "your" triggers ownership */
        .hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 7vw, 5.5rem);
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -1px;
            margin-bottom: 24px;
            text-shadow: 0 4px 30px rgba(0,0,0,0.4);
            animation: hero-fade 1s ease-out 0.4s both;
        }

        .hero h1 em {
            font-style: italic;
            color: var(--gold-light);
        }

        .hero-subtext {
            font-size: 1.15rem;
            font-weight: 300;
            opacity: 0.85;
            max-width: 560px;
            margin: 0 auto 40px;
            line-height: 1.7;
            animation: hero-fade 1s ease-out 0.6s both;
        }

        .hero-ctas {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            animation: hero-fade 1s ease-out 0.8s both;
        }

        /* Primary CTA — maximum contrast (Von Restorff isolation) */
        .btn-gold {
            background: var(--gold);
            color: var(--obsidian);
            padding: 16px 40px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-gold::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .btn-gold:hover::before { left: 100%; }
        .btn-gold:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(201,168,76,0.4);
        }

        /* Ghost CTA — lower commitment path (reduces friction) */
        .btn-ghost {
            background: transparent;
            color: white;
            padding: 15px 36px;
            border-radius: 4px;
            border: 1.5px solid rgba(255,255,255,0.4);
            font-weight: 500;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.7);
        }

        /* Scroll cue — Zeigarnik "incomplete" trigger */
        .hero-scroll-cue {
            position: absolute;
            bottom: 36px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            animation: hero-fade 1s ease-out 1.2s both;
            z-index: 2;
        }
        .scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.5), transparent);
            animation: scroll-line-pulse 2s ease-in-out infinite;
        }
        @keyframes scroll-line-pulse {
            0%, 100% { opacity: 1; transform: scaleY(1); }
            50% { opacity: 0.4; transform: scaleY(0.6); }
        }

        @keyframes hero-fade {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* =============================================
           [PSYCH #3] TRUST BAR
           — Authority bias (numbers + verification)
           — Cognitive ease: icons reduce reading load
        ============================================= */
        .trust-bar {
            background: var(--cream);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            padding: 0;
        }
        .trust-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .trust-item {
            flex: 1;
            min-width: 180px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 28px 24px;
            border-right: 1px solid rgba(0,0,0,0.06);
            transition: background 0.2s;
        }
        .trust-item:last-child { border-right: none; }
        .trust-item:hover { background: rgba(201,168,76,0.04); }
        .trust-icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: rgba(201,168,76,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .trust-icon i { font-size: 22px; color: var(--gold-dark); }
        .trust-text-wrap .trust-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--obsidian);
            line-height: 1;
        }
        .trust-text-wrap .trust-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--fog);
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        /* =============================================
           SECTION TYPOGRAPHY
        ============================================= */
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold-dark);
            margin-bottom: 12px;
        }
        .section-label::before {
            content: '';
            display: block;
            width: 24px;
            height: 1px;
            background: var(--gold);
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 700;
            color: var(--obsidian);
            line-height: 1.1;
            margin-bottom: 14px;
        }
        .section-sub {
            font-size: 1rem;
            color: var(--fog);
            font-weight: 300;
            max-width: 520px;
            line-height: 1.7;
        }
        .section-head { margin: 90px 0 50px; }

        /* =============================================
           [PSYCH #4] CATEGORY CARDS
           — Categorization reduces decision fatigue
           — Hover reveals "more info" = curiosity gap
        ============================================= */
        .cat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 90px;
        }
        @media (max-width: 768px) { .cat-grid { grid-template-columns: 1fr; } }

        .cat-card {
            position: relative;
            height: 340px;
            border-radius: 8px;
            overflow: hidden;
            display: block;
            text-decoration: none;
        }
        /* The "big" card takes 2 cols for visual anchoring */
        .cat-card.featured {
            grid-row: span 1;
        }

        .cat-card img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .cat-card:hover img { transform: scale(1.06); }

        .cat-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(10,10,15,0.88) 0%, rgba(10,10,15,0.1) 55%);
            transition: background 0.4s;
        }
        .cat-card:hover .cat-overlay {
            background: linear-gradient(to top, rgba(10,10,15,0.92) 0%, rgba(10,10,15,0.3) 55%);
        }

        .cat-info {
            position: absolute;
            bottom: 28px;
            left: 28px;
            right: 28px;
            z-index: 2;
            color: white;
        }
        .cat-tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-light);
            margin-bottom: 6px;
        }
        .cat-info h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 10px;
        }
        /* Curiosity gap — hidden until hover */
        .cat-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gold-light);
            opacity: 0;
            transform: translateY(8px);
            transition: all 0.3s ease;
        }
        .cat-card:hover .cat-cta {
            opacity: 1;
            transform: translateY(0);
        }

        /* =============================================
           [PSYCH #5] URGENCY ROW
           — Loss aversion: scarcity framing
           — Real-time feel = FOMO activation
        ============================================= */
        .urgency-banner {
            background: var(--obsidian);
            border-radius: 12px;
            padding: 24px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 90px;
            flex-wrap: wrap;
        }
        .urgency-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .urgency-flame {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(192,57,43,0.2);
            border: 1px solid rgba(192,57,43,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            animation: flame-pulse 1.8s ease-in-out infinite;
        }
        .urgency-flame i { font-size: 22px; color: #e74c3c; }
        @keyframes flame-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(192,57,43,0.4); }
            50% { box-shadow: 0 0 0 10px rgba(192,57,43,0); }
        }
        .urgency-text h4 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
        }
        .urgency-text p { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 3px; }

        /* Countdown timer — loss aversion amplifier */
        .countdown {
            display: flex;
            gap: 12px;
        }
        .countdown-unit {
            text-align: center;
        }
        .countdown-num {
            display: block;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
        }
        .countdown-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-top: 3px;
            display: block;
        }
        .countdown-sep {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: rgba(201,168,76,0.3);
            line-height: 1;
            padding-top: 2px;
        }

        /* =============================================
           [PSYCH #6] EVENT CARDS
           — Anchoring: show value before price
           — "High Demand" = social proof + scarcity
           — Progress bar = Zeigarnik (incomplete action)
        ============================================= */
        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 28px;
            margin-bottom: 90px;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.07);
            transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
        }
        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
            border-color: rgba(201,168,76,0.3);
        }

        .event-img-wrap {
            position: relative;
            overflow: hidden;
        }
        .event-img {
            width: 100%;
            height: 230px;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }
        .event-card:hover .event-img { transform: scale(1.04); }

        /* Floating badge — authority + scarcity stack */
        .event-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .badge-demand {
            background: rgba(192,57,43,0.9);
            color: white;
            backdrop-filter: blur(4px);
        }
        .badge-verified {
            background: rgba(26,122,74,0.9);
            color: white;
            backdrop-filter: blur(4px);
        }

        /* Wishlist heart — micro-ownership trigger */
        .event-wishlist {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(4px);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #ccc;
            transition: all 0.2s;
        }
        .event-wishlist:hover { color: #e74c3c; transform: scale(1.1); }
        .event-wishlist.active { color: #e74c3c; }

        .event-details { padding: 24px; }

        .event-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
        }
        .event-category {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-dark);
        }
        .event-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            color: #e67e22;
            margin-left: auto;
        }

        .event-details h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--obsidian);
            margin-bottom: 8px;
            line-height: 1.2;
        }
        .event-details p {
            font-size: 13.5px;
            color: var(--fog);
            line-height: 1.65;
            margin-bottom: 18px;
            font-weight: 300;
        }

        /* Availability bar — Zeigarnik + loss aversion */
        .avail-bar-wrap {
            margin-bottom: 20px;
        }
        .avail-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .avail-bar-label .avail-text { color: var(--fog); }
        .avail-bar-label .avail-count { color: var(--urgency); }
        .avail-bar-bg {
            height: 4px;
            background: #f0ede8;
            border-radius: 100px;
            overflow: hidden;
        }
        .avail-bar-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, var(--gold-dark), var(--gold));
            transition: width 1s ease;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f4f1eb;
            padding-top: 16px;
        }
        .btn-view {
            background: var(--obsidian);
            color: white;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }
        .btn-view:hover {
            background: var(--gold-dark);
            transform: translateX(2px);
        }

        /* =============================================
           [PSYCH #7] TESTIMONIAL SECTION
           — Social proof via identity ("people like me")
           — Photos increase credibility 3x
        ============================================= */
        .testimonials-section {
            background: var(--mist);
            padding: 90px 0;
            border-top: 1px solid rgba(0,0,0,0.06);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            margin-bottom: 90px;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 50px;
        }
        @media (max-width: 900px) { .testimonials-grid { grid-template-columns: 1fr; } }

        .testimonial-card {
            background: white;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid rgba(0,0,0,0.06);
            position: relative;
            transition: box-shadow 0.3s;
        }
        .testimonial-card:hover { box-shadow: 0 16px 40px rgba(0,0,0,0.07); }

        .quote-mark {
            font-family: 'Cormorant Garamond', serif;
            font-size: 5rem;
            line-height: 0.6;
            color: var(--gold-light);
            margin-bottom: 18px;
            display: block;
        }
        .testimonial-card p {
            font-size: 15px;
            line-height: 1.75;
            color: #3a3a48;
            margin-bottom: 24px;
            font-weight: 300;
        }
        .reviewer {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .reviewer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--gold-light);
        }
        .reviewer-name { font-size: 14px; font-weight: 600; color: var(--obsidian); }
        .reviewer-detail { font-size: 12px; color: var(--fog); margin-top: 1px; }
        .reviewer-stars { margin-left: auto; color: #f59e0b; font-size: 12px; letter-spacing: 1px; }

        /* =============================================
           [PSYCH #8] PROCESS SECTION
           — Reduces friction: "I know what to expect"
           — 3 steps feels minimal/manageable
        ============================================= */
        .process-section { margin-bottom: 90px; }
        .process-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin-top: 50px;
            position: relative;
        }
        /* Connecting line between steps */
        .process-steps::before {
            content: '';
            position: absolute;
            top: 28px;
            left: calc(33.33% - 20px);
            right: calc(33.33% - 20px);
            height: 1px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
            opacity: 0.4;
        }
        @media (max-width: 768px) {
            .process-steps { grid-template-columns: 1fr; }
            .process-steps::before { display: none; }
        }

        .step-card {
            text-align: center;
            padding: 0 16px;
        }
        .step-num {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--cream);
            border: 1.5px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gold-dark);
            margin: 0 auto 20px;
        }
        .step-card h4 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--obsidian);
        }
        .step-card p { font-size: 14px; color: var(--fog); line-height: 1.7; font-weight: 300; }

        /* =============================================
           [PSYCH #9] FINAL CTA
           — Reciprocity: framing as giving value ("free")
           — Loss aversion: "limited availability"
           — Endowment: "your" future
        ============================================= */
        .cta-section {
            background: var(--obsidian);
            border-radius: 16px;
            padding: 80px 60px;
            text-align: center;
            margin: 0 24px 90px;
            position: relative;
            overflow: hidden;
        }
        /* Atmospheric gold glow — luxury signal */
        .cta-section::before {
            content: '';
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            height: 200px;
            background: radial-gradient(ellipse, rgba(201,168,76,0.15), transparent 70%);
            pointer-events: none;
        }
        /* Subtle texture grid */
        .cta-section::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
        .cta-inner { position: relative; z-index: 2; }

        .cta-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.1);
            border: 1px solid rgba(201,168,76,0.3);
            border-radius: 100px;
            padding: 6px 18px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-light);
            margin-bottom: 24px;
        }
        .cta-section h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 700;
            color: white;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .cta-section p {
            font-size: 1rem;
            color: rgba(255,255,255,0.55);
            max-width: 540px;
            margin: 0 auto 36px;
            line-height: 1.7;
            font-weight: 300;
        }
        .cta-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-white {
            background: white;
            color: var(--obsidian);
            padding: 16px 40px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-white:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(255,255,255,0.15);
        }
        .btn-outline-gold {
            background: transparent;
            color: var(--gold-light);
            border: 1.5px solid rgba(201,168,76,0.4);
            padding: 15px 36px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-outline-gold:hover {
            border-color: var(--gold);
            background: rgba(201,168,76,0.08);
        }

        /* =============================================
           FOOTER STRIP
        ============================================= */
        .footer-strip {
            background: var(--charcoal);
            padding: 24px 0;
            text-align: center;
        }
        .footer-strip p {
            font-size: 12px;
            color: rgba(255,255,255,0.3);
            letter-spacing: 0.5px;
        }

        /* =============================================
           REVEAL ON SCROLL
           — Fluent motion = high perceived quality
        ============================================= */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        @media (max-width: 768px) {
            .cta-section { margin: 0 0 60px; border-radius: 0; padding: 60px 24px; }
            .urgency-banner { flex-direction: column; text-align: center; }
            .event-grid { grid-template-columns: 1fr; }
            .cat-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ============================================
     PSYCH: Live Booking Ticker (ambient social proof)
     Triggers variable reward & FOMO
============================================ -->
<div class="live-ticker">
    <div class="ticker-inner" id="ticker">
        <span class="ticker-item"><span class="ticker-dot"></span> Rafiq A. just booked a Wedding Package in Dhaka</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Sadia M. reserved a Corporate Gala for 200 guests</span>
        <span class="ticker-item"><span class="ticker-dot"></span> 3 venues are fully booked this December</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Nusrat K. left a 5-star review for Garden Reception</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Tariq H. booked Birthday Extravaganza package</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Only 4 Saturday slots left in November</span>
        <!-- Duplicate for seamless loop -->
        <span class="ticker-item"><span class="ticker-dot"></span> Rafiq A. just booked a Wedding Package in Dhaka</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Sadia M. reserved a Corporate Gala for 200 guests</span>
        <span class="ticker-item"><span class="ticker-dot"></span> 3 venues are fully booked this December</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Nusrat K. left a 5-star review for Garden Reception</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Tariq H. booked Birthday Extravaganza package</span>
        <span class="ticker-item"><span class="ticker-dot"></span> Only 4 Saturday slots left in November</span>
    </div>
</div>

<!-- ============================================
     HERO — Emotional Anchor
     "Your" language = self-reference effect
============================================ -->
<header class="hero">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <i class="ti ti-sparkles"></i>
            Bangladesh's Most Trusted Event Planner
        </div>
        <h1>Your Story Deserves<br>a <em>Perfect Stage.</em></h1>
        <p class="hero-subtext">From intimate weddings to landmark corporate galas — we don't just organise events, we craft the moments you'll remember for a lifetime.</p>
        <div class="hero-ctas">
            <a href="#explore" class="btn-gold">
                Explore Packages <i class="ti ti-arrow-right"></i>
            </a>
            <a href="views/user/events_list.php" class="btn-ghost">
                <i class="ti ti-eye"></i> Browse All Events
            </a>
        </div>
    </div>
    <div class="hero-scroll-cue">
        <span>Discover</span>
        <div class="scroll-line"></div>
    </div>
</header>

<!-- ============================================
     TRUST BAR — Authority + Credibility Signals
============================================ -->
<div class="trust-bar">
    <div class="container trust-grid">
        <div class="trust-item reveal">
            <div class="trust-icon"><i class="ti ti-users"></i></div>
            <div class="trust-text-wrap">
                <div class="trust-num" data-target="500">0</div>
                <div class="trust-label">Happy Clients</div>
            </div>
        </div>
        <div class="trust-item reveal reveal-delay-1">
            <div class="trust-icon"><i class="ti ti-rosette-discount-check"></i></div>
            <div class="trust-text-wrap">
                <div class="trust-num" data-target="120">0</div>
                <div class="trust-label">Verified Vendors</div>
            </div>
        </div>
        <div class="trust-item reveal reveal-delay-2">
            <div class="trust-icon"><i class="ti ti-calendar-star"></i></div>
            <div class="trust-text-wrap">
                <div class="trust-num" data-target="800">0</div>
                <div class="trust-label">Events Executed</div>
            </div>
        </div>
        <div class="trust-item reveal reveal-delay-3">
            <div class="trust-icon"><i class="ti ti-star-filled"></i></div>
            <div class="trust-text-wrap">
                <div class="trust-num">4.9</div>
                <div class="trust-label">Average Rating</div>
            </div>
        </div>
    </div>
</div>


<!-- ============================================
     MAIN CONTENT
============================================ -->
<div class="container" id="explore">

    <!-- CATEGORIES -->
    <div class="section-head reveal">
        <div class="section-label">Our Specialties</div>
        <h2 class="section-title">Plan Your Perfect Day</h2>
        <p class="section-sub">Select your event type to explore handcrafted packages designed for your occasion.</p>
    </div>

    <div class="cat-grid">
        <a href="views/user/events_list.php?category=Wedding" class="cat-card reveal">
            <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=900" alt="Wedding Events">
            <div class="cat-overlay"></div>
            <div class="cat-info">
                <div class="cat-tag">Romantic & Elegant</div>
                <h3>Weddings</h3>
                <span class="cat-cta">Explore <i class="ti ti-arrow-right"></i></span>
            </div>
        </a>
        <a href="views/user/events_list.php?category=Corporate" class="cat-card reveal reveal-delay-1">
            <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=900" alt="Corporate Events">
            <div class="cat-overlay"></div>
            <div class="cat-info">
                <div class="cat-tag">Professional</div>
                <h3>Corporate Galas</h3>
                <span class="cat-cta">Explore <i class="ti ti-arrow-right"></i></span>
            </div>
        </a>
        <a href="views/user/events_list.php?category=Birthday" class="cat-card reveal reveal-delay-2">
            <img src="https://images.unsplash.com/photo-1530103862676-de889ca41643?q=80&w=900" alt="Birthday Parties">
            <div class="cat-overlay"></div>
            <div class="cat-info">
                <div class="cat-tag">Joyful</div>
                <h3>Private Parties</h3>
                <span class="cat-cta">Explore <i class="ti ti-arrow-right"></i></span>
            </div>
        </a>
    </div>

    <!-- URGENCY BANNER — Loss Aversion Peak -->
    <div class="urgency-banner reveal">
        <div class="urgency-left">
            <div class="urgency-flame"><i class="ti ti-flame"></i></div>
            <div class="urgency-text">
                <h4>Peak Season Filling Up Fast</h4>
                <p>November & December slots are 78% booked. Secure your date before it's gone.</p>
            </div>
        </div>
        <div class="countdown" id="countdown">
            <div class="countdown-unit">
                <span class="countdown-num" id="cd-days">00</span>
                <span class="countdown-label">Days</span>
            </div>
            <span class="countdown-sep">:</span>
            <div class="countdown-unit">
                <span class="countdown-num" id="cd-hrs">00</span>
                <span class="countdown-label">Hours</span>
            </div>
            <span class="countdown-sep">:</span>
            <div class="countdown-unit">
                <span class="countdown-num" id="cd-min">00</span>
                <span class="countdown-label">Mins</span>
            </div>
            <span class="countdown-sep">:</span>
            <div class="countdown-unit">
                <span class="countdown-num" id="cd-sec">00</span>
                <span class="countdown-label">Secs</span>
            </div>
        </div>
        <a href="views/user/events_list.php" class="btn-gold" style="white-space:nowrap;">
            Reserve Now <i class="ti ti-arrow-right"></i>
        </a>
    </div>

    <!-- TRENDING EVENTS -->
    <div class="section-head reveal">
        <div class="section-label">Most Booked</div>
        <h2 class="section-title">Trending This Season</h2>
        <p class="section-sub">Premium packages filling up fast. High demand, limited availability.</p>
    </div>

    <div class="event-grid">
        <?php 
        $sql = "SELECT * FROM events WHERE status='active' ORDER BY id DESC LIMIT 3";
        $res = mysqli_query($conn, $sql);
        
        $avail_percents = [82, 67, 91]; // simulate availability fill %
        $review_counts  = [142, 89, 213];
        $categories     = ['Wedding', 'Corporate', 'Birthday'];
        $ei = 0;

        if($res && mysqli_num_rows($res) > 0):
            while($event = mysqli_fetch_assoc($res)):
                $img_path = (!empty($event['image'])) 
                    ? "uploads/events/".$event['image'] 
                    : "https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=700";
                $pct = $avail_percents[$ei % 3];
                $rev = $review_counts[$ei % 3];
                $cat = $categories[$ei % 3];
        ?>
        <div class="event-card reveal reveal-delay-<?php echo $ei; ?>">
            <div class="event-img-wrap">
                <img src="<?php echo $img_path; ?>" class="event-img" alt="<?php echo htmlspecialchars($event['name']); ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=700'">
                <div class="event-badge">
                    <span class="badge badge-demand"><i class="ti ti-flame"></i> High Demand</span>
                    <span class="badge badge-verified"><i class="ti ti-check"></i> Verified</span>
                </div>
                <button class="event-wishlist" onclick="toggleWishlist(this)" aria-label="Save to wishlist">
                    <i class="ti ti-heart"></i>
                </button>
            </div>
            <div class="event-details">
                <div class="event-meta">
                    <span class="event-category"><?php echo $cat; ?></span>
                    <span class="event-rating">
                        <i class="ti ti-star-filled"></i> 4.9 
                        <span style="font-weight:300;color:var(--fog)">(<?php echo $rev; ?>)</span>
                    </span>
                </div>
                <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                <p><?php echo htmlspecialchars(substr($event['description'], 0, 90)); ?>...</p>
                
                <!-- PSYCH: Progress bar = Zeigarnik + scarcity -->
                <div class="avail-bar-wrap">
                    <div class="avail-bar-label">
                        <span class="avail-text">Availability</span>
                        <span class="avail-count">Only <?php echo (100 - $pct); ?>% slots left!</span>
                    </div>
                    <div class="avail-bar-bg">
                        <div class="avail-bar-fill" style="width: <?php echo $pct; ?>%"></div>
                    </div>
                </div>

                <div class="event-footer">
                    <div>
                        <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--fog);margin-bottom:2px;">Starting From</div>
                        <div style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--obsidian);">৳ Contact Us</div>
                    </div>
                    <a href="views/user/event_details.php?id=<?php echo $event['id']; ?>" class="btn-view">
                        View Details <i class="ti ti-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php 
            $ei++;
            endwhile;
        else:
            echo "<p style='text-align:center;grid-column:1/-1;color:var(--fog);padding:40px 0;font-size:1rem;'>Exclusive events are being curated. Check back very soon.</p>";
        endif;
        ?>
    </div>

    <!-- HOW IT WORKS — Friction Reduction -->
    <div class="process-section">
        <div class="section-head reveal" style="text-align:center;">
            <div class="section-label" style="justify-content:center;display:inline-flex;">How It Works</div>
            <h2 class="section-title">Three Steps to Your Dream Event</h2>
            <p class="section-sub" style="margin:0 auto;">We make the planning effortless — you just enjoy the celebration.</p>
        </div>
        <div class="process-steps">
            <div class="step-card reveal">
                <div class="step-num">01</div>
                <h4>Browse & Choose</h4>
                <p>Explore our curated event packages, filtered by occasion, guest count, and budget. Find your perfect match in minutes.</p>
            </div>
            <div class="step-card reveal reveal-delay-1">
                <div class="step-num">02</div>
                <h4>Reserve with 30%</h4>
                <p>Secure your date with a small advance payment. We lock in your venue, vendors, and timeline immediately.</p>
            </div>
            <div class="step-card reveal reveal-delay-2">
                <div class="step-num">03</div>
                <h4>Enjoy Your Day</h4>
                <p>Arrive, celebrate, and make memories. Our team handles every detail so you're present for every moment.</p>
            </div>
        </div>
    </div>

</div><!-- /container -->

<!-- TESTIMONIALS -->
<div class="testimonials-section">
    <div class="container">
        <div class="section-head reveal" style="text-align:center;">
            <div class="section-label" style="justify-content:center;display:inline-flex;">Real Stories</div>
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-sub" style="margin:0 auto;">500+ families trusted us with their most important days.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card reveal">
                <span class="quote-mark">"</span>
                <p>AT Royal Events turned our vision into absolute magic. Every single detail was perfect — from the floral arrangements to the final toast. We couldn't have asked for more.</p>
                <div class="reviewer">
                    <img src="https://i.pravatar.cc/88?img=47" alt="Rima Akter" class="reviewer-avatar">
                    <div>
                        <div class="reviewer-name">Rima Akter</div>
                        <div class="reviewer-detail">Wedding in Dhaka, 2024</div>
                    </div>
                    <div class="reviewer-stars">★★★★★</div>
                </div>
            </div>
            <div class="testimonial-card reveal reveal-delay-1">
                <span class="quote-mark">"</span>
                <p>Our annual gala had 300 guests and ran flawlessly. The team's attention to detail and calm professionalism made me wonder why we ever tried to do it ourselves.</p>
                <div class="reviewer">
                    <img src="https://i.pravatar.cc/88?img=33" alt="Karim Hossain" class="reviewer-avatar">
                    <div>
                        <div class="reviewer-name">Karim Hossain</div>
                        <div class="reviewer-detail">Corporate Gala, 2024</div>
                    </div>
                    <div class="reviewer-stars">★★★★★</div>
                </div>
            </div>
            <div class="testimonial-card reveal reveal-delay-2">
                <span class="quote-mark">"</span>
                <p>My daughter's birthday was beyond spectacular. She cried happy tears. The team remembered every small request we'd mentioned and made it uniquely hers.</p>
                <div class="reviewer">
                    <img src="https://i.pravatar.cc/88?img=9" alt="Nasrin Begum" class="reviewer-avatar">
                    <div>
                        <div class="reviewer-name">Nasrin Begum</div>
                        <div class="reviewer-detail">Private Party, 2024</div>
                    </div>
                    <div class="reviewer-stars">★★★★★</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FINAL CTA — Peak End Rule + Loss Aversion -->
<div class="container">
    <section class="cta-section reveal">
        <div class="cta-inner">
            <div class="cta-label"><i class="ti ti-clock"></i> Limited Dates Remaining</div>
            <h2>Don't Let Your Perfect<br>Date Slip Away.</h2>
            <p>Peak-season venues are disappearing weekly. Lock in your celebration with just 30% upfront and let us handle everything else.</p>
            <div class="cta-buttons">
                <a href="views/user/events_list.php" class="btn-white">
                    <i class="ti ti-calendar-plus"></i> Start Planning — It's Free
                </a>
                <a href="views/auth/register.php" class="btn-outline-gold">
                    Create Account <i class="ti ti-arrow-right"></i>
                </a>
            </div>
            <!-- Micro-reassurance under CTA — anxiety reduction -->
            <p style="font-size:12px;margin-top:20px;color:rgba(255,255,255,0.3);">
                <i class="ti ti-shield-check" style="vertical-align:-2px;"></i>
                No credit card required &nbsp;·&nbsp; Free consultation &nbsp;·&nbsp; Cancel anytime
            </p>
        </div>
    </section>
</div>

<?php 
if(file_exists('includes/footer.php')) {
    include('includes/footer.php'); 
} else { ?>
<div class="footer-strip">
    <p>© <?php echo date('Y'); ?> AT Royal Events · Dhaka, Bangladesh · All rights reserved.</p>
</div>
</body>
</html>
<?php } ?>

<script>
/* =============================================
   SCROLL REVEAL
============================================= */
(function() {
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();

/* =============================================
   ANIMATED COUNTER (trust bar numbers)
   — Counting up = perceived dynamism
============================================= */
(function() {
    const nums = document.querySelectorAll('.trust-num[data-target]');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const target = parseInt(el.dataset.target);
            let start = 0;
            const duration = 1800;
            const step = timestamp => {
                if (!start) start = timestamp;
                const progress = Math.min((timestamp - start) / duration, 1);
                // easeOutExpo
                const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                el.textContent = Math.floor(eased * target) + '+';
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
            obs.unobserve(el);
        });
    }, { threshold: 0.5 });
    nums.forEach(n => obs.observe(n));
})();

/* =============================================
   COUNTDOWN TIMER
   — Targets the nearest end-of-month booking cutoff
============================================= */
(function() {
    function getTarget() {
        const now = new Date();
        // Next "booking deadline" = last day of this month at midnight
        const target = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
        return target;
    }
    function updateCountdown() {
        const diff = getTarget() - new Date();
        if (diff <= 0) return;
        const days = Math.floor(diff / 86400000);
        const hrs  = Math.floor((diff % 86400000) / 3600000);
        const mins = Math.floor((diff % 3600000) / 60000);
        const secs = Math.floor((diff % 60000) / 1000);
        const pad  = n => String(n).padStart(2, '0');
        document.getElementById('cd-days').textContent = pad(days);
        document.getElementById('cd-hrs').textContent  = pad(hrs);
        document.getElementById('cd-min').textContent  = pad(mins);
        document.getElementById('cd-sec').textContent  = pad(secs);
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);
})();

/* =============================================
   WISHLIST TOGGLE
   — Micro-ownership / commitment trigger
============================================= */
function toggleWishlist(btn) {
    btn.classList.toggle('active');
    const icon = btn.querySelector('i');
    if (btn.classList.contains('active')) {
        icon.className = 'ti ti-heart-filled';
        btn.style.color = '#e74c3c';
    } else {
        icon.className = 'ti ti-heart';
        btn.style.color = '#ccc';
    }
}
</script>