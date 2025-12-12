// Wait until the HTML page has fully loaded
document.addEventListener("DOMContentLoaded", () => {

    // Get references to the checkout form, message area, and submit button
    const form = document.querySelector("#checkout-form");
    const msg  = document.querySelector("#checkout-message");
    const btn  = document.querySelector("#place-order-btn");
  
    // If the checkout form does not exist on this page, stop here
    if (!form) return;
  
    // ----------------------------
    // Handle checkout form submit
    // ----------------------------
    form.addEventListener("submit", async (e) => {
  
      // Prevent the form from submitting normally (page reload)
      e.preventDefault();
  
      // Clear any previous messages
      msg.textContent = "";
  
      // Disable the button to prevent multiple submissions
      btn.disabled = true;
  
      try {
        // Collect all form input values
        const formData = new FormData(form);
  
        // Send the form data to the server using fetch (AJAX)
        const res = await fetch("api/place_order.php", {
          method: "POST",
          body: formData
        });
  
        // Convert the server response from JSON to an object
        const data = await res.json();
  
        // If the order was not successful, show an error
        if (data.status !== "ok") {
          msg.textContent = data.message || "Order failed";
          btn.disabled = false;
          return;
        }
  
        // Display success message with the order ID
        msg.textContent = `${data.message} (Order #${data.orderId})`;
  
        // Optional redirect to a confirmation page
        // window.location.href = `order_confirmation.php?id=${data.orderId}`;
  
        // Optional redirect back to merch page after a short delay
        // setTimeout(() => window.location.href = "merch.php", 800);
  
      } catch (err) {
        // Handle network or server errors
        console.error(err);
        msg.textContent = "Something went wrong.";
        btn.disabled = false;
      }
    });
  });
  
  