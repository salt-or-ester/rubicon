<?php include './standard/header.php'; ?>
<details>
<summary>Search Threads by User</summary>
<p>
<form id="nameForm">
  <input type="text" id="name" placeholder="Enter a string">
  <button type="submit">Submit</button>
</form>
<div id="resultContainerName">
  <table id="searchName" class="hidden">
    <thead>
      <tr>
        <th>Thread</th>
        <th>Name</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<script>
document.getElementById("nameForm").addEventListener("submit", function(e) {
  e.preventDefault();
  var inputString = JSON.stringify({ name: document.getElementById("name").value });
  var inputObject = JSON.parse(inputString);
  console.log(inputObject);
  var host = window.location.host;
  var path = '/julius/search/name';
  var endpoint = `http://${host}${path}`;
  var endpoint = `http://${host}${path}`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", endpoint, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        console.log(response);
        var table = document.getElementById("searchName");
        var tableBody = table.getElementsByTagName("tbody")[0];
        tableBody.innerHTML = "";
        for (var i = 0; i < response.length; i++) {
          var rowData = response[i];
          var row = document.createElement("tr");
          var cell1 = document.createElement("td");
          var link1 = document.createElement("a");
          link1.href = "https://boards.4channel.org/x/thread/" + rowData[0];
          link1.target = "_blank";
          link1.textContent = rowData[0];
          cell1.appendChild(link1);
          var cell2 = document.createElement("td");
          var link2 = document.createElement("a");
          link2.href = "https://archive.4plebs.org/x/search/subject/" + encodeURIComponent(rowData[1]);
          link2.target = "_blank";
          link2.textContent = rowData[1];
          cell2.appendChild(link2);
          row.appendChild(cell1);
          row.appendChild(cell2);
          tableBody.appendChild(row);
        }
        table.classList.remove("hidden");
      }
    };
    xhr.send(JSON.stringify(inputObject));
  });

</script>
</details>
<details>
<summary>Search Threads by Subject</summary>
<p>
<form id="subjectForm">
  <input type="text" id="subjectInput" placeholder="Enter a string">
  <button type="submit">Submit</button>
</form>
<div id="resultContainerSubject">
  <table id="searchSubject" class="hidden">
    <thead>
      <tr>
        <th>Thread</th>
        <th>Subject</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<script>
  document.getElementById("subjectForm").addEventListener("submit", function(e) {
    e.preventDefault();
    var subjectInput = JSON.stringify({ subject: document.getElementById("subjectInput").value });
    var inputObject = JSON.parse(subjectInput);
    console.log(inputObject);
    var host = window.location.host;
    var path = '/julius/search/subject';
    var endpoint = `http://${host}${path}`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", endpoint, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        console.log(response);
        var table = document.getElementById("searchSubject");
        var tableBody = table.getElementsByTagName("tbody")[0];
        tableBody.innerHTML = "";
        for (var i = 0; i < response.length; i++) {
          var rowData = response[i];
          var row = document.createElement("tr");
          var cell1 = document.createElement("td");
          var link1 = document.createElement("a");
          link1.href = "https://boards.4channel.org/x/thread/" + rowData[0];
          link1.target = "_blank";
          link1.textContent = rowData[0];
          cell1.appendChild(link1);
          var cell2 = document.createElement("td");
          var link2 = document.createElement("a");
          link2.href = "https://archive.4plebs.org/x/search/subject/" + encodeURIComponent(rowData[1]);
          link2.target = "_blank";
          link2.textContent = rowData[1];
          cell2.appendChild(link2);
          row.appendChild(cell1);
          row.appendChild(cell2);
          tableBody.appendChild(row);
        }
        table.classList.remove("hidden");
      }
    };
    xhr.send(JSON.stringify(inputObject));
  });
</script>
</details>
<details>
<summary>Search Threads by Comment</summary>
<p>
<form id="commentForm">
  <input type="text" id="commentInput" placeholder="Enter a string">
  <button type="submit">Submit</button>
</form>
<div id="resultContainerComment">
  <table id="searchComment" class="hidden">
    <thead>
      <tr>
        <th>Thread</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<script>
  document.getElementById("commentForm").addEventListener("submit", function(e) {
    e.preventDefault();
    var commentInput = JSON.stringify({ comment: document.getElementById("commentInput").value });
    var inputObject = JSON.parse(commentInput);
    console.log(inputObject);
    var host = window.location.host;
    var path = '/julius/search/comment';
    var endpoint = `http://${host}${path}`;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", endpoint, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        console.log(response);
        var table = document.getElementById("searchComment");
        var tableBody = table.getElementsByTagName("tbody")[0];
        tableBody.innerHTML = "";
        for (var i = 0; i < response.length; i++) {
          var rowData = response[i];
          var row = document.createElement("tr");
          var cell1 = document.createElement("td");
          var link1 = document.createElement("a");
          link1.href = "https://boards.4channel.org/x/thread/" + rowData[0];
          link1.target = "_blank";
          link1.textContent = rowData[0];
          cell1.appendChild(link1);
          var cell2 = document.createElement("td");
          cell2.textContent = rowData[1];
          cell2.classList.add("word-wrap");
          row.appendChild(cell1);
          row.appendChild(cell2);
          tableBody.appendChild(row);
        }
        table.classList.remove("hidden");
      }
    };
    xhr.send(JSON.stringify(inputObject));
  });
</script>

</details>
<style>
  .hidden {
    display: none;
  }
  .word-wrap {
    word-break: break-all;
  }
</style>
</p>
</section>
</body>


</html>
