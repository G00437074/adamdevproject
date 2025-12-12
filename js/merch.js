document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form.product-form").forEach((form) => {
      form.addEventListener("submit", async (e) => {
        e.preventDefault();
  
        const formData = new FormData(form);
  
        try {
          const res = await fetch("api/add_to_cart.php", {
            method: "POST",
            body: formData,
          });
  
          const data = await res.json();
  
          if (data.status !== "ok") {
            alert(data.message || "Could not add to cart");
            return;
          }
  
          // Optional: update a cart count badge if you have one
          const badge = document.querySelector("[data-cart-count]");
          if (badge && typeof data.cartCount !== "undefined") {
            badge.textContent = data.cartCount;
          }
  
          // Simple success feedback
          alert("Added to cart ✅");
        } catch (err) {
          console.error(err);
          alert("Something went wrong adding to cart");
        }
      });
    });
  });
  