<?php

class Trip {
    private static $tmpDir;

    public static function getTmpDir() {
        if (!isset(self::$tmpDir)) {
            self::$tmpDir = realpath(__DIR__ . '/../../tmp') . '/';
        }
        return self::$tmpDir;
    }

    public static function singleTripFile($targetTripcode) {
        $tmpDir = self::getTmpDir();
        $tmpTripFile = $tmpDir . 'tripfile' . rand();
        file_put_contents($tmpTripFile, $targetTripcode);
        return $tmpTripFile;
    }
}