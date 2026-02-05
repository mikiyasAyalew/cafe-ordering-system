document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Stop normal form submit

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (username === "" || password === "") {
      alert("Please fill in all fields");
      return;
    }

    fetch("login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "username=" +
        encodeURIComponent(username) +
        "&password=" +
        encodeURIComponent(password),
    })
      .then((response) => response.text())
      .then((data) => {
        console.log("Server response:", data);

        if (data === "LOGIN_SUCCESS") {
          window.location.href = "student.php";
        } else if (data === "WRONG_PASSWORD") {
          alert("Incorrect password");
        } else if (data === "USER_NOT_FOUND") {
          alert("User not found");
        } else if (data === "EMPTY_FIELDS") {
          alert("Please fill in all fields");
        } else if (data === "DB_ERROR") {
          alert("Database connection error");
        } else {
          alert("Unexpected response: " + data);
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        alert("Something went wrong. Check console.");
      });
  });
});
