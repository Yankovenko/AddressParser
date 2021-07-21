<?php

namespace Yankovenko\utils;

class AddressParser
{
    static public function parse($addr)
    {
        $oaddr = $addr;
        $res = [];
        $addr = self::trim($addr);

        if (preg_match('/(\d{6})/u', $addr, $match)) {
            $res['index'] = $match[1];
            $addr = str_replace($match[1], ' ', $addr);
        }
        $stripped = explode (';', $addr);
        if (count($stripped) == 3) {
            $res['street'] = self::trim($stripped[0]);
            $res['house'] = self::stripHouse($stripped[1]);
            $res['room'] = self::tripRoom($stripped[2]);
            return $res;
        }

        if (preg_match_all('/(\d+)/u', $addr, $match)) {
            $cntDig = sizeof($match[1]);
        }
        if (($cntDig == 2 && preg_match('/(\d+)\s*\-\s*(\d+)/u', $addr, $match))
            || preg_match('/(\d+)\s*\-\s*(\d+)$/u', $addr, $match)
            ) {
                $res['house'] = self::trim($match[1]);
                $res['room'] = self::trim($match[2]);
                $addr = str_replace($match[0], '', $addr);
                $res['street'] = self::trim($addr);
                return $res;
        }

        if (preg_match('/(кв|к|оф|офис|о|каб)[\.,]?\s*([\d\/\\\]+[^\d]*)$/iu', $addr, $match)) {
            $res['room'] = self::trim($match[2]);
            $addr = self::trim(str_replace ($match[0], ' ', $addr));
        }
        if (preg_match('/(дом|д)[\.,]?\s*([\d\/\\\]+[^\d]*)$/iu', $addr, $match)) {
            $res['house'] = self::trim($match[2]);
            $addr = self::trim(str_replace ($match[0], ' ', $addr));
        }

        if (preg_match('/(дом|д)[\.,](.*)$/iu', $addr, $match)) {
            $res['house'] = self::trim($match[2]);
            $addr = self::trim(str_replace ($match[0], ' ', $addr));
        }

        if (preg_match('/\d+(.{0,10})$/iu', $addr, $match)) {
            $res['house'] = self::trim($match[0]);
            $addr = self::trim(str_replace ($match[0], ' ', $addr));
        }

        if (isset($res['room']) || isset ($res['house'])) {
            $res['street'] = self::trim($addr);
            return $res;
        }

        if (preg_match('/[.,;][\D]+$/', $oaddr, $match)) {
            $addr = str_replace ($match[0], '', $oaddr);
            return self::parse($addr);
        }
        return null;
    }

    static protected function trim($str) {
        return trim($str, " \t\n\r\0\x0B,.;");
    }

    static protected function stripRoom($str) {
        $str = preg_replace('/(кв|к|оф|офис|о|каб)[\.,]?/iu', '', $str);
        return self::trim($str);
    }

    static protected function stripHouse($str) {
        $str = preg_replace('/(дом|д)[\.,]?/iu', '', $str);
        return self::trim($str);
    }
}