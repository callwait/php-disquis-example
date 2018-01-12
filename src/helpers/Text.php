<?php

namespace helpers;

class Text
{
    public function relativeTime($dt, $precision = 2)
    {
        $cl = 'ru';

        if ($cl == "en") {
            $times = array(
                365 * 24 * 60 * 60 => "year",
                30 * 24 * 60 * 60 => "month",
                7 * 24 * 60 * 60 => "week",
                24 * 60 * 60 => "day",
                60 * 60 => "hour",
                60 => "minute",
                1 => "second"
            );
        } else {
            $times = array(
                365 * 24 * 60 * 60 => array("год", "года", "лет"),
                30 * 24 * 60 * 60 => array("месяц", "месяца", "месяцев"),
                7 * 24 * 60 * 60 => array("неделя", "недели", "недель"),
                24 * 60 * 60 => array("день", "дня", "дней"),
                60 * 60 => array("час", "часа", "часов"),
                60 => array("минута", "минуты", "минут"),
                1 => array("секунду", "секунды", "секунд")
            );
        }

        $passed = time() - $dt;

        $later = ($passed < 0);
        $passed = abs($passed);

        if ($passed < 5) {
            $output = ($cl == "en") ? "now" : 'сейчас';
        } else {
            $output = array();
            $exit = 0;
            foreach ($times as $period => $name) {
                if ($exit >= $precision || ($exit > 0 && $period < 60)) break;
                $result = floor($passed / $period);

                if ($result > 0) {
                    if ($cl == "en") {
                        $output[] = $result . ' ' . $name . ($result > 1 ? 's' : '');
                    } else {
                        $output[] = $result . ' ' . self::plural($result, $name);
                    }
                    $passed -= $result * $period;
                    $exit++;
                } else if ($exit > 0) $exit++;
            }
            $output = join(($cl == "en") ? " & " : ' и ', $output);

            if ($later) {
                $output = (($cl == "en") ? $output . ' from now' : 'через ' . $output);
            } else {
                $output .= " " . (($cl == "en") ? "ago" : 'назад');
            }
        }
        return $output;
    }

    public function plural($n, &$plurals)
    {
        $plural =
            ($n % 10 == 1 && $n % 100 != 11 ? 0 :
                ($n % 10 >= 2 && $n % 10 <= 4 &&
                ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
        return $plurals[$plural];
    }


    public function humanNum($num, $a1, $a2, $a3)
    {
        if ($num <= 1) {
            return $a1;
        } elseif ($num <= 3) {
            return $a2;
        } else {
            return $a3;
        }
    }

    public function loadSvg($name)
    {
        $file = 'css/svg/' . $name . '.svg';
        if (file_exists($file)) {
            $icon = file_get_contents($file);
            return $icon;
        }
    }
}