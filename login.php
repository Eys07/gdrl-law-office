<?php
session_start();
include 'config.php'; // Include database configuration

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Prepare and execute query to check credentials using username only
    $stmt = $conn->prepare("SELECT user_id, full_name, role, username, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            
            $stmt->close();
            $conn->close();
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid password. Please try again.';
        }
    } else {
        $error = 'Invalid username. Please try again.';
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>GDRL Law Office | Secure Login Portal</title>
    <!-- Bootstrap 5 CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        /* MAIN CONTAINER: Two-column layout for logo + login form */
        .login-wrapper {
            max-width: 1100px;
            width: 100%;
            background: #ffffff;
            border-radius: 56px;
            box-shadow: 0 35px 60px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(212, 175, 55, 0.25);
            transition: all 0.4s ease;
            z-index: 2;
            animation: fadeSlideUp 0.7s ease-out;
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

        /* Left Panel: GDRL Branding Area (Gold & White Elegance) */
        .brand-panel {
            background: linear-gradient(145deg, #fefcf8, #ffffff);
            padding: 3rem 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-right: 1px solid rgba(212, 175, 55, 0.25);
        }

        /* Large Gold Circle Emblem for Left Panel */
        .brand-logo-large {
            margin-bottom: 1.8rem;
        }
        .gold-circle-large {
            width: 120px;
            height: 120px;
            background: #ffffff;
            border: 3px solid #d4af37;
            border-radius: 50%;
            position: relative;
            margin: 0 auto;
            box-shadow: 0 0 0 5px rgba(212, 175, 55, 0.15), inset 0 0 0 2px rgba(212, 175, 55, 0.1);
        }
        .gold-circle-large::before,
        .gold-circle-large::after {
            content: "";
            position: absolute;
            background: #d4af37;
        }
        .gold-circle-large::before {
            width: 3px;
            height: 68%;
            left: 50%;
            top: 16%;
            transform: translateX(-50%);
        }
        .gold-circle-large::after {
            width: 68%;
            height: 3px;
            top: 50%;
            left: 16%;
            transform: translateY(-50%);
        }
        .gold-circle-large .dot-left,
        .gold-circle-large .dot-right {
            position: absolute;
            width: 11px;
            height: 11px;
            background: #d4af37;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        .gold-circle-large .dot-left { left: 18px; }
        .gold-circle-large .dot-right { right: 18px; }

        .brand-title {
            font-size: 3.2rem;
            font-weight: 800;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #c9a227, #d4af37, #f3d382, #c9a227);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.1;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            font-weight: 600;
            color: #b8860b;
            letter-spacing: 3px;
            text-transform: uppercase;
            border-top: 2px solid rgba(212, 175, 55, 0.4);
            display: inline-block;
            padding-top: 10px;
            font-family: 'Montserrat', sans-serif;
        }

        .brand-tagline {
            margin-top: 2rem;
            font-style: italic;
            font-size: 1.2rem;
            color: #a67c1e;
            background: rgba(212, 175, 55, 0.08);
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
        }

        /* Right Panel: Login Form */
        .login-panel {
            padding: 2.8rem 2.5rem;
            background: #ffffff;
        }

        h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.7rem;
            color: #b8860b;
            margin-bottom: 0.25rem;
        }

        .subtitle {
            color: #6b5a3a;
            font-size: 0.85rem;
            margin-bottom: 1.8rem;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            display: inline-block;
            padding-bottom: 5px;
        }

        /* Form Inputs with Password Toggle */
        .form-floating {
            margin-bottom: 1.2rem;
            position: relative;
        }
        .form-control {
            border-radius: 40px;
            border: 1px solid rgba(212, 175, 55, 0.4);
            padding: 1rem 1.2rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            background: #fefcf8;
        }
        .form-control:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            outline: none;
        }
        .form-floating label {
            font-family: 'Montserrat', sans-serif;
            color: #a08a5c;
            font-weight: 500;
        }

        /* Password toggle button styling */
        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #b8860b;
            cursor: pointer;
            font-size: 1.1rem;
            z-index: 10;
            transition: all 0.2s ease;
            padding: 5px;
        }
        .password-toggle:hover {
            color: #d4af37;
        }
        .password-toggle:focus {
            outline: none;
        }

        .login-btn {
            width: 100%;
            background: #ffffff;
            border: 2px solid #d4af37;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            color: #b8860b;
            border-radius: 60px;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        .login-btn:hover {
            background: #fffcf5;
            border-color: #e6c458;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
        }

        .error-alert {
            background: #fff5f0;
            border-left: 4px solid #d4af37;
            color: #8b5e2e;
            font-size: 0.8rem;
            padding: 0.7rem 1rem;
            border-radius: 30px;
            margin-bottom: 1.2rem;
        }

        /* Link Container Styling - only back link remains */
        .auth-links {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            gap: 0.8rem;
        }
        .back-link {
            text-align: center;
        }
        .back-link a {
            color: #b8860b;
            text-decoration: none;
            font-size: 0.85rem;
            font-family: 'Montserrat', sans-serif;
            transition: color 0.2s ease;
        }
        .back-link a:hover {
            text-decoration: underline;
            color: #d4af37;
        }

        .demo-note {
            background: rgba(212, 175, 55, 0.08);
            border-radius: 30px;
            padding: 0.6rem 1rem;
            font-size: 0.7rem;
            text-align: center;
            margin-top: 1.2rem;
            color: #8b7652;
            font-family: 'Montserrat', sans-serif;
        }

        /* Responsive: Stack on mobile */
        @media (max-width: 768px) {
            .login-wrapper {
                border-radius: 40px;
            }
            .brand-panel {
                border-right: none;
                border-bottom: 1px solid rgba(212, 175, 55, 0.25);
                padding: 2rem 1.5rem;
            }
            .login-panel {
                padding: 2rem 1.5rem;
            }
            .brand-title {
                font-size: 2.5rem;
            }
            .gold-circle-large {
                width: 90px;
                height: 90px;
            }
            .gold-circle-large .dot-left { left: 12px; }
            .gold-circle-large .dot-right { right: 12px; }
            .brand-tagline {
                font-size: 0.9rem;
            }
            h2 {
                font-size: 1.4rem;
            }
            .auth-links {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }
            .brand-title {
                font-size: 2rem;
                letter-spacing: 2px;
            }
            .brand-subtitle {
                font-size: 0.85rem;
                letter-spacing: 2px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="row g-0">
        <!-- LEFT SIDE: GDRL Branding (Logo + Name + Tagline) -->
        <div class="col-12 col-md-5">
            <div class="brand-panel">
                <div class="brand-logo-large">
                    <div class="gold-circle-large">
                        <span class="dot-left"></span>
                        <span class="dot-right"></span>
                    </div>
                </div>
                <div class="brand-title">GDRL</div>
                <div class="brand-subtitle">LAW OFFICE</div>
                <div class="brand-tagline">
                    <i class="bi bi-gem me-1"></i> Galima³ · Dangilan · Reyes · Lopez
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Login Form -->
        <div class="col-12 col-md-7">
            <div class="login-panel">
                <div class="text-center">
                    <h2><i class="bi bi-shield-lock-fill me-2" style="font-size: 1.7rem;"></i>Secure Portal Login</h2>
                    <div class="subtitle">Authorized Personnel Only</div>
                </div>

                <?php if ($error): ?>
                    <div class="error-alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <!-- Username Field -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                        <label for="username"><i class="bi bi-person-fill me-1"></i> Username</label>
                    </div>

                    <!-- Password Field with Show/Hide Toggle -->
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="bi bi-key-fill me-1"></i> Password</label>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>

                    <button type="submit" class="login-btn">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Access Portal
                    </button>
                </form>

                <!-- Link Section: Only Return to Portal Link -->
                <div class="auth-links">
                    <div class="back-link">
                        <a href="index.php">
                            <i class="bi bi-arrow-left-circle"></i> Return to Firm Portal
                        </a>
                    </div>
                </div>

                <div class="demo-note">
                    <i class="bi bi-info-circle-fill me-1"></i> Demo Credentials:<br>
                    <strong>Attorney:</strong> attorney_gdrl / justice123 &nbsp;|&nbsp; 
                    <strong>Secretary:</strong> secretary_gdrl / faithful123 &nbsp;|&nbsp;
                    <strong>Super Admin:</strong> super_admin / admin123
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Password Show/Hide Toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle the eye icon
        if (type === 'password') {
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    });
</script>
<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>