<?php
include 'connessione.php';

header('Content-Type: application/json');

// Get active operators
$query = $conn->query("SELECT id, nome, cognome FROM operatori WHERE attivo = 1 ORDER BY nome, cognome");

$operators = [];
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $operators[] = $row;
    }
}

echo json_encode($operators);
$conn->close();
?>