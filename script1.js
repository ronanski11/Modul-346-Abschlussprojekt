document.addEventListener("DOMContentLoaded", function() {
  // Event listener for form submission to add a new student
  const form = document.getElementById("student-form");
  form.addEventListener("submit", function(event) {
    event.preventDefault();

    var studentData = {
      "firstname": document.getElementById("firstname").value,
      "lastname": document.getElementById("lastname").value,
      "age": document.getElementById("age").value,
      "year": document.getElementById("year").value,
      "getAllStudents": false
    };

    fetch("/api.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(studentData)
    })
    .then(response => response.json())
    .then(data => {
      console.log("Success:", data);
      const successMessage = document.getElementById("success-message");
      successMessage.classList.remove("invisible")
      successMessage.classList.add("visible");

      setTimeout(() => {
      successMessage.classList.remove("visible");
      successMessage.classList.add("invisible");
    }, 3000);
    })
    .catch(error => {
      console.error("Error:", error);
    });
  });
  var container = document.getElementById("container");

  // Event listener for the "Show All Students" button
  document.getElementById("showStudents").addEventListener("click", function() {
    container.classList.remove("invisible");
    container.classList.add("visible");
    document.getElementById("showStudents").innerHTML = "Refresh";

    fetch("/api.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        getAllStudents: true
      })
    })
    .then(response => response.json())
    .then(data => {
    let studentsList = document.getElementById("studentsList");
    studentsList.innerHTML = ""; // Clear previous list
    data.forEach(student => {
        studentsList.innerHTML += `
        <tr>
            <td>${student.firstname}</td>
            <td>${student.lastname}</td>
            <td>${student.age}</td>
            <td>${student.year}</td>
        </tr>`;
    });
    })
    .catch(error => console.error("Error:", error));
  });
});