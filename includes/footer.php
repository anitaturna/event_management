<style>
/* =============================================
   FOOTER — Neuromarketing Edition
   Psychology:
   — Newsletter = reciprocity (free value offer)
   — Social icons = tribal belonging
   — Gold accents = luxury continuity from index
   — Trust badges at bottom = last-impression anchoring
   — Bengali tagline = cultural identity resonance
============================================= */

.site-footer {
    background: #0a0a0f;
    color: rgba(255,255,255,0.55);
    font-family: 'Outfit', sans-serif;
    border-top: 1px solid rgba(201,168,76,0.15);
    margin-top: 0;
}

/* Gold top accent line — visual continuity with navbar */
.site-footer::before {
    content: '';
    display: block;
    height: 1px;
    background: linear-gradient(90deg, transparent, #c9a84c, transparent);
    opacity: 0.6;
}

.footer-main {
    max-width: 1240px;
    margin: 0 auto;
    padding: 70px 24px 50px;
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1.4fr;
    gap: 50px;
}

@media (max-width: 1024px) {
    .footer-main { grid-template-columns: 1fr 1fr; gap: 40px; }
}
@media (max-width: 600px) {
    .footer-main { grid-template-columns: 1fr; gap: 36px; padding: 50px 20px 40px; }
}

/* ===== BRAND COL ===== */
.footer-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    margin-bottom: 20px;
}
.footer-logo-icon {
    width: 38px; height: 38px;
    border-radius: 8px;
    background: linear-gradient(135deg, #c9a84c, #8a6d2f);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px; font-weight: 700;
    color: #0a0a0f;
    flex-shrink: 0;
}
.footer-logo-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px; font-weight: 700;
    color: #fff; letter-spacing: 0.5px;
}
.footer-logo-text span { color: #c9a84c; }

.footer-brand-desc {
    font-size: 13.5px;
    line-height: 1.85;
    color: rgba(255,255,255,0.45);
    margin-bottom: 10px;
    font-weight: 300;
}
.footer-brand-bangla {
    font-size: 13px;
    color: rgba(201,168,76,0.6);
    font-weight: 300;
    font-style: italic;
    line-height: 1.7;
    margin-bottom: 24px;
    padding-left: 12px;
    border-left: 2px solid rgba(201,168,76,0.25);
}

/* Social icons — tribal belonging signal */
.footer-socials {
    display: flex;
    gap: 10px;
}
.social-btn {
    width: 38px; height: 38px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.03);
    color: rgba(255,255,255,0.45);
    font-size: 17px;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    transition: all 0.25s ease;
}
.social-btn:hover {
    border-color: rgba(201,168,76,0.4);
    color: #c9a84c;
    background: rgba(201,168,76,0.06);
    transform: translateY(-2px);
}

/* ===== COLUMN HEADERS ===== */
.footer-col-title {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.9);
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.footer-col-title::before {
    content: '';
    display: inline-block;
    width: 14px; height: 1px;
    background: #c9a84c;
    flex-shrink: 0;
}

/* ===== NAV LINKS ===== */
.footer-nav {
    list-style: none;
    padding: 0;
}
.footer-nav li { margin-bottom: 12px; }
.footer-nav a {
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}
.footer-nav a i { font-size: 14px; opacity: 0.5; transition: opacity 0.2s; }
.footer-nav a:hover { color: rgba(255,255,255,0.9); padding-left: 4px; }
.footer-nav a:hover i { opacity: 1; color: #c9a84c; }

/* ===== CONTACT ===== */
.contact-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
    font-size: 13.5px;
    font-weight: 300;
    color: rgba(255,255,255,0.45);
    line-height: 1.65;
}
.contact-icon {
    width: 32px; height: 32px;
    border-radius: 7px;
    background: rgba(201,168,76,0.08);
    border: 1px solid rgba(201,168,76,0.15);
    display: flex; align-items: center; justify-content: center;
    color: #c9a84c;
    font-size: 15px;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ===== NEWSLETTER — Reciprocity trigger ===== */
.newsletter-desc {
    font-size: 13px;
    color: rgba(255,255,255,0.4);
    line-height: 1.7;
    font-weight: 300;
    margin-bottom: 16px;
}
.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.newsletter-input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    padding: 11px 16px;
    font-size: 13px;
    font-family: 'Outfit', sans-serif;
    color: white;
    outline: none;
    transition: border-color 0.2s;
}
.newsletter-input::placeholder { color: rgba(255,255,255,0.25); }
.newsletter-input:focus { border-color: rgba(201,168,76,0.4); }
.newsletter-btn {
    width: 100%;
    background: #c9a84c;
    color: #0a0a0f;
    border: none;
    padding: 11px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Outfit', sans-serif;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.25s;
    letter-spacing: 0.3px;
}
.newsletter-btn:hover {
    background: #f0d48a;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(201,168,76,0.25);
}
.newsletter-note {
    font-size: 11px;
    color: rgba(255,255,255,0.2);
    text-align: center;
}

/* ===== DIVIDER ===== */
.footer-divider {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
    border: none;
    border-top: 1px solid rgba(255,255,255,0.05);
}

/* ===== TRUST BAR — Last-impression anchoring ===== */
.footer-trust {
    max-width: 1240px;
    margin: 0 auto;
    padding: 22px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.trust-badges {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.trust-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 500;
    color: rgba(255,255,255,0.35);
    letter-spacing: 0.2px;
}
.trust-badge i { font-size: 14px; color: rgba(201,168,76,0.6); }

/* ===== BOTTOM BAR ===== */
.footer-bottom {
    background: rgba(0,0,0,0.3);
    border-top: 1px solid rgba(255,255,255,0.04);
    padding: 18px 24px;
    text-align: center;
}
.footer-bottom p {
    font-size: 12px;
    color: rgba(255,255,255,0.2);
    letter-spacing: 0.4px;
    line-height: 1.6;
}
.footer-bottom strong { color: rgba(255,255,255,0.45); font-weight: 600; }
.footer-bottom a {
    color: rgba(201,168,76,0.5);
    text-decoration: none;
    transition: color 0.2s;
}
.footer-bottom a:hover { color: #c9a84c; }
</style>

<footer class="site-footer">
    <div class="footer-main">

        <!-- COL 1: BRAND -->
        <div>
            <a href="<?php echo SITEURL; ?>" class="footer-logo">
                <div class="footer-logo-icon">A</div>
                <div class="footer-logo-text">AT Royal <span>Events</span></div>
            </a>
            <p class="footer-brand-desc">
                Bangladesh's most trusted event partner — turning your most important occasions into lifelong memories. Every detail, perfectly executed.
            </p>
            <p class="footer-brand-bangla">
                আপনার জীবনের শ্রেষ্ঠ মুহূর্তগুলোকে রাজকীয় আভিজাত্যে সাজিয়ে তোলাই আমাদের লক্ষ্য।
            </p>
            <div class="footer-socials">
                <a href="#" class="social-btn" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                <a href="#" class="social-btn" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                <a href="#" class="social-btn" aria-label="YouTube"><i class="ti ti-brand-youtube"></i></a>
                <a href="#" class="social-btn" aria-label="WhatsApp"><i class="ti ti-brand-whatsapp"></i></a>
            </div>
        </div>

        <!-- COL 2: EXPLORE -->
        <div>
            <div class="footer-col-title">Explore</div>
            <ul class="footer-nav">
                <li><a href="<?php echo SITEURL; ?>index.php"><i class="ti ti-home"></i> Home</a></li>
                <li><a href="<?php echo SITEURL; ?>views/user/events_list.php"><i class="ti ti-calendar-event"></i> All Events</a></li>
                <li><a href="<?php echo SITEURL; ?>views/user/events_list.php?category=Wedding"><i class="ti ti-heart"></i> Weddings</a></li>
                <li><a href="<?php echo SITEURL; ?>views/user/events_list.php?category=Corporate"><i class="ti ti-briefcase"></i> Corporate Galas</a></li>
                <li><a href="<?php echo SITEURL; ?>views/user/events_list.php?category=Birthday"><i class="ti ti-confetti"></i> Private Parties</a></li>
                <li><a href="<?php echo SITEURL; ?>views/user/my_bookings.php"><i class="ti ti-receipt"></i> My Bookings</a></li>
            </ul>
        </div>

        <!-- COL 3: CONTACT -->
        <div>
            <div class="footer-col-title">Contact Us</div>
            <div class="contact-row">
                <div class="contact-icon"><i class="ti ti-map-pin"></i></div>
                <span>Jigatola, Dhanmondi<br>Dhaka, Bangladesh</span>
            </div>
            <div class="contact-row">
                <div class="contact-icon"><i class="ti ti-phone"></i></div>
                <span>+880 1234 567 890</span>
            </div>
            <div class="contact-row">
                <div class="contact-icon"><i class="ti ti-mail"></i></div>
                <span>info@atroyalevents.com</span>
            </div>
            <div class="contact-row">
                <div class="contact-icon"><i class="ti ti-clock"></i></div>
                <span>Sat – Thu &nbsp; 9am – 8pm</span>
            </div>
        </div>

        <!-- COL 4: NEWSLETTER (Reciprocity trigger) -->
        <div>
            <div class="footer-col-title">Stay Updated</div>
            <p class="newsletter-desc">
                Get exclusive early-access deals, seasonal offers, and event inspiration — free, straight to your inbox.
            </p>
            <div class="newsletter-form">
                <input type="email" class="newsletter-input" placeholder="Your email address" />
                <button class="newsletter-btn" onclick="handleNewsletter(this)">
                    <i class="ti ti-send"></i> Subscribe — It's Free
                </button>
            </div>
            <p class="newsletter-note" style="margin-top:10px;">
                <i class="ti ti-shield-check" style="vertical-align:-1px;"></i>
                No spam. Unsubscribe anytime.
            </p>
        </div>

    </div>

    <hr class="footer-divider">

    <!-- TRUST BADGES ROW — Last-impression authority anchoring -->
    <div class="footer-trust">
        <div class="trust-badges">
            <span class="trust-badge"><i class="ti ti-shield-check"></i> 100% Secure Booking</span>
            <span class="trust-badge"><i class="ti ti-rosette-discount-check"></i> Verified Vendors Only</span>
            <span class="trust-badge"><i class="ti ti-users"></i> 500+ Happy Clients</span>
            <span class="trust-badge"><i class="ti ti-star-filled"></i> 4.9 Average Rating</span>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <a href="#" style="color:rgba(255,255,255,0.25);font-size:12px;text-decoration:none;font-family:'Outfit',sans-serif;" onmouseover="this.style.color='rgba(201,168,76,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Privacy Policy</a>
            <span style="color:rgba(255,255,255,0.1);font-size:12px;">·</span>
            <a href="#" style="color:rgba(255,255,255,0.25);font-size:12px;text-decoration:none;font-family:'Outfit',sans-serif;" onmouseover="this.style.color='rgba(201,168,76,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Terms & Conditions</a>
        </div>
    </div>

    <!-- BOTTOM COPYRIGHT -->
    <div class="footer-bottom">
        <p>
            &copy; <?php echo date('Y'); ?> <strong>AT Royal Events</strong> &nbsp;·&nbsp;
            Jigatola, Dhanmondi, Dhaka &nbsp;·&nbsp;
            Crafted with <i class="ti ti-heart-filled" style="color:#c9a84c;font-size:11px;vertical-align:-1px;"></i> for unforgettable memories
        </p>
    </div>
</footer>

<script>
function handleNewsletter(btn) {
    const input = btn.previousElementSibling;
    const email = input.value.trim();
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRe.test(email)) {
        input.style.borderColor = 'rgba(248,113,113,0.6)';
        input.focus();
        return;
    }
    input.style.borderColor = 'rgba(52,211,153,0.5)';
    btn.innerHTML = '<i class="ti ti-check"></i> You\'re subscribed!';
    btn.style.background = '#1a7a4a';
    btn.style.color = 'white';
    btn.disabled = true;
    input.disabled = true;
}
</script>

<script src="<?php echo SITEURL; ?>assets/js/script.js"></script>
</body>
</html>