<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}
include 'connessione.php';

// Handle booking status changes
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $booking_id = intval($_GET['id']);
    
    if ($action === 'confirm') {
        $stmt = $conn->prepare("UPDATE prenotazioni SET stato = 'Confermata' WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            $success_message = "Prenotazione confermata con successo.";
        } else {
            $error_message = "Errore nella conferma della prenotazione.";
        }
        $stmt->close();
    } elseif ($action === 'cancel') {
        $stmt = $conn->prepare("UPDATE prenotazioni SET stato = 'Cancellata' WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            $success_message = "Prenotazione cancellata con successo.";
        } else {
            $error_message = "Errore nella cancellazione della prenotazione.";
        }
        $stmt->close();
    }
    
    // Redirect to avoid resubmission
    header("Location: admin.php");
    exit();
}

// Handle data clearing
if (isset($_POST['clear_data'])) {
    // First delete from cancellation_tokens (child table)
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    $conn->query("DELETE FROM cancellation_tokens");
    $conn->query("DELETE FROM prenotazioni");
    $conn->query("DELETE FROM storico_ricavi");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    $success_message = "Tutti i dati sono stati cancellati con successo.";
}

// Create necessary tables if they don't exist
$conn->query("CREATE TABLE IF NOT EXISTS giorni_lavorativi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    giorno_settimana ENUM('lunedi', 'martedi', 'mercoledi', 'giovedi', 'venerdi', 'sabato', 'domenica') UNIQUE,
    attivo TINYINT(1) DEFAULT 1
)");

$conn->query("CREATE TABLE IF NOT EXISTS fasce_orarie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orario TIME NOT NULL,
    attivo TINYINT(1) DEFAULT 1,
    descrizione VARCHAR(255) DEFAULT NULL,
    UNIQUE KEY unique_orario (orario)
)");

$conn->query("CREATE TABLE IF NOT EXISTS servizi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL UNIQUE,
    prezzo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    durata INT DEFAULT 60,
    descrizione TEXT,
    attivo TINYINT(1) DEFAULT 1
)");

$conn->query("CREATE TABLE IF NOT EXISTS operatori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(255),
    specialita TEXT,
    attivo TINYINT(1) DEFAULT 1,
    data_inserimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS limiti_orari (
    id INT AUTO_INCREMENT PRIMARY KEY,
    giorno_settimana ENUM('lunedi', 'martedi', 'mercoledi', 'giovedi', 'venerdi', 'sabato', 'domenica'),
    orario TIME,
    limite_persone INT NOT NULL,
    attivo TINYINT(1) DEFAULT 1,
    UNIQUE KEY unique_day_time (giorno_settimana, orario)
)");

$conn->query("CREATE TABLE IF NOT EXISTS limiti_date_specifiche (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_specifica DATE,
    orario TIME,
    limite_persone INT NOT NULL,
    attivo TINYINT(1) DEFAULT 1,
    UNIQUE KEY unique_date_time (data_specifica, orario)
)");

$conn->query("CREATE TABLE IF NOT EXISTS storico_ricavi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATE UNIQUE,
    ricavo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    data_aggiornamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Add stato column to prenotazioni if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM prenotazioni LIKE 'stato'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE prenotazioni ADD COLUMN stato ENUM('In attesa', 'Confermata', 'Cancellata') DEFAULT 'In attesa'");
}

// Add operatore_id column to prenotazioni if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM prenotazioni LIKE 'operatore_id'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE prenotazioni ADD COLUMN operatore_id INT NULL, ADD FOREIGN KEY (operatore_id) REFERENCES operatori(id) ON DELETE SET NULL");
}

// Initialize working days if empty
$working_days_count = $conn->query("SELECT COUNT(*) as count FROM giorni_lavorativi")->fetch_assoc()['count'];
if ($working_days_count == 0) {
    $giorni = ['lunedi', 'martedi', 'mercoledi', 'giovedi', 'venerdi', 'sabato', 'domenica'];
    $default_active = ['lunedi' => 1, 'martedi' => 1, 'mercoledi' => 1, 'giovedi' => 1, 'venerdi' => 1, 'sabato' => 1, 'domenica' => 0];
    
    foreach ($giorni as $giorno) {
        $attivo = $default_active[$giorno];
        $stmt = $conn->prepare("INSERT INTO giorni_lavorativi (giorno_settimana, attivo) VALUES (?, ?)");
        $stmt->bind_param("si", $giorno, $attivo);
        $stmt->execute();
        $stmt->close();
    }
}

// Initialize time slots if empty
$time_slots_count = $conn->query("SELECT COUNT(*) as count FROM fasce_orarie")->fetch_assoc()['count'];
if ($time_slots_count == 0) {
    $default_times = ['09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00', '14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00', '16:30:00', '17:00:00', '17:30:00', '18:00:00'];
    
    foreach ($default_times as $time) {
        $stmt = $conn->prepare("INSERT INTO fasce_orarie (orario, attivo) VALUES (?, 1)");
        $stmt->bind_param("s", $time);
        $stmt->execute();
        $stmt->close();
    }
}

// Initialize services if empty
$services_count = $conn->query("SELECT COUNT(*) as count FROM servizi")->fetch_assoc()['count'];
if ($services_count == 0) {
    $default_services = [
        ['Taglio e Piega', 35.00],
        ['Colore', 50.00],
        ['Meches', 60.00],
        ['Trattamento Ricostruttivo', 40.00],
        ['Piega', 25.00],
        ['Taglio', 30.00],
        ['Trattamento Anticaduta', 45.00],
        ['Acconciatura Sposa', 80.00],
        ['Extension', 100.00],
        ['Permanente', 55.00]
    ];
    
    foreach ($default_services as $service) {
        $stmt = $conn->prepare("INSERT INTO servizi (nome, prezzo) VALUES (?, ?)");
        $stmt->bind_param("sd", $service[0], $service[1]);
        $stmt->execute();
        $stmt->close();
    }
}

// Get statistics
$statistiche = ['Confermata' => 0, 'In attesa' => 0, 'Cancellata' => 0];
$totali = $conn->query("SELECT stato, COUNT(*) as totale FROM prenotazioni GROUP BY stato");
if ($totali) {
    while ($row = $totali->fetch_assoc()) {
        $stato = $row['stato'] ?? 'In attesa';
        $statistiche[$stato] = $row['totale'];
    }
}

// Calculate total revenue
$totale_ricavi = 0;
$entrate = $conn->query("
    SELECT SUM(s.prezzo) as totale 
    FROM prenotazioni p 
    JOIN servizi s ON p.servizio = s.nome 
    WHERE p.stato = 'Confermata'
");
if ($entrate) {
    $entrate_row = $entrate->fetch_assoc();
    $totale_ricavi = $entrate_row['totale'] ?? 0;
}

// Get booking dates for revenue tracking
$date_prenotazioni = array();
$query = "SELECT DISTINCT data_prenotazione FROM prenotazioni WHERE (stato != 'Cancellata' OR stato IS NULL) ORDER BY data_prenotazione DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (isset($row['data_prenotazione']) && !empty($row['data_prenotazione'])) {
            $date_prenotazioni[] = [
                'raw' => $row['data_prenotazione'],
                'formatted' => date('d/m/Y', strtotime($row['data_prenotazione']))
            ];
        }
    }
}

// Get recent bookings
$prenotazioni = $conn->query("
    SELECT p.*, CONCAT(o.nome, ' ', o.cognome) as operatore_nome 
    FROM prenotazioni p 
    LEFT JOIN operatori o ON p.operatore_id = o.id 
    ORDER BY p.data_prenotazione DESC, p.id DESC 
    LIMIT 100
");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard Admin - Bella Vista Hair Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            color: #ffffff;
            min-height: 100vh;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #d4af37;
            border: none;
            border-radius: 8px;
            color: #1a1a2e;
            padding: 0.8rem;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: #ffd700;
            transform: scale(1.05);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 80px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.expanded {
            width: 280px;
        }

        .sidebar-header {
            padding: 0 1.5rem;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: center;
        }

        .sidebar.expanded .sidebar-header {
            padding: 0 2rem;
            justify-content: flex-start;
        }

        .sidebar-logo {
            font-size: 2rem;
            color: #d4af37;
        }

        .sidebar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            transition: opacity 0.3s ease;
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.expanded .sidebar-title {
            opacity: 1;
            width: auto;
        }

        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: -15px;
            width: 30px;
            height: 30px;
            background: #d4af37;
            border: none;
            border-radius: 50%;
            color: #1a1a2e;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: #ffd700;
            transform: scale(1.1);
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 1rem;
        }

        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0.5rem;
            color: #a0a0a0;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            justify-content: center;
        }

        .sidebar.expanded .sidebar-nav a {
            padding: 1rem;
            justify-content: flex-start;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(212, 175, 55, 0.1);
            color: #d4af37;
            transform: translateX(5px);
        }

        .sidebar-nav i {
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-nav span {
            display: none;
        }

        .sidebar.expanded .sidebar-nav span {
            display: inline;
        }

        /* Main Content */
        .main {
            margin-left: 80px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main.expanded {
            margin-left: 280px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            text-decoration: none;
            border-radius: 12px;
            border: 1px solid rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .stat-card h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #d4af37;
            margin-bottom: 0.5rem;
        }

        .stat-card .label {
            font-size: 0.85rem;
            color: #6b7280;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .content-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .content-card h3 i {
            color: #d4af37;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02);
            scrollbar-width: thin;
            scrollbar-color: #d4af37 rgba(255, 255, 255, 0.1);
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #d4af37;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th, td {
            padding: 1rem 0.8rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            white-space: nowrap;
        }

        th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            color: #d4af37;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #e0e0e0;
            font-weight: 400;
            font-size: 0.9rem;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Status Badges */
        .status {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .status.confermata {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status.in-attesa {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .status.cancellata {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Action Buttons */
        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin: 0 0.2rem;
            white-space: nowrap;
        }

        .action-btn.confirm {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .action-btn.cancel {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Messages */
        .error-message, .success-message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
        }

        /* Management Buttons */
        .management-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .management-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .management-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .management-card i {
            font-size: 3rem;
            color: #d4af37;
            margin-bottom: 1rem;
        }

        .management-card h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .management-card p {
            color: #a0a0a0;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .management-card a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #d4af37, #ffd700);
            color: #1a1a2e;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .management-card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
        }

        /* Clear Data Section */
        .danger-zone {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
        }

        .danger-zone h3 {
            color: #f87171;
            margin-bottom: 1rem;
        }

        .danger-zone p {
            color: #fca5a5;
            margin-bottom: 1.5rem;
        }

        .danger-btn {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .danger-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .management-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 1rem;
                padding-top: 4rem;
            }

            .main.expanded {
                margin-left: 0;
            }

            .header {
                padding: 1rem;
                flex-direction: column;
                text-align: center;
            }

            .header-title {
                font-size: 1.4rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .content-card {
                padding: 1.5rem;
            }

            .table-container {
                margin: 0 -1rem;
                border-radius: 0;
            }
            
            th, td {
                padding: 0.8rem 0.5rem;
                font-size: 0.8rem;
            }
            
            .action-btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.7rem;
                margin: 0.1rem;
            }
        }

        @media (max-width: 480px) {
            .main {
                padding: 0.5rem;
                padding-top: 3.5rem;
            }
            
            .header {
                padding: 0.8rem;
                margin-bottom: 1rem;
            }
            
            .header-title {
                font-size: 1.2rem;
            }
            
            .content-card {
                padding: 1rem;
            }
            
            .content-card h3 {
                font-size: 1.1rem;
            }
            
            .action-btn {
                display: block;
                margin: 0.2rem 0;
                text-align: center;
                width: 100%;
            }
            
            th, td {
                padding: 0.6rem 0.3rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 360px) {
            .main {
                padding: 0.3rem;
                padding-top: 3rem;
            }
            
            .header-title {
                font-size: 1.1rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .action-btn, .management-card a, .danger-btn {
                min-height: 44px;
                min-width: 44px;
            }
            
            .sidebar-toggle {
                width: 40px;
                height: 40px;
            }
            
            .mobile-menu-btn {
                padding: 1rem;
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

<button class="mobile-menu-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="sidebar-header">
        <i class="fas fa-cut sidebar-logo"></i>
        <span class="sidebar-title">Admin Panel</span>
    </div>
    
    <ul class="sidebar-nav">
        <li><a href="#" class="active"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
        <li><a href="gestione_prenotazioni.php"><i class="fas fa-calendar-alt"></i><span>Prenotazioni</span></a></li>
        <li><a href="gestione_operatori.php"><i class="fas fa-scissors"></i><span>Operatori</span></a></li>
        <li><a href="impostazioni.php"><i class="fas fa-cog"></i><span>Impostazioni</span></a></li>
        <li><a href="index.php"><i class="fas fa-arrow-left"></i><span>Torna al sito</span></a></li>
    </ul>
</div>

<div class="main" id="main">
    <div class="header">
        <h1 class="header-title">Dashboard Amministratore</h1>
        <div class="header-actions">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Prenotazioni Confermate</h3>
            <div class="value"><?php echo $statistiche['Confermata']; ?></div>
            <div class="label">Appuntamenti confermati</div>
        </div>
        <div class="stat-card">
            <h3>In Attesa</h3>
            <div class="value"><?php echo $statistiche['In attesa']; ?></div>
            <div class="label">Da confermare</div>
        </div>
        <div class="stat-card">
            <h3>Cancellate</h3>
            <div class="value"><?php echo $statistiche['Cancellata']; ?></div>
            <div class="label">Prenotazioni annullate</div>
        </div>
        <div class="stat-card">
            <h3>Ricavi Totali</h3>
            <div class="value">€<?php echo number_format($totale_ricavi, 2); ?></div>
            <div class="label">Da prenotazioni confermate</div>
        </div>
    </div>

    <div class="management-grid">
        <div class="management-card">
            <i class="fas fa-calendar-alt"></i>
            <h4>Gestione Prenotazioni</h4>
            <p>Configura giorni lavorativi, orari e limiti di prenotazione</p>
            <a href="gestione_prenotazioni.php">
                <i class="fas fa-cog"></i>
                Gestisci
            </a>
        </div>
        <div class="management-card">
            <i class="fas fa-scissors"></i>
            <h4>Gestione Operatori</h4>
            <p>Aggiungi, modifica e gestisci il personale del salone</p>
            <a href="gestione_operatori.php">
                <i class="fas fa-users"></i>
                Gestisci
            </a>
        </div>
        <div class="management-card">
            <i class="fas fa-cog"></i>
            <h4>Impostazioni Sistema</h4>
            <p>Configura password e impostazioni generali</p>
            <a href="impostazioni.php">
                <i class="fas fa-wrench"></i>
                Configura
            </a>
        </div>
    </div>

    <div class="content-grid">
        <div class="content-card">
            <h3><i class="fas fa-list"></i>Prenotazioni Recenti</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Telefono</th>
                            <th>Data</th>
                            <th>Orario</th>
                            <th>Servizio</th>
                            <th>Operatore</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <?php if ($prenotazioni && $prenotazioni->num_rows > 0): ?>
                            <?php $i = 1; ?>
                            <?php while ($row = $prenotazioni->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['nome'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php 
                                    if (isset($row['data_prenotazione']) && $row['data_prenotazione']) {
                                        echo date('d/m/Y', strtotime($row['data_prenotazione']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td><?php echo isset($row['orario']) ? date('H:i', strtotime($row['orario'])) : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars($row['servizio'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['operatore_nome'] ?? 'Non assegnato'); ?></td>
                                <td>
                                    <?php 
                                    $stato = $row['stato'] ?? 'In attesa';
                                    $statusClass = '';
                                    if ($stato === 'Confermata') $statusClass = 'confermata';
                                    elseif ($stato === 'In attesa') $statusClass = 'in-attesa';
                                    elseif ($stato === 'Cancellata') $statusClass = 'cancellata';
                                    ?>
                                    <span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($stato); ?></span>
                                </td>
                                <td>
                                    <?php if ($stato !== 'Cancellata'): ?>
                                        <a href="?action=confirm&id=<?php echo $row['id']; ?>" class="action-btn confirm" onclick="return confirm('Confermare questa prenotazione?')">
                                            <i class="fas fa-check"></i>Conferma
                                        </a>
                                        <a href="?action=cancel&id=<?php echo $row['id']; ?>" class="action-btn cancel" onclick="return confirm('Cancellare questa prenotazione?')">
                                            <i class="fas fa-times"></i>Cancella
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #6b7280;">Nessuna azione</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; color: #a0a0a0;">
                                    Nessuna prenotazione trovata
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="danger-zone">
        <h3><i class="fas fa-exclamation-triangle"></i> Zona Pericolosa</h3>
        <p>Attenzione: questa azione cancellerà TUTTI i dati delle prenotazioni in modo permanente.</p>
        <form method="POST" onsubmit="return confirm('ATTENZIONE: Questa azione cancellerà TUTTI i dati delle prenotazioni in modo permanente. Sei sicuro di voler continuare?')">
            <button type="submit" name="clear_data" class="danger-btn">
                <i class="fas fa-trash-alt"></i>
                Cancella Tutti i Dati
            </button>
        </form>
    </div>
</div>

<script>
let sidebarCollapsed = true;
let mobileOpen = false;

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const toggleIcon = document.querySelector('.sidebar-toggle i');
    const isMobile = window.innerWidth <= 768;

    if (isMobile) {
        mobileOpen = !mobileOpen;
        sidebar.classList.toggle('mobile-open');
    } else {
        sidebarCollapsed = !sidebarCollapsed;
        sidebar.classList.toggle('expanded');
        main.classList.toggle('expanded');
        toggleIcon.className = sidebarCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
    }
}

// Close mobile sidebar when clicking outside
document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('sidebar');
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    
    if (window.innerWidth <= 768 && mobileOpen && 
        !sidebar.contains(e.target) && 
        !mobileBtn.contains(e.target)) {
        toggleSidebar();
    }
});

// Handle window resize
window.addEventListener('resize', () => {
    const sidebar = document.getElementById('sidebar');
    
    if (window.innerWidth > 768) {
        sidebar.classList.remove('mobile-open');
        mobileOpen = false;
    }
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
</script>
</body>
</html>
```