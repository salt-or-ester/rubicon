<?php 

class Julius {
    public $apiHost = 'https://a.4cdn.org/';
    public $scrapePath = __DIR__ . '/../../scrapes/';
    public $board = 'x';

    public function downloadAllPosts() {
        $catalogEndPoint = $this->apiHost . $this->board . '/catalog.json';
        $threadPath = $this->scrapePath . $this->board . '/catalog.json';
    
        $this->removeDirectoryIfExists($this->scrapePath . $this->board);
        mkdir($this->scrapePath . $this->board, 0777, true);
        file_put_contents($threadPath, file_get_contents($catalogEndPoint));
    
        $posts = $this->getThreads();
        $msg = '';
        $t = "[" . date('d/m/Y H:i:s') . "]/" . $this->board . "/> ";
    
        foreach ($posts as $post) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                // Fork failed
                die("Error forking process.");
            } elseif ($pid == 0) {
                // Child process
                $threadEndPoint = $this->apiHost . $this->board . '/thread/' . $post . '.json';
                $threadPath = $this->scrapePath . $this->board . '/' . $post . '.json';
    
                $ch = curl_init($threadEndPoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
    
                // Save response to file or perform other operations
                file_put_contents($threadPath, $response);
    
                curl_close($ch);
                exit(); // Exit child process
            } else {
                // Parent process
                $msg .= $t . 'scanning ' . $post . "\n";
                echo $msg;
            }
            
        }

        // Wait for all child processes to finish
        while (pcntl_waitpid(0, $status) != -1) {
            register_shutdown_function(function() {
                $pid = getmypid();
                exec("pkill -P $pid");
            });
            echo "DONE !\n";
        }
    }    
        
    public function getThreads() {
        $cleanThreads = [];
        $catalogPath = $this->scrapePath . $this->board . '/catalog.json';
        $jsonCatalog = json_decode(file_get_contents($catalogPath), true);

        foreach ($jsonCatalog as $threads) {
            if (is_array($threads) || is_object($threads)) {
                foreach ($threads as $thread) { // threads
                    for ($j=0; $j < count($threads['threads']); $j++) {
                        array_push($cleanThreads, $threads['threads'][$j]['no']);
                    }
                }
            }
        }
        return $cleanThreads;
    }

    public function removeDirectoryIfExists($directory) {
        if (is_dir($directory)) {
            $this->removeDirectory($directory);
        }
    }

    public function removeDirectory($directory) {
        $files = glob($directory . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

    public function getUniqueIPs() {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $allUniqueIPs = [];

        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
            
            $threads = json_decode(file_get_contents($threadPath . $threadName), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (key_exists('unique_ips', $post)) {
                        $uniqueIPs = $post['unique_ips'];
                        $threadNumber = substr($threadName, 0, -5);
                        array_push($allUniqueIPs, array($threadNumber, $uniqueIPs));
                    }
                }
            }
        }
        file_put_contents(__DIR__ . '/../../output/uniqueips.json', json_encode($uniqueIPs));
        return json_encode($allUniqueIPs);
    }

    public function getAllTripcodes() {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $allTripcodes = [];
    
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
    
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (key_exists('trip', $post)) {
                        $tripcode = $post['trip'];
                        $threadNumber = substr($threadName, 0, -5);
                        $allTripcodes[] = [$threadNumber, $tripcode];
                    }
                }
            }
        }
        file_put_contents(__DIR__ . '/../../output/trips.json', json_encode($allTripcodes));
        return json_encode($allTripcodes);
    }
    
    
    public function getSignal2Noise(): array {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $allRatios = [];
    
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
                
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (key_exists('unique_ips', $post) && key_exists('replies', $post) && $post['replies'] != 0) {
                        $uniqueIps = $post['unique_ips'];
                        $replies = $post['replies'];
                        $ratio = (int)$uniqueIps / (int)$replies;
                        $threadNumber = substr($threadName, 0, -5);
                        $allRatios[] = [$threadNumber, $ratio];
                    }
                }
            }
        }
        file_put_contents(__DIR__ . '/../../output/signal2noise.json', json_encode($allRatios));
        return $allRatios;
    } 
    
    public function getCapcode() {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $allCapcode = [];
    
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (key_exists('capcode', $post)) {
                        $capcode = $post['capcode'];
                        $threadNumber = substr($threadName, 0, -5);
                        $allCapcode[] = [$threadNumber, $capcode];
                    }
                }
            }
        }
        file_put_contents(__DIR__ . '/../../output/capcodes.json', json_encode($allCapcode));
        return $allCapcode;
    }   
    
    public function getNamedUsers(): array {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $allNamedUsers = [];
    
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (key_exists('name', $post) && ($post['name'] != 'Anonymous')) {
                        $userName = $post['name'];
                        $threadNumber = substr($threadName, 0, -5);
                        $allNamedUsers[] = [$threadNumber, $userName];
                    }
                }
            }
        }
        file_put_contents(__DIR__ . '/../../output/namedusers.json', json_encode($allNamedUsers));    
        return $allNamedUsers;
    }

    public function searchName($keyword) {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        
        $nameArray = [];
        $uniqueThreadNumbers = [];
    
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (strtoupper($post['name']) == strtoupper($keyword)) {
                        $name = $post['name'];
                        $threadNumber = substr($threadName, 0, -5);
                        if (!in_array($threadNumber, $uniqueThreadNumbers)) {
                            $nameArray[] = [$threadNumber, $name];
                            $uniqueThreadNumbers[] = $threadNumber;
                        }
                    }
                }
            }
        }
        file_put_contents(__DIR__ . '/../../output/searchName-' . preg_replace('/[^A-Za-z0-9]/', '', $keyword) . '.json', json_encode($nameArray));
        return $nameArray;
    }

    public function searchSubj($keyword) {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $subjArray = [];
    
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (@strpos(strtoupper($post['sub']), strtoupper($keyword)) !== false) {
                        $sub = $post['sub'];
                        $threadNumber = substr($threadName, 0, -5);
                        $subjArray[] = [$threadNumber, $sub];
                    }
                }
            }
        }
        
        file_put_contents(__DIR__ . '/../../output/searchSubj-' . preg_replace('/[^A-Za-z0-9]/', '', $keyword) . '.json', json_encode($subjArray));
        return $subjArray;
    }

    public function searchComment($keyword) {
        $threadPath = $this->scrapePath . $this->board . '/';
        $threadDir = scandir($threadPath);
        $comArray = [];
        
        foreach ($threadDir as $threadName) {
            if ($threadName === '.' || $threadName === '..' || $threadName === 'catalog.json') continue;
            $threadLocalPath = $threadPath . $threadName;
            $threads = json_decode(file_get_contents($threadLocalPath), true);
            
            foreach ($threads as $thread) {
                foreach ($thread as $post) {
                    if (@strpos(strtoupper($post['com']), strtoupper($keyword)) !== false) {
                        $com = $post['com'];
                        $threadNumber = substr($threadName, 0, -5);
                        $comArray[] = [$threadNumber, $com];
                    }
                }
            }
        }       
        file_put_contents(__DIR__ . '/../../output/searchComment-' . preg_replace('/[^A-Za-z0-9]/', '', $keyword) . '.json', json_encode($comArray));
        return $comArray;
    }
}

// debug
//$jul = new Julius();
//$jul->downloadAllPosts();
//print_r($jul->getUniqueIPs());
//print_r($jul->getAllTripcodes());
//print_r($jul->getSignal2Noise());
//print_r($jul->getCapcode());
//print_r($jul->getNamedUsers());
//print_r($jul->searchName('LilAnon'));
//print_r($jul->searchSubj('/div/ - Divination General'));
//print_r($jul->searchComment('might as well flip a coin lol'));