<?php

function load_data(string $filename): array {
    return file($filename,FILE_IGNORE_NEW_LINES);
}

# helper
function is_part_number_digit(array &$data, int $row, int $col): bool {
    $invalid = '.0123456789';

    for ($_row=-1;$_row<=1; $_row++) {
        for ($_col=-1; $_col<=1; $_col++) {            
            
            # check boundaries
            if ($row+$_row<0) continue;
            if ($row+$_row>=count($data)) continue;
            if ($col+$_col<0) continue;
            if ($col+$_col>strlen($data[$row+$_row])-1) continue;

            # check for  avalid symbol
            $char = $data[$row+$_row][$col+$_col];
            if (str_contains($invalid, $char)) continue;

            # this is a valid part number
            return true;
        }
    }
    # this is not a valid part number
    return false;
}

# helper
function is_part_number(array &$data, int $row, int $col, int $len) {
    
    # check each digit, return true if one found
    for ($i=0; $i<$len; $i++) {
        if (is_part_number_digit($data, $row, $col+$i)) return true;
    }
    # not a valid part_number
    return false;
}

# Part 1
function day_03_part1(string $filename) {

    $data = load_data($filename);
    $part_numbers = [];

    $p='/[\d]+/';

    for ($_row=0; $_row<count($data); $_row++) {

        # extract all numbers with they positons
        preg_match_all($p,trim($data[$_row]),$numbers, PREG_OFFSET_CAPTURE);
        foreach ($numbers[0] as $number) {
            # check if it is a valid part number
            if (is_part_number($data, $_row, $number[1], strlen($number[0]))) {
                # save to the list
                $part_numbers[]=$number[0];
            }
        }
    }
    
    # add all part numbers together
    $result = array_sum($part_numbers);

    return $result;
}


# Part 2
function day_03_part2(string $filename) {

    $result = 0;

    $data = load_data($filename);
    $part_numbers = [];
    $gears = [];
    $connections = [];

    $part_pattern='/[\d]+/'; 
    $gear_pattern='/\*/';

    # find all the part numbers with position
    for ($_row=0; $_row<count($data); $_row++) {

        # extract all numbers with they positons
        preg_match_all($part_pattern,$data[$_row],$numbers, PREG_OFFSET_CAPTURE);
        
        foreach ($numbers[0] as $number) {
            # check if it is a valid part number
            if (is_part_number($data, $_row, $number[1], strlen($number[0]))) {
                # save to the list
                $part_numbers[] = [ $_row, $number[1], $number[0] ];
            }
        }
    }

    # find all the gears with position
    for ($_row=1; $_row<count($data); $_row++) {

        $found = preg_match_all($gear_pattern,$data[$_row],$gears_raw, PREG_OFFSET_CAPTURE);
        
        # skip if no gear symbol was found in the current row
        if (!$found) continue;
        
        # sage gear to the list
        foreach ($gears_raw[0] as $gear) {
            $gears[] = [ $_row, $gear[1], $gear[0] ];
        }
    }
    
    # find all connected part_numbers
    foreach ($gears as $gear) {
        
        list($_row, $_col) = $gear;
        $connection = [];

        # check each possible rows
        for ($i=$_row-1; $i<$_row+1; $i++) {

            # skip non existing rows
            if ($i<0) continue;
            if ($i>count($data)-1) continue;

            # check each part number 
            #NOTE: this could be optimized if the part numbers were stored in a row indexed array, but the we would need to check for missing rows
            foreach ($part_numbers as $part) {

                # skip if the part is to far away
                if ( abs($_row-$part[0])>1 ) continue;

                # check if the gear is adjacent to the part
                if ( ($_col>= $part[1]-1) && ($_col <= $part[1]+strlen($part[2]+1)) ) {
                    # store if it is adjecent
                    if (!in_array($part,$connection)) $connection[] = $part;
                }
            }
        }
        # check if the gear has exactly two connections to part numbers
        if (count($connection)==2) $connections[] = $connection;
        

    }

    # add the gear ratios together
    foreach ($connections as $connection) {
        $result += $connection[0][2] * $connection[1][2];
    }


    return $result;
}

echo "[TEST] Day 03 Part 1: ".day_03_part1('res/test1.dat')."\n"; // 4361
echo "[PROD] Day 03 Part 1: ".day_03_part1('res/input.dat')."\n"; // 544664
echo "[TEST] Day 03 Part 2: ".day_03_part2('res/test1.dat')."\n"; // 467835
echo "[PROD] Day 03 Part 2: ".day_03_part2('res/input.dat')."\n"; // 84495585
