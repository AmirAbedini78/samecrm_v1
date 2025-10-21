<?php

namespace App\Helpers;

class PersianCalendarHelper
{
    /**
     * Convert Persian date to Gregorian date
     * 
     * @param string $persianDate Persian date in format YYYY/MM/DD
     * @return string Gregorian date in format Y-m-d
     */
    public static function persianToGregorian($persianDate)
    {
        if (empty($persianDate)) {
            return null;
        }

        // Split Persian date
        $parts = explode('/', $persianDate);
        if (count($parts) !== 3) {
            return null;
        }

        $year = (int) $parts[0];
        $month = (int) $parts[1];
        $day = (int) $parts[2];

        // Convert Persian to Gregorian using the algorithm
        $gregorianDate = self::jalaliToGregorian($year, $month, $day);
        
        return sprintf('%04d-%02d-%02d', $gregorianDate[0], $gregorianDate[1], $gregorianDate[2]);
    }

    /**
     * Convert Gregorian date to Persian date
     * 
     * @param string $gregorianDate Gregorian date in format Y-m-d
     * @return string Persian date in format YYYY/MM/DD
     */
    public static function gregorianToPersian($gregorianDate)
    {
        if (empty($gregorianDate)) {
            return null;
        }

        $date = \DateTime::createFromFormat('Y-m-d', $gregorianDate);
        if (!$date) {
            return null;
        }

        $year = (int) $date->format('Y');
        $month = (int) $date->format('m');
        $day = (int) $date->format('d');

        // Convert Gregorian to Persian
        $persianDate = self::gregorianToJalali($year, $month, $day);
        
        return sprintf('%04d/%02d/%02d', $persianDate[0], $persianDate[1], $persianDate[2]);
    }

    /**
     * Convert Jalali (Persian) date to Gregorian
     */
    private static function jalaliToGregorian($j_y, $j_m, $j_d)
    {
        $j_y += 1595;
        $days = -355668 + (365 * $j_y) + ((int)($j_y / 33) * 8) + ((int)(((($j_y % 33) + 3) / 4)) + $j_d);
        if ($j_m < 7) {
            $days += ($j_m - 1) * 31;
        } else {
            $days += (($j_m - 7) * 30) + 186;
        }
        $g_y = 400 * ((int)($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $days--;
            $g_y += 100 * ((int)($days / 36524));
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }
        $g_y += 4 * ((int)($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $g_y += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $g_d = $days + 1;
        if (($g_y % 4 == 0 && $g_y % 100 != 0) || ($g_y % 400 == 0)) {
            $leap = 1;
        } else {
            $leap = 0;
        }
        if ($g_d > 59 + $leap) {
            $g_d += 2 - $leap;
        }
        $g_m = ((int)(($g_d - 1) / 31) + 1);
        if ($g_d > 31) {
            $g_d = ($g_d - 31) % 31;
        }
        return [$g_y, $g_m, $g_d];
    }

    /**
     * Convert Gregorian date to Jalali (Persian)
     */
    private static function gregorianToJalali($g_y, $g_m, $g_d)
    {
        $g_y -= 1600;
        $g_m -= 1;
        $g_d -= 1;
        $g_day_no = 365 * $g_y + ((int)(($g_y + 3) / 4)) - ((int)(($g_y + 99) / 100)) + ((int)(($g_y + 399) / 400)) - 80 + $g_d;
        if ($g_m < 7) {
            $g_day_no += $g_m * 31;
        } else {
            $g_day_no += (($g_m - 7) * 30) + 186;
        }
        $j_day_no = $g_day_no - 79;
        $j_np = ((int)($j_day_no / 12053));
        $j_day_no %= 12053;
        $j_y = 979 + 33 * $j_np + 4 * ((int)($j_day_no / 1461));
        $j_day_no %= 1461;
        if ($j_day_no >= 366) {
            $j_y += ((int)(($j_day_no - 1) / 365));
            $j_day_no = ($j_day_no - 1) % 365;
        }
        if ($j_day_no < 186) {
            $j_m = 1 + (int)($j_day_no / 31);
            $j_d = 1 + ($j_day_no % 31);
        } else {
            $j_m = 7 + (int)(($j_day_no - 186) / 30);
            $j_d = 1 + (($j_day_no - 186) % 30);
        }
        return [$j_y, $j_m, $j_d];
    }

    /**
     * Format Persian date for display
     */
    public static function formatPersianDate($persianDate, $format = 'Y/m/d')
    {
        if (empty($persianDate)) {
            return '';
        }

        $parts = explode('/', $persianDate);
        if (count($parts) !== 3) {
            return $persianDate;
        }

        $year = (int) $parts[0];
        $month = (int) $parts[1];
        $day = (int) $parts[2];

        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];

        $persianDays = [
            1 => 'شنبه', 2 => 'یکشنبه', 3 => 'دوشنبه', 4 => 'سه‌شنبه',
            5 => 'چهارشنبه', 6 => 'پنج‌شنبه', 7 => 'جمعه'
        ];

        switch ($format) {
            case 'Y/m/d':
                return sprintf('%04d/%02d/%02d', $year, $month, $day);
            case 'd/m/Y':
                return sprintf('%02d/%02d/%04d', $day, $month, $year);
            case 'Y-m-d':
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            case 'd-m-Y':
                return sprintf('%02d-%02d-%04d', $day, $month, $year);
            case 'j F Y':
                return sprintf('%d %s %d', $day, $persianMonths[$month], $year);
            default:
                return $persianDate;
        }
    }

    /**
     * Get current Persian date
     */
    public static function getCurrentPersianDate()
    {
        $now = new \DateTime();
        return self::gregorianToPersian($now->format('Y-m-d'));
    }

    /**
     * Validate Persian date
     */
    public static function isValidPersianDate($persianDate)
    {
        if (empty($persianDate)) {
            return false;
        }

        $parts = explode('/', $persianDate);
        if (count($parts) !== 3) {
            return false;
        }

        $year = (int) $parts[0];
        $month = (int) $parts[1];
        $day = (int) $parts[2];

        if ($year < 1300 || $year > 1500) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        if ($day < 1 || $day > 31) {
            return false;
        }

        // Check for leap year and month days
        if ($month <= 6 && $day > 31) {
            return false;
        }
        if ($month > 6 && $day > 30) {
            return false;
        }

        return true;
    }
}
