// Wait until the HTML page has fully loaded
document.addEventListener("DOMContentLoaded", () => {

    // Select all product forms on the page
    // Each form represents an "Add to Cart" action
    document.querySelectorAll("form.product-form").forEach((form) => {
  
      // Listen for form submission
      form.addEventListener("submit", async (e) => {
  
        // Prevent the page from reloading
        e.preventDefault();
  
        // Collect all form inputs (product ID, quantity, size, etc.)
        const formData = new FormData(form);
  
        try {
          // Send the form data to the add_to_cart API using POST
          const res = await fetch("api/add_to_cart.php", {
            method: "POST",
            body: formData,
          });
  
          // Convert the server response from JSON into an object
          const data = await res.json();
  
          // If the server returned an error, show a message
          if (data.status !== "ok") {
            alert(data.message || "Could not add to cart");
            return;
          }
  
          // ----------------------------
          // Update cart count badge
          // ----------------------------
          // (Only runs if you have a badge element on the page)
          const badge = document.querySelector("[data-cart-count]");
          if (badge && typeof data.cartCount !== "undefined") {
            badge.textContent = data.cartCount;
          }
  
          // ----------------------------
          // Show success feedback
          // ----------------------------
          alert("Added to cart <3");
  
        } catch (err) {
          // Handle network or server errors
          console.error(err);
          alert("Something went wrong adding to cart");
        }
      });
    });
  });
  