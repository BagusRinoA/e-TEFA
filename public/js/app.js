/**
 * e-TEFA Kompeni - Main JavaScript File
 * Hydroponic Agriculture Platform
 *
 * This file contains all interactive functionality for the Laravel application
 */

(function() {
  'use strict';

  // ============================================================================
  // UTILITY FUNCTIONS
  // ============================================================================

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  function show(element) {
    if (typeof element === 'string') {
      document.querySelectorAll(element).forEach(el => el.style.display = 'block');
    } else {
      element.style.display = 'block';
    }
  }

  function hide(element) {
    if (typeof element === 'string') {
      document.querySelectorAll(element).forEach(el => el.style.display = 'none');
    } else {
      element.style.display = 'none';
    }
  }

  function toggle(element) {
    if (typeof element === 'string') {
      document.querySelectorAll(element).forEach(el => {
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
      });
    } else {
      element.style.display = element.style.display === 'none' ? 'block' : 'none';
    }
  }

  // ============================================================================
  // MOBILE MENU
  // ============================================================================

  function initMobileMenu() {
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (mobileMenuButton && mobileMenu) {
      mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });

      document.addEventListener('click', (e) => {
        if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
          mobileMenu.classList.add('hidden');
        }
      });
    }
  }

  // ============================================================================
  // DROPDOWNS
  // ============================================================================

  function initDropdowns() {
    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');

    dropdownToggles.forEach(toggle => {
      const dropdownId = toggle.getAttribute('data-dropdown-toggle');
      const dropdown = document.getElementById(dropdownId);

      if (dropdown) {
        toggle.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();

          document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
          });

          dropdown.classList.toggle('hidden');
        });
      }
    });

    document.addEventListener('click', () => {
      document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
        dropdown.classList.add('hidden');
      });
    });
  }

  // ============================================================================
  // MODALS
  // ============================================================================

  function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-toggle]');
    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = trigger.getAttribute('data-modal-toggle');
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
          document.body.style.overflow = 'hidden';
        }
      });
    });

    const modalCloses = document.querySelectorAll('[data-modal-close]');
    modalCloses.forEach(close => {
      close.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = close.getAttribute('data-modal-close');
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.style.overflow = '';
        }
      });
    });

    const modals = document.querySelectorAll('[data-modal]');
    modals.forEach(modal => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.style.overflow = '';
        }
      });
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        modals.forEach(modal => {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.style.overflow = '';
        });
      }
    });
  }

  // ============================================================================
  // TABS
  // ============================================================================

  function initTabs() {
    const tabButtons = document.querySelectorAll('[data-tab-toggle]');

    tabButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const tabId = button.getAttribute('data-tab-toggle');
        const tabGroup = button.closest('[data-tab-group]');

        if (tabGroup) {
          tabGroup.querySelectorAll('[data-tab-toggle]').forEach(btn => {
            btn.classList.remove('active');
          });
          button.classList.add('active');

          tabGroup.querySelectorAll('[data-tab-panel]').forEach(panel => {
            panel.classList.add('hidden');
          });

          const selectedPanel = document.getElementById(tabId);
          if (selectedPanel) {
            selectedPanel.classList.remove('hidden');
          }
        }
      });
    });
  }

  // ============================================================================
  // FORM VALIDATION
  // ============================================================================

  function initFormValidation() {
    const forms = document.querySelectorAll('[data-validate]');

    forms.forEach(form => {
      form.addEventListener('submit', (e) => {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        form.querySelectorAll('.form-error').forEach(error => error.remove());

        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            isValid = false;
            const error = document.createElement('div');
            error.className = 'form-error';
            error.textContent = 'This field is required';
            field.parentNode.appendChild(error);
          }

          if (field.type === 'email' && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
              isValid = false;
              const error = document.createElement('div');
              error.className = 'form-error';
              error.textContent = 'Please enter a valid email';
              field.parentNode.appendChild(error);
            }
          }
        });

        if (!isValid) e.preventDefault();
      });
    });
  }

  // ============================================================================
  // SEARCH
  // ============================================================================

  function initSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');

    searchInputs.forEach(input => {
      const targetSelector = input.getAttribute('data-search');
      const searchHandler = debounce(() => {
        const query = input.value.toLowerCase().trim();
        const items = document.querySelectorAll(targetSelector);

        items.forEach(item => {
          const text = item.textContent.toLowerCase();
          item.style.display = text.includes(query) ? '' : 'none';
        });
      }, 300);

      input.addEventListener('input', searchHandler);
    });
  }

  // ============================================================================
  // FILTERS
  // ============================================================================

  function initFilters() {
    const filterButtons = document.querySelectorAll('[data-filter]');

    filterButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const filterValue = button.getAttribute('data-filter');
        const targetSelector = button.getAttribute('data-filter-target');
        const items = document.querySelectorAll(targetSelector);

        const filterGroup = button.closest('[data-filter-group]');
        if (filterGroup) {
          filterGroup.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('active');
          });
        }
        button.classList.add('active');

        items.forEach(item => {
          if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  }

  // ============================================================================
  // TOAST NOTIFICATIONS
  // ============================================================================

  window.showToast = function(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
      type === 'success' ? 'bg-green-500 text-white' :
      type === 'error' ? 'bg-red-500 text-white' :
      type === 'warning' ? 'bg-yellow-500 text-white' :
      'bg-blue-500 text-white'
    }`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.3s';
      setTimeout(() => document.body.removeChild(toast), 300);
    }, duration);
  };

  // ============================================================================
  // AJAX HELPER
  // ============================================================================

  window.ajax = function(url, options = {}) {
    const defaults = {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      }
    };

    const config = { ...defaults, ...options };

    if (config.body && typeof config.body === 'object') {
      config.body = JSON.stringify(config.body);
    }

    return fetch(url, config)
      .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
      })
      .catch(error => {
        console.error('AJAX Error:', error);
        throw error;
      });
  };

  // ============================================================================
  // CART FUNCTIONS
  // ============================================================================

  function initCart() {
    const cartButtons = document.querySelectorAll('[data-add-to-cart]');

    cartButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.getAttribute('data-add-to-cart');
        const originalText = button.textContent;
        
        button.disabled = true;
        button.textContent = 'Adding...';

        ajax('/cart/add', {
          method: 'POST',
          body: { product_id: productId, quantity: 1 }
        })
          .then(data => {
            showToast('Product added to cart!', 'success');
            updateCartCount(data.cart_count);
            button.textContent = 'Added!';
            setTimeout(() => {
              button.disabled = false;
              button.textContent = originalText;
            }, 1000);
          })
          .catch(error => {
            showToast('Failed to add to cart', 'error');
            button.disabled = false;
            button.textContent = originalText;
          });
      });
    });
  }

  function updateCartCount(count) {
    document.querySelectorAll('[data-cart-count]').forEach(element => {
      element.textContent = count;
      element.style.display = count > 0 ? '' : 'none';
    });
  }

  // ============================================================================
  // IMAGE PREVIEW
  // ============================================================================

  function initImagePreview() {
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');

    fileInputs.forEach(input => {
      input.addEventListener('change', (e) => {
        const previewId = input.getAttribute('data-preview');
        const preview = document.getElementById(previewId);
        const file = e.target.files[0];

        if (file && preview) {
          const reader = new FileReader();
          reader.onload = (event) => {
            preview.src = event.target.result;
            preview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      });
    });
  }

  // ============================================================================
  // ACCORDIONS
  // ============================================================================

  function initAccordions() {
    const accordionButtons = document.querySelectorAll('[data-accordion-toggle]');

    accordionButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const contentId = button.getAttribute('data-accordion-toggle');
        const content = document.getElementById(contentId);

        if (content) {
          const isOpen = !content.classList.contains('hidden');
          const group = button.closest('[data-accordion-group]');
          
          if (group) {
            group.querySelectorAll('[data-accordion-content]').forEach(c => {
              c.classList.add('hidden');
            });
          }

          if (!isOpen) {
            content.classList.remove('hidden');
          }
        }
      });
    });
  }

  // ============================================================================
  // CONFIRM DIALOGS
  // ============================================================================

  function initConfirmDialogs() {
    const confirmButtons = document.querySelectorAll('[data-confirm]');

    confirmButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        const message = button.getAttribute('data-confirm');
        if (!confirm(message)) {
          e.preventDefault();
          return false;
        }
      });
    });
  }

  // ============================================================================
  // AUTO-HIDE ALERTS
  // ============================================================================

  function initAutoHideAlerts() {
    const alerts = document.querySelectorAll('[data-auto-hide]');

    alerts.forEach(alert => {
      const delay = parseInt(alert.getAttribute('data-auto-hide')) || 5000;
      setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s';
        setTimeout(() => alert.remove(), 300);
      }, delay);
    });
  }

  // ============================================================================
  // LAZY LOADING
  // ============================================================================

  function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
            imageObserver.unobserve(img);
          }
        });
      });

      images.forEach(img => imageObserver.observe(img));
    } else {
      images.forEach(img => {
        img.src = img.getAttribute('data-src');
        img.removeAttribute('data-src');
      });
    }
  }

  // ============================================================================
  // INITIALIZE ALL
  // ============================================================================

  document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();
    initDropdowns();
    initModals();
    initTabs();
    initFormValidation();
    initSearch();
    initFilters();
    initCart();
    initImagePreview();
    initAccordions();
    initConfirmDialogs();
    initAutoHideAlerts();
    initLazyLoading();

    console.log('e-TEFA Kompeni initialized');
  });

  // ============================================================================
  // GLOBAL EXPORTS
  // ============================================================================

  window.eTEFA = {
    show,
    hide,
    toggle,
    showToast,
    ajax,
    debounce
  };

})();
