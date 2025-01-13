        <?php include './standard/header.php'; ?>
        <label for="tripcodeTextArea">Tripcodes - One Per Line</label><br/>
    <textarea id="tripcodeTextArea"></textarea><br/>
    <button id="encryptList">Encrypt</button><button id="decryptList">Decrypt</button>
    <table id="resultTable" style="display: none;">
    <thead>
    </thead>
    <tbody id="resultTableBody"></tbody>
    </table>
    <br/>
    <p class="notice">Seeing a "false" for the decrypted tripcode value?  Try adding better wordlists.<br/><br/>
        This can be done by adding new wordlists to your wordlist directory and clicking the "Rebuild" button on the <a href="http://localhost:8888/ui/rainbow.php" target="_blank">Rainbow Table UI</a>.
    </p>
    <script>
    async function getEncryptedData(input) {
        const url = 'http://localhost:8888/encrypt/list';

        const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ stringToEncrypt: input })
        });

        if (!response.ok) {
        throw new Error('Request failed');
        }

        const data = await response.json();
        return data;
    }

    document.getElementById('encryptList').addEventListener('click', async () => {
        const textarea = document.getElementById('tripcodeTextArea');
        const input = textarea.value
        .split('\n')
        .map(value => value.trim())
        .filter(value => value !== '');

        try {
        const encryptedData = await getEncryptedData(input);
        populateTable(input, encryptedData);
        } catch (error) {
        console.error('Error:', error);
        }
    });

    async function getDecryptedData(input) {
        const url = 'http://localhost:8888/decrypt/list';

        const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ tripcodeList: input })
        });

        if (!response.ok) {
        throw new Error('Request failed');
        }

        const data = await response.json();
        return data;
    }

    document.getElementById('decryptList').addEventListener('click', async () => {
        const textarea = document.getElementById('tripcodeTextArea');
        const input = textarea.value
        .split('\n')
        .map(value => value.trim())
        .filter(value => value !== '');

        try {
        const decryptedData = await getDecryptedData(input);
        populateTable(input, decryptedData);
        } catch (error) {
        console.error('Error:', error);
        }
    });

    function populateTable(input, resultData) {
        const tableBody = document.getElementById('resultTableBody');
        tableBody.innerHTML = ''; // Clear existing table rows

        const parentKey = Object.keys(resultData)[0]; // Get the first key in the resultData object
        const JSONparentKey = resultData[parentKey] || {}; // Use the parent key to access the decrypted object

        // Create header row
        const headerRow = document.createElement('tr');
        const headerKeyCell = document.createElement('th');
        headerKeyCell.textContent = 'Key';
        headerRow.appendChild(headerKeyCell);
        const headerResultCell = document.createElement('th');
        headerResultCell.textContent = 'Value';
        headerRow.appendChild(headerResultCell);
        tableBody.appendChild(headerRow);

        for (const key in JSONparentKey) {
        const value = JSONparentKey[key];

        const row = document.createElement('tr');

        const keyCell = document.createElement('td');
        keyCell.textContent = key;
        row.appendChild(keyCell);

        const valueCell = document.createElement('td');
        valueCell.textContent = value;
        row.appendChild(valueCell);

        tableBody.appendChild(row);
        }

        document.getElementById('resultTable').style.display = 'block'; // Show the table
    }
    </script>

        <?php include './standard/footer.php'; ?>
        </body>
    </html>