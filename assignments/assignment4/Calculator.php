<?php
class Calculator {

    public function calc($operator = null, $num1 = null, $num2 = null) {

        // checks for correct amount of arguments
        if (func_num_args() != 3) {
            return "<p>Cannot perform operation. You must have three arguments. A string for the operator (+,-,*,/) and two integers or floats for the numbers.</p>";
        }

        // checks that both number values are valid
        if (!is_numeric($num1) || !is_numeric($num2)) {
            return "<p>Cannot perform operation. You must have three arguments. A string for the operator (+,-,*,/) and two integers or floats for the numbers.</p>";
        }

        // checks for valid operator
        $validOperators = ['+', '-', '*', '/'];
        if (!in_array($operator, $validOperators)) {
            return "<p>Cannot perform operation. You must have three arguments. A string for the operator (+,-,*,/) and two integers or floats for the numbers.</p>";
        }

        // checks for division by zero
        if ($operator === '/' && $num2 == 0) {
            return "<p>The calculation is $num1 / $num2. The answer is cannot divide a number by zero.</p>";
        }

        // calculations
        switch ($operator) {
            case '+':
                $result = $num1 + $num2;
                break;
            case '-':
                $result = $num1 - $num2;
                break;
            case '*':
                $result = $num1 * $num2;
                break;
            case '/':
                $result = $num1 / $num2;
                break;
        }

        // output for successful calculation
        return "<p>The calculation is $num1 $operator $num2. The answer is $result.</p>";
    }
}
?>


