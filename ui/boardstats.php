    <?php include './standard/header.php'; ?>
    <details>
    <summary>Unique IPs by Thread</summary>
    <p>
        <table id="uniqueIPstable">
            <thead>
                <tr>
                    <th>Thread</th>
                    <th>Unique IPs</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody id="uniqueIPsTbody"></tbody>
        </table>
    </p>
    <script>
        var host = window.location.host;
        var path = '/julius/uniqueips';
        var endpoint = `http://${host}${path}`;
        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                data.sort((a, b) => b[1] - a[1]);
                var table = document.getElementById('uniqueIPstable');
                if (table) {
                    var tableHTML = '';
                    for (var i = 0; i < data.length; i++) {
                        tableHTML += '<tr>';
                        for (var j = 0; j < data[i].length; j++) {
                            if (j === 0) {
                                tableHTML += '<td><a href="https://boards.4channel.org/x/thread/' + data[i][j] + '" target="_blank">' + data[i][j] + '</a></td>';
                            } else {
                                tableHTML += '<td>' + data[i][j] + '</td>';
                            }
                        }
                        tableHTML += '</tr>';
                    }
                    var tbody = document.getElementById('uniqueIPsTbody');
                    if (tbody) {
                        tbody.innerHTML = tableHTML;
                    } else {
                        console.error('Error: Table body not found.');
                    }
                } else {
                    console.error('Error: Table not found.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    </script>
</details>
<details><summary>Tripcodes by Thread</summary>
    <p>
    <table id="TripcodesTable">
        <tbody id="TripcodesTbody"></tbody>
    </table>
        </p></details>

    <script>
var host = window.location.host;
var path = '/julius/alltripcodes';
var endpoint = `http://${host}${path}`;

fetch(endpoint)
  .then(response => response.json())
  .then(data => {
    console.log(data);
    var table = document.getElementById('TripcodesTable');
    if (table) {
      var tableHTML = '';
      for (var i = 0; i < data.length; i++) {
        tableHTML += '<tr>';
        tableHTML += '<td><a href="https://boards.4channel.org/x/thread/' + data[i][0] + '" target="_blank">' + data[i][0] + '</a></td>';
        tableHTML += '<td><a href="https://archive.4plebs.org/x/search/tripcode/' + data[i][1] + '" target="_blank">' + data[i][1] + '</a></td>';
        tableHTML += '</tr>';
      }
      var tbody = document.getElementById('TripcodesTbody');
      if (tbody) {
        tbody.innerHTML = tableHTML;
      } else {
        console.error('Error: Table body not found.');
      }
    } else {
      console.error('Error: Table not found.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });

    </script>
</details>
<details>
<summary>Signal2Noise Ratio</summary>
    <p>
        <table id="s2nTable">
            <thead>
                <tr>
                    <th>Thread</th>
                    <th>S2N Ratio</th>
                </tr>
            </thead>
            <tbody id="s2nTbody"></tbody>
        </table>
    </p>
<script>
        var host = window.location.host;
        var path = '/julius/s2n';
        var endpoint = `http://${host}${path}`;
        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                data.sort((a, b) => b[1] - a[1]);
                var table = document.getElementById('s2nTable');
                if (table) {
                    var tableHTML = '';
                    for (var i = 0; i < data.length; i++) {
                        tableHTML += '<tr>';
                        for (var j = 0; j < data[i].length; j++) {
                            if (j === 0) {
                                tableHTML += '<td><a href="https://boards.4channel.org/x/thread/' + data[i][j] + '" target="_blank">' + data[i][j] + '</a></td>';
                            } else {
                                tableHTML += '<td>' + data[i][j].toFixed(2) + '</td>';
                            }
                        }
                        tableHTML += '</tr>';
                    }
                    var tbody = document.getElementById('s2nTbody');
                    if (tbody) {
                        tbody.innerHTML = tableHTML;
                    } else {
                        console.error('Error: Table body not found.');
                    }
                } else {
                    console.error('Error: Table not found.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    </script>
</details>
<details>
<summary>Capcodes</summary>
    <p>
        <table id="capcodesTable">
            <thead>
                <tr>
                    <th>Thread</th>
                    <th>Capcodes</th>
                </tr>
            </thead>
            <tbody id="capcodesTbody"></tbody>
        </table>
    </p>
<script>
        var host = window.location.host;
        var path = '/julius/capcode';
        var endpoint = `http://${host}${path}`;
        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                data.sort((a, b) => b[1] - a[1]);
                var table = document.getElementById('capcodesTable');
                if (table) {
                    var tableHTML = '';
                    for (var i = 0; i < data.length; i++) {
                        tableHTML += '<tr>';
                        for (var j = 0; j < data[i].length; j++) {
                            if (j === 0) {
                                tableHTML += '<td><a href="https://boards.4channel.org/x/thread/' + data[i][j] + '" target="_blank">' + data[i][j] + '</a></td>';
                            } else {
                                tableHTML += '<td>' + data[i][j] + '</td>';
                            }
                        }
                        tableHTML += '</tr>';
                    }
                    var tbody = document.getElementById('capcodesTbody');
                    if (tbody) {
                        tbody.innerHTML = tableHTML;
                    } else {
                        console.error('Error: Table body not found.');
                    }
                } else {
                    console.error('Error: Table not found.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    </script>
</details>
<details>
<summary>Named Users by Thread</summary>
    <p>
        <table id="namedUsersTable">
            <thead>
                <tr>
                    <th>Thread</th>
                    <th>Named User</th>
                </tr>
            </thead>
            <tbody id="namedUsersTbody"></tbody>
        </table>
    </p>
<script>
        var host = window.location.host;
        var path = '/julius/namedusers';
        var endpoint = `http://${host}${path}`;
        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                data.sort((a, b) => b[1] - a[1]);
                var table = document.getElementById('namedUsersTable');
                if (table) {
                    var tableHTML = '';
                    for (var i = 0; i < data.length; i++) {
                        tableHTML += '<tr>';
                        for (var j = 0; j < data[i].length; j++) {
                            if (j === 0) {
                                tableHTML += '<td><a href="https://boards.4channel.org/x/thread/' + data[i][j] + '" target="_blank">' + data[i][j] + '</a></td>';
                            } else {
                                tableHTML += '<td><a href="https://archive.4plebs.org/x/search/username/' + data[i][j] + '" target="_blank">' + data[i][j] + '</a></td>';
                            }
                        }
                        tableHTML += '</tr>';
                    }
                    var tbody = document.getElementById('namedUsersTbody');
                    if (tbody) {
                        tbody.innerHTML = tableHTML;
                    } else {
                        console.error('Error: Table body not found.');
                    }
                } else {
                    console.error('Error: Table not found.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    </script>
</details>
<?php include './standard/footer.php'; ?>
</body>
</html>