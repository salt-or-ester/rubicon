<?php 
/* 
* sailboat-anon | fairwinds! | https://github.com/sailboat-anon
* pr1 - private reserve
* tripcode tester (wordlist and bruteforce) | rubicon.php
* 
*                                 |
*                                  |
*                           |    __-__
*                         __-__ /  | (
*                        /  | ((   | |
*                      /(   | ||___|_.  .|
*                    .' |___|_|`---|-'.' (
*               '-._/_| (   |\     |.'    \
*                   '-._|.-.|-.    |'-.____'.
*                       |------------------'
*                        `----------------'   
* 
* install:  git clone git://github.com/sailboat-anon/rubicon.git ; cd rubicon ; composer install
* use:  php rubicon.php --help
*/

include "vendor/autoload.php";
use yidas\BruteForceAttacker; // https://github.com/yidas/brute-force-attacker-php
$getopts = new Fostam\GetOpts\Handler(); // https://github.com/fostam/php-getopt
$debug = false;
$logfile_bool = false;

echo <<<EOT
            _     _                         _           
           | |   (_)                       | |          
 _ __ _   _| |__  _  ___ ___  _ __    _ __ | |__  _ __  
| .__| | | | '_ \| |/ __/ _ \| ._ \  | ._ \| ._ \| ._ \ 
| |  | |_| | |_) | | (_| (_) | | | |_| |_) | | | | |_) |
|_|   \__,_|_.__/|_|\___\___/|_| |_(_) .__/|_| |_| .__/ 
        sailboat-anon@gh             | |  pvtrsv | |    
                                     |_|         |_|    


EOT;

$getopts->addOption('targetTripcode') // sailb | JPgyvDQMfk
    ->short('t')->argument('target-tripcode')
    ->description('target tripcode to test');
$getopts->addOption('targetList')
    ->long('targets')->argument('targets-list')
    ->description('file filled with tripcodes to test, one per line; cant be used with -t');
$getopts->addOption('wordlistFile')
    ->short('w')->argument('wordlist-file')->multiple()
    ->long('wordlist')
    ->description('textfile containing all keywords to test, one per line');
$getopts->addOption('wordlistDir')
    ->long('dir')->argument('wordlist-directory')
    ->description('try all wordlists in a directory (must be .txt)');
$getopts->addOption('bruteforceFlag') // AAAAAAAA | DLUg7SsaxM
    ->short('b')
    ->description('use bruteforce');
$getopts->addOption('bruteforceMin')
    ->long('min')->argument('min_char')
    ->description('minimum number of characters (bruteforce only)');
$getopts->addOption('bruteforceMax')
    ->long('max')->argument('max_char')
    ->description('maximum number of characters (bruteforce only)');
$getopts->addOption('justEncrypt')
    ->short('e')->argument('string')
    ->long('encrypt')
    ->description('run a string through the tripcode encryption algo (for testing)')
    ->defaultValue(false);
$getopts->addOption('verboseLevel')
    ->short('v')
    ->long('verbose')
    ->description('increase verbosity (debug); will be slower due to i/o');
$getopts->parse();
$opts = $getopts->get();

echo "> Crossing the rubicon...\n";

// just encrypt a string and exit
if ($opts['justEncrypt']) {
    $enc_usr_string = encrypt($opts['justEncrypt']);
    echo $enc_usr_string . "\n";
    exit;
}

if ($opts['verboseLevel']) {
    $debug = true;
    echo "> Verbose mode\n";
}

if (!is_null($opts['targetTripcode']) && !is_null($opts['targetList'])) { $getopts->getHelpText(); }

if ($opts['targetTripcode']) {
    $target_tripcode = $opts['targetTripcode'];
    if ($opts['wordlistFile'])  {
        wordlist($target_tripcode, $opts['wordlistFile']);
    }
    if ($opts['wordlistDir'])  {
        wordlist_directory($target_tripcode, $opts['wordlistDir']); 
    }
    if ($opts['bruteforceFlag']) {
        if ($opts['bruteforceMin'] && $opts['bruteforceMax']) {
            brute($target_tripcode, $opts['bruteforceMin'], $opts['bruteforceMax']);
        }
        else {
            brute($target_tripcode);
        }
    }
    echo "> Complete! \n";
}

elseif ($opts['targetList']) {
    $fpointer = fopen($target_list, "r") or die("> Target file not found: {$opts['targetList']} ");       
    while (!feof($fpointer)) {
        $target_tripcode = trim(fgets($fpointer));
        if ($opts['wordlistFile'])  {
            wordlist($target_tripcode, $opts['wordlistFile']);
        }
        if ($opts['wordlistDir'])  {
            wordlist_directory($target_tripcode, $opts['wordlistDir']); 
        }
        if ($opts['bruteforceFlag']) {
            if ($opts['bruteforceMin'] && $opts['bruteforceMax']) {
                brute($target_tripcode, $opts['bruteforceMin'], $opts['bruteforceMax']);
            }
            else {
                brute($target_tripcode);
            }
            exit;
        }
    }
    echo "> Complete! \n";
}

else { $getopts->getHelpText(); }

function wordlist_directory($target, $dir) {
    $files = scandir($dir);
    $wordlist_arr = array();
    foreach ($files as $txt_file) {
        if (strpos($txt_file, '.txt') !== false) {
            $txt_file = $dir . '/' . $txt_file;
            array_push($wordlist_arr, $txt_file);
        }
    }
    wordlist($target, $wordlist_arr);
}

function target_list($target_list, $wordlist_dir, $wordlist_file) {
    $fpointer = fopen($target_list, "r") or die("> Target file not found: $target_list ");       
    while (!feof($fpointer)) {
        $target_tripcode = trim(fgets($fpointer));
        if (!is_null($wordlist_dir)) {
            wordlist_directory($target_tripcode, $wordlist_dir); 
        }
        if (!is_null($wordlist_file)) {
            wordlist($target_tripcode, $wordlist_file);
        }
    }
    fclose($fpointer);
    return null;
}

function brute($target, $start = 8, $stop = 14) { 
    global $debug;
    echo "> Stating bruteforce.  Please wait, this may take a while...\n";
    for ($i=$start; $i<=$stop; $i++) {
        //!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~
        \yidas\BruteForceAttacker::run([
            'length' => $i,
            'charMap' => array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'), ["!","\"","#","$","%","&","'","(",")","*","+",",","-",".","/",":",";","<","=",">","?","@","[","\\","]","^","_","`","{","|","}","~"]),
            'callback' => function ($key, & $count) use ($target) {
                $enc = encrypt($key);
                if ($enc == $target) {
                    echo "> Tripcode target FOUND: {$target} | {$enc} | {$key} \n";
                    exit;
                }
                if ($count == 0 || $count > 100000) { // status indicator
                    echo "> Target:{$target} | Word:{$key} | EncWord:{$enc} " . date("H:i:s") . "\n";
                    $count = 0;
                }
            },
            'skipLength' => 0, 
            'skipCount' => 0,
        ]);
    }
}

function wordlist($target, array $wordlist_file_arr) {
    global $debug;
    $count = 0;
    for ($i=0; $i<count($wordlist_file_arr); $i++) {
        $wl = $wordlist_file_arr[$i];
        echo "> Wordlist: $wl - please wait, this may take a while...\n";
        $fpointer = fopen($wl, "r") or die("> Dictionary file not found: $wl ");       
        while (!feof($fpointer)) {
            $word_from_dict = trim(fgets($fpointer));
            $enc_word_from_dict = encrypt($word_from_dict);
            if ($debug) {
                echo "> Target:{$target} | Word:{$word_from_dict} | EncWord:{$enc_word_from_dict} " . date("H:i:s") . "\n";
            }
            elseif ($count == 0 || $count > 1000000) { // status indicator
                echo "> Target:{$target} | Word:{$word_from_dict} | EncWord:{$enc_word_from_dict} " . date("H:i:s") . "\n";

                $count = 0;
            }
            if (trim($enc_word_from_dict) == $target) {
                fclose($fpointer);
                echo "> Tripcode target FOUND: {$word_from_dict} | {$enc_word_from_dict} \n";
                exit;
            }
            $count++;
        }
        fclose($fpointer);
    }
    return null;
}

function encrypt($plain) {
    $salt = substr($plain."H.",1,2);
    $salt = preg_replace("[^\.-z]",".",$salt);
    $salt = strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef"); 
    return substr(crypt($plain,$salt),-10);
}
