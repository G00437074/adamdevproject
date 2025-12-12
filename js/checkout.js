document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#checkout-form");
    const msg = document.querySelector("#checkout-message");
    const btn = document.querySelector("#place-order-btn");
  
    if (!form) return;
  
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      msg.textContent = "";
      btn.disabled = true;
  
      try {
        const formData = new FormData(form);
        const res = await fetch("api/place_order.php", { method: "POST", body: formData });
        const data = await res.json();
  
        if (data.status !== "ok") {
          msg.textContent = data.message || "Order failed";
          btn.disabled = false;
          return;
        }
  
        msg.textContent = `${data.message} (Order #${data.orderId})`;
        // redirect to a simple confirmation page if you want:
        // window.location.href = `order_confirmation.php?id=${data.orderId}`;
        //setTimeout(() => window.location.href = "merch.php", 800);
  
      } catch (err) {
        console.error(err);
        msg.textContent = "Something went wrong.";
        btn.disabled = false;
      }
    });
  });
  