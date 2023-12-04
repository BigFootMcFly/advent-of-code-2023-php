<?php

function load_data(string $filename): array {
    
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $result = [];
    $p='/[\d]+/';
    
    foreach ($lines as $line) {
        
        # remove unnecessary information
        $tmp = explode(':',$line);
        
        # separate to winning numbers and card munbers
        $tmp = explode('|', $tmp[1]);
        
        # parse all
        preg_match_all($p, $tmp[0],$w);
        preg_match_all($p, $tmp[1],$n);
        
        # ad to result 
        $result[] = [$w[0], $n[0]];
    }

    return $result;

}



# Part 1
function day_04_part1(string $filename) {

    $sum = 0;
    $data = load_data($filename);

    foreach ($data as $card) {
        
        # count the winning numbers 
        $x = count(array_intersect($card[0], $card[1]));
        
        #skip if none win, it is worthless
        if ($x == 0) continue;

        # calculate the worth of the card
        $sum += pow(2, $x-1);
    }

    return $sum;
}


# Part 2
function day_04_part2(string $filename) {

    $raw = load_data($filename);
    $data = [];
    $sum = 0;

    # update data structure to a more readable way and extend with the new fields
    foreach ($raw as $card) {
        $card_data['wins'] = $card[0];
        $card_data['nums'] = $card[1];
        $card_data['found'] = count(array_intersect($card[0], $card[1]));
        $card_data['count'] = 1;
        $data[] = $card_data;
    }

    # calculate the number of card including the copies
    for ($card_id=0; $card_id<count($data); $card_id++) {
        
        $card = $data[$card_id];
        for ($i = 1; $i<= $card['found']; $i++) {
  
            # make sure we dont try to go beyond the card count
            if (count($data)<$card_id) continue;
  
            # add won cards to the pile
            $data[$card_id+$i]['count'] += $card['count'];
        }
    }
    
    # count all the cards
    for ($i=0; $i<count($data); $i++) $sum += $data[$i]['count'];

    return $sum;
}

echo "[TEST] Day 04 Part 1: ".day_04_part1('res/test1.dat')."\n"; // 13
echo "[PROD] Day 04 Part 1: ".day_04_part1('res/input.dat')."\n"; // 26218
echo "[TEST] Day 04 Part 2: ".day_04_part2('res/test1.dat')."\n"; // 30
echo "[PROD] Day 04 Part 2: ".day_04_part2('res/input.dat')."\n"; // 9997537
