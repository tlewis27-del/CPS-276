<?php
class Validation {
    private $errors = [];

    public function checkFormat($value, $type, $customErrorMsg = null) {
        $patterns = [
            'name'    => "/^[A-Za-z' ]+$/",  // Letters, spaces, apostrophes
            'email'   => "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/", // Standard email
            'password'=> "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", // ≥8 chars, 1 uppercase, 1 number, 1 symbol
            'none'    => '/.*/'
        ];

        $pattern = $patterns[$type] ?? '/.*/';

        if (!preg_match($pattern, $value)) {
            $errorMessage = $customErrorMsg ?? "Invalid $type format.";
            $this->errors[$type] = $errorMessage;
            return false;
        }

        return true;
    }

    public function getErrors() { return $this->errors; }
    public function hasErrors() { return !empty($this->errors); }
}
?>
