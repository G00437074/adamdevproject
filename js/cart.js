// Wait until the HTML page has fully loaded before running any JavaScript
document.addEventListener("DOMContentLoaded", () => {

    // Get references to key elements on the page
    const table   = document.querySelector("#cart-table");   // Cart table
    const totalEl = document.querySelector("#cart-total");   // Cart total display
    const emptyBtn = document.querySelector("#empty-cart");  // "Empty Cart" button
  
    // ----------------------------
    // Format a number as currency
    // ----------------------------
    function money(n) {
      return "€" + Number(n).toFixed(2);
    }
  
    // ----------------------------
    // Recalculate the cart total
    // ----------------------------
    function recalcTotal() {
      let total = 0;
  
      // Loop through all line-total cells
      document.querySelectorAll("[data-line-total]").forEach((cell) => {
        // Add the raw numeric value stored in data-raw
        total += Number(cell.getAttribute("data-raw") || 0);
      });
  
      // Update the total displayed on the page
      if (totalEl) totalEl.textContent = money(total);
    }
  
    // ----------------------------
    // Helper function for POST requests
    // ----------------------------
    async function post(url, bodyObj) {
  
      // Create FormData for sending to PHP
      const form = new FormData();
  
      // Add all key/value pairs to the form
      Object.entries(bodyObj).forEach(([k, v]) => form.append(k, v));
  
      // Send POST request to the server
      const res = await fetch(url, {
        method: "POST",
        body: form
      });
  
      // Parse and return the JSON response
      return res.json();
    }
  
    // ----------------------------
    // Update item quantity
    // ----------------------------
    if (table) {
      table.addEventListener("change", async (e) => {
  
        // Detect quantity input change
        const input = e.target.closest("[data-qty]");
        if (!input) return;
  
        // Find the table row and cart key
        const row = input.closest("tr[data-key]");
        const key = row?.getAttribute("data-key");
        const qty = Number(input.value);
  
        // Validate input
        if (!key || Number.isNaN(qty) || qty < 0) return;
  
        // Send updated quantity to the server
        const data = await post("api/update_cart.php", {
          key,
          quantity: qty
        });
  
        // Handle server error
        if (data.status !== "ok") {
          alert(data.message || "Could not update cart");
          return;
        }
  
        // If quantity is 0, remove the row from the page
        if (qty === 0) {
          row.remove();
        } else {
          // Update the line total in the table
          const priceCell = row.querySelector("[data-price]");
          const lineCell  = row.querySelector("[data-line-total]");
  
          const price = Number(priceCell?.getAttribute("data-price") || 0);
          const lineTotal = price * qty;
  
          if (lineCell) {
            lineCell.textContent = money(lineTotal);
            lineCell.setAttribute("data-raw", String(lineTotal));
          }
        }
  
        // If the cart is now empty, reload the page
        const remainingRows = table.querySelectorAll("tbody tr").length;
        if (remainingRows === 0) {
          window.location.reload();
          return;
        }
  
        // Recalculate cart total
        recalcTotal();
      });
  
      // ----------------------------
      // Remove an item from the cart
      // ----------------------------
      table.addEventListener("click", async (e) => {
  
        // Detect remove button click
        const btn = e.target.closest("[data-remove]");
        if (!btn) return;
  
        // Find row and cart key
        const row = btn.closest("tr[data-key]");
        const key = row?.getAttribute("data-key");
        if (!key) return;
  
        // Send remove request to the server
        const data = await post("api/remove_from_cart.php", { key });
  
        // Handle error
        if (data.status !== "ok") {
          alert(data.message || "Could not remove item");
          return;
        }
  
        // Remove row from the page
        row.remove();
  
        // Reload if cart is empty
        const remainingRows = table.querySelectorAll("tbody tr").length;
        if (remainingRows === 0) {
          window.location.reload();
          return;
        }
  
        // Recalculate cart total
        recalcTotal();
      });
    }
  
    // ----------------------------
    // Empty the entire cart
    // ----------------------------
    if (emptyBtn) {
      emptyBtn.addEventListener("click", async () => {
  
        // Call the empty cart API
        const data = await post("api/empty_cart.php", {});
  
        // Handle error
        if (data.status !== "ok") {
          alert(data.message || "Could not empty cart");
          return;
        }
  
        // Reload page to show empty cart state
        window.location.reload();
      });
    }
  
    // ----------------------------
    // Initialise raw totals on page load
    // ----------------------------
    // This ensures totals can be recalculated correctly
    document.querySelectorAll("tr[data-key]").forEach((row) => {
      const lineCell = row.querySelector("[data-line-total]");
      if (!lineCell) return;
  
      // Extract numeric value from displayed text
      const text = lineCell.textContent.replace("€", "").trim();
      const val = Number(text);
  
      // Store numeric value for calculations
      if (!Number.isNaN(val)) {
        lineCell.setAttribute("data-raw", String(val));
      }
    });
  });
  