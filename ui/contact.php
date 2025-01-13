    <?php include './standard/header.php'; ?>
    <form id="my-form" action="https://formspree.io/f/mvonjwdp" method="POST">
    <label>Email:</label>
    <input type="email" name="email" required />
    
    <label>Subject:</label>
    <select name="subject" required>
        <option value="information-request" selected>Information Request</option>
        <option value="bug-report">Bug Report</option>
        <option value="premium-features">Premium Features</option>
    </select>
    
    <label>Message:</label>
    <textarea name="message" required></textarea>
    
    <button id="my-form-button">Submit</button>
    <p id="my-form-status"></p>
</form>

<script>
    var form = document.getElementById("my-form");
    
    async function handleSubmit(event) {
        event.preventDefault();
        var status = document.getElementById("my-form-status");
        
        if (form.checkValidity()) {
            var data = new FormData(event.target);
            fetch(event.target.action, {
                method: form.method,
                body: data,
                headers: {
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    status.innerHTML = "<h2><strong>Thanks for your submission!</strong></h2>";
                    form.reset();
                } else {
                    response.json().then(data => {
                        if (Object.hasOwnProperty.call(data, 'errors')) {
                            status.innerHTML = data["errors"].map(error => error["message"]).join(", ");
                        } else {
                            status.innerHTML = "<h2><strong>Oops! There was a problem submitting your form.</strong></h2>";
                        }
                    });
                }
            }).catch(error => {
                status.innerHTML = "<h2><strong>Oops! There was a problem submitting your form.</strong></h2>";
            });
        } else {
            status.innerHTML = "<h2><strong>Please fill in all required fields.</strong></h2>";
        }
    }
    
    form.addEventListener("submit", handleSubmit);
</script>
        <?php include './standard/footer.php'; ?>
        </body>
    </html>