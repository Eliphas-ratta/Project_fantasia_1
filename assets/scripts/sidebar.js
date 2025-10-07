document.addEventListener('DOMContentLoaded', () => {
  // ✅ Toggle sidebar
  const btn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('friendsSidebar');
  if (btn && sidebar) {
    btn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
  }

  // ✅ Helper for messages
  const showMessage = (msg, type = 'success') => {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = msg;
    Object.assign(alert.style, {
      position: 'fixed',
      top: '1rem',
      right: '1rem',
      zIndex: '9999',
      padding: '10px 15px',
      borderRadius: '8px'
    });
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
  };

  // ✅ Refresh sidebar content
  const refreshSidebar = async () => {
    const sidebarBox = document.querySelector('#friendsBox');
    if (!sidebarBox) return;
    sidebarBox.style.opacity = '0.5';
    try {
      const response = await fetch('/friend/sidebar', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      sidebarBox.innerHTML = await response.text();
    } catch (e) {
      console.error(e);
    } finally {
      sidebarBox.style.opacity = '1';
    }
  };

  // ✅ Add Friend
  document.body.addEventListener('submit', async (e) => {
    const form = e.target.closest('#add-friend-form');
    if (!form) return;
    e.preventDefault();
    const response = await fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json().catch(() => ({ message: 'Error', status: 'danger' }));
    showMessage(data.message, data.status || 'info');
    form.reset();
    await refreshSidebar();
  });

  // ✅ Accept / Decline / Remove
  document.body.addEventListener('click', async (e) => {
    const link = e.target.closest('.ajax-action');
    if (!link) return;
    e.preventDefault();
    const response = await fetch(link.href, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json().catch(() => ({ message: 'Error', status: 'danger' }));
    showMessage(data.message, data.status || 'info');
    await refreshSidebar();
  });
});
