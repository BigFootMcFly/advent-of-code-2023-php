<?php

function load_data(string $filename): array {

    $lines = file($filename,FILE_IGNORE_NEW_LINES);

    $data = [];
    foreach ($lines as $line) {
        $data[] = array_map('intval',explode(' ', $line));
    }

    return $data ?: $lines;
}

# Part 1 helpers

function gen_sequenses(array $source): array {

    $result = [$source];

    # generate sequences until all values reach to zero
    do {
        $current_row = count($result);
        $result[] = [];
        for ($i=1; $i<count($result[$current_row-1]); $i++) {
            $result[$current_row][$i-1]=$result[$current_row-1][$i]-$result[$current_row-1][$i-1];
        }
    } while (array_unique($result[count($result)-1]) !== [0]);

    return $result;
}

function extrapolate(array $source): array  {
    
    # extraploate each sequence from bottom to top
    $source[count($source)-1][] = 0;
    for ($i=count($source)-2; $i>=0; $i--) {
        
        $source[$i][] = end($source[$i+1])+end($source[$i]);
    }
    return $source;
}

# Part 1
function day_09_part1(string $filename) {

    $data = load_data($filename);
    $values = [];

    foreach ($data as $array) {
        $values[] = extrapolate(gen_sequenses($array));
    }
    # calculate sum
    $sum = 0;
    array_walk($values, function ($array) use (&$sum) { $sum += end($array[0]);});

    return $sum;
}


# Part 2
function day_09_part2(string $filename) {

    $data = load_data($filename);

    $result = null;

    return $result;
}

echo "[TEST] Day 09 Part 1: ".day_09_part1('res/test1.dat')."\n"; // 114
echo "[PROD] Day 09 Part 1: ".day_09_part1('res/input.dat')."\n"; // 1789635132
#echo "[TEST] Day 09 Part 2: ".day_09_part2('res/test1.dat')."\n"; // 2286
#echo "[PROD] Day 09 Part 2: ".day_09_part2('res/input.dat')."\n"; // 59795


