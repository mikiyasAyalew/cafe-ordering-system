// ===============================
// STATE
// ===============================

let mealsFromDB = [];
let selectedMeal = null;
let orderedMeals = new Set();
let feedbackOrderId = null;

// ===============================
// FETCH MEALS FROM DB
// ===============================

function fetchMeals() {
  return fetch("get_meals.php")
    .then((res) => res.json())
    .then((data) => {
      mealsFromDB = Array.isArray(data) ? data : [];
    });
}

// ===============================
// DISPLAY MEALS
// ===============================

function displayMeals() {
  const container = document.getElementById("mealList");
  container.innerHTML = "";

  mealsFromDB.forEach((meal) => {
    const isAvailable = Number(meal.available) === 1;
    const isOrdered = orderedMeals.has(Number(meal.id));

    const card = document.createElement("div");
    card.className = "meal-card";

    const imageStyle = meal.image
      ? `background-image:url('assets/images/${meal.image}')`
      : "";

    card.innerHTML = `
      <div class="card-bg" style="${imageStyle}"></div>
      <div class="card-content">
        <h3>${meal.name}</h3>
        <p class="${isAvailable ? "available" : "unavailable"}">
          ${isAvailable ? "Available" : "Unavailable"}
        </p>
        <p>${meal.description}</p>
        <p>$${Number(meal.price).toFixed(2)}</p>
        <button class="btn"
          ${!isAvailable || isOrdered ? "disabled" : ""}
          onclick="openOrderModal(${meal.id})">
          ${isOrdered ? "Pending" : "Order Now"}
        </button>
      </div>
    `;

    container.appendChild(card);
  });
}

// ===============================
// ORDER MODAL
// ===============================

function openOrderModal(mealId) {
  selectedMeal = mealsFromDB.find((m) => Number(m.id) === Number(mealId));
  if (!selectedMeal) return;

  document.getElementById("meal-name").textContent =
    "You are ordering: " + selectedMeal.name;

  document.getElementById("meal-price").textContent =
    "Price: $" + Number(selectedMeal.price).toFixed(2);

  document.getElementById("orderModal").style.display = "flex";
}

function closeOrderModal() {
  document.getElementById("orderModal").style.display = "none";
}

// ===============================
// PLACE ORDER
// ===============================

function confirmOrder() {
  if (!selectedMeal) return;

  fetch("place_order.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body:
      "meal_name=" +
      encodeURIComponent(selectedMeal.name) +
      "&price=" +
      encodeURIComponent(selectedMeal.price),
  })
    .then((res) => res.text())
    .then((data) => {
      if (data === "ORDER_SUCCESS") {
        orderedMeals.add(Number(selectedMeal.id));
        closeOrderModal();

        const msg = document.getElementById("orderMessage");
        msg.style.display = "block";
        msg.scrollIntoView({ behavior: "smooth", block: "center" });

        displayMeals();
        fetchStudentOrders();
      }
    });
}

// ===============================
// FETCH & DISPLAY STUDENT ORDERS + FEEDBACK
// ===============================

function fetchStudentOrders() {
  Promise.all([
    fetch("get_student_orders.php").then((res) => res.json()),
    fetch("get_feedback_status.php").then((res) => res.json()),
  ]).then(([orders, feedbackGiven]) => {
    const list = document.getElementById("orderList");
    list.innerHTML = "";

    if (orders.length === 0) {
      list.innerHTML = "<p>No orders yet.</p>";
      return;
    }

    orders.forEach((order) => {
      const card = document.createElement("div");
      card.className = "order-card";

      card.innerHTML = `
        <h3>${order.meal_name}</h3>
        <p>Price: $${Number(order.price).toFixed(2)}</p>
        <p>Status: <strong>${order.status}</strong></p>
        <p>Ordered at: ${order.created_at}</p>
      `;

      // Show feedback button only when allowed
      if (
        order.status === "completed" &&
        !feedbackGiven.includes(Number(order.id))
      ) {
        const btn = document.createElement("button");
        btn.className = "btn";
        btn.textContent = "Leave Feedback";
        btn.onclick = () => openFeedbackModal(order.id);
        card.appendChild(btn);
      }

      list.appendChild(card);
    });
  });
}

// ===============================
// FEEDBACK MODAL
// ===============================

function openFeedbackModal(orderId) {
  feedbackOrderId = orderId;
  document.getElementById("feedbackModal").style.display = "flex";
}

function closeFeedbackModal() {
  document.getElementById("feedbackModal").style.display = "none";
}

// ===============================
// SUBMIT FEEDBACK
// ===============================

function submitFeedback() {
  const rating = document.getElementById("feedbackRating").value;
  const comment = document.getElementById("feedbackComment").value;

  fetch("submit_feedback.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body:
      "order_id=" +
      feedbackOrderId +
      "&rating=" +
      rating +
      "&comment=" +
      encodeURIComponent(comment),
  })
    .then((res) => res.text())
    .then((data) => {
      if (data === "FEEDBACK_SUCCESS") {
        closeFeedbackModal();
        fetchStudentOrders();
      }
    });
}

// ===============================
// INIT
// ===============================

document.addEventListener("DOMContentLoaded", () => {
  fetchMeals().then(displayMeals);
  fetchStudentOrders();

  document.getElementById("confirmOrder").onclick = confirmOrder;
  document.getElementById("cancelOrder").onclick = closeOrderModal;
  document.getElementById("submitFeedback").onclick = submitFeedback;
});
