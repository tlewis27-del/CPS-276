<?php
require_once "../classes/Pdo_methods.php";

//1) In main.js, the addName function uses fetch() to send data to addName.php. Explain the request/response
//   flow: what data format is sent from JavaScript, how does PHP receive it, and what format must PHP return 
//   for JavaScript to process it?

//2) Looking at displayNames.php, it returns different JSON structures for success cases (with names field) 
//   versus error cases (with msg field). Explain why maintaining a consistent response structure is important 
//   for the JavaScript code that processes these responses.

//3) In displayNames.php, the code checks if $records === "error" and also checks count($records) > 0. Explain
//   the difference between these two conditions and why both checks are necessary before processing the database results.

//4) When a user clicks the "Add Name" button, main.js calls names.addName(), which then calls names.displayNames().
//   Explain why displayNames() is called after adding a name, and describe the sequence of AJAX requests that occur 
//   during this process.

//5) After clearNames.php successfully clears the database, the JavaScript calls names.displayNames() to refresh the
//   list. Explain why this refresh is necessary and what would happen if this call were omitted. How does this demonstrate the stateless nature of HTTP requests?




$pdo = new PdoMethods();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["name"]) || trim($data["name"]) === "") {
    echo json_encode([
        "masterstatus" => "error",
        "msg" => "You must enter a name"
    ]);
    exit;
}

$parts = explode(" ", trim($data["name"]));

if (count($parts) < 2) {
    echo json_encode([
        "masterstatus" => "error",
        "msg" => "You must enter first and last name"
    ]);
    exit;
}

$first = $parts[0];
$last  = $parts[1];
$formatted = "$last, $first";

$sql = "INSERT INTO names (name) VALUES (:name)";
$bindings = [
    [":name", $formatted, "str"]
];

$result = $pdo->otherBinded($sql, $bindings);

if ($result === "error") {
    echo json_encode([
        "masterstatus" => "error",
        "msg" => "Could not add name"
    ]);
} else {
    echo json_encode([
        "masterstatus" => "success",
        "msg" => "Name added"
    ]);
}
