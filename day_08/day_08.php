<?php


function load_data(string $filename): object {

    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $data = [
        'instructions' => $lines[0],
        'nodes' => []
    ];

    $pattern_node = '/[\w]{3}/';

    # get all the node informations
    for ($line_index=2; $line_index<count($lines); $line_index++) {
        preg_match_all($pattern_node, $lines[$line_index], $matches);
        $data['nodes'][$matches[0][0]] = (object)['name'=>$matches[0][0],'L'=>$matches[0][1],'R'=>$matches[0][2]];
    }  

    # return as an object for fun this time
    return (object)$data;
}

# Part 1
function day_08_part1(string $filename) {

    $data = load_data($filename);

    $steps = 0;
    $instruction_index = 0;

    # initialize starting state
    $instruction = $data->instructions[$instruction_index++];
    $current_node = $data->nodes['AAA'];

    do {
        
        # get next instruction
        $current_node = $data->nodes[$current_node->$instruction];
        
        # increase step count
        $steps++;
        
        # get next instruction
        if ($instruction_index>=strlen($data->instructions)) {
            $instruction_index = 0;
        }
        $instruction = $data->instructions[$instruction_index++];

    } while ($current_node->name != 'ZZZ');

    return $steps;
}


# Part 2
//NOTE: This may take a while... (just left it here ho historicall reasons)
//NOTE: SO NOT TRY THIS AT HOME! (use 'day_08_part2' instead)
function day_08_part2_brute_force(string $filename) {

    $data = load_data($filename);

    $steps = 0;
    $instruction_index = 0;

    # initialize starting state
    $instruction = $data->instructions[$instruction_index++];

    $current_nodes = [];
    # get all startting nodes
    foreach ($data->nodes as $node) {
        if ($node->name[2] == 'A') {
            $current_nodes[] = $node;
        }
    }
    $finished = false;
    do {

        $finished = true;
        for ($node_index=0; $node_index<count($current_nodes); $node_index++)  {
            $current_nodes[$node_index] = $data->nodes[$current_nodes[$node_index]->$instruction];
            
            if ($current_nodes[$node_index]->name[2] != 'Z') $finished = false;
        }
        # count steps
        $steps++;
        if ($steps % 1000 == 0) echo "$steps\n";
        # get next instruction
        if ($instruction_index>=strlen($data->instructions)) {
            $instruction_index = 0;
        }
        $instruction = $data->instructions[$instruction_index++];

    } while (false === $finished);
    
    return $steps;
}

# Part 2 helpers

// Euclidean algorithm
function greatest_common_divider(int $number_1, int $number_2): int {
    if ($number_2 == 0) return $number_1;

    return greatest_common_divider($number_2, $number_1 % $number_2);
}

function least_common_multiple(array $numbers): int {

    $current = $numbers[0];

    for ($number_index=1; $number_index<count($numbers); $number_index++) {
        $current = ((($numbers[$number_index] * $current)) / (greatest_common_divider($numbers[$number_index], $current)));
    }
    return $current;
}

# Part 2 - using least common multiple
function day_08_part2(string $filename) {

    $data = load_data($filename);

    $steps = 0;
    $instruction_index = 0;
    # initialize starting state
    $instruction = $data->instructions[$instruction_index++];

    $current_nodes = [];
    $steps = [];
    # get all startting nodes
    foreach ($data->nodes as $node) {
        if ($node->name[2] == 'A') {
            $current_nodes[] = $node;
            $steps[] = 0;
        }
        
    }

    // for each starting node find find the snding node
    for ($i=0; $i<count($current_nodes); $i++) {
        $current_node = $current_nodes[$i];

        // count the steps needed to find the ending node
        do {
            
            # get next instruction
            $current_node = $data->nodes[$current_node->$instruction];
            
            # increase step count
            $steps[$i]++;
            
            # get next instruction
            if ($instruction_index>=strlen($data->instructions)) {
                $instruction_index = 0;
            }
            $instruction = $data->instructions[$instruction_index++];

        } while ($current_node->name[2] != 'Z');

    }

    # calculate the result
    $result = least_common_multiple($steps);

    return $result;
}

echo "[TEST] Day 08 Part 1: ".day_08_part1('res/test1.dat')."\n"; // 2
echo "[TEST] Day 08 Part 1: ".day_08_part1('res/test2.dat')."\n"; // 6
echo "[PROD] Day 08 Part 1: ".day_08_part1('res/input.dat')."\n"; // 19241
echo "[TEST] Day 08 Part 2: ".day_08_part2('res/test3.dat')."\n"; // 6
echo "[PROD] Day 08 Part 2: ".day_08_part2('res/input.dat')."\n"; // 9606140307013


