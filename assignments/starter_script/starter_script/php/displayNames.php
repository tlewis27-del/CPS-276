<?php
require_once "../classes/Pdo_methods.php";

$pdo = new PdoMethods();

$sql = "SELECT name FROM names ORDER BY name ASC";
$records = $pdo->selectNotBinded($sql);

if ($records === "error") {
    echo json_encode([
        "masterstatus" => "error",
        "msg" => "Could not retrieve names"
    ]);
    exit;
}

if (count($records) === 0) {
    echo json_encode([
        "masterstatus" => "success",
        "names" => "<p>No names to display</p>",
        "msg" => ""
    ]);
    exit;
}

$output = "";
foreach ($records as $row) {
    $output .= "<p>" . htmlspecialchars($row["name"]) . "</p>";
}

echo json_encode([
    "masterstatus" => "success",
    "names" => $output,
    "msg" => ""
]);
