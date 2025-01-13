<?php

class Wordlist {
    private static $wordListDir;

    public static function getWordListDir() {
        if (!isset(self::$wordListDir)) {
            self::$wordListDir = realpath(__DIR__ . '/../../wordlists') . '/';
        }
        return self::$wordListDir;
    }

    public static function prepareWordListFile($wordListFile) {
        $wordListDir = self::getWordListDir();
        return [$wordListDir . $wordListFile];
    }

    public static function prepareWordListDir() {
        $wordListDir = self::getWordListDir();
        $wordLists = array_filter(scandir($wordListDir), function ($item) {
            return !in_array($item, ['.', '..']);
        });

        return array_map(function ($wordList) use ($wordListDir) {
            return $wordListDir . $wordList;
        }, $wordLists);
    }
}
// debug
/*
$p = new Wordlist();
print_r($p->prepareWordListDir());
*/