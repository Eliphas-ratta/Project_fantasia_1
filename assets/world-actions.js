// ==============================
// 🌍 Project Fantasia - World Actions
// ==============================

import Swal from "sweetalert2";



console.log("🔥 world-actions.js chargé avec succès !");
console.log("Version SweetAlert2 :", Swal ? Swal.version : "non chargé");

// Attente que tout soit prêt
document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ World Actions script loaded");

  // =======================
  // 🔹 Confirmation Leave World
  // =======================
  const leaveForm = document.querySelector(".leave-world-form");
  if (leaveForm) {
    leaveForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      if (typeof Swal === "undefined") {
        console.warn("⚠️ SweetAlert2 not loaded, fallback confirm()");
        if (confirm("Are you sure you want to leave this world?")) leaveForm.submit();
        return;
      }

      const result = await Swal.fire({
        title: "Leave this world?",
        text: "You will lose access to this world and its content.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e63946",
        cancelButtonColor: "#4a4a4a",
        confirmButtonText: "Yes, leave",
        cancelButtonText: "Cancel",
        background: "#1b1b1b",
        color: "#fff",
        iconColor: "#ff4d4d",
      });

      if (result.isConfirmed) {
        leaveForm.submit();
      }
    });
  }

  // =======================
  // 🔹 Confirmation Delete World
  // =======================
  const deleteForm = document.querySelector(".delete-world-form");
  if (deleteForm) {
    deleteForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      if (typeof Swal === "undefined") {
        console.warn("⚠️ SweetAlert2 not loaded, fallback confirm()");
        if (confirm("Are you sure you want to delete this world?")) deleteForm.submit();
        return;
      }

      const result = await Swal.fire({
        title: "Delete this world?",
        text: "This action is irreversible — all data will be permanently lost.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e63946",
        cancelButtonColor: "#4a4a4a",
        confirmButtonText: "Yes, delete it",
        cancelButtonText: "Cancel",
        background: "#1b1b1b",
        color: "#fff",
        iconColor: "#ff4d4d",
      });

      if (result.isConfirmed) {
        deleteForm.submit();
      }
    });
  }
});
