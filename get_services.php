<?php
include 'connessione.php';

header('Content-Type: application/json');

// Get active services
$query = $conn->query("SELECT nome, prezzo FROM servizi WHERE attivo = 1 ORDER BY nome");

$services = [];
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $services[] = $row;
    }
}

echo json_encode($services);
$conn->close();
?>