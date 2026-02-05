fetch("get_manager_data.php")
  .then((res) => res.json())
  .then((data) => {
    // Totals
    document.getElementById("totalOrders").textContent = data.totalOrders;

    document.getElementById("totalRevenue").textContent =
      "$" + Number(data.totalRevenue).toFixed(2);

    // Orders by meal
    const mealContainer = document.getElementById("ordersByMeal");
    mealContainer.innerHTML = "";

    data.ordersByMeal.forEach((item) => {
      const div = document.createElement("div");
      div.className = "order-card";
      div.innerHTML = `
        <h3>${item.meal_name}</h3>
        <p>Orders: ${item.count}</p>
      `;
      mealContainer.appendChild(div);
    });

    // Feedback
    const feedbackContainer = document.getElementById("feedbackList");
    feedbackContainer.innerHTML = "";

    data.feedback.forEach((fb) => {
      const div = document.createElement("div");
      div.className = "order-card";
      div.innerHTML = `
        <h3>${fb.meal_name}</h3>
        <p>By: ${fb.username}</p>
        <p>Rating: ${fb.rating} / 5</p>
        <p>${fb.comment}</p>
        <small>${fb.created_at}</small>
      `;
      feedbackContainer.appendChild(div);
    });
  });
