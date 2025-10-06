<?php
class Calculator {

    public function calc($operator = null, $num1 = null, $num2 = null) {

        //1) Explain the purpose of require_once "Calculator.php"; in th index.php page. What would be the difference if 
        //// include or require were used instead of require_once?

        //2) How does the divide method specifically prevent and report an error for division by zero? Why is this a critical
        //// consideration in calculator applications?

        //3) If you were tasked with adding a new mathematical operation (e.g., exponentiation ^) to this calculator, what
        //// specific modifications would be required in both Calculator.php?

        //4) Explain the difference between the Calculator class and the $Calculator object. Why do we create an instance
        //// of the class?

        //5) Why is it important to check that the last two parameters are numbers in our Calculator class?
        //// Index.php handles the display of the results using HTML, while Calculator.php contains the core calculation logic.
        //// Discuss the importance of separating user interface (presentation) concerns from business logic.

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


