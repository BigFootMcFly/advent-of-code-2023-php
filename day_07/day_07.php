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
        $hand['type'] = $hand_type;
        $data['hands'][] = $hand;
    }


    return $data ?: $lines;
}

function load_data_part2(string $filename): array {

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

        // count cards in hand 
        foreach ($card_types as $card_type) {
            $hand['card_types'][$card_type] = substr_count($hand['cards'],$card_type);
        }

        // count jokers in hand
        $hand['jokers'] = substr_count($hand['cards'],'J');
        $jokers = intval($hand['jokers']);

        # do not calculate jokers in the result jet
        $tmp = $hand['card_types'];
        unset($tmp['J']);
        $counts = array_count_values($tmp);


        // Five of a kind, what a lucky call...
        if ($jokers == 5 ) {
            $hand_type = 0;
            goto store_hand;
        }

        // Five of a kind, with or without joker(s)
        if (array_key_exists(5-$jokers, $counts)) {
            $hand_type = 0;
            goto store_hand;
        }

        // Four of a kind, with or without joker(s)
        if (array_key_exists(4-$jokers, $counts)) {
            $hand_type = 1;
            goto store_hand;
        }

        // Full house
        if (array_key_exists(3, $counts) && array_key_exists(2, $counts)) {
            $hand_type = 2;
            goto store_hand;
        }
        
        // Full house, with one joker
        if ($jokers==1 &&array_key_exists(2, $counts) && $counts[2]==2) {
            $hand_type = 2;
            goto store_hand;
        }

        // Three of a kind
        if (array_key_exists(3, $counts)) {
            $hand_type = 3;
            goto store_hand;
        }

        // Three of a kind, with one joker
        if ($jokers==1 && array_key_exists(2, $counts)) {
            $hand_type = 3;
            goto store_hand;
        }
        // Three of a kind, with two joker
        if ($jokers==2) { 
            $hand_type = 3;
            goto store_hand;
        }

        // Two pair
        if (array_key_exists('2', $counts) && $counts['2']==2) {
            $hand_type = 4;
            goto store_hand;
        }

        // One pair
        if (array_key_exists('2', $counts)) {
            $hand_type = 5;
            goto store_hand;
        }
        
        // One pair, with one joker
        if ($jokers==1) {
            $hand_type = 5;
            goto store_hand;
        }

        // As last resort High card
        $hand_type = 6;


    store_hand:
        $hand['type'] = $hand_type;
        $data['hands'][] = $hand;
    }


    return $data ?: $lines;
}


# Part 1
function day_07_part1(string $filename) {

    $data = load_data($filename);

    // sort data, from least valuable to highes rank
    usort($data['hands'], function ($a, $b) {
        $card_types = 'AKQJT98765432';
        
        // check for type difference
        $type_compare = $b['type'] <=> $a['type'];
        if ($type_compare !== 0) return $type_compare;
        
        // check for stronger hand
        $i = strspn($a['cards'] ^ $b['cards'], "\0");
        
        return strpos($card_types,$b['cards'][$i]) <=> strpos($card_types,$a['cards'][$i]);
    });

    // calculate total winnings
    $winning = 0;
    array_walk($data['hands'], function ($hand, $key) use (&$winning) { $winning += ($key+1)*$hand['bid'];});

    return $winning;
}


# Part 2
function day_07_part2(string $filename) {

    $data = load_data_part2($filename);

    // sort data, from least valuable to highes rank
    usort($data['hands'], function ($a, $b) {
        $card_types = 'AKQT98765432J';

        // check for type difference
        $type_compare = $b['type'] <=> $a['type'];
        if ($type_compare !== 0) return $type_compare;

        // check for stronger hand
        $i = strspn($a['cards'] ^ $b['cards'], "\0");
        return strpos($card_types,$b['cards'][$i]) <=> strpos($card_types,$a['cards'][$i]);
    });

    // calculate total winnings
    $winning = 0;
    array_walk($data['hands'], function ($hand, $key) use (&$winning) { $winning += ($key+1)*$hand['bid'];});

    return $winning;
}

echo "[TEST] Day 07 Part 1: ".day_07_part1('res/test1.dat')."\n"; // 6440
echo "[PROD] Day 07 Part 1: ".day_07_part1('res/input.dat')."\n"; // 247815719
echo "[TEST] Day 07 Part 2: ".day_07_part2('res/test1.dat')."\n"; // 5905
echo "[PROD] Day 07 Part 2: ".day_07_part2('res/input.dat')."\n"; // 248747492


