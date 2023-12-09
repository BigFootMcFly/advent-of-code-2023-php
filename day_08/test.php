<?php

// Euclidean algorithm
function greatest_common_divider(int $a, int $b): int {
    if ($b == 0) return $a;

    return greatest_common_divider($b, $a % $b);
}

function least_common_multiple(array $numbers): int {

    $current = $numbers[0];

    for ($number_index=1; $number_index<count($numbers); $number_index++) {
        $current = ((($numbers[$number_index] * $current)) / (greatest_common_divider($numbers[$number_index], $current)));
    }
    return $current;
}

    $numbers = array(21409,11653,19241,12737,14363,15989);
    echo least_common_multiple($numbers);
return 0;
