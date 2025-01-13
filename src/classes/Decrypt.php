<?php
require_once __DIR__ . '/Encrypt.php';
require_once __DIR__ . '/Rainbow.php';

class Decrypt {

    public static function decryptTripcodeList($tripcodeList) {
        $encObj = new Encrypt();
        $rainbowObj = new Rainbow();
        $decyptedArr = [];
        foreach($tripcodeList as $tripcode) {
            //$keyVal = array($tripcode, $rainbowObj->retrieveFromRainbowTable($tripcode));
            //array_push($decyptedArr['decryptedTripcode'], $keyVal);
            $decyptedArr['decrypted'][$tripcode] = $rainbowObj->retrieveFromRainbowTable($tripcode);
        }
        return json_encode($decyptedArr);
    }


    public static function wordlistDecrypt(array $wordlist_file_arr, $targetList) {
        $encryptObj = new Encrypt();
        for ($i=0; $i<count($wordlist_file_arr); $i++) {
            $wl = $wordlist_file_arr[$i];   
            echo "> Using wordlist file: {$wl} " . "\n";
                if ($targetList) {
                    $tlpointer = fopen($targetList, 'r') or die("> Target list not found: {$targetList}");
                    while ($word_from_targetlist = trim(fgets($tlpointer))) {
                        $wlpointer = fopen($wl, 'r') or die("> Dictionary file not found: {$wl}"); 
                        while($word_from_wordlist = trim(fgets($wlpointer))) {
                            $enc_word_from_wordlist = $encryptObj->encryptString($word_from_wordlist);
                            if (trim($enc_word_from_wordlist) == $word_from_targetlist) {
                                echo "> Tripcode target FOUND: {$word_from_targetlist} | {$word_from_wordlist} \n";
                                file_put_contents(realpath(__DIR__ . '/../../tmp') . '/tripStatus.txt', $word_from_wordlist);
                                if (count(file($targetList)) > 1) break;
                                exit;
                            }
                        }
                        fclose($wlpointer);
                    }
                }
            }
        return true;
    }
}

// debug
/*
require_once __DIR__ . '/WordList.php';
require_once __DIR__ . '/Trip.php';

$wordList = new WordList();
$preparedWordList = $wordList->prepareWordListFile('ignis-10M.txt');
$tripFile = new Trip();
$preparedTripFile = $tripFile->singleTripFile('QZ5rf9d3T.');

$asyncObj = new Decrypt();
$asyncObj->asyncDecrypt($preparedWordList, $preparedTripFile)->then(function ($result) {
    echo $result;
});
*/