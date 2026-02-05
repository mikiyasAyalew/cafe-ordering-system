// Submit order to backend when an order is placed
document
  .getElementById("orderForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const mealName = document.getElementById("mealName").value;
    const quantity = document.getElementById("quantity").value;
    const totalPrice = document.getElementById("totalPrice").value; // This should be calculated on the frontend

    fetch("place_order.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `meal_name=${mealName}&quantity=${quantity}&total_price=${totalPrice}`,
    })
      .then((response) => response.text())
      .then((data) => alert(data)) // Show server response
      .catch((error) => console.error("Error:", error));
  });
