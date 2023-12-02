<?php

# Part 1
function day2_part1(string $filename) {

    $lines = file($filename);

    $pattern = '/(?:Game (?P<id>[\d]+))|([\d]+ (red|green|blue))/';
   
    
    $sum = 0; // summary
    $max = ['red'=>12,'green'=>13,'blue'=>14];

    foreach ($lines as $line) {

        # match the line
        preg_match_all($pattern, $line, $m);

        # extract the game id
        $game_id=$m['id'][0];

        # remove the game id from the grab list
        array_shift($m[0]);

        # inicialize the game draw list
        $game_max=['red'=>0,'green'=>0,'blue'=>0];

        # update the maximum cubes of each color
        foreach (array_map(fn ($value) => explode(' ', $value), $m[0]) as $hand) {
            $game_max[$hand[1]] = max($game_max[$hand[1]],$hand[0]);
        }

        # fail if the game is inpossible
        foreach ($game_max as $gc=>$gm) {
            if ($max[$gc]<$gm) continue 2;
        }
        
        # add game id to summary
        $sum += $game_id;
    
    }

    return $sum;
}


# Part 2
function day2_part2(string $filename) {

    $lines = file($filename);

    $pattern = '/(?:Game (?P<id>[\d]+))|([\d]+ (red|green|blue))/';
   
    
    $sum = 0; // summary
    $max = ['red'=>12,'green'=>13,'blue'=>14];

    foreach ($lines as $line) {

        # match the line
        preg_match_all($pattern, $line, $m);

        # extract the game id
        $game_id=$m['id'][0];

        # remove the game id from the grab list
        array_shift($m[0]);

        # inicialize the game draw list
        $game_max=['red'=>0,'green'=>0,'blue'=>0];

        # update the maximum cubes of each color
        foreach (array_map(fn ($value) => explode(' ', $value), $m[0]) as $hand) {
            $game_max[$hand[1]] = max($game_max[$hand[1]],$hand[0]);
        }

        # snap - till this point this is same as in Part 1

        # calculate the power of the set
        $power = max($game_max['red'],1) * max($game_max['green'],1) * max($game_max['blue'],1);
        
        # add power of the set to summary
        $sum += $power;
    
    }

    return $sum;
}

echo "[TEST] Day 2 Part 1: ".day2_part1('res/test1.dat')."\n"; // 8
echo "[PROD] Day 2 Part 1: ".day2_part1('res/input.dat')."\n"; // 2617
echo "[TEST] Day 2 Part 2: ".day2_part2('res/test1.dat')."\n"; // 2286
echo "[PROD] Day 2 Part 2: ".day2_part2('res/input.dat')."\n"; // 59795


