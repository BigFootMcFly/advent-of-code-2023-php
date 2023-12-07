<?php

// NOTE: i dont think ever used gotos in any php project...
// so, let's have some fun by using it
function load_data(string $filename): array {

    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $data = [
        'card_types' => 'AKQJT98765432',
        'hand_types' => [
            'Five of a kind',   // 0
            'Four of a kind',   // 1
            'Full house',       // 2
            'Three of a kind',  // 3
            'Two pair',         // 4
            'One pair',         // 5
            'High card'         // 6
        ],
        'hands'=>[]
    ];

    $card_types = str_split($data['card_types'],1);

    foreach ($lines as $line) {
        
        // parsing hand
        $hand = [
            'cards' => strtok($line, ' '),
            'bid' => intval(strtok(' ')),
            'card_types' => [],
            'type' => 6     // defaults to 'High card'
        ];

        foreach ($card_types as $card_type) {
            $hand['card_types'][$card_type] = substr_count($hand['cards'],$card_type);
        }

        $counts = array_count_values($hand['card_types']);


        if (array_key_exists('5',$counts)) { // Five of a kind
            $hand_type = 0;
            goto store_hand;
        }

        if (array_key_exists('4',$counts)) { // Four of a kind
            $hand_type = 1;
            goto store_hand;
        }

        if (array_key_exists('3',$counts) && array_key_exists('2',$counts)) { // Full house
            $hand_type = 2;
            goto store_hand;
        }

        if (array_key_exists('3',$counts)) { // Three of a kind
            $hand_type = 3;
            goto store_hand;
        }

        if (array_key_exists('2',$counts) && $counts['2']==2) { // Two pair
            $hand_type = 4;
            goto store_hand;
        }

        #NOTE: count==1 not realy required here..
        if (array_key_exists('2',$counts) && $counts['2']==1) { // One pair
            $hand_type = 5;
            goto store_hand;
        }

        $hand_type = 6;


    store_hand:
        // type_name is just here for fun or debugging...
        $hand['type_name'] = $data['hand_types'][$hand_type];
        $hand['type'] = $hand_type;
        $data['hands'][] = $hand;
    }


    return $data ?: $lines;
}

# Part 1
function day_07_part1(string $filename) {

    $data = load_data($filename);

    usort($data['hands'], function ($a, $b) {
        $card_types = 'AKQJT98765432';
        $type_compare = $b['type'] <=> $a['type'];
        if ($type_compare !== 0) return $type_compare;
        $i = strspn($a['cards'] ^ $b['cards'], "\0");
        return strpos($card_types,$b['cards'][$i]) <=> strpos($card_types,$a['cards'][$i]);
    });

    $winning = 0;
    array_walk($data['hands'], function ($hand, $key) use (&$winning) { $winning += ($key+1)*$hand['bid'];});

    return $winning;
}


# Part 2
function day_07_part2(string $filename) {

    $data = load_data($filename);

    $result = null;

    return $result;
}

echo "[TEST] Day 07 Part 1: ".day_07_part1('res/test1.dat')."\n"; // 6440
echo "[PROD] Day 07 Part 1: ".day_07_part1('res/input.dat')."\n"; // 247815719
#echo "[TEST] Day 07 Part 2: ".day_07_part2('res/test1.dat')."\n"; // 2286
#echo "[PROD] Day 07 Part 2: ".day_07_part2('res/input.dat')."\n"; // 59795


