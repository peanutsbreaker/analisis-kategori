document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("expense-form");
  const salaryForm = document.getElementById("salary-form");
  const salaryInput = document.getElementById("salary");
  const tbody = document.querySelector("#expense-table tbody");
  const chartCtx = document.getElementById("expense-chart").getContext("2d");
  const deleteSalaryBtn = document.getElementById("delete-salary");
  const salaryHistoryList = document.getElementById("salary-history");
  let chart;

  // Fungsi memuat pengeluaran dan saldo
  function loadExpenses() {
    fetch("load_expenses.php")
      .then(res => {
        if (!res.ok) throw new Error(`Gagal memuat data: ${res.status}`);
        return res.json();
      })
      .then(data => {
        const expenses = Array.isArray(data.expenses) ? data.expenses : [];
        const salary = parseFloat(data.salary || 0);

        // Update placeholder gaji
        if (salaryInput) {
          salaryInput.placeholder = `Total gaji: Rp ${salary.toLocaleString("id-ID")}`;
        }

        // Render tabel pengeluaran
        tbody.innerHTML = "";
        let categoryTotals = {};

        expenses.forEach((item, index) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.description}</td>
            <td>Rp ${parseFloat(item.amount).toLocaleString("id-ID")}</td>
            <td>${item.category}</td>
            <td>${new Date(item.date).toLocaleDateString("id-ID")}</td>
            <td><button class="delete-btn" data-id="${item.id}">Hapus</button></td>
          `;
          tbody.appendChild(row);

          categoryTotals[item.category] = (categoryTotals[item.category] || 0) + parseFloat(item.amount);
        });

        // Update chart
        updateChart(categoryTotals);

        // Update saldo
        const totalExpenses = expenses.reduce((sum, e) => sum + parseFloat(e.amount), 0);
        const balance = salary - totalExpenses;
        document.getElementById("balance-display").textContent = `Rp ${balance.toLocaleString("id-ID")}`;

        // Event listener hapus pengeluaran
        attachDeleteListeners();
      })
      .catch(error => {
        console.error("Gagal memuat pengeluaran:", error);
        alert("Terjadi kesalahan saat memuat data. Pastikan Anda sudah login dan server berjalan.");
      });
  }

  // Fungsi memuat riwayat gaji
  function loadSalaryHistory() {
    fetch("load_salaries.php")
    .then(res => {
        if (!res.ok) {
            console.error("HTTP Error:", res.status);
            return res.text().then(text => { throw new Error(text) });
        }
        return res.json();
    })
    .then(data => {
        if (!data.success) {
            alert("Gagal memuat: " + (data.error || "Unknown error"));
            return;
        }
        
        const historyList = document.getElementById("salary-history");
        if (!historyList) {
            console.error("Element #salary-history tidak ditemukan!");
            return;
        }

        historyList.innerHTML = data.data.map(s => `
            <li class="salary-item">
                <span class="salary-date">${s.date}</span>
                <span class="salary-amount">Rp ${Number(s.salary).toLocaleString("id-ID")}</span>
            </li>
        `).join("");
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Gagal memuat riwayat. Cek konsol untuk detail.");
    });
  }

  // Fungsi update chart
  function updateChart(categoryTotals) {
    if (chart) chart.destroy();
    
    if (Object.keys(categoryTotals).length > 0) {
      chart = new Chart(chartCtx, {
        type: "pie",
        data: {
          labels: Object.keys(categoryTotals),
          datasets: [{
            label: "Pengeluaran per Kategori",
            data: Object.values(categoryTotals),
            backgroundColor: ["#4CAF50", "#2196F3", "#FFC107", "#FF5722", "#9C27B0"]
          }]
        }
      });
    }
  }

  // Fungsi hapus pengeluaran
  function attachDeleteListeners() {
    document.querySelectorAll(".delete-btn").forEach(button => {
      button.addEventListener("click", () => {
        const id = button.getAttribute("data-id");
        if (confirm("Yakin ingin menghapus data ini?")) {
          fetch("delete_expense.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
          })
          .then(res => {
            if (!res.ok) {
              return res.json().then(data => {
                throw new Error(data.error || "Gagal menghapus");
              });
            }
            return res.json();
          })
          .then(data => {
            if (data.success) {
              alert("Data berhasil dihapus!");
              loadExpenses(); // Reload data setelah berhasil hapus
            } else {
              throw new Error(data.error || "Gagal menghapus");
            }
          })
          .catch(err => {
            console.error("Error detail:", err);
            alert("Error saat menghapus: " + err.message);
          });
        }
      });
    });
  }

  // Event listener form pengeluaran
  form.addEventListener("submit", e => {
    e.preventDefault();
    const newExpense = {
      description: document.getElementById("description").value,
      amount: document.getElementById("amount").value,
      category: document.getElementById("category").value
    };

    fetch("save_expenses.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newExpense)
    })
    .then(res => {
      if (!res.ok) throw new Error("Gagal menyimpan pengeluaran");
      form.reset();
      loadExpenses();
    })
    .catch(err => alert("Error: " + err.message));
  });

  // Event listener form gaji
  salaryForm.addEventListener("submit", e => {
    e.preventDefault();
    const salary = parseFloat(salaryInput.value);
    if (isNaN(salary)) return alert("Masukkan angka yang valid untuk gaji.");
    
    fetch("save_salary.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ salary })
    })
    .then(res => {
      if (!res.ok) throw new Error("Gagal menyimpan gaji");
      salaryInput.value = "";
      loadExpenses();
      loadSalaryHistory();
    })
    .catch(err => alert("Error menyimpan gaji: " + err.message));
  });

  // Inisialisasi pertama kali
  loadExpenses();
  loadSalaryHistory();
});