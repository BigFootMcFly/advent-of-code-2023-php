<?php

class Day11Challenge {

    //---------------------------------------------------------------------
    protected function load_data(string $filename): array {

        $lines = file('res/'.$filename,FILE_IGNORE_NEW_LINES);
        $data = [];
        foreach ($lines as $line) $data[] = str_split($line);
    
        return $data;
    }

    private function rotateCW($arr): array {
        return array_map(function($row, $i) use ($arr){
            return array_reverse(array_column($arr, $i));
        }, $arr[0], array_keys($arr[0]));
    }

    private function rotateCCW($arr): array {
        return array_map(function($row, $i) use ($arr){
            return array_column($arr, count($arr[0]) - 1 -$i);
        }, $arr[0], array_keys($arr[0]));
    }

    protected function expandMap(array $map): array
    {
        $result = [];

        # expand horizontally
        foreach ($map as $row) {
            $result[] = $row;
            if (!in_array('#', $row)) $result[] = $row; # duplicate
        }

        # rotate clockwise
        $tmp = $this->rotateCW($result);
        $result = [];

        # expand vertically
        foreach ($tmp as $row) {
            $result[] = $row;
            if (!in_array('#', $row)) $result[] = $row; # duplicate
        }

        # restore rotation and return the result
        return $this->rotateCCW($result);
    }

    private function findGalaxies(array $map): array
    {
        $result = [];
        for ($row = 0; $row < count($map); $row++) {
            for ($col = 0; $col < count($map[$row]) ; $col++) {
                if ($map[$row][$col] == '#') $result[] = [$row,$col];
            }
        }
        return $result;
    }

    private function countExpandingRegions(array $region, int $max): int {

        $sum = 0;
        foreach ($region as $item) {
            if ($item>=$max) break;
            $sum++;
        }
        return $sum;

    }

    public function calcGalaxyDistances(array $galaxies): array
    {
        $distances = [];

        for ($i = 0; $i < count($galaxies); $i++) {
            for ($j = 0; $j < count($galaxies); $j++) {
                if ($i == $j) continue; // dont measure self
                $name = min($i,$j).'-'.max($i,$j);
                if (array_key_exists($name,$distances)) continue; // already stored
                $a = $galaxies[$i];
                $b = $galaxies[$j];
                # calculate distance
                $distance = abs($a[0]-$b[0]) + abs($a[1]-$b[1]);
                # store distance
                $distances[$name] = [$galaxies[$i], $galaxies[$j], $distance];
            }
        }

        return $distances;
    }

    public function solvePart1(string $filename): int
    {

        $map = $this->load_data($filename);

        $map = $this->expandMap($map);
        $galaxies = $this->findGalaxies($map);

        $distances = $this->calcGalaxyDistances($galaxies);

        # calculate sum
        $sum = 0;
        array_walk($distances, function ($dist) use (&$sum) { $sum += $dist[2];});

        return $sum;
    }

    public function solvePart2(string $filename, int $expansionMultiplier=1000000): int
    {

        $map = $this->load_data($filename);
        $galaxies = $this->findGalaxies($map);

        $expandingColumns = [];
        $expandingRows = [];

        # find expanding rows
        for ($row = 0; $row < count($map); $row++) {
            if (!in_array('#',$map[$row])) $expandingRows[] = $row;
        }

        # find expanding columns
        $tmp = $this->rotateCW($map);
        for ($row = 0; $row < count($tmp); $row++) {
            if (!in_array('#',$tmp[$row])) $expandingColumns[] = $row;
        }
        unset($tmp);

        # update galaxy coordinates
        foreach ($galaxies as &$galaxy) {
            $galaxy[0] += ($expansionMultiplier-1) * $this->countExpandingRegions($expandingRows, $galaxy[0]);
            $galaxy[1] += ($expansionMultiplier-1) * $this->countExpandingRegions($expandingColumns, $galaxy[1]);
        }

        # calculate sum
        $sum = 0;
        $distances = $this->calcGalaxyDistances($galaxies);
        array_walk($distances, function ($dist) use (&$sum) { $sum += $dist[2];});

        return $sum;
    }


}

$challenge = new Day11Challenge();

echo "[TEST] Day 11 Part 1: ".$challenge->solvePart1('test1.dat')."\n"; // 374
echo "[PROD] Day 11 Part 1: ".$challenge->solvePart1('input.dat')."\n"; // 9521550
//NOTE: part 2 can be used to solve par 1 as well...
// echo "[PROD] Day 11 Part 1: ".$challenge->solvePart2('input.dat',2)."\n"; // 9521550
echo "[TEST] Day 11 Part 2 (10x): ".$challenge->solvePart2('test1.dat',10)."\n"; // 1030
echo "[TEST] Day 11 Part 2 (100x): ".$challenge->solvePart2('test1.dat',100)."\n"; // 8410
echo "[PROD] Day 11 Part 2 (1.000.000x): ".$challenge->solvePart2('input.dat',1000000)."\n"; // 298932923702


