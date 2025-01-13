<!DOCTYPE html>
<html>
<head>
    <?php 
        $hdrChoices = array(
            array('Home', 'index.php'),
            array('Rainbow Table', 'rainbow.php'),
            array('Tripcodes', 'tripcodes.php'),
            array('Board Stats', 'boardstats.php'),
            array('Search', 'search.php'),
            array('Contact', 'contact.php'),
            array('Premium', 'premium.php')
    ); ?>
    <title><?php
        foreach ($hdrChoices as $hdrChoice) {
            if (basename($_SERVER['PHP_SELF']) == $hdrChoice[1])
                echo $hdrChoice[0];
        }
    ?></title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
    <link rel="icon" href="/ui/standard/img/favicon.png" type="image/x-icon">
</head>
<body>
<?php 
echo '
<div>
    <center>
        <header>
            <nav>';
foreach ($hdrChoices as $hdrChoice)
    if ($hdrChoice[0] == 'Premium')
        echo '<a href="'.$hdrChoice[1].'" style="background-color:limegreen;font-weight:bold;" class="' . ((basename($_SERVER['PHP_SELF'])) == $hdrChoice[1] ? 'current' : '') . '">'.$hdrChoice[0].'</a>';
    else
        echo '<a href="'.$hdrChoice[1].'" class="' . ((basename($_SERVER['PHP_SELF'])) == $hdrChoice[1] ? 'current' : '') . '">'.$hdrChoice[0].'</a>';
echo '
<button id="rescan">Rescan</button>        
    </nav>
        
        </header>
    </center>
</div>
<p id="message"></p>
<script>
function runJulius() {
    var host = window.location.host;
    var path = "/julius/run";
    var endpoint = `http://${host}${path}`;
    
    // Show message to the user
    var messageElement = document.getElementById("message");
    if (messageElement) {
      messageElement.textContent = "Scanning in progress, please allow up to 90 seconds before using Boardstats...";
    }
    
    fetch(endpoint)
      .then(response => response.json())
      .then(json => {
        console.log(json);
        // Clear the message after scanning is complete
        if (messageElement) {
          messageElement.textContent = "";
        }
      })
      .catch(error => {
        console.error("Error:", error);
        // Clear the message in case of an error
        if (messageElement) {
          messageElement.textContent = "";
        }
      });
  }
  
  var rebuildButton = document.getElementById("rescan");
  rebuildButton.addEventListener("click", runJulius);
  
</script>
';