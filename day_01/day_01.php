<?php

# Part 1
function day1_part1(string $filename) {

    $lines = file($filename);

    $sum = 0; // summary

    foreach ($lines as $line) {

        $numbers = preg_replace('/[\D]/','',trim($line));

        # add the found number to the summary
        $sum += $numbers[0].substr($numbers,-1);

    }

    return $sum;
}

# Helper for Part 2
function find_first(string $input, array $wordlist) {

    $current = trim($input);
    $first=null;

    do {
        # if is numeric, save and finish
        if (is_numeric($current[0])) {
            $first = $current[0];
            break;
        }
        # if is a valid number spelled with letters, convert and finish
        foreach ($wordlist as $index=>$number) {
            if (strpos($current, $number)===0) {
                $first = $index+1;
                break 2;
            }
        }
        # not found, continue to search
        $current = substr($current,1);
    } while ($first===null);

    return $first;

}

//NOTE: we need to find the first and the last digits, and spelled digits may overlap (like: ninEight)
//      so parsing either left_to_right or right_to_left may give false results
# Part 2
function day1_part2(string $filename) {

    $numbers=['one','two','three','four','five','six','seven','eight','nine'];
    $rev_numbers=array_map('strrev',$numbers);

    $map = file($filename);
    $sum = 0;

    foreach ($map as $line) {
        $current = trim($line);

        $first = find_first($current, $numbers);
        $last = find_first(strrev($current),$rev_numbers);

        $sum += $first.$last;
    }

    return $sum;
}

echo "[TEST] Day 1 Part 1: ".day1_part1('res/test1.dat')."\n"; // 142
echo "[PROD] Day 1 Part 1: ".day1_part1('res/input.dat')."\n"; // 53651
echo "[TEST] Day 1 Part 2: ".day1_part2('res/test2.dat')."\n"; // 281
echo "[PROD] Day 1 Part 2: ".day1_part2('res/input.dat')."\n"; // 53894


