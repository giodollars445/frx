<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Bella Vista Hair Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 30%, #f3e8ff 70%, #e0e7ff 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #374151;
            overflow-x: hidden;
            padding: 1rem;
        }

        /* Background Animation */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ec4899' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(236, 72, 153, 0.1);
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 
                0 25px 50px rgba(236, 72, 153, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.5), transparent);
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 35px 70px rgba(236, 72, 153, 0.15),
                0 0 0 1px rgba(236, 72, 153, 0.1);
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .logo i {
            font-size: 2.5rem;
            color: #ec4899;
            filter: drop-shadow(0 0 10px rgba(236, 72, 153, 0.3));
        }

        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ec4899, #be185d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #ec4899;
            font-size: 1rem;
            z-index: 2;
        }

        input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            color: #374151;
            font-size: 1rem;
            font-weight: 400;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        input:focus {
            outline: none;
            border-color: #ec4899;
            background: rgba(255, 255, 255, 1);
            box-shadow: 
                0 0 0 4px rgba(236, 72, 153, 0.1),
                0 8px 25px rgba(236, 72, 153, 0.1);
            transform: translateY(-1px);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1.2rem 2rem;
            background: linear-gradient(135deg, #ec4899, #be185d);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 1rem;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(236, 72, 153, 0.4);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(236, 72, 153, 0.1);
        }

        .back-link a {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-link a:hover {
            color: #ec4899;
            transform: translateX(-5px);
        }

        /* Security Badge */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            padding: 0.8rem;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 8px;
            color: #059669;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .security-badge i {
            font-size: 1rem;
        }

        /* Error Messages */
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }

        /* Loading Animation */
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(236, 72, 153, 0.3);
            border-top: 3px solid #ec4899;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
            }
            
            .login-container {
                padding: 2rem 1.5rem;
                max-width: 100%;
            }

            .logo h1 {
                font-size: 1.6rem;
            }

            .logo i {
                font-size: 2.2rem;
            }

            .login-title {
                font-size: 1.3rem;
            }

            input {
                padding: 0.9rem 0.9rem 0.9rem 2.8rem;
                font-size: 0.95rem;
            }

            .submit-btn {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem 1rem;
                border-radius: 20px;
            }

            .logo {
                flex-direction: column;
                gap: 0.5rem;
            }

            .logo h1 {
                font-size: 1.4rem;
            }

            .logo i {
                font-size: 2rem;
            }

            .login-title {
                font-size: 1.2rem;
            }

            .login-subtitle {
                font-size: 0.9rem;
            }

            input {
                padding: 0.8rem 0.8rem 0.8rem 2.5rem;
                font-size: 0.9rem;
            }

            .submit-btn {
                padding: 0.9rem 1.2rem;
                font-size: 0.95rem;
            }

            .security-badge {
                font-size: 0.8rem;
                padding: 0.6rem;
            }

            .back-link a {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 1.2rem 0.8rem;
            }

            .logo h1 {
                font-size: 1.2rem;
            }

            .login-title {
                font-size: 1.1rem;
            }

            input {
                padding: 0.7rem 0.7rem 0.7rem 2.2rem;
                font-size: 0.85rem;
            }

            .submit-btn {
                padding: 0.8rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Landscape orientation adjustments for mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .login-container {
                padding: 1.5rem;
                margin: 0.5rem 0;
            }

            .login-header {
                margin-bottom: 1.5rem;
            }

            .logo {
                margin-bottom: 1rem;
            }

            .security-badge {
                margin-top: 1rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            input, .submit-btn {
                min-height: 44px;
            }

            .back-link a {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
            }
        }

        /* High DPI displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .logo i {
                filter: drop-shadow(0 0 5px rgba(236, 72, 153, 0.3));
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .bg-animation::before {
                animation: none;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-user-shield"></i>
                <h1>Admin Panel</h1>
            </div>
            <h2 class="login-title">Accesso Amministratore</h2>
            <p class="login-subtitle">Inserisci le tue credenziali per accedere</p>
        </div>

        <form method="POST" action="login_check.php" id="loginForm">
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Nome utente" required autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i>
                Accedi al Dashboard
            </button>
        </form>

        <div class="security-badge">
            <i class="fas fa-shield-alt"></i>
            <span>Connessione sicura e crittografata</span>
        </div>

        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i>
                Torna al sito principale
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('loginForm');
            const loading = document.getElementById('loading');
            const inputs = document.querySelectorAll('input');

            // Form submission with loading
            form.addEventListener('submit', (e) => {
                loading.style.display = 'flex';
            });

            // Add smooth animations to inputs
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', () => {
                    input.parentElement.parentElement.style.transform = 'translateY(0)';
                });
            });

            // Auto-focus first input
            if (inputs.length > 0) {
                inputs[0].focus();
            }

            // Touch device optimizations
            if ('ontouchstart' in window) {
                document.body.classList.add('touch-device');
            }

            // Viewport height fix for mobile browsers
            function setViewportHeight() {
                const vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }

            setViewportHeight();
            window.addEventListener('resize', setViewportHeight);
            window.addEventListener('orientationchange', () => {
                setTimeout(setViewportHeight, 100);
            });
        });
    </script>
</body>
</html>