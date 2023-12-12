<?php

class Day11Challenge {

    protected mixed $data;

    //---------------------------------------------------------------------
    public function __construct(
        protected string $filename,
    ){
        $this->data = $this->load_data($filename);
    }

    //---------------------------------------------------------------------
    protected function load_data(string $filename): mixed {

        $lines = file($filename,FILE_IGNORE_NEW_LINES);
        $data = [];
    
        return $data ?: $lines;
    }
    

}

$challenge = new Day11Challenge('test1.dat');
$challenge->solvePart1();


# Part 1
function day_11_part1(string $filename) {

    $data = load_data($filename);

    $result = null;

    return $result;
}


# Part 2
function day_11_part2(string $filename) {

    $data = load_data($filename);

    $result = null;

    return $result;
}

echo "[TEST] Day 11 Part 1: ".day_11_part1('res/test1.dat')."\n"; // 8
#echo "[PROD] Day 11 Part 1: ".day_11_part1('res/input.dat')."\n"; // 2617
#echo "[TEST] Day 11 Part 2: ".day_11_part2('res/test1.dat')."\n"; // 2286
#echo "[PROD] Day 11 Part 2: ".day_11_part2('res/input.dat')."\n"; // 59795


