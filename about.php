<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'config/db.php';
require 'includes/header.php';
?>
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<style>
/* ============================================================
   ABOUT PAGE — PREMIUM REDESIGN
   Preserving brand: Gold #F4B400 | Black #1A1A1A | Poppins + Playfair
============================================================ */

/* ── HERO ── */
.about-hero-premium {
    position: relative;
    min-height: 88vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
    background: #0F0F0F;
}
.about-hero-bg {
    position: absolute; inset: 0;
    background-image: url('img/about us-hero.png');
    background-size: cover;
    background-position: center 30%;
    opacity: 0.35;
    transform: scale(1.05);
    transition: transform 8s ease-out;
}
.about-hero-bg.loaded { transform: scale(1); }
.about-hero-gradient {
    position: absolute; inset: 0;
    background: linear-gradient(
        135deg,
        rgba(10,10,10,0.85) 0%,
        rgba(20,15,5,0.70) 50%,
        rgba(10,10,10,0.92) 100%
    );
}
.about-hero-inner {
    position: relative; z-index: 2;
    padding: 120px 5% 60px;
    max-width: 820px;
    margin: 0 auto;
}
.about-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(244,180,0,0.15);
    border: 1px solid rgba(244,180,0,0.35);
    color: var(--primary-gold);
    padding: 6px 18px; border-radius: 30px;
    font-size: 0.78rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 28px;
    backdrop-filter: blur(8px);
}
.about-hero-badge i { font-size: 0.7rem; }
.about-hero-inner h1 {
    font-family: var(--font-heading);
    font-size: clamp(2.4rem, 6vw, 4.2rem);
    color: #fff; line-height: 1.15; margin-bottom: 22px;
    text-shadow: 0 2px 30px rgba(0,0,0,0.4);
}
.about-hero-inner h1 span {
    color: var(--primary-gold);
    background: linear-gradient(135deg, #F4B400, #ffd740);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}
.about-hero-inner p {
    font-size: 1.15rem; color: rgba(255,255,255,0.78);
    line-height: 1.7; max-width: 600px; margin: 0 auto 40px;
}
.about-hero-actions {
    display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;
}
.about-hero-actions .btn-hero-primary {
    padding: 14px 36px; background: var(--primary-gold);
    color: #111; border-radius: 50px; font-weight: 700;
    font-size: 0.95rem; border: none; cursor: pointer;
    transition: all 0.3s ease; text-decoration: none;
    box-shadow: 0 8px 25px rgba(244,180,0,0.35);
    font-family: var(--font-body);
}
.about-hero-actions .btn-hero-primary:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 14px 35px rgba(244,180,0,0.5);
    background: #ffc930;
}
.about-hero-actions .btn-hero-outline {
    padding: 14px 36px; background: transparent;
    color: #fff; border-radius: 50px; font-weight: 600;
    font-size: 0.95rem; border: 1.5px solid rgba(255,255,255,0.4);
    cursor: pointer; transition: all 0.3s ease;
    text-decoration: none; font-family: var(--font-body);
}
.about-hero-actions .btn-hero-outline:hover {
    background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.7);
    transform: translateY(-2px);
}

/* Animated stats bar at bottom of hero */
.about-hero-statsbar {
    position: absolute; bottom: 0; left: 0; right: 0; z-index: 3;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(12px);
    border-top: 1px solid rgba(244,180,0,0.2);
    display: grid; grid-template-columns: repeat(4,1fr);
}
.about-stat-item {
    padding: 22px 20px; text-align: center;
    border-right: 1px solid rgba(255,255,255,0.06);
}
.about-stat-item:last-child { border-right: none; }
.about-stat-num {
    font-family: var(--font-heading);
    font-size: 2rem; font-weight: 700;
    color: var(--primary-gold); line-height: 1;
}
.about-stat-lbl { font-size: 0.75rem; color: rgba(255,255,255,0.55); margin-top: 4px; text-transform: uppercase; letter-spacing: 1px; }

/* ── BRAND STORY ── */
.about-story-wrap {
    padding: 100px 5%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}
.about-story-img-col { position: relative; }
.about-story-img {
    width: 100%; border-radius: 24px;
    box-shadow: 0 30px 70px rgba(0,0,0,0.14);
    object-fit: cover; aspect-ratio: 4/5;
    display: block; position: relative; z-index: 2;
}
.about-story-img-decor {
    position: absolute; bottom: -20px; right: -20px;
    width: 55%; height: 55%; border-radius: 16px;
    background: linear-gradient(135deg, var(--primary-gold), #d19c00);
    opacity: 0.12; z-index: 1;
}
.about-story-badge-float {
    position: absolute; top: 24px; right: -18px; z-index: 3;
    background: #fff; border-radius: 14px;
    padding: 14px 20px; box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    display: flex; align-items: center; gap: 12px;
    border-left: 4px solid var(--primary-gold);
}
.about-story-badge-float .badge-icon { font-size: 1.6rem; color: var(--primary-gold); }
.about-story-badge-float .badge-text { font-size: 0.78rem; font-weight: 700; color: #111; line-height: 1.3; }
.about-story-badge-float .badge-sub { font-size: 0.68rem; color: #888; font-weight: 400; }

.about-story-text-col {}
.about-section-tag {
    display: inline-block;
    background: rgba(244,180,0,0.12); color: var(--primary-gold);
    padding: 5px 14px; border-radius: 20px; font-size: 0.72rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
    margin-bottom: 18px;
}
.about-story-text-col h2 {
    font-family: var(--font-heading);
    font-size: clamp(1.9rem, 3.5vw, 2.8rem);
    color: #1A1A1A; line-height: 1.2; margin-bottom: 20px;
}
.about-story-text-col h2 span { color: var(--primary-gold); }
.about-pullquote {
    border-left: 3px solid var(--primary-gold);
    padding: 16px 20px; margin: 24px 0;
    background: rgba(244,180,0,0.04); border-radius: 0 8px 8px 0;
    font-size: 1.05rem; font-style: italic;
    color: #444; line-height: 1.6;
}
.about-story-text-col p {
    color: #555; line-height: 1.8; margin-bottom: 16px; font-size: 0.97rem;
}
.about-story-values {
    display: flex; gap: 16px; flex-wrap: wrap; margin-top: 28px;
}
.about-val-chip {
    display: flex; align-items: center; gap: 8px;
    background: #f8f9fa; border: 1px solid #eee;
    padding: 8px 16px; border-radius: 50px;
    font-size: 0.82rem; font-weight: 600; color: #333;
    transition: all 0.3s ease;
}
.about-val-chip:hover {
    background: var(--primary-gold); color: #111; border-color: var(--primary-gold);
    transform: translateY(-2px); box-shadow: 0 5px 15px rgba(244,180,0,0.3);
}
.about-val-chip i { color: var(--primary-gold); font-size: 0.85rem; }
.about-val-chip:hover i { color: #111; }

/* ── MISSION / VISION / VALUES ── */
.about-mvv-section {
    background: #0F0F0F; padding: 100px 5%;
}
.about-mvv-section .about-section-header { text-align: center; margin-bottom: 60px; }
.about-mvv-section .about-section-header h2 {
    font-family: var(--font-heading); font-size: clamp(1.8rem, 3vw, 2.6rem);
    color: #fff; margin-bottom: 14px;
}
.about-mvv-section .about-section-header h2 span { color: var(--primary-gold); }
.about-mvv-section .about-section-header p { color: rgba(255,255,255,0.5); max-width: 500px; margin: 0 auto; }
.about-mvv-grid {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 28px;
    max-width: 1100px; margin: 0 auto;
}
.about-mvv-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px; padding: 44px 34px;
    transition: all 0.4s cubic-bezier(0.165,0.84,0.44,1);
    position: relative; overflow: hidden;
}
.about-mvv-card::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary-gold), transparent);
    opacity: 0; transition: opacity 0.4s ease;
}
.about-mvv-card:hover { border-color: rgba(244,180,0,0.25); transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
.about-mvv-card:hover::before { opacity: 1; }
.about-mvv-icon {
    width: 62px; height: 62px; border-radius: 16px;
    background: linear-gradient(135deg, rgba(244,180,0,0.2), rgba(244,180,0,0.08));
    border: 1px solid rgba(244,180,0,0.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: var(--primary-gold);
    margin-bottom: 28px; transition: all 0.3s ease;
}
.about-mvv-card:hover .about-mvv-icon { background: rgba(244,180,0,0.25); transform: scale(1.08); }
.about-mvv-card h3 { font-family: var(--font-heading); font-size: 1.35rem; color: #fff; margin-bottom: 14px; }
.about-mvv-card p { color: rgba(255,255,255,0.5); line-height: 1.75; font-size: 0.92rem; }

/* ── TIMELINE ── */
.about-timeline-section { padding: 100px 5%; background: #fff; }
.about-timeline-section .about-section-header { text-align: center; margin-bottom: 60px; }
.about-timeline-section .about-section-header h2 { font-family: var(--font-heading); font-size: clamp(1.8rem, 3vw, 2.6rem); color: #1A1A1A; margin-bottom: 12px; }
.about-timeline-section .about-section-header h2 span { color: var(--primary-gold); }
.about-timeline-section .about-section-header p { color: #666; max-width: 500px; margin: 0 auto; }
.about-timeline {
    position: relative; max-width: 900px; margin: 0 auto;
    padding: 0 0 20px;
}
.about-timeline::before {
    content: ''; position: absolute;
    left: 50%; top: 0; bottom: 0; width: 2px;
    background: linear-gradient(180deg, var(--primary-gold), rgba(244,180,0,0.1));
    transform: translateX(-50%);
}
.tl-item {
    display: grid; grid-template-columns: 1fr 60px 1fr;
    gap: 0; align-items: center; margin-bottom: 50px;
}
.tl-item:last-child { margin-bottom: 0; }
.tl-content-left { text-align: right; padding-right: 36px; }
.tl-content-right { text-align: left; padding-left: 36px; }
.tl-empty { padding: 20px; }
.tl-dot {
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--primary-gold); color: #111;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.75rem; z-index: 2;
    box-shadow: 0 0 0 6px rgba(244,180,0,0.15); margin: 0 auto;
    font-family: var(--font-body);
}
.tl-year { font-size: 0.72rem; font-weight: 700; color: var(--primary-gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px; }
.tl-title { font-family: var(--font-heading); font-size: 1.1rem; color: #1A1A1A; margin-bottom: 6px; }
.tl-desc { font-size: 0.88rem; color: #666; line-height: 1.6; }

/* ── TEAM ── */
.about-team-section { padding: 100px 5%; background: #F8F9FA; }
.about-team-section .about-section-header { text-align: center; margin-bottom: 60px; }
.about-team-section .about-section-header h2 { font-family: var(--font-heading); font-size: clamp(1.8rem, 3vw, 2.6rem); color: #1A1A1A; margin-bottom: 12px; }
.about-team-section .about-section-header h2 span { color: var(--primary-gold); }
.about-team-section .about-section-header p { color: #666; max-width: 500px; margin: 0 auto; }
.about-team-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 30px; max-width: 1000px; margin: 0 auto; }
.team-card-premium {
    background: #fff; border-radius: 22px;
    box-shadow: 0 6px 28px rgba(0,0,0,0.06);
    overflow: hidden; text-align: center;
    transition: all 0.4s cubic-bezier(0.165,0.84,0.44,1);
    border: 1px solid transparent;
}
.team-card-premium:hover {
    transform: translateY(-10px); border-color: rgba(244,180,0,0.3);
    box-shadow: 0 20px 50px rgba(0,0,0,0.12), 0 0 0 1px rgba(244,180,0,0.15);
}
.team-avatar-wrap {
    height: 200px; display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}
.team-avatar-gradient {
    width: 120px; height: 120px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-heading); font-size: 2.8rem; font-weight: 700;
    color: #fff; position: relative; z-index: 2;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    border: 4px solid rgba(255,255,255,0.3);
}
.team-avatar-wrap::before {
    content: ''; position: absolute; inset: 0;
    opacity: 0.08; background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.6), transparent);
}
.avatar-jenny { background: linear-gradient(135deg, #F4B400, #e67e00); }
.avatar-fatima { background: linear-gradient(135deg, #9B59B6, #6C3483); }
.avatar-sana { background: linear-gradient(135deg, #1ABC9C, #16816A); }
.team-card-body { padding: 6px 28px 32px; }
.team-card-name { font-family: var(--font-heading); font-size: 1.25rem; color: #1A1A1A; margin-bottom: 4px; }
.team-card-role { font-size: 0.8rem; font-weight: 600; color: var(--primary-gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 14px; }
.team-card-desc { font-size: 0.88rem; color: #666; line-height: 1.7; }
.team-card-socials { display: flex; justify-content: center; gap: 10px; margin-top: 18px; }
.team-card-socials a {
    width: 34px; height: 34px; border-radius: 50%;
    background: #f0f0f0; display: flex; align-items: center; justify-content: center;
    color: #555; font-size: 0.78rem; transition: all 0.3s ease; text-decoration: none;
}
.team-card-socials a:hover { background: var(--primary-gold); color: #111; transform: translateY(-2px); }

/* ── WHY CHOOSE US ── */
.about-trust-section { padding: 100px 5%; background: #fff; }
.about-trust-section .about-section-header { text-align: center; margin-bottom: 60px; }
.about-trust-section .about-section-header h2 { font-family: var(--font-heading); font-size: clamp(1.8rem, 3vw, 2.6rem); color: #1A1A1A; margin-bottom: 12px; }
.about-trust-section .about-section-header h2 span { color: var(--primary-gold); }
.about-trust-grid {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 24px;
    max-width: 960px; margin: 0 auto;
}
.about-trust-card {
    background: #F8F9FA; border-radius: 18px; padding: 36px 28px;
    display: flex; flex-direction: column; align-items: flex-start;
    border: 1px solid #eee; transition: all 0.35s ease;
}
.about-trust-card:hover {
    background: #fff; border-color: rgba(244,180,0,0.3);
    transform: translateY(-5px); box-shadow: 0 12px 35px rgba(0,0,0,0.08);
}
.about-trust-icon {
    width: 54px; height: 54px; border-radius: 14px;
    background: rgba(244,180,0,0.1); display: flex; align-items: center;
    justify-content: center; color: var(--primary-gold); font-size: 1.3rem;
    margin-bottom: 20px; transition: all 0.3s ease;
}
.about-trust-card:hover .about-trust-icon { background: var(--primary-gold); color: #111; }
.about-trust-card h4 { font-size: 1rem; font-weight: 700; color: #1A1A1A; margin-bottom: 10px; }
.about-trust-card p { font-size: 0.875rem; color: #666; line-height: 1.65; }

/* ── CTA SECTION ── */
.about-cta-section {
    background: linear-gradient(135deg, #0F0F0F 0%, #1a1200 50%, #0F0F0F 100%);
    padding: 100px 5%; text-align: center; position: relative; overflow: hidden;
}
.about-cta-section::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(244,180,0,0.08), transparent);
}
.about-cta-inner { position: relative; z-index: 2; max-width: 620px; margin: 0 auto; }
.about-cta-inner h2 { font-family: var(--font-heading); font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-bottom: 18px; }
.about-cta-inner h2 span { color: var(--primary-gold); }
.about-cta-inner p { color: rgba(255,255,255,0.55); font-size: 1.05rem; margin-bottom: 40px; line-height: 1.7; }
.about-cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
.about-cta-buttons .btn-cta-gold {
    padding: 16px 44px; background: var(--primary-gold); color: #111;
    border-radius: 50px; font-weight: 700; font-size: 1rem;
    border: none; cursor: pointer; transition: all 0.3s ease;
    text-decoration: none; font-family: var(--font-body);
    box-shadow: 0 8px 25px rgba(244,180,0,0.35);
}
.about-cta-buttons .btn-cta-gold:hover { background: #ffc930; transform: translateY(-3px); box-shadow: 0 15px 40px rgba(244,180,0,0.5); }
.about-cta-buttons .btn-cta-outline {
    padding: 16px 40px; background: transparent; color: rgba(255,255,255,0.8);
    border-radius: 50px; font-weight: 600; font-size: 1rem;
    border: 1.5px solid rgba(255,255,255,0.3); cursor: pointer;
    transition: all 0.3s ease; text-decoration: none; font-family: var(--font-body);
}
.about-cta-buttons .btn-cta-outline:hover { border-color: var(--primary-gold); color: var(--primary-gold); }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    .about-story-wrap { grid-template-columns: 1fr; gap: 50px; }
    .about-story-img-col { max-width: 500px; margin: 0 auto; width: 100%; }
    .about-story-badge-float { right: 10px; }
}
@media (max-width: 900px) {
    .about-mvv-grid, .about-team-grid, .about-trust-grid { grid-template-columns: 1fr 1fr; }
    .about-hero-statsbar { grid-template-columns: repeat(2,1fr); position: relative; }
}
@media (max-width: 768px) {
    .about-hero-inner { padding: 100px 5% 40px; }
    .about-hero-inner h1 { font-size: 2.2rem; }
    .about-timeline::before { left: 24px; }
    .tl-item { grid-template-columns: 60px 1fr; }
    .tl-content-left { display: none; }
    .tl-empty { display: none; }
    .tl-content-right { grid-column: 2; text-align: left; padding-left: 20px; }
    .tl-dot { margin: 0; }
}
@media (max-width: 600px) {
    .about-mvv-grid, .about-team-grid, .about-trust-grid { grid-template-columns: 1fr; }
    .about-hero-statsbar { grid-template-columns: repeat(2,1fr); }
    .about-stat-num { font-size: 1.5rem; }
    .about-cta-buttons { flex-direction: column; align-items: center; }
    .tl-item { grid-template-columns: 50px 1fr; }
}
</style>

<?php require 'includes/navbar.php'; ?>

<!-- ============================================================ -->
<!-- HERO SECTION -->
<!-- ============================================================ -->
<section class="about-hero-premium">
    <div class="about-hero-bg" id="aboutHeroBg"></div>
    <div class="about-hero-gradient"></div>

    <div class="about-hero-inner" data-aos="fade-up">
        <div class="about-hero-badge">
            <i class="fas fa-gem"></i> Est. 2020 — Karachi, Pakistan
        </div>
        <h1>Where Beauty Meets <span>Passion</span></h1>
        <p>From a home-based dream to Pakistan's most-loved cosmetics & jewelry destination — crafted for the modern woman who deserves the best.</p>
        <div class="about-hero-actions">
            <a href="products.php" class="btn-hero-primary"><i class="fas fa-shopping-bag" style="margin-right:8px;"></i> Shop Our Collection</a>
            <a href="contact.php" class="btn-hero-outline"><i class="fas fa-headset" style="margin-right:8px;"></i> Get in Touch</a>
        </div>
    </div>

    <!-- Stat bar at bottom of hero -->
    <div class="about-hero-statsbar">
        <div class="about-stat-item">
            <div class="about-stat-num" data-count="6">0</div>
            <div class="about-stat-lbl">Years in Business</div>
        </div>
        <div class="about-stat-item">
            <div class="about-stat-num" data-count="15000">0</div>
            <div class="about-stat-lbl">Happy Customers</div>
        </div>
        <div class="about-stat-item">
            <div class="about-stat-num" data-count="500">0</div>
            <div class="about-stat-lbl">Premium Products</div>
        </div>
        <div class="about-stat-item">
            <div class="about-stat-num" data-count="50">0</div>
            <div class="about-stat-lbl">Cities Served</div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- BRAND STORY -->
<!-- ============================================================ -->
<section style="background:#fff; padding-top:0;">
<div class="about-story-wrap">
    <!-- Image Column -->
    <div class="about-story-img-col" data-aos="fade-right">
        <img src="img/our story.png" alt="Jenny's Brand Story" class="about-story-img" loading="lazy">
        <div class="about-story-img-decor"></div>
        <div class="about-story-badge-float">
            <span class="badge-icon"><i class="fas fa-gem"></i></span>
            <div>
                <div class="badge-text">100% Authentic</div>
                <div class="badge-sub">Quality Guaranteed</div>
            </div>
        </div>
    </div>

    <!-- Text Column -->
    <div class="about-story-text-col" data-aos="fade-left">
        <div class="about-section-tag"><i class="fas fa-book-open" style="margin-right:6px;"></i>Our Story</div>
        <h2>Crafted with <span>Passion</span>,<br>Built on <span>Trust</span></h2>

        <div class="about-pullquote">
            "Every woman deserves to feel beautiful — not just on special occasions, but every single day."
        </div>

        <p>Jenny's Cosmetics & Jewelry was born from a simple dream: to bring high-quality, affordable beauty and elegance to every woman in Pakistan. Founded in 2020 by <strong>Jenny</strong>, a passionate makeup artist and jewelry enthusiast, what started as a small home-based venture has blossomed into a trusted national brand.</p>

        <p>We source exclusively from certified, cruelty-free manufacturers and personally test every product before it reaches your hands. Our imitation jewelry is crafted with the precision and care of fine jewelry — because you deserve nothing less.</p>

        <div class="about-story-values">
            <div class="about-val-chip"><i class="fas fa-leaf"></i> Cruelty Free</div>
            <div class="about-val-chip"><i class="fas fa-check-circle"></i> 100% Authentic</div>
            <div class="about-val-chip"><i class="fas fa-heart"></i> Made with Love</div>
            <div class="about-val-chip"><i class="fas fa-award"></i> Award Winning</div>
        </div>
    </div>
</div>
</section>

<!-- ============================================================ -->
<!-- MISSION / VISION / VALUES -->
<!-- ============================================================ -->
<section class="about-mvv-section">
    <div class="about-section-header" data-aos="fade-up">
        <h2>Our <span>Core Pillars</span></h2>
        <p>The values that guide every product we curate and every customer we serve.</p>
    </div>
    <div class="about-mvv-grid">
        <div class="about-mvv-card" data-aos="fade-up" data-aos-delay="0">
            <div class="about-mvv-icon"><i class="fas fa-bullseye"></i></div>
            <h3>Our Mission</h3>
            <p>To empower every woman to express her unique beauty through premium, affordable cosmetics and jewelry that enhance confidence and self-love — without compromise.</p>
        </div>
        <div class="about-mvv-card" data-aos="fade-up" data-aos-delay="100">
            <div class="about-mvv-icon"><i class="fas fa-telescope"></i></div>
            <h3>Our Vision</h3>
            <p>To become Pakistan's most trusted and loved online destination for beauty — known for authenticity, elegance, and an exceptional customer experience at every touchpoint.</p>
        </div>
        <div class="about-mvv-card" data-aos="fade-up" data-aos-delay="200">
            <div class="about-mvv-icon"><i class="fas fa-hands-holding-heart"></i></div>
            <h3>Our Values</h3>
            <p>We stand for <strong style="color:var(--primary-gold)">Quality</strong>, <strong style="color:var(--primary-gold)">Trust</strong>, <strong style="color:var(--primary-gold)">Elegance</strong>, and <strong style="color:var(--primary-gold)">Customer Happiness</strong>. Every product is a promise — a promise of excellence we deliver on every single order.</p>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- BRAND TIMELINE -->
<!-- ============================================================ -->
<section class="about-timeline-section">
    <div class="about-section-header" data-aos="fade-up">
        <h2>Our <span>Journey</span></h2>
        <p>Six years of growth, learning, and bringing beauty to thousands of homes across Pakistan.</p>
    </div>

    <div class="about-timeline">
        <div class="tl-item" data-aos="fade-up" data-aos-delay="0">
            <div class="tl-content-left">
                <div class="tl-year">2020</div>
                <div class="tl-title">The Beginning</div>
                <div class="tl-desc">Jenny's launches as a home-based cosmetics service in Karachi, serving friends & family with handpicked beauty products.</div>
            </div>
            <div class="tl-dot">2020</div>
            <div class="tl-empty"></div>
        </div>
        <div class="tl-item" data-aos="fade-up" data-aos-delay="60">
            <div class="tl-empty"></div>
            <div class="tl-dot">2021</div>
            <div class="tl-content-right">
                <div class="tl-year">2021</div>
                <div class="tl-title">Going Digital</div>
                <div class="tl-desc">Launched the first official e-commerce website, expanding reach across Pakistan with nationwide shipping.</div>
            </div>
        </div>
        <div class="tl-item" data-aos="fade-up" data-aos-delay="120">
            <div class="tl-content-left">
                <div class="tl-year">2022</div>
                <div class="tl-title">Jewelry Line Added</div>
                <div class="tl-desc">Introduced the Imitation Jewelry collection — premium-quality pieces that look indistinguishable from real gold.</div>
            </div>
            <div class="tl-dot">2022</div>
            <div class="tl-empty"></div>
        </div>
        <div class="tl-item" data-aos="fade-up" data-aos-delay="180">
            <div class="tl-empty"></div>
            <div class="tl-dot">2023</div>
            <div class="tl-content-right">
                <div class="tl-year">2023</div>
                <div class="tl-title">5,000+ Customers</div>
                <div class="tl-desc">Crossed the 5,000 happy customer milestone and received recognition as one of Karachi's top beauty e-tailers.</div>
            </div>
        </div>
        <div class="tl-item" data-aos="fade-up" data-aos-delay="240">
            <div class="tl-content-left">
                <div class="tl-year">2024</div>
                <div class="tl-title">National Expansion</div>
                <div class="tl-desc">Expanded delivery to 50+ cities across Pakistan with same-day dispatch from our Karachi fulfilment centre.</div>
            </div>
            <div class="tl-dot">2024</div>
            <div class="tl-empty"></div>
        </div>
        <div class="tl-item" data-aos="fade-up" data-aos-delay="300">
            <div class="tl-empty"></div>
            <div class="tl-dot">2026</div>
            <div class="tl-content-right">
                <div class="tl-year">2026</div>
                <div class="tl-title">15,000+ Happy Customers</div>
                <div class="tl-desc">Today we serve 15,000+ loyal customers with 500+ premium products — and we're just getting started.</div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- TEAM SECTION -->
<!-- ============================================================ -->
<section class="about-team-section">
    <div class="about-section-header" data-aos="fade-up">
        <h2>Meet Our <span>Team</span></h2>
        <p>The passionate people behind Jenny's Cosmetics & Jewelry.</p>
    </div>
    <div class="about-team-grid">
        <div class="team-card-premium" data-aos="fade-up" data-aos-delay="0">
            <div class="team-avatar-wrap" style="background:linear-gradient(160deg,#2d1a00,#6b3a00);">
                <div class="team-avatar-gradient avatar-jenny">J</div>
            </div>
            <div class="team-card-body">
                <div class="team-card-name">Jenny</div>
                <div class="team-card-role">Founder &amp; CEO</div>
                <div class="team-card-desc">The visionary heart of Jenny's — a certified makeup artist with a passion for making every woman feel beautiful. She curates every collection personally.</div>
                <div class="team-card-socials">
                    <a href="https://instagram.com" target="_blank" aria-label="Jenny on Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com" target="_blank" aria-label="Jenny on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="team-card-premium" data-aos="fade-up" data-aos-delay="100">
            <div class="team-avatar-wrap" style="background:linear-gradient(160deg,#1a0030,#4a0080);">
                <div class="team-avatar-gradient avatar-fatima">F</div>
            </div>
            <div class="team-card-body">
                <div class="team-card-name">Fatima</div>
                <div class="team-card-role">Product Curator</div>
                <div class="team-card-desc">Fatima handpicks every cosmetic and jewelry item to ensure the highest quality standards. If it doesn't meet her bar, it never reaches our shelves.</div>
                <div class="team-card-socials">
                    <a href="https://instagram.com" target="_blank" aria-label="Fatima on Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com" target="_blank" aria-label="Fatima on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="team-card-premium" data-aos="fade-up" data-aos-delay="200">
            <div class="team-avatar-wrap" style="background:linear-gradient(160deg,#001a14,#004d38);">
                <div class="team-avatar-gradient avatar-sana">S</div>
            </div>
            <div class="team-card-body">
                <div class="team-card-name">Sana</div>
                <div class="team-card-role">Customer Happiness</div>
                <div class="team-card-desc">Sana ensures every customer leaves with a smile — handling support, returns, and everything in between. Your satisfaction is her personal mission.</div>
                <div class="team-card-socials">
                    <a href="https://instagram.com" target="_blank" aria-label="Sana on Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com" target="_blank" aria-label="Sana on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- WHY CHOOSE US -->
<!-- ============================================================ -->
<section class="about-trust-section">
    <div class="about-section-header" data-aos="fade-up">
        <h2>Why Choose <span>Jenny's</span></h2>
    </div>
    <div class="about-trust-grid">
        <div class="about-trust-card" data-aos="fade-up" data-aos-delay="0">
            <div class="about-trust-icon"><i class="fas fa-leaf"></i></div>
            <h4>Cruelty Free</h4>
            <p>Every product we sell is 100% cruelty-free. We never test on animals and partner only with ethical manufacturers.</p>
        </div>
        <div class="about-trust-card" data-aos="fade-up" data-aos-delay="60">
            <div class="about-trust-icon"><i class="fas fa-check-circle"></i></div>
            <h4>100% Authentic</h4>
            <p>All products are sourced directly from certified manufacturers. Every item is quality-verified before dispatch.</p>
        </div>
        <div class="about-trust-card" data-aos="fade-up" data-aos-delay="120">
            <div class="about-trust-icon"><i class="fas fa-truck-fast"></i></div>
            <h4>Fast Nationwide Delivery</h4>
            <p>Orders are dispatched within 24 hours. Free delivery on all orders over Rs. 1,999 — nationwide.</p>
        </div>
        <div class="about-trust-card" data-aos="fade-up" data-aos-delay="180">
            <div class="about-trust-icon"><i class="fas fa-rotate-left"></i></div>
            <h4>Easy 7-Day Returns</h4>
            <p>Not satisfied? We offer a hassle-free 7-day return and exchange policy — no questions asked.</p>
        </div>
        <div class="about-trust-card" data-aos="fade-up" data-aos-delay="240">
            <div class="about-trust-icon"><i class="fas fa-headset"></i></div>
            <h4>24/7 Customer Support</h4>
            <p>Our dedicated support team is always available to help you with orders, tracking, and product queries.</p>
        </div>
        <div class="about-trust-card" data-aos="fade-up" data-aos-delay="300">
            <div class="about-trust-icon"><i class="fas fa-shield-halved"></i></div>
            <h4>Secure Payments</h4>
            <p>Shop confidently with our encrypted, secure payment gateway. Multiple payment options including COD.</p>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CTA SECTION -->
<!-- ============================================================ -->
<section class="about-cta-section">
    <div class="about-cta-inner" data-aos="fade-up">
        <h2>Ready to <span>Elevate</span> Your Beauty?</h2>
        <p>Explore our curated collection of premium cosmetics and imitation jewelry — crafted for the modern Pakistani woman.</p>
        <div class="about-cta-buttons">
            <a href="products.php" class="btn-cta-gold"><i class="fas fa-shopping-bag" style="margin-right:8px;"></i>Shop All Products</a>
            <a href="contact.php" class="btn-cta-outline"><i class="fas fa-envelope" style="margin-right:8px;"></i>Contact Us</a>
        </div>
    </div>
</section>

<!-- CART SIDEBAR -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h2>Your Cart</h2>
        <span class="cart-close" onclick="closeCart()"><i class="fas fa-times"></i></span>
    </div>
    <div class="cart-items-container" id="cartItemsContainer">
        <div class="empty-cart-msg" id="emptyCartMsg">
            <i class="fas fa-shopping-bag" style="font-size:3rem;color:#ddd;margin-bottom:10px;display:block;"></i>
            Your cart is empty.
        </div>
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <span>Total:</span>
            <span id="cartTotalPrice">Rs. 0</span>
        </div>
        <button class="checkout-btn" onclick="location.href='checkout.php'">Proceed to Checkout</button>
    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script>
// Parallax hero BG
window.addEventListener('load', function() {
    document.getElementById('aboutHeroBg').classList.add('loaded');
});

// Scroll-triggered stat counters
(function() {
    const counters = document.querySelectorAll('.about-stat-num[data-count]');
    let animated = false;
    function runCounters() {
        if (animated) return;
        const section = document.querySelector('.about-hero-statsbar');
        if (!section) return;
        const rect = section.getBoundingClientRect();
        if (rect.top < window.innerHeight) {
            animated = true;
            counters.forEach(el => {
                const target = parseInt(el.dataset.count);
                let start = 0;
                const duration = 2200;
                const step = target / (duration / 16);
                const timer = setInterval(() => {
                    start += step;
                    if (start >= target) { el.textContent = target.toLocaleString(); clearInterval(timer); return; }
                    el.textContent = Math.floor(start).toLocaleString();
                }, 16);
            });
        }
    }
    window.addEventListener('scroll', runCounters, { passive: true });
    runCounters();
})();
</script>
</body>
</html>