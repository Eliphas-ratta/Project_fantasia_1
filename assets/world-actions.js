// ==============================
// 🌍 Project Fantasia - World Actions
// ==============================
import "./styles/world.scss";
import Swal from "sweetalert2";

console.log("🔥 world-actions.js chargé avec succès !");
console.log("Version SweetAlert2 :", Swal ? Swal.version : "non chargé");

document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ World Actions script loaded");

  // -------------------------------------------------
  // Helpers
  // -------------------------------------------------
  const DEFAULT_AVATAR = "/uploads/profile_images/default.png";

  /** Construit un select custom (avatars) pour un <select> donné */
  function buildCustomSelect(selectEl) {
    // Nettoyage si déjà customisé
    if (selectEl.previousElementSibling?.classList.contains("custom-select-wrapper")) {
      selectEl.previousElementSibling.remove();
    }

    // Pas d’options -> on ne construit rien
    if (selectEl.options.length === 0) return;

    const wrapper = document.createElement("div");
    wrapper.classList.add("custom-select-wrapper");

    const button = document.createElement("button");
    button.type = "button";
    button.classList.add("custom-select-button");

    const dropdown = document.createElement("div");
    dropdown.classList.add("custom-select-dropdown");

    // Option affichée par défaut
    const firstOpt = selectEl.options[0];
    const firstImg = firstOpt?.dataset?.img || DEFAULT_AVATAR;
    const firstText = firstOpt?.text || "No more friend";

    if (firstOpt?.disabled) button.classList.add("no-friends");

    button.innerHTML = `
      <img src="${firstImg}" class="select-avatar" alt="">
      <span>${firstText}</span>
    `;

    // Si il y a au moins une option "cliquable", on génère la liste
    const hasChoices = Array.from(selectEl.options).some((o) => !o.disabled);
    if (hasChoices) {
      Array.from(selectEl.options).forEach((opt) => {
        if (opt.disabled) return;
        const item = document.createElement("div");
        item.classList.add("select-item");
        item.innerHTML = `
          <img src="${opt.dataset.img || DEFAULT_AVATAR}" class="select-avatar" alt="">
          <span>${opt.text}</span>
        `;
        item.addEventListener("click", () => {
          selectEl.value = opt.value;
          button.innerHTML = item.innerHTML;
          dropdown.classList.remove("open");
        });
        dropdown.appendChild(item);
      });

      button.addEventListener("click", () => dropdown.classList.toggle("open"));
      document.addEventListener("click", (e) => {
        if (!wrapper.contains(e.target)) dropdown.classList.remove("open");
      });
    }

    wrapper.appendChild(button);
    wrapper.appendChild(dropdown);

    // Cache le select natif et insère le custom avant
    selectEl.style.display = "none";
    selectEl.parentNode.insertBefore(wrapper, selectEl);
  }

  /** Reconstruit un select custom après modification des options */
  function rebuildCustomSelect(selectEl) {
    // Réaffiche temporairement le select pour permettre la mesure/MAJ
    selectEl.style.display = "";
    buildCustomSelect(selectEl);
  }

  // -------------------------------------------------
  // Leave World confirm
  // -------------------------------------------------
  const leaveForm = document.querySelector(".leave-world-form");
  if (leaveForm) {
    leaveForm.addEventListener("submit", async (e) => {
      e.preventDefault();
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
      if (result.isConfirmed) leaveForm.submit();
    });
  }

  // -------------------------------------------------
  // Delete World confirm
  // -------------------------------------------------
  const deleteForm = document.querySelector(".delete-world-form");
  if (deleteForm) {
    deleteForm.addEventListener("submit", async (e) => {
      e.preventDefault();
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
      if (result.isConfirmed) deleteForm.submit();
    });
  }

  // -------------------------------------------------
  // Add Member via AJAX (+ MAJ UI)
  // -------------------------------------------------
  // On supporte potentiellement plusieurs formulaires (show/admin)
  document.querySelectorAll("#add-member-form").forEach((addMemberForm) => {
    addMemberForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      e.stopPropagation();

      const select = addMemberForm.querySelector(".user-select-input");
      if (!select) return;

      const selectedOption = select.options[select.selectedIndex];
      if (!selectedOption || !selectedOption.value || selectedOption.disabled) {
        await Swal.fire({
          icon: "warning",
          title: "Please select a friend first",
          background: "#1b1b1b",
          color: "#fff",
        });
        return;
      }

      const formData = new FormData(addMemberForm);

      try {
        const response = await fetch(addMemberForm.action, {
          method: "POST",
          body: formData,
          headers: { "X-Requested-With": "XMLHttpRequest" },
        });

        const contentType = response.headers.get("content-type") || "";
        if (!contentType.includes("application/json")) {
          await Swal.fire({
            icon: "error",
            title: "Unexpected response",
            text: "Server returned an HTML page instead of JSON.",
            background: "#1b1b1b",
            color: "#fff",
          });
          return;
        }

        const result = await response.json();

        if (!result.success) {
          await Swal.fire({
            icon: "error",
            title: "Error",
            text: result.error || "An unknown error occurred.",
            background: "#1b1b1b",
            color: "#fff",
            iconColor: "#ff4d4d",
          });
          return;
        }

        // --- Succès ---
        await Swal.fire({
          icon: "success",
          title: "Member added!",
          text: `${result.username} has been added as ${result.role}.`,
          background: "#1b1b1b",
          color: "#fff",
          iconColor: "#3ecf5a",
          timer: 1400,
          showConfirmButton: false,
        });

        // 1) Retire l’option du select natif
        const removedImg = selectedOption.dataset.img || DEFAULT_AVATAR;
        select.remove(select.selectedIndex);

        // 2) Si plus aucune option -> ajoute "No more friend"
        if (select.options.length === 0) {
          const empty = document.createElement("option");
          empty.value = "";
          empty.textContent = "No more friend";
          empty.disabled = true;
          empty.selected = true;
          empty.dataset.img = DEFAULT_AVATAR;
          select.appendChild(empty);
        }

        // 3) Reconstruit le select custom pour refléter l’état
        rebuildCustomSelect(select);

       // 4) Ajoute visuellement le membre dans la liste Users (page admin ou show)
const userListAdmin = document.querySelector(".admin-users-card ul");
const userListShow  = document.querySelector(".world-users-card ul");

if (userListAdmin) {
  // --- Style pour page admin ---
  const li = document.createElement("li");
  li.className = "user-row d-flex justify-content-between align-items-center mb-3 p-2 rounded";
  li.style.backgroundColor = "#1a1a1a";

  li.innerHTML = `
    <div class="d-flex align-items-center gap-2">
      <img 
        src="${removedImg}" 
        alt="${result.username}" 
        class="user-avatar role-viewer"
      >
      <span class="fw-semibold">${result.username}</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <label class="small">Role:</label>
      <select class="role-dropdown" disabled>
        <option selected>Viewer</option>
      </select>
    </div>
  `;
  userListAdmin.appendChild(li);
}

else if (userListShow) {
  // --- Style pour page show ---
  const li = document.createElement("li");
  li.className = "d-flex align-items-center mb-3";
  li.innerHTML = `
    <img 
      src="${removedImg}" 
      alt="${result.username}" 
      class="user-avatar role-viewer"
    >
    <span class="ms-3">
      ${result.username}
      <small class="ms-2">(Viewer)</small>
    </span>
  `;
  userListShow.appendChild(li);
}


      } catch (err) {
        console.error("❌ AJAX error:", err);
        await Swal.fire({
          icon: "error",
          title: "Server error",
          text: "Could not add member.",
          background: "#1b1b1b",
          color: "#fff",
          iconColor: "#ff4d4d",
        });
      }
    });
  });

  // -------------------------------------------------
  // Custom select (avatars) pour TOUS les selects d'amis
  // -------------------------------------------------
  document.querySelectorAll(".user-select-input").forEach((selectEl) => {
    // Si aucune option, on injecte un placeholder "No more friend"
    if (selectEl.options.length === 0) {
      const empty = document.createElement("option");
      empty.value = "";
      empty.textContent = "No more friend";
      empty.disabled = true;
      empty.selected = true;
      empty.dataset.img = DEFAULT_AVATAR;
      selectEl.appendChild(empty);
    }
    // Sécurise les data-img manquants
    Array.from(selectEl.options).forEach((opt) => {
      if (!opt.dataset.img) opt.dataset.img = DEFAULT_AVATAR;
    });
    buildCustomSelect(selectEl);
  });
});

  // -------------------------------------------------
// 🎭 Change user role via AJAX + MAJ UI
// -------------------------------------------------
document.querySelectorAll(".role-select").forEach((select) => {
  select.addEventListener("change", async () => {
    const userId = select.dataset.userId;
    const worldId = select.dataset.worldId;
    const newRole = select.value;
    const avatar = select.closest(".user-row").querySelector(".user-avatar");

    const formData = new FormData();
    formData.append("userId", userId);
    formData.append("role", newRole);

    try {
      const response = await fetch(`/world/${worldId}/update-role`, {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" },
      });

      const result = await response.json();

      if (!result.success) {
        throw new Error(result.error || "Update failed");
      }

      // ✅ Changement visuel immédiat
      avatar.classList.remove("role-viewer", "role-moderator", "role-admin");
      if (newRole === "ADMIN") avatar.classList.add("role-admin");
      else if (newRole === "MODERATOR") avatar.classList.add("role-moderator");
      else avatar.classList.add("role-viewer");

      
      // 🌟 Effet d'animation visuel
      avatar.classList.add("role-changed");
      setTimeout(() => avatar.classList.remove("role-changed"), 600);

      await Swal.fire({
        icon: "success",
        title: "Role updated!",
        text: result.message || `${newRole} assigned successfully.`,
        background: "#1b1b1b",
        color: "#fff",
        iconColor: "#3ecf5a",
        timer: 1200,
        showConfirmButton: false,
      });
    } catch (err) {
      console.error("❌ Role update error:", err);
      await Swal.fire({
        icon: "error",
        title: "Error",
        text: err.message || "Could not update role.",
        background: "#1b1b1b",
        color: "#fff",
        iconColor: "#ff4d4d",
      });
    }
  });
});

