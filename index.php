<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>GDRL Law Office | Gold & White Portal</title>
    <!-- Bootstrap 5 CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Elegant Fonts: Serif for luxury + clean sans -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cormorant Garamond', 'Georgia', serif;
            background: linear-gradient(135deg, #fdfbf7 0%, #f4f0e6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle gold dust particles on white background */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 15% 40%, rgba(212, 175, 55, 0.12) 1.5px, transparent 1.5px),
                              radial-gradient(circle at 75% 85%, rgba(212, 175, 55, 0.08) 1px, transparent 1px);
            background-size: 55px 55px, 90px 90px;
            pointer-events: none;
            z-index: 0;
        }

        /* Main Card: PURE WHITE + GOLD ACCENTS */
        .main-card {
            max-width: 1200px;
            width: 100%;
            background: #ffffff;
            border-radius: 64px;
            box-shadow: 0 35px 60px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(212, 175, 55, 0.35), 0 0 0 8px rgba(255, 255, 255, 0.8);
            transition: all 0.4s ease;
            z-index: 2;
            animation: fadeSlideUp 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            overflow: hidden;
        }

        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(35px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-content {
            padding: 3.5rem 3rem;
            background: #ffffff;
        }

        /* Logo Row: Enhanced size and spacing */
        .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        /* LARGER Gold Circle Emblem - refined and majestic */
        .gold-circle {
            width: 110px;
            height: 110px;
            background: #ffffff;
            border: 3px solid #d4af37;
            border-radius: 50%;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.2), inset 0 0 0 2px rgba(212, 175, 55, 0.15);
            flex-shrink: 0;
        }

        .gold-circle::before,
        .gold-circle::after {
            content: "";
            position: absolute;
            background: #d4af37;
        }
        .gold-circle::before {
            width: 3px;
            height: 68%;
            left: 50%;
            top: 16%;
            transform: translateX(-50%);
            box-shadow: 0 0 3px #e6c458;
        }
        .gold-circle::after {
            width: 68%;
            height: 3px;
            top: 50%;
            left: 16%;
            transform: translateY(-50%);
            box-shadow: 0 0 3px #e6c458;
        }
        .gold-circle .dot-left,
        .gold-circle .dot-right {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #d4af37;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 4px #e6c458;
        }
        .gold-circle .dot-left { left: 16px; }
        .gold-circle .dot-right { right: 16px; }

        /* LARGER Brand Text: GDRL with grand scale, Gold Gradient */
        .brand-text-group {
            text-align: center;
        }
        .gdrl-text {
            font-size: 3.8rem;
            font-weight: 800;
            letter-spacing: 5px;
            background: linear-gradient(135deg, #c9a227, #d4af37, #f3d382, #c9a227);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.05;
            margin: 0;
            text-shadow: 0 2px 5px rgba(0,0,0,0.02);
            font-family: 'Montserrat', sans-serif;
        }
        .law-office-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: #b8860b;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            border-top: 2px solid rgba(212, 175, 55, 0.5);
            display: inline-block;
            padding-top: 10px;
            margin-top: 8px;
            font-family: 'Montserrat', sans-serif;
        }

        /* LARGER TAGLINE - CENTERED, BOLD, COMMANDING */
        .tagline-gold {
            margin: 1.8rem auto 1.4rem auto;
            font-style: italic;
            font-size: 1.6rem;
            font-weight: 700;
            color: #b8860b;
            text-align: center;
            letter-spacing: 1px;
            background: linear-gradient(115deg, #fef8e7, #fffcf2);
            display: inline-block;
            width: auto;
            padding: 0.8rem 2.8rem;
            border-radius: 80px;
            backdrop-filter: blur(2px);
            border: 1px solid rgba(212, 175, 55, 0.5);
            box-shadow: 0 6px 18px rgba(212, 175, 55, 0.12);
            font-family: 'Cormorant Garamond', serif;
            line-height: 1.3;
        }

        /* ENHANCED MISSION & VISION CARDS - LARGER, MORE READABLE, BOLD */
        .mission-vision-grid {
            margin: 2.5rem 0 2rem 0;
        }
        .mv-card-enhanced {
            background: linear-gradient(145deg, #ffffff, #fefaf2);
            padding: 2.2rem 2rem;
            border-radius: 42px;
            border: 1px solid rgba(212, 175, 55, 0.4);
            box-shadow: 0 18px 32px rgba(0, 0, 0, 0.04), 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
            text-align: center;
        }
        .mv-card-enhanced:hover {
            transform: translateY(-6px);
            border-color: #d4af37;
            box-shadow: 0 22px 40px rgba(212, 175, 55, 0.15);
            background: #ffffff;
        }
        .mv-icon-large {
            font-size: 3.4rem;
            color: #d4af37;
            margin-bottom: 1.2rem;
            display: inline-block;
        }
        .mv-title-enhanced {
            font-weight: 800;
            font-size: 2rem;
            color: #b8860b;
            letter-spacing: 2px;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 1.1rem;
            text-transform: uppercase;
            background: linear-gradient(135deg, #b8860b, #d4af37);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .mv-text-enhanced {
            font-size: 1.18rem;
            color: #2c2b28;
            line-height: 1.55;
            font-weight: 500;
            font-family: 'Cormorant Garamond', serif;
            max-width: 92%;
            margin: 0 auto;
        }

        /* Responsive design for better scaling */
        @media (max-width: 768px) {
            .card-content {
                padding: 2.2rem 1.8rem;
            }
            .gdrl-text {
                font-size: 2.8rem;
                letter-spacing: 3px;
            }
            .law-office-text {
                font-size: 1.1rem;
                letter-spacing: 2.5px;
            }
            .tagline-gold {
                font-size: 1.3rem;
                padding: 0.6rem 1.8rem;
                margin: 1.2rem auto;
            }
            .gold-circle {
                width: 85px;
                height: 85px;
            }
            .gold-circle .dot-left, .gold-circle .dot-right {
                width: 7px;
                height: 7px;
            }
            .gold-circle .dot-left { left: 12px; }
            .gold-circle .dot-right { right: 12px; }
            .mv-title-enhanced {
                font-size: 1.7rem;
            }
            .mv-text-enhanced {
                font-size: 1.05rem;
                max-width: 100%;
            }
            .mv-card-enhanced {
                padding: 1.6rem 1.3rem;
            }
            .mv-icon-large {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }
            .main-card {
                border-radius: 48px;
            }
            .card-content {
                padding: 1.8rem 1.2rem;
            }
            .gdrl-text {
                font-size: 2.2rem;
                letter-spacing: 2px;
            }
            .law-office-text {
                font-size: 0.9rem;
                letter-spacing: 2px;
                padding-top: 6px;
            }
            .tagline-gold {
                font-size: 1.1rem;
                padding: 0.5rem 1.2rem;
                letter-spacing: 0.5px;
            }
            .gold-circle {
                width: 70px;
                height: 70px;
            }
            .gold-circle .dot-left { left: 9px; }
            .gold-circle .dot-right { right: 9px; }
            .gold-circle .dot-left, .gold-circle .dot-right {
                width: 6px;
                height: 6px;
            }
            .mv-title-enhanced {
                font-size: 1.4rem;
            }
            .mv-text-enhanced {
                font-size: 0.95rem;
            }
            .login-gold-btn {
                padding: 0.7rem 1.8rem;
                font-size: 0.95rem;
            }
        }

        /* Role Cards (if needed, kept subtle) */
        .role-card {
            background: #fefcf8;
            padding: 1.4rem 1rem;
            border-radius: 32px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.02);
        }
        .role-card:hover {
            transform: translateY(-6px);
            background: #ffffff;
            border-color: #d4af37;
            box-shadow: 0 12px 24px rgba(212, 175, 55, 0.12);
        }
        .role-icon {
            font-size: 2.3rem;
            margin-bottom: 12px;
            color: #d4af37;
        }
        .role-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #9e7b2f;
            letter-spacing: 0.5px;
            font-family: 'Montserrat', sans-serif;
        }
        .role-desc {
            font-size: 0.75rem;
            color: #5c4a28;
            margin-top: 8px;
            font-weight: 500;
        }

        /* LOGIN BUTTON: Gold & White Theme, enhanced for elegance */
        .login-gold-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            background: #ffffff;
            border: 2px solid #d4af37;
            padding: 1rem 3rem;
            font-size: 1.25rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            color: #b8860b;
            text-decoration: none;
            border-radius: 80px;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .login-gold-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.18), transparent);
            transition: left 0.5s ease;
            z-index: -1;
        }
        .login-gold-btn:hover::before {
            left: 100%;
        }
        .login-gold-btn:hover {
            background: #fffcf5;
            color: #a67c1e;
            border-color: #e6c458;
            transform: scale(1.02);
            box-shadow: 0 12px 28px rgba(212, 175, 55, 0.28);
        }
        .login-gold-btn:active {
            transform: scale(0.98);
        }

        .gold-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4af37, #f3d382, #d4af37, transparent);
            width: 60%;
            margin: 2rem auto 1.2rem auto;
        }

        .footer-note {
            text-align: center;
            font-size: 0.75rem;
            color: #b38f40;
            margin-top: 1rem;
            letter-spacing: 0.8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            background: rgba(212, 175, 55, 0.06);
            display: inline-block;
            width: auto;
            padding: 0.4rem 1.5rem;
            border-radius: 60px;
        }

        a:hover {
            text-decoration: none;
        }

        /* Fix for missing col class typo in original (coal-12) */
        .row.mission-vision-grid .col-md-6 {
            width: 50%;
        }
        @media (max-width: 768px) {
            .row.mission-vision-grid .col-md-6 {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="main-card">
    <div class="card-content">
        <!-- Logo Row: Enhanced larger emblem + bigger GDRL and Law Office text -->
        <div class="logo-row">
            <div class="gold-circle">
                <span class="dot-left"></span>
                <span class="dot-right"></span>
            </div>
            <div class="brand-text-group">
                <div class="gdrl-text">GDRL</div>
                <div class="law-office-text">Law Office</div>
            </div>
        </div>

        <!-- LARGER TAGLINE - CENTERED, BOLD, ELEVATED PRESENCE -->
        <div class="text-center">
            <div class="tagline-gold">
                “Galima³ · Dangilan · Reyes · Lopez”
            </div>
        </div>

        <!-- ENHANCED MISSION & VISION SECTION - LARGER, MORE READABLE, BOLD & CENTERED -->
        <div class="row g-6 mission-vision-grid">
            <div class="col-12 col-md-6">
                <div class="mv-card-enhanced">
                    <div class="mv-icon-large">
                        <i class="bi bi-scale"></i>
                    </div>
                    <div class="mv-title-enhanced">MISSION</div>
                    <div class="mv-text-enhanced">
                        A Law office where faithfulness is the standard — delivering unwavering integrity, strategic excellence, and compassionate counsel.
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="mv-card-enhanced">
                    <div class="mv-title-enhanced">VISION</div>
                    <div class="mv-text-enhanced">
                        Justice above all: truthfulness in pleading, righteousness in service, and efficiency in action. Forging a legacy of legal brilliance.
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Login Button: Gold & White Elegance with refined spacing -->
        <div class="text-center mt-4 mb-2">
            <a href="login.php" class="login-gold-btn">
                <span>✦ LOGIN TO PORTAL ✦</span>
                <i class="bi bi-arrow-right-circle" style="font-size: 1.3rem;"></i>
            </a>
        </div>

        <div class="gold-divider"></div>
        <div class="text-center">
            <div class="footer-note">
                <i class="bi bi-shield-lock-fill me-1"></i> Internal Firm Portal · Authorized Personnel Only (Attorney & Secretary)
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>