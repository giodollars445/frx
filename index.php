<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bella Vista Hair Salon - Prenotazioni Online</title>
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
            min-height: 100vh;
            color: #374151;
            overflow-x: hidden;
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
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ec4899' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm0 0c0-11.046 8.954-20 20-20s20 8.954 20 20-8.954 20-20 20-20-8.954-20-20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            animation: float 25s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #ec4899, #be185d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 4px rgba(236, 72, 153, 0.2));
        }

        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ec4899, #be185d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-links a:hover {
            color: #ec4899;
            background: rgba(236, 72, 153, 0.1);
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            margin-bottom: 4rem;
        }

        .hero h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #1f2937, #6b7280);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.2rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .hero-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(236, 72, 153, 0.1);
            transition: all 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(236, 72, 153, 0.15);
            border-color: rgba(236, 72, 153, 0.2);
        }

        .feature i {
            font-size: 2.5rem;
            color: #ec4899;
            margin-bottom: 1rem;
        }

        .feature h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .feature p {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Booking Form */
        .booking-section {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            border: 1px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 25px 50px rgba(236, 72, 153, 0.1);
            margin-bottom: 3rem;
        }

        .booking-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .booking-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .booking-header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: #374151;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label i {
            color: #ec4899;
            font-size: 1.1rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            color: #374151;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ec4899;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
            transform: translateY(-2px);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        /* Time Slots */
        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .time-slot {
            padding: 1rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            color: #374151;
        }

        .time-slot:hover {
            border-color: #ec4899;
            background: rgba(236, 72, 153, 0.05);
            transform: translateY(-2px);
        }

        .time-slot.selected {
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: white;
            border-color: #be185d;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
        }

        .time-slot.unavailable {
            background: #f3f4f6;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .time-slot.unavailable:hover {
            transform: none;
            background: #f3f4f6;
            border-color: #e5e7eb;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1.2rem 2rem;
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
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
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(236, 72, 153, 0.4);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Services Section */
        .services-section {
            margin-top: 4rem;
        }

        .services-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .services-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid rgba(236, 72, 153, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(236, 72, 153, 0.15);
            border-color: rgba(236, 72, 153, 0.2);
        }

        .service-card i {
            font-size: 3rem;
            color: #ec4899;
            margin-bottom: 1.5rem;
        }

        .service-card h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .service-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ec4899;
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(236, 72, 153, 0.1);
            padding: 3rem 0;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .footer-section p,
        .footer-section a {
            color: #6b7280;
            text-decoration: none;
            line-height: 1.6;
            margin-bottom: 0.5rem;
            display: block;
        }

        .footer-section a:hover {
            color: #ec4899;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.3);
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
            width: 60px;
            height: 60px;
            border: 4px solid rgba(236, 72, 153, 0.2);
            border-top: 4px solid #ec4899;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Messages */
        .message {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-weight: 500;
            display: none;
        }

        .message.success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #059669;
        }

        .message.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
        }

        .message.info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #2563eb;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                padding: 2rem 1.5rem;
            }

            .hero h2 {
                font-size: 2.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .time-slots {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .logo h1 {
                font-size: 1.5rem;
            }

            .logo i {
                font-size: 2rem;
            }

            .hero h2 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .booking-section {
                padding: 2rem 1.5rem;
            }

            .booking-header h3 {
                font-size: 1.8rem;
            }

            .hero-features {
                grid-template-columns: 1fr;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1rem;
            }

            .booking-section {
                padding: 1.5rem 1rem;
            }

            .hero h2 {
                font-size: 1.8rem;
            }

            .time-slots {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 0.5rem;
            }

            .time-slot {
                padding: 0.8rem 0.5rem;
                font-size: 0.9rem;
            }

            .submit-btn {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .time-slot, .submit-btn, .feature, .service-card {
                min-height: 44px;
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

        /* High contrast mode */
        @media (prefers-contrast: high) {
            .form-group input,
            .form-group select,
            .time-slot {
                border-width: 3px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-spa"></i>
                <h1>Bella Vista</h1>
            </div>
            <nav class="nav-links">
                <a href="#home">Home</a>
                <a href="#services">Servizi</a>
                <a href="#booking">Prenota</a>
                <a href="#contact">Contatti</a>
                <a href="cancel_booking.php">Cancella Prenotazione</a>
                <a href="login.php">Admin</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <section class="hero" id="home">
            <h2>Il Tuo Salone di Bellezza di Fiducia</h2>
            <p>Trasforma il tuo look con i nostri servizi professionali. Esperienza, eleganza e cura per la tua bellezza naturale.</p>
            
            <div class="hero-features">
                <div class="feature">
                    <i class="fas fa-crown"></i>
                    <h3>Trattamenti Premium</h3>
                    <p>Servizi di alta qualità con prodotti professionali per risultati eccellenti</p>
                </div>
                <div class="feature">
                    <i class="fas fa-heart"></i>
                    <h3>Cura Personalizzata</h3>
                    <p>Ogni cliente è unica, ogni trattamento è studiato su misura per te</p>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <h3>Prenotazione Facile</h3>
                    <p>Sistema di prenotazione online semplice e veloce, disponibile 24/7</p>
                </div>
                <div class="feature">
                    <i class="fas fa-star"></i>
                    <h3>Esperienza Unica</h3>
                    <p>Ambiente rilassante e accogliente per un'esperienza di bellezza completa</p>
                </div>
            </div>
        </section>

        <section class="booking-section" id="booking">
            <div class="booking-header">
                <h3>Prenota il Tuo Appuntamento</h3>
                <p>Scegli il servizio, la data e l'orario che preferisci</p>
            </div>

            <div id="message" class="message"></div>

            <form id="bookingForm" method="POST" action="prenota.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">
                            <i class="fas fa-user"></i>
                            Nome Completo
                        </label>
                        <input type="text" id="nome" name="nome" placeholder="Il tuo nome e cognome" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" id="email" name="email" placeholder="la-tua-email@esempio.com">
                    </div>

                    <div class="form-group">
                        <label for="telefono">
                            <i class="fas fa-phone"></i>
                            Telefono
                        </label>
                        <input type="tel" id="telefono" name="telefono" placeholder="+39 123 456 7890">
                    </div>

                    <div class="form-group">
                        <label for="servizio">
                            <i class="fas fa-cut"></i>
                            Servizio
                        </label>
                        <select id="servizio" name="servizio" required>
                            <option value="">Seleziona un servizio</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="operatore_id">
                            <i class="fas fa-user-tie"></i>
                            Parrucchiera (opzionale)
                        </label>
                        <select id="operatore_id" name="operatore_id">
                            <option value="">Nessuna preferenza</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="data_prenotazione">
                            <i class="fas fa-calendar"></i>
                            Data
                        </label>
                        <input type="date" id="data_prenotazione" name="data_prenotazione" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-clock"></i>
                        Orario Disponibile
                    </label>
                    <div class="time-slots" id="timeSlots">
                        <div class="time-slot unavailable">Seleziona prima una data</div>
                    </div>
                    <input type="hidden" id="orario" name="orario" required>
                </div>

                <button type="submit" class="submit-btn" disabled>
                    <i class="fas fa-calendar-check"></i>
                    Conferma Prenotazione
                </button>
            </form>
        </section>

        <section class="services-section" id="services">
            <div class="services-header">
                <h3>I Nostri Servizi</h3>
                <p>Scopri tutti i trattamenti disponibili nel nostro salone</p>
            </div>

            <div class="services-grid" id="servicesGrid">
                <!-- Services will be loaded dynamically -->
            </div>
        </section>
    </div>

    <footer class="footer" id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Bella Vista Hair Salon</h4>
                <p>Il tuo salone di bellezza di fiducia dal 1995. Esperienza, professionalità e passione per la bellezza femminile.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Contatti</h4>
                <p><i class="fas fa-map-marker-alt"></i> Via Roma 123, 00100 Roma</p>
                <p><i class="fas fa-phone"></i> +39 06 123 4567</p>
                <p><i class="fas fa-envelope"></i> info@bellavistasalon.it</p>
                <p><i class="fas fa-globe"></i> www.bellavistasalon.it</p>
            </div>

            <div class="footer-section">
                <h4>Orari di Apertura</h4>
                <p>Lunedì - Venerdì: 9:00 - 19:00</p>
                <p>Sabato: 9:00 - 18:00</p>
                <p>Domenica: Chiuso</p>
                <p>Su appuntamento anche in orari serali</p>
            </div>

            <div class="footer-section">
                <h4>Link Utili</h4>
                <a href="#services">I Nostri Servizi</a>
                <a href="#booking">Prenota Online</a>
                <a href="cancel_booking.php">Cancella Prenotazione</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Termini e Condizioni</a>
            </div>
        </div>
    </footer>

    <script>
        let selectedTimeSlot = null;
        let availableTimeSlots = [];

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadServices();
            loadOperators();
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('data_prenotazione').setAttribute('min', today);
        });

        // Load services
        function loadServices() {
            fetch('get_services.php')
                .then(response => response.json())
                .then(data => {
                    const serviceSelect = document.getElementById('servizio');
                    serviceSelect.innerHTML = '<option value="">Seleziona un servizio</option>';
                    
                    data.forEach(service => {
                        const option = document.createElement('option');
                        option.value = service.nome;
                        option.textContent = service.nome;
                        serviceSelect.appendChild(option);
                    });

                    // Also update services grid
                    updateServicesGrid(data);
                })
                .catch(error => {
                    console.error('Error loading services:', error);
                    showMessage('Errore nel caricamento dei servizi.', 'error');
                });
        }

        // Update services grid
        function updateServicesGrid(services) {
            const servicesGrid = document.getElementById('servicesGrid');
            servicesGrid.innerHTML = '';

            const serviceIcons = {
                'Taglio e Piega': 'fas fa-cut',
                'Colore': 'fas fa-palette',
                'Meches': 'fas fa-magic',
                'Trattamento Ricostruttivo': 'fas fa-leaf',
                'Piega': 'fas fa-wind',
                'Taglio': 'fas fa-scissors',
                'Trattamento Anticaduta': 'fas fa-seedling',
                'Acconciatura Sposa': 'fas fa-crown',
                'Extension': 'fas fa-sparkles',
                'Permanente': 'fas fa-sync-alt'
            };

            services.forEach(service => {
                const serviceCard = document.createElement('div');
                serviceCard.className = 'service-card';
                
                const icon = serviceIcons[service.nome] || 'fas fa-spa';
                
                serviceCard.innerHTML = `
                    <i class="${icon}"></i>
                    <h4>${service.nome}</h4>
                    <p>Servizio professionale di alta qualità per la cura e la bellezza dei tuoi capelli</p>
                    <div class="service-price">€${parseFloat(service.prezzo).toFixed(2)}</div>
                `;
                
                servicesGrid.appendChild(serviceCard);
            });
        }

        // Load operators
        function loadOperators() {
            fetch('get_operators.php')
                .then(response => response.json())
                .then(data => {
                    const operatorSelect = document.getElementById('operatore_id');
                    operatorSelect.innerHTML = '<option value="">Nessuna preferenza</option>';
                    
                    data.forEach(operator => {
                        const option = document.createElement('option');
                        option.value = operator.id;
                        option.textContent = `${operator.nome} ${operator.cognome}`;
                        operatorSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading operators:', error);
                });
        }

        // Date change handler
        document.getElementById('data_prenotazione').addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                checkWorkingDay(selectedDate);
            } else {
                resetTimeSlots();
            }
        });

        // Check if selected date is a working day
        function checkWorkingDay(date) {
            fetch(`check_working_day.php?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.isWorkingDay) {
                        loadTimeSlots();
                    } else {
                        showMessage('Il giorno selezionato non è lavorativo. Scegli un altro giorno.', 'error');
                        resetTimeSlots();
                    }
                })
                .catch(error => {
                    console.error('Error checking working day:', error);
                    showMessage('Errore nel controllo del giorno lavorativo.', 'error');
                });
        }

        // Load available time slots
        function loadTimeSlots() {
            fetch('get_time_slots.php')
                .then(response => response.json())
                .then(data => {
                    availableTimeSlots = data;
                    renderTimeSlots();
                })
                .catch(error => {
                    console.error('Error loading time slots:', error);
                    showMessage('Errore nel caricamento degli orari.', 'error');
                });
        }

        // Render time slots
        function renderTimeSlots() {
            const timeSlotsContainer = document.getElementById('timeSlots');
            const selectedDate = document.getElementById('data_prenotazione').value;
            
            if (!selectedDate || availableTimeSlots.length === 0) {
                timeSlotsContainer.innerHTML = '<div class="time-slot unavailable">Nessun orario disponibile</div>';
                return;
            }

            timeSlotsContainer.innerHTML = '';

            availableTimeSlots.forEach(slot => {
                checkAvailability(selectedDate, slot.orario).then(isAvailable => {
                    const timeSlotElement = document.createElement('div');
                    timeSlotElement.className = `time-slot ${isAvailable ? '' : 'unavailable'}`;
                    timeSlotElement.textContent = slot.orario.substring(0, 5);
                    
                    if (isAvailable) {
                        timeSlotElement.addEventListener('click', () => selectTimeSlot(slot.orario, timeSlotElement));
                    }
                    
                    timeSlotsContainer.appendChild(timeSlotElement);
                });
            });
        }

        // Check availability for specific date and time
        function checkAvailability(date, time) {
            return fetch(`check_availability.php?date=${date}&time=${time}`)
                .then(response => response.json())
                .then(data => data.available)
                .catch(error => {
                    console.error('Error checking availability:', error);
                    return false;
                });
        }

        // Select time slot
        function selectTimeSlot(time, element) {
            // Remove previous selection
            document.querySelectorAll('.time-slot.selected').forEach(slot => {
                slot.classList.remove('selected');
            });
            
            // Add selection to clicked element
            element.classList.add('selected');
            selectedTimeSlot = time;
            document.getElementById('orario').value = time;
            
            // Enable submit button
            updateSubmitButton();
        }

        // Reset time slots
        function resetTimeSlots() {
            document.getElementById('timeSlots').innerHTML = '<div class="time-slot unavailable">Seleziona prima una data</div>';
            selectedTimeSlot = null;
            document.getElementById('orario').value = '';
            updateSubmitButton();
        }

        // Update submit button state
        function updateSubmitButton() {
            const submitBtn = document.querySelector('.submit-btn');
            const form = document.getElementById('bookingForm');
            const requiredFields = form.querySelectorAll('[required]');
            
            let allFieldsFilled = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    allFieldsFilled = false;
                }
            });
            
            if (allFieldsFilled && selectedTimeSlot) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Conferma Prenotazione';
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Compila tutti i campi';
            }
        }

        // Form validation
        document.getElementById('bookingForm').addEventListener('input', updateSubmitButton);

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (!selectedTimeSlot) {
                e.preventDefault();
                showMessage('Seleziona un orario per la prenotazione.', 'error');
                return;
            }
            
            // Show loading
            document.getElementById('loading').style.display = 'flex';
        });

        // Show message
        function showMessage(text, type) {
            const messageElement = document.getElementById('message');
            messageElement.textContent = text;
            messageElement.className = `message ${type}`;
            messageElement.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 5000);
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

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

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.feature, .service-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>