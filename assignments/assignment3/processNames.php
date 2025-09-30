<?php
function addClearNames() {
    session_start();

    // empty array
    if (!isset($_SESSION['names'])) {
        $_SESSION['names'] = [];
    }

    // clear button is clicked
    if (isset($_POST['clear'])) {
        $_SESSION['names'] = [];
        return "";
    }

    // add name button is clicked
    if (isset($_POST['add'])) {
        $input = trim($_POST['name']);
        list($first, $last) = explode(" ", $input, 2);
        // formats the names
        $formatted = ucfirst(strtolower($last)) . ", " . ucfirst(strtolower($first));
        // adds to session
        $_SESSION['names'][] = $formatted;
    }

    // sorts names
    sort($_SESSION['names']);
    
    // returns a string in textarea using \n
    return implode("\n", $_SESSION['names']);
}
