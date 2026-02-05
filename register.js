document
  .getElementById("registerForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent normal form submission

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    // Check if passwords match
    if (password !== confirmPassword) {
      alert("Passwords do not match!");
      return;
    }

    // Send registration request to the backend
    fetch("register.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${username}&password=${password}&confirmPassword=${confirmPassword}`,
    })
      .then((response) => response.text())
      .then((data) => {
        alert(data); // Show server response
        if (data.includes("Registration successful")) {
          window.location.href = "login.html"; // Redirect to login page after successful registration
        }
      })
      .catch((error) => console.error("Error:", error));
  });
