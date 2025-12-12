document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#cart-table");
    const totalEl = document.querySelector("#cart-total");
    const emptyBtn = document.querySelector("#empty-cart");
  
    function money(n) {
      return "€" + Number(n).toFixed(2);
    }
  
    function recalcTotal() {
      let total = 0;
      document.querySelectorAll("[data-line-total]").forEach((cell) => {
        total += Number(cell.getAttribute("data-raw") || 0);
      });
      if (totalEl) totalEl.textContent = money(total);
    }
  
    async function post(url, bodyObj) {
      const form = new FormData();
      Object.entries(bodyObj).forEach(([k, v]) => form.append(k, v));
  
      const res = await fetch(url, { method: "POST", body: form });
      return res.json();
    }
  
    // Update quantity
    if (table) {
      table.addEventListener("change", async (e) => {
        const input = e.target.closest("[data-qty]");
        if (!input) return;
  
        const row = input.closest("tr[data-key]");
        const key = row?.getAttribute("data-key");
        const qty = Number(input.value);
  
        if (!key || Number.isNaN(qty) || qty < 0) return;
  
        const data = await post("api/update_cart.php", { key, quantity: qty });
        if (data.status !== "ok") {
          alert(data.message || "Could not update cart");
          return;
        }
  
        // If qty is 0, remove row from DOM
        if (qty === 0) {
          row.remove();
        } else {
          // Update line total in DOM
          const priceCell = row.querySelector("[data-price]");
          const lineCell = row.querySelector("[data-line-total]");
          const price = Number(priceCell?.getAttribute("data-price") || 0);
          const lineTotal = price * qty;
  
          if (lineCell) {
            lineCell.textContent = money(lineTotal);
            lineCell.setAttribute("data-raw", String(lineTotal));
          }
        }
  
        // If table empty, reload to show "cart is empty" state
        const remainingRows = table.querySelectorAll("tbody tr").length;
        if (remainingRows === 0) {
          window.location.reload();
          return;
        }
  
        recalcTotal();
      });
  
      // Remove item
      table.addEventListener("click", async (e) => {
        const btn = e.target.closest("[data-remove]");
        if (!btn) return;
  
        const row = btn.closest("tr[data-key]");
        const key = row?.getAttribute("data-key");
        if (!key) return;
  
        const data = await post("api/remove_from_cart.php", { key });
        if (data.status !== "ok") {
          alert(data.message || "Could not remove item");
          return;
        }
  
        row.remove();
  
        const remainingRows = table.querySelectorAll("tbody tr").length;
        if (remainingRows === 0) {
          window.location.reload();
          return;
        }
  
        recalcTotal();
      });
    }
  
    // Empty cart
    if (emptyBtn) {
      emptyBtn.addEventListener("click", async () => {
        const data = await post("api/empty_cart.php", {});
        if (data.status !== "ok") {
          alert(data.message || "Could not empty cart");
          return;
        }
        window.location.reload();
      });
    }
  
    // Set initial raw totals for recalc (based on existing text)
    document.querySelectorAll("tr[data-key]").forEach((row) => {
      const lineCell = row.querySelector("[data-line-total]");
      if (!lineCell) return;
      const text = lineCell.textContent.replace("€", "").trim();
      const val = Number(text);
      if (!Number.isNaN(val)) lineCell.setAttribute("data-raw", String(val));
    });
  });
  