<?php

namespace StiavaMerchantsApp\Includes;

class Hours
{
    public $min = null;
    public $breaks = [];
    public $max = null;
    public $asString = "";
    private $hours_regex = "/(\d{1,2}):?(\d{1,2})?([APM]{0,2})?/";

    public function __construct(string $hours)
    {
        preg_match_all($this->hours_regex, $hours, $results);

        // Correct missing AM or PM values
        $HOURvalues = &$results[1];
        $MINvalues = &$results[2];
        $APMvalues = array_reverse($results[3]);
        $partial = "";
        foreach ($APMvalues as $value) {
            if ($value == "") {
                $value = $partial;
            }
            $value = $partial;
        }
        $APMvalues = array_reverse($APMvalues);


        // Loop through each statement
        for ($column = 0; $column < count($results[0]); $column++) {

            // Add hour
            $hour_to_24 = $this->hour_to_24(@floatval($HOURvalues[$column]), $APMvalues[$column]);
            $new = $this->adjust_hour($hour_to_24);

            // If minute specified, add it.
            if ($MINvalues[$column] != "") {
                $new += @floatval($MINvalues[$column]) / 60;
            }

            $this->add($new);
        }

        $this->setString();

    }

    private function isOpen($hour)
    {

        if ($this->min === null) {
            return false;
        }

        if ($hour < $this->min) {
            return false;
        }

        $status = true;
        foreach ($this->breaks as $break) {
            if ($hour < $break) {
                return $status;
            }

            $status = !$status;
        }

        if ($hour < $this->max) {
            return true;
        }

        return false;
    }

    private function add($value)
    {
        // Case 1: no start
        if ($this->min == null) {
            $this->min = $value;
            $this->breaks = [];
            $this->max = null;
            return;
        }

        // Case 2: no end
        if ($this->max == null) {
            $this->max = $value;
            return;
        }

        // Case 3: swap max with value
        array_push($this->breaks, $this->max);
        $this->max = $value;
        return;
    }

    public function compare(Hours $other): bool
    {
        // Min the same
        if ($this->min == $other->min) {
            // Max the same
            if ($this->max == $other->max) {
                // Breaks are the same
                if (empty(array_diff($this->breaks, $other->breaks))) {
                    return true;
                }
            }
        }
        return false;
    }

    public function setString()
    {
        if ($this->min == null || $this->max == null) {
            return "";
        }

        if (count($this->breaks) == 0) {
            $new_string = $this->unadjust_hour_with_amp($this->min) . '-' . $this->unadjust_hour_with_amp($this->max);
            $this->asString = $new_string;
            return;
        }

        $new_string = $this->unadjust_hour_with_amp($this->min);
        $breaks = $this->breaks;
        $end_range = true;
        foreach ($breaks as $i => $item) {
            if ($end_range) {
                $new_string = $new_string . '-' . $this->unadjust_hour_with_amp($item) . ', ';
                $end_range = false;
                continue;
            }
            $new_string = $new_string . $this->unadjust_hour_with_amp($item);
            $end_range = true;
        }
        $new_string = $new_string . '-' . $this->unadjust_hour_with_amp($this->max);

        $this->asString = $new_string;
    }


    private function hour_to_24($hour, $type)
    {

        $type = @strval($type);
        $type = strtoupper($type);

        $hour = @intval($hour);

        if ($type == "AM" || $type == "A") {
            if ($hour == 12) {
                return 0;
            }

            return $hour;
        }

        if ($type == "PM" || $type == "P") {

            if ($hour == 12) {
                return $hour;
            }
            return $hour + 12;
        }


        return $hour;
    }


    private function adjust_hour($hour)
    {
        $hour = @intval($hour);

        $hour = $hour - 4;

        if ($hour < 0) {
            $hour = 24 + $hour;
        }
        return $hour;
    }

    private function unadjust_hour_with_amp($value): string
    {
        $hour = floor($value) + 4;
        $minute = (floatval($value) - floor($value)) * 60;
        $minute_string = strval($minute);
        $apm_string = "am";

        if ($hour == 24) {
            return "Midnight";
        } else if ($hour > 24) {
            $hour -= 24;
        } else if ($hour > 12) {
            $apm_string = "pm";
            $hour -= 12;
        } else if ($hour == 12) {
            return "Noon";
        }

        if ($minute == 0) {
            $minute_string = "";
        } else if ($minute < 10) {
            $minute_string = ':0' . $minute_string;
        } else {
            $minute_string = ':' . $minute_string;
        }

        $final = strval($hour) . $minute_string . $apm_string;

        return $final;
    }

}