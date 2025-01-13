<?php 
            if (basename($_SERVER['PHP_SELF']) == 'index.php') {
                if ($_SERVER['SERVER_NAME'] == '4chan.ai') {
                    echo "<p class='notice'><strong>What is 4chan.ai?</strong><br/>
                    4chan.ai is a proof-of-concept demonstrating how a <a href='https://gitgud.io/saltorester/rubicon/' target='_blank'>RUBICON</a> user interface can be constructed using the <a href='https://gitgud.io/saltorester/rubicon/-/wikis/Julius-API-Specification' target='_blank'>REST APIs</a>.
                    <br/><br/>";
                }
                else {
                    echo "<p class='notice'><strong>What is this site?</strong><br/>
                    This is a proof-of-concept demonstrating how a <a href='https://gitgud.io/saltorester/rubicon/' target='_blank'>RUBICON</a> user interface can be constructed using the <a href='https://gitgud.io/saltorester/rubicon/-/wikis/Julius-API-Specification' target='_blank'>REST APIs</a>.
                    <br/><br/>";
                }
        ?>
            <strong>What is RUBICON?</strong><br/>
            <a href='https://gitgud.io/saltorester/rubicon/' target='_blank'>RUBICON</a> is a 4chan Command & Control Suite that has all sorts of neato APIs to run analytics
            or machine learning on 4chan boards and users.<br/><br/>
            <strong>How do I use RUBICON?</strong><br/>
            Take a look at the <a href="https://gitgud.io/saltorester/rubicon/-/wikis/home" target="_blank">wiki</a>!
        </p>
            <strong>Features</strong>
            <ul>
                <li>Download All Current Threads/Comments on a 4chan Board</li>
                <li>Load Password Wordlists into RUBICON's <a href="https://gitgud.io/saltorester/rubicon/-/wikis/Rainbow-Table-API-Specification" target="_blank">Redis Rainbow Table</a></li>
                <li><a href="encrypttrip.php" target="_blank">Encrypt</a> a Password to get the Corresponding Insecure Tripcode</li>
                <li><a href="encrypttrip.php" target="_blank">Decrypt</a> an list of Insecure Tripcodes to get the Corresponding Passwords from Your <a href="https://gitgud.io/saltorester/rubicon/-/wikis/Rainbow-Table-API-Specification" target="_blank">Rainbow Table</a></li>
                <li>Get <a href="boardstats.php" target="_blank">Board Metrics and Statistics</a></li>
                <ul>
                    <li>Get Unique IPs per Thread</li>
                    <li>Get all Tripcode Users per Thread</li>
                    <li>Get all Named Users per Thread</li>
                    <li>Get all Capcode Users per Thread</li>
                    <li>Get the Signal2Noise Ratio per Thread to Identify Shills</li>
                </ul>
                <li><a href="search.php" target="_blank">Search</a></li>
                <ul>
                    <li>Search Threads for a Named User</li>
                    <li>Search Threads for a Subject Keyword of Phrase</li>
                    <li>Search Threads for a Comment Keywordor Phrase</li>
                </ul> <?php } ?>
                <?php 
                    if (basename($_SERVER['PHP_SELF']) == 'index.php')
                        echo '<li><a href="premium.php" target="_blank" style="text-decoration: underline; text-decoration-color: black;"><mark>PREMIUM FEATURES!</mark></a></li>';
                    else
                        echo '<li><style="text-decoration: underline; text-decoration-color: black;"><mark>PREMIUM FEATURES!</mark></style></li>';
                ?>        
                <ul>
                    <li>Botnet: Command &amp; Control Center</li>
                    <li>Botnet: VPN Switching (Nord, Express)</li>
                    <li>Botnet: Automated Captcha Solving</li>
                    <li>Botnet: Content Fuzzing (Organic Comments Based on Theme)</li>
                    <li>Machine Learning: Supervised Learning</li>
                    <li>Machine Learning: Unsupervised Learning</li>
                    <li>Machine Learning: Semi-Supervised Learning</li>
                    <li>Machine Learning: Reinforcement Learning</li>
                    <li>Machine Learning: Deep Learning</li>
                    <li>Dashboards &amp; Analytics: Influencers</li>
                    <li>Dashboards &amp; Analytics: Shill Detection</li>
                    <li>Dashboards &amp; Analytics: Hivemind</li>
                    <li>Full Data Model Access:  All Boards, All Posts &amp; Images</li>
                </ul>
            </ul>