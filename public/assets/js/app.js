(() => {
  if (window.__worklessDropdownsInitialized) return;
  window.__worklessDropdownsInitialized = true;

  const closeDropdowns = (except = null) => {
    document.querySelectorAll('details[data-dropdown][open]').forEach((dropdown) => {
      if (dropdown !== except) dropdown.removeAttribute('open');
    });
  };

  document.addEventListener('click', (event) => {
    const target = event.target;
    if (!(target instanceof Element)) return;

    const activeDropdown = target.closest('details[data-dropdown]');
    closeDropdowns(activeDropdown);

    if (activeDropdown && target.closest('[data-dropdown-menu] a')) {
      activeDropdown.removeAttribute('open');
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;

    const openDropdown = document.querySelector('details[data-dropdown][open]');
    closeDropdowns();

    if (openDropdown instanceof HTMLElement) {
      openDropdown.querySelector('summary')?.focus();
    }
  });
})();
