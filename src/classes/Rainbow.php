<?php
require_once 'src/classes/Encrypt.php';

class Rainbow {
    private $redis;

    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('0.0.0.0', 6379);
    }

    public function redisRowCount() {
        if (!$this->redis) {
            echo "Redis connection is not established.";
            return;
        }

        $count = $this->redis->dbSize();
        echo json_encode($count);
    }

    public function addWordListDirToRainbow($wordListDir) {
        $encObj = new Encrypt();
        $childProcesses = array();
    
        foreach ($wordListDir as $wordList) {
            $pid = pcntl_fork();
            if ($pid == -1) {

                echo "Failed to create child process.\n";
                exit(1);
            } 
            elseif ($pid == 0) {
                // child process
                $file = fopen($wordList, 'r');
                if ($file) {
                    while (($line = fgets($file)) !== false) {
                        $this->addToRainbowTable($this->encrypt($line), $line);
                    }
                    fclose($file);
                } 
                else echo "Wordlist {$wordList} not found in {$wordListDir}\n";
                exit(0);
            } 
            else {
                // parent process
                $childProcesses[] = $pid;
            }
        }
    
        // wait for all child processes to finish
        foreach ($childProcesses as $pid) {
            pcntl_waitpid($pid, $status);
        }
    }
    

    public function purgeRainbowTable() {
        $this->redis->flushDB();

        $isEmpty = ($this->redis->dbSize() === 0);

        if ($isEmpty) echo "Redis database is empty.\n";
        else echo "Failed to purge all entries in Redis.\n";
    }

    public function addToRainbowTable($hash, $string) {
        //echo "adding {$hashes} and {$string}\n";
        //foreach ($hashes['stringToEncrypt'] as $hash)
        $this->redis->set(trim($hash), trim($string));
    }

    public function retrieveFromRainbowTable($hash) {
        return $this->redis->get($hash);
    }

    public function bruteRainbowTable($start=1, $stop=10) {
        echo "> Starting bruteforce.  Please wait, this may take a while...\n"; //AA Emjcf.lfLU | 89 v/LK4luEPU
        $specialCharacters = ['!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'];
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'), $specialCharacters);
        // good luck.  the charmap has 839,299,365,868,340,224 possible permutations
        $characterCount = count($characters);
        for ($length = $start; $length <= $stop; $length++) {
            generateCombinations($characters, $characterCount, $length);
        }
        return false;
    }

    public function generateCombinations($characters, $characterCount, $length, $prefix = '') {
        if ($length === 0) {
            $combination = trim($prefix);
            $val = encrypt($combination);
            if (!is_null($val))
                addToRainbowTable($combination, $val);
            return true;
        }
        for ($i = 0; $i < $characterCount; $i++) {
            $newPrefix = $prefix . $characters[$i];
            generateCombinations($characters, $characterCount, $length - 1, $newPrefix);
        }
    }

    public function encrypt($plain) {
        $salt = substr($plain."H.",1,2);
        $salt = preg_replace("[^\.-z]",".",$salt);
        $salt = strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef"); 
        return substr(crypt($plain,$salt),-10);
    }
}