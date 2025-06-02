<?php
include '../controller/conexion.php';

$nom_torneo = $_POST['nom_torneo'] ?? '';
$fk_tipo_torneo = $_POST['fk_tipo_torneo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$detalles = $_POST['detalles'] ?? '';
$estatus = 1;

$carpeta_destino = "../uploads/";
if (!file_exists($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
}

$img_principal = $_FILES['img_proyecto']['name'] ?? '';
$tmp_img_principal = $_FILES['img_proyecto']['tmp_name'] ?? '';
$ruta_img_principal = $carpeta_destino . basename($img_principal);

if ($img_principal != '' && is_uploaded_file($tmp_img_principal)) {
    move_uploaded_file($tmp_img_principal, $ruta_img_principal);
}

$sql = "INSERT INTO torneos (nom_torneo, fk_tipo_torneo, estatus, img, descripcion, detalles)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $connect->prepare($sql);
$stmt->execute([$nom_torneo, $fk_tipo_torneo, $estatus, $img_principal, $descripcion, $detalles]);

$id_torneo = $connect->lastInsertId();

if (!empty($_FILES['img_adicionales']['name'][0])) {
    foreach ($_FILES['img_adicionales']['tmp_name'] as $key => $tmp_name) {
        $img_name = $_FILES['img_adicionales']['name'][$key];
        $ruta_img = $carpeta_destino . basename($img_name);

        if (is_uploaded_file($tmp_name)) {
            move_uploaded_file($tmp_name, $ruta_img);

            $query_img = "INSERT INTO img_torneos (img, fk_torneo) VALUES (?, ?)";
            $stmt_img = $connect->prepare($query_img);
            $stmt_img->execute([$img_name, $id_torneo]);
        }
    }
}

echo "✅ Torneo guardado correctamente.";
?>
