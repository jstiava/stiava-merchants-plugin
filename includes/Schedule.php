<?php


namespace StiavaMerchantsApp\Includes;

use StiavaMerchantsApp\Includes\Hours;

class Schedule
{
    public $name = "";
    public $start = 0;
    public $end = 0;

    /**
     * [M, Tu, W, Th, F, Sa, Su]
     * [0, 0, 0, 1, 1, false, true]
     */
    public $days = array(false, false, false, false, false, false, false);

    /**
     * Array of Hours instances
     */
    public $schemes = [];

    private $days_regex = "/(?!AM|PM)[A-Z]{2,8}/";
    private $comma_separated_days = "/((?!AM|PM)[A-Z]{2,8}),((?!AM|PM)[A-Z]{2,8})/";
    private $split_regex = "/(?:;|\n)+/";

    private $getDayIndex = [
        'MO' => 0,
        'TU' => 1,
        'WE' => 2,
        'TH' => 3,
        'FR' => 4,
        'SA' => 5,
        'SU' => 6
    ];

    public function __construct(string $input)
    {

        if ($input == "") {
            return;
        }
        // Clean
        $new_string = $this->clean_string($input);

        // Split
        $strings_array = preg_split($this->split_regex, $new_string);

        for ($index = 0; $index < count($strings_array); $index++) {
            $str = $strings_array[$index];

            // echo $onstring . PHP_EOL;
            $str = str_replace("NOON", "12pm", $str);
            $str = str_replace("MIDNIGHT", "12am", $str);

            // echo $str;
            while (preg_match($this->comma_separated_days, $str, $match)) {
                $new_line = preg_replace($this->comma_separated_days, "$2", $str);
                $strings_array[]  = $new_line;
  
                $str = preg_replace($this->comma_separated_days, "$1",  $str);
            }


            
            // Collect array of large words from the string
            preg_match_all($this->days_regex, $str, $words);

            // Get days array
            $day_range_array = $this->get_day_range_from_all_words($words[0]);
            // echo json_encode($day_range_array) . PHP_EOL;

            if (is_bool($day_range_array)) {
                continue;
            }

            // CLOSED
            if (in_array('CLOSED', $words[0])) {
                $this->spread_pointers_across_days($day_range_array, false);
                continue;
            }

            // OPEN
            if (in_array('OPEN', $words[0])) {
                $this->spread_pointers_across_days($day_range_array, true);
                continue;
            }

            // Converts a statement to a series of instructions
            $new_scheme = new Hours($str);


            $found_similar = false;
            foreach ($this->schemes as $i => $scheme) {
                if ($new_scheme->compare($scheme)) {
                    $this->spread_pointers_across_days($day_range_array, $i);
                    $found_similar = true;
                }
            }

            if (!$found_similar) {
                $index = $this->add_scheme($new_scheme);
                $this->spread_pointers_across_days($day_range_array, $index);
            }

        }
    }

    private function add_scheme($new_scheme)
    {
        array_push($this->schemes, $new_scheme);
        return sizeof($this->schemes) - 1;
    }

    public function clean_string(string $input)
    {
        $new_string = str_replace(['-', '–', '—', '/', '\\'], '-', $input);
        $new_string = str_replace(["to", "until", "til"], '-', $new_string);
        $new_string = str_replace("\n", ";", $new_string);
        $new_string = preg_replace('/[^A-Za-z0-9:;\-,]/', '', $new_string);
        $new_string = strtoupper($new_string);

        return $new_string;
    }

    private function spread_pointers_across_days($days_range, $value)
    {
        while ($days_range[0] !== $days_range[1]) {

            $this->days[$days_range[0]] = $value;

            $days_range[0]++;
            if ($days_range[0] > 6) {
                $days_range[0] = 0;
            }
        }

        $this->days[$days_range[1]] = $value;

        return;
    }

    private function get_day_range_from_all_words($words)
    {
        $range = [];

        $all_words = array_intersect($words, ["EVERYDAY", "DAILY", "ALL"]);
        if (count($all_words) > 0) {
            return [0, 6];
        }

        if (in_array("WEEKDAYS", $words)) {
            return [0, 4];
        }

        $weekend_words = array_intersect($words, ["WEEKEND", "WEEKENDS"]);
        if (count($weekend_words) > 0) {
            return [5, 6];
        }

        foreach ($words as $word) {
            if (count($range) == 2) {
                break;
            }

            $value = substr($word, 0, 2);
            if (array_key_exists($value, $this->getDayIndex)) {
                array_push($range, $this->getDayIndex[$value]);
            }
        }

        if (count($range) == 1) {
            array_push($range, $range[0]);
        } else if (count($range) == 0 || count($range) > 2) {
            $range = false;
        }

        return $range;
    }

    private function get_day_name($index)
    {
        switch ($index) {
            case 0:
                return "Mon";
            case 1:
                return "Tue";
            case 2:
                return "Wed";
            case 3:
                return "Thu";
            case 4:
                return "Fri";
            case 5:
                return "Sat";
            case 6:
                return "Sun";
        }
    }


    private function preclean_days_pointer($index)
    {
        $string = $this->days[$index];

        if (is_bool($string)) {
            return $string ? "Open All Day" : "Closed";
        } else {
            return $this->schemes[$string]->asString;
        }
    }

    private function is_next_same($current, $next)
    {
        if (!isset($next)) {
            return false;
        }
        if (is_bool($current)) {
            if (is_bool($next)) {
                if ($current == $next) {
                    return true;
                }
            }
            return false;
        }
        if ($current == $next) {
            if (is_bool($next)) {
                return false;
            }
            return true;
        }
        return false;
    }


    public function stringify()
    {
        $sets = $this->hours_abstractor();
        return implode('; ', $sets);
    }

    public function hours_abstractor()
    {
        $sets = [];

        $start = null;
        $days = $this->days;
        
        // Loop through each day in days.
        for ($current = 0; $current < count($days); $current++) {

            if (is_null($days[$current])) {
                continue;
            }
            
            // Collect matching days
            $collected_days = [$current];
            for ($i = $current + 1; $i < count($days); $i++) {
                if (is_null($days[$i])) {
                    continue;
                }
                if ($days[$i] === $days[$current]) {
                    $collected_days[] = $i;
                    $days[$i] = null;
                }
            }
            
            // Construct days string
            $strings = [];
            $last = -1;
            while (!empty($collected_days)) {
                $target = array_shift($collected_days);
                
                // Base case
                if (empty($strings)) {
                    $strings[] = $this->get_day_name($target);
                }
                // consecutive
                else if ($last == $target - 1) {
                    if (strpos($strings[count($strings) - 1], '-') !== false) {
                        array_pop($strings);
                    }
                    $strings[] = '-' . $this->get_day_name($target);
                }
                // non-consec
                else {
                    $strings[] = ', ' . $this->get_day_name($target);
                }
                
                $last = $target;
            }

            $days_string = implode('', $strings);
            if ($collected_days === [0, 1, 2, 3, 4]) {
                $days_string = "Weekdays";
            }
            else if ($collected_days === [5, 6]) {
                $days_string = "Weekends";
            }

            $sets[] = $days_string . ': ' . $this->preclean_days_pointer($current);
        
        }

        return $sets;
    }
}