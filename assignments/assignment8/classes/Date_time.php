<?php
require_once 'Pdo_methods.php';

//Why are we using timestamps instead of the date?
//Is there any advantage to using the Date_time class over just having a PHP function file.  What are they?

//When a user requests to view notes within a specific date range, what logical steps must the application take 
// to retrieve and present only the relevant notes, show that in your code and explan it?

//Explain the importance of converting dates and times into a standardized format (like a timestamp) before storing 
// them in a database. What problems might arise if you don't?

//Imagine the application becomes very popular and has millions of notes. What performance considerations might arise 
// when displaying notes, and how could you address them?

class Date_time {

    public function checkSubmit() {
        if (isset($_POST['addNote'])) {
            return $this->addNote();
        } elseif (isset($_POST['getNotes'])) {
            return $this->getNotes();
        }
        return '';
    }

    private function addNote() {
        $dateTime = trim($_POST['dateTime'] ?? '');
        $note = trim($_POST['note'] ?? '');

        if (empty($dateTime) || empty($note)) {
            return '<div class="alert alert-danger mt-3">You must enter a date, time, and note.</div>';
        }

        $timestamp = strtotime($dateTime);

        try {
            $pdo = new PdoMethods();
            $sql = "INSERT INTO note (date_time, note) VALUES (:date_time, :note)";
            $bindings = [
                [':date_time', $timestamp, 'int'],
                [':note', $note, 'str']
            ];

            $result = $pdo->otherBinded($sql, $bindings);

            if ($result === 'noerror') {
                return '<div class="alert alert-success mt-3">Note added successfully!</div>';
            } else {
                return '<div class="alert alert-danger mt-3">Error adding note.</div>';
            }

        } catch (Exception $e) {
            return '<div class="alert alert-danger mt-3">Database error: ' . $e->getMessage() . '</div>';
        }
    }

    private function getNotes() {
        $begDate = trim($_POST['begDate'] ?? '');
        $endDate = trim($_POST['endDate'] ?? '');

        if (empty($begDate) || empty($endDate)) {
            return '<div class="alert alert-danger mt-3">No notes found for the date range selected.</div>';
        }

        $begTime = strtotime($begDate . ' 00:00:00');
        $endTime = strtotime($endDate . ' 23:59:59');

        try {
            $pdo = new PdoMethods();
            $sql = "SELECT date_time, note FROM note 
                    WHERE date_time BETWEEN :begDate AND :endDate 
                    ORDER BY date_time DESC";

            $bindings = [
                [':begDate', $begTime, 'int'],
                [':endDate', $endTime, 'int']
            ];

            $records = $pdo->selectBinded($sql, $bindings);

            if (!$records) {
                return '<div class="alert alert-warning mt-3">No notes found for the date range selected.</div>';
            }

            $output = '<table class="table table-striped table-bordered mt-3">';
            $output .= '<thead class="table-light"><tr><th>Date and Time</th><th>Note</th></tr></thead><tbody>';

            foreach ($records as $r) {
                $formatted = date('m/d/Y h:i a', $r['date_time']);
                $output .= "<tr><td>{$formatted}</td><td>" . htmlspecialchars($r['note']) . "</td></tr>";
            }

            $output .= '</tbody></table>';
            return $output;

        } catch (Exception $e) {
            return '<div class="alert alert-danger mt-3">Database error: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

