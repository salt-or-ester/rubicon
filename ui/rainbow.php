    <?php include './standard/header.php'; ?>
    <p class="notice" id="result"></p>
    <br/><br/>
    <button id="purge">Purge</button><button id="rebuild">Rebuild</button>
    <article>
<h2>What is a Rainbow Table?</h2>
<p>Rainbow tables are essentially large precomputed tables that contain pairs of plaintext passwords and their corresponding hashed values. These tables are generated in advance by hashing a vast number of possible plaintext passwords and storing the resulting hashes.
RUBICON utilizes <a href="https://redis.io/docs/about/" target="_blank">redis</a> in many areas to increase speed and efficiency to replace what would normally require intensive i/o or processing.  Using redis, we can crack tripcodes with incredible speed, assuming your wordlists are comprehensive.</p>
</article>
<article><h2>Preparing Wordlists</h2>
<p>The RUBICON Command & Control Center has various features that require user input for the initial setup.  Because tripcode testing is a key component of the suite, it's important we have proper wordlists in place to allow us to generate and crack tripcodes.
RUBICON is containerzied, meaning the image is immutable, however you can keep wordlists locally and RUBICON will automatically copy them into the image for ingestion into redis.
To do this, create a folder called "wordlists", filled with plain-text wordlist files, and run the RUBICON Docker image from the parent directory.  There are many places to find wordlists for your specific purposes -- <a href="https://github.com/danielmiessler/SecLists" target="_blank">SecLists</a> has a nice collection.  A popular (but small) wordlist <a href="https://raw.githubusercontent.com/ignis-sec/Pwdb-Public/master/wordlists/ignis-100K.txt" target="_blank">ignis-100K.txt</a> is included in the Docker image by default.</h2>
</p></article> 
    <script>
        function startTimer() {
            setInterval(getRainbowCount, 2000); 
        }
        getRainbowCount();
        startTimer(); 
        function getRainbowCount() {
            var host = window.location.host;
            var path = '/rainbow/count';
            var endpoint = `http://${host}${path}`;
            fetch(endpoint)
                .then(response => response.json())
                .then(json => {
                    console.log(json);
                    output ='Total key/hash composite keys loaded in the Rainbow Table: <strong><mark>' + json + '</strong></mark>';
                    newOut = output.replace(/"/g, '');
                    document.getElementById('result').innerHTML = JSON.stringify(newOut);
                }); 
        }
        
        function buildRainbowTable() {
            var host = window.location.host;
            var path = '/rainbow/build';
            var endpoint = `http://${host}${path}`;
            fetch(endpoint)
                .then(response => response.json())
                .then(getRainbowCount());
        }

function performDeleteRequest() {
    var host = window.location.host;
    var path = '/rainbow/purge';
    var endpoint = `http://${host}${path}`;

    fetch(endpoint, {
        method: 'DELETE'
    })
        .then(getRainbowCount());
}

var button = document.getElementById('purge');
button.addEventListener('click', performDeleteRequest);

var rebuildButton = document.getElementById('rebuild');
rebuildButton.addEventListener('click', buildRainbowTable);

    </script>
    <?php include './standard/footer.php'; ?>
</body>
</html>