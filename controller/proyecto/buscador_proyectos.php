<?php
require_once(__DIR__ . '/../conexion.php');

$search = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($search === '') {
    echo json_encode([]);
    exit;
}

$stmt = $connect->prepare("SELECT * FROM proyectos WHERE nom_proyecto LIKE :search");
$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm);
$stmt->execute();

$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($proyectos);
