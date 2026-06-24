/**
 * e-TEFA Kompeni - Main JavaScript File
 * Hydroponic Agriculture Platform
 *
 * This file contains all interactive functionality including:
 * - Mobile menu, modals, dropdowns, tabs
 * - Form validation and submission
 * - Cart functionality
 * - Search and filters
 * - Image handling
 * - AJAX requests
 * - Notifications
 */

(function() {
  'use strict';

  // ============================================================================
  // UTILITY FUNCTIONS
  // ============================================================================

  /**
   * Add event listener with support for multiple elements
   */
  function on(selector: string | Element, event: string, handler: EventListener, options = {}) {
    const elements = typeof selector === 'string' ? document.querySelectorAll(selector) : [selector];
    elements.forEach(element => {
      if (element) {
        element.addEventListener(event, handler, options);
      }
    });
  }

  /**
   * Debounce function to limit rate of function calls
   */
  function debounce(func: Function, wait: number) {
    let timeout: NodeJS.Timeout;
    return function executedFunction(...args: any[]) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  /**
   * Show/Hide elements
   */
  function show(element: string | HTMLElement) {
    if (typeof element === 'string') {
      document.querySelectorAll(element).forEach(el => {
        (el as HTMLElement).style.display = 'block';
      });
    } else {
      element.style.display = 'block';
    }
  }

  function hide(element: string | HTMLElement) {
    if (typeof element === 'string') {
      document.querySelectorAll(element).forEach(el => {
        (el as HTMLElement).style.display = 'none';
      });
    } else {
      element.style.display = 'none';
    }
  }

  function toggle(element: string | HTMLElement) {
    if (typeof element === 'string') {
      document.querySelectorAll(element).forEach(el => {
        const htmlEl = el as HTMLElement;
        htmlEl.style.display = htmlEl.style.display === 'none' ? 'block' : 'none';
      });
    } else {
      element.style.display = element.style.display === 'none' ? 'block' : 'none';
    }
  }

  // ============================================================================
  // MOBILE MENU TOGGLE
  // ============================================================================

  function initMobileMenu() {
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (mobileMenuButton && mobileMenu) {
      mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });

      // Close menu when clicking outside
      document.addEventListener('click', (e) => {
        if (!mobileMenuButton.contains(e.target as Node) && !mobileMenu.contains(e.target as Node)) {
          mobileMenu.classList.add('hidden');
        }
      });
    }
  }

  // ============================================================================
  // DROPDOWN MENUS
  // ============================================================================

  function initDropdowns() {
    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');

    dropdownToggles.forEach(toggle => {
      const dropdownId = toggle.getAttribute('data-dropdown-toggle');
      const dropdown = document.getElementById(dropdownId!);

      if (dropdown) {
        toggle.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();

          // Close other dropdowns
          document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d !== dropdown) {
              d.classList.add('hidden');
            }
          });

          // Toggle current dropdown
          dropdown.classList.toggle('hidden');
        });
      }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
      document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
        dropdown.classList.add('hidden');
      });
    });
  }

  // ============================================================================
  // MODAL DIALOGS
  // ============================================================================

  function initModals() {
    // Open modal
    const modalTriggers = document.querySelectorAll('[data-modal-toggle]');
    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = trigger.getAttribute('data-modal-toggle');
        const modal = document.getElementById(modalId!);
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
          document.body.style.overflow = 'hidden';
        }
      });
    });

    // Close modal
    const modalCloses = document.querySelectorAll('[data-modal-close]');
    modalCloses.forEach(close => {
      close.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = close.getAttribute('data-modal-close');
        const modal = document.getElementById(modalId!);
        if (modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.style.overflow = '';
        }
      });
    });

    // Close modal when clicking backdrop
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

    // Close modal with Escape key
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
          // Remove active class from all buttons in group
          tabGroup.querySelectorAll('[data-tab-toggle]').forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-muted-foreground');
          });

          // Add active class to clicked button
          button.classList.add('border-primary', 'text-primary');
          button.classList.remove('border-transparent', 'text-muted-foreground');

          // Hide all tab panels
          tabGroup.querySelectorAll('[data-tab-panel]').forEach(panel => {
            panel.classList.add('hidden');
          });

          // Show selected tab panel
          const selectedPanel = document.getElementById(tabId!);
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

        // Clear previous errors
        form.querySelectorAll('.form-error').forEach(error => error.remove());
        form.querySelectorAll('.border-destructive').forEach(el => {
          el.classList.remove('border-destructive');
        });

        requiredFields.forEach(field => {
          const inputField = field as HTMLInputElement;
          if (!inputField.value.trim()) {
            isValid = false;
            field.classList.add('border-destructive');

            const error = document.createElement('div');
            error.className = 'form-error';
            error.textContent = 'This field is required';
            field.parentNode!.appendChild(error);
          }

          // Email validation
          if (inputField.type === 'email' && inputField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(inputField.value)) {
              isValid = false;
              field.classList.add('border-destructive');

              const error = document.createElement('div');
              error.className = 'form-error';
              error.textContent = 'Please enter a valid email address';
              field.parentNode!.appendChild(error);
            }
          }

          // Password confirmation
          if (inputField.name === 'password_confirmation') {
            const password = form.querySelector('[name="password"]') as HTMLInputElement;
            if (password && inputField.value !== password.value) {
              isValid = false;
              field.classList.add('border-destructive');

              const error = document.createElement('div');
              error.className = 'form-error';
              error.textContent = 'Passwords do not match';
              field.parentNode!.appendChild(error);
            }
          }
        });

        if (!isValid) {
          e.preventDefault();
        }
      });

      // Real-time validation on blur
      const inputs = form.querySelectorAll('input, textarea, select');
      inputs.forEach(input => {
        input.addEventListener('blur', () => {
          const inputElement = input as HTMLInputElement;
          const errorElement = input.parentNode!.querySelector('.form-error');

          if (input.hasAttribute('required') && !inputElement.value.trim()) {
            input.classList.add('border-destructive');
            if (!errorElement) {
              const error = document.createElement('div');
              error.className = 'form-error';
              error.textContent = 'This field is required';
              input.parentNode!.appendChild(error);
            }
          } else {
            input.classList.remove('border-destructive');
            if (errorElement) {
              errorElement.remove();
            }
          }
        });
      });
    });
  }

  // ============================================================================
  // SEARCH FUNCTIONALITY
  // ============================================================================

  function initSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');

    searchInputs.forEach(input => {
      const targetSelector = input.getAttribute('data-search');
      const searchHandler = debounce(() => {
        const query = (input as HTMLInputElement).value.toLowerCase().trim();
        const items = document.querySelectorAll(targetSelector!);

        items.forEach(item => {
          const text = item.textContent!.toLowerCase();
          const htmlItem = item as HTMLElement;
          if (text.includes(query)) {
            htmlItem.style.display = '';
          } else {
            htmlItem.style.display = 'none';
          }
        });
      }, 300);

      input.addEventListener('input', searchHandler as EventListener);
    });
  }

  // ============================================================================
  // FILTER FUNCTIONALITY
  // ============================================================================

  function initFilters() {
    const filterButtons = document.querySelectorAll('[data-filter]');

    filterButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const filterValue = button.getAttribute('data-filter');
        const targetSelector = button.getAttribute('data-filter-target');
        const items = document.querySelectorAll(targetSelector!);

        // Update active state
        const filterGroup = button.closest('[data-filter-group]');
        if (filterGroup) {
          filterGroup.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-primary-foreground');
            btn.classList.add('bg-secondary', 'text-secondary-foreground');
          });
        }
        button.classList.add('bg-primary', 'text-primary-foreground');
        button.classList.remove('bg-secondary', 'text-secondary-foreground');

        // Filter items
        items.forEach(item => {
          const htmlItem = item as HTMLElement;
          if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
            htmlItem.style.display = '';
          } else {
            htmlItem.style.display = 'none';
          }
        });
      });
    });
  }

  // ============================================================================
  // TOAST NOTIFICATIONS
  // ============================================================================

  (window as any).showToast = function(message: string, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 animate-slideUp ${
      type === 'success' ? 'bg-green-500 text-white' :
      type === 'error' ? 'bg-red-500 text-white' :
      type === 'warning' ? 'bg-yellow-500 text-white' :
      'bg-blue-500 text-white'
    }`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(20px)';
      toast.style.transition = 'all 0.3s';
      setTimeout(() => {
        document.body.removeChild(toast);
      }, 300);
    }, duration);
  };

  // ============================================================================
  // AJAX HELPERS
  // ============================================================================

  /**
   * Perform AJAX request
   */
  (window as any).ajax = function(url: string, options: any = {}) {
    const defaults = {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    };

    const config = { ...defaults, ...options };

    if (config.body && typeof config.body === 'object') {
      config.body = JSON.stringify(config.body);
    }

    return fetch(url, config)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .catch(error => {
        console.error('AJAX Error:', error);
        throw error;
      });
  };

  // ============================================================================
  // SHOPPING CART
  // ============================================================================

  function initCart() {
    // Add to cart
    const cartButtons = document.querySelectorAll('[data-add-to-cart]');

    cartButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.getAttribute('data-add-to-cart');
        const btn = button as HTMLButtonElement;

        // Add loading state
        const originalText = btn.textContent || '';
        btn.disabled = true;
        btn.textContent = 'Adding...';

        // AJAX request to add to cart
        (window as any).ajax('/cart/add', {
          method: 'POST',
          body: { product_id: productId, quantity: 1 }
        })
          .then((data: any) => {
            (window as any).showToast('Product added to cart!', 'success');
            updateCartCount(data.cart_count);
            btn.textContent = 'Added!';
            setTimeout(() => {
              btn.disabled = false;
              btn.textContent = originalText;
            }, 1000);
          })
          .catch((error: any) => {
            (window as any).showToast('Failed to add product to cart', 'error');
            btn.disabled = false;
            btn.textContent = originalText;
          });
      });
    });

    // Update quantity
    const quantityButtons = document.querySelectorAll('[data-quantity-update]');
    quantityButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const action = button.getAttribute('data-quantity-update');
        const cartItemId = button.getAttribute('data-cart-item');
        const quantityDisplay = button.parentElement!.querySelector('[data-quantity-display]')!;

        let currentQuantity = parseInt(quantityDisplay.textContent || '0');
        let newQuantity = action === 'increase' ? currentQuantity + 1 : currentQuantity - 1;

        if (newQuantity < 1) return;

        (window as any).ajax('/cart/update', {
          method: 'POST',
          body: { cart_item_id: cartItemId, quantity: newQuantity }
        })
          .then((data: any) => {
            quantityDisplay.textContent = newQuantity.toString();
            updateCartTotal(data.total);
            (window as any).showToast('Cart updated', 'success', 1500);
          })
          .catch((error: any) => {
            (window as any).showToast('Failed to update cart', 'error');
          });
      });
    });

    // Remove from cart
    const removeButtons = document.querySelectorAll('[data-remove-from-cart]');
    removeButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const cartItemId = button.getAttribute('data-remove-from-cart');

        if (confirm('Remove this item from cart?')) {
          (window as any).ajax('/cart/remove', {
            method: 'POST',
            body: { cart_item_id: cartItemId }
          })
            .then((data: any) => {
              const cartItem = button.closest('[data-cart-item-row]');
              if (cartItem) {
                cartItem.remove();
              }
              updateCartCount(data.cart_count);
              updateCartTotal(data.total);
              (window as any).showToast('Item removed from cart', 'success');
            })
            .catch((error: any) => {
              (window as any).showToast('Failed to remove item', 'error');
            });
        }
      });
    });
  }

  function updateCartCount(count: number) {
    const cartCountElements = document.querySelectorAll('[data-cart-count]');
    cartCountElements.forEach(element => {
      const htmlElement = element as HTMLElement;
      htmlElement.textContent = count.toString();
      if (count > 0) {
        htmlElement.style.display = '';
      } else {
        htmlElement.style.display = 'none';
      }
    });
  }

  function updateCartTotal(total: string) {
    const cartTotalElements = document.querySelectorAll('[data-cart-total]');
    cartTotalElements.forEach(element => {
      element.textContent = total;
    });
  }

  // ============================================================================
  // IMAGE PREVIEW
  // ============================================================================

  function initImagePreview() {
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');

    fileInputs.forEach(input => {
      input.addEventListener('change', (e) => {
        const inputElement = input as HTMLInputElement;
        const previewId = inputElement.getAttribute('data-preview');
        const preview = document.getElementById(previewId!) as HTMLImageElement;
        const file = inputElement.files?.[0];

        if (file && preview) {
          const reader = new FileReader();
          reader.onload = (event) => {
            preview.src = event.target!.result as string;
            preview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      });
    });
  }

  // ============================================================================
  // ACCORDION
  // ============================================================================

  function initAccordions() {
    const accordionButtons = document.querySelectorAll('[data-accordion-toggle]');

    accordionButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const contentId = button.getAttribute('data-accordion-toggle');
        const content = document.getElementById(contentId!);

        if (content) {
          const isOpen = !content.classList.contains('hidden');

          // Close all accordions in the same group
          const group = button.closest('[data-accordion-group]');
          if (group) {
            group.querySelectorAll('[data-accordion-content]').forEach(c => {
              c.classList.add('hidden');
            });
            group.querySelectorAll('[data-accordion-toggle]').forEach(b => {
              b.setAttribute('aria-expanded', 'false');
            });
          }

          // Toggle current accordion
          if (!isOpen) {
            content.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
          }
        }
      });
    });
  }

  // ============================================================================
  // COPY TO CLIPBOARD
  // ============================================================================

  function initCopyButtons() {
    const copyButtons = document.querySelectorAll('[data-copy]');

    copyButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const text = button.getAttribute('data-copy')!;

        navigator.clipboard.writeText(text).then(() => {
          (window as any).showToast('Copied to clipboard!', 'success', 1500);

          const originalText = button.textContent || '';
          button.textContent = 'Copied!';
          setTimeout(() => {
            button.textContent = originalText;
          }, 2000);
        }).catch((err: any) => {
          (window as any).showToast('Failed to copy', 'error');
        });
      });
    });
  }

  // ============================================================================
  // BACK TO TOP BUTTON
  // ============================================================================

  function initBackToTop() {
    const backToTopButton = document.querySelector('[data-back-to-top]') as HTMLElement;

    if (backToTopButton) {
      window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
          backToTopButton.style.display = 'block';
        } else {
          backToTopButton.style.display = 'none';
        }
      });

      backToTopButton.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  }

  // ============================================================================
  // CONFIRM DIALOGS
  // ============================================================================

  function initConfirmDialogs() {
    const confirmButtons = document.querySelectorAll('[data-confirm]');

    confirmButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        const message = button.getAttribute('data-confirm')!;
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
      const htmlAlert = alert as HTMLElement;
      const delay = parseInt(alert.getAttribute('data-auto-hide') || '5000');
      setTimeout(() => {
        htmlAlert.style.opacity = '0';
        htmlAlert.style.transition = 'opacity 0.3s';
        setTimeout(() => {
          alert.remove();
        }, 300);
      }, delay);
    });
  }

  // ============================================================================
  // CHARACTER COUNTER
  // ============================================================================

  function initCharacterCounters() {
    const textareas = document.querySelectorAll('[data-max-length]');

    textareas.forEach(textarea => {
      const textareaElement = textarea as HTMLTextAreaElement;
      const maxLength = parseInt(textarea.getAttribute('data-max-length')!);
      const counterId = textarea.getAttribute('data-counter');
      const counter = counterId ? document.getElementById(counterId) : null;

      if (counter) {
        const updateCounter = () => {
          const remaining = maxLength - textareaElement.value.length;
          counter.textContent = `${remaining} characters remaining`;

          if (remaining < 0) {
            counter.classList.add('text-destructive');
          } else {
            counter.classList.remove('text-destructive');
          }
        };

        textarea.addEventListener('input', updateCounter);
        updateCounter();
      }
    });
  }

  // ============================================================================
  // LAZY LOADING IMAGES
  // ============================================================================

  function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target as HTMLImageElement;
            img.src = img.getAttribute('data-src')!;
            img.removeAttribute('data-src');
            imageObserver.unobserve(img);
          }
        });
      });

      images.forEach(img => imageObserver.observe(img));
    } else {
      // Fallback for browsers without IntersectionObserver
      images.forEach(img => {
        const imgElement = img as HTMLImageElement;
        imgElement.src = img.getAttribute('data-src')!;
        img.removeAttribute('data-src');
      });
    }
  }

  // ============================================================================
  // SIDEBAR TOGGLE (for mobile)
  // ============================================================================

  function initSidebarToggle() {
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebar = document.querySelector('[data-sidebar]');
    const sidebarClose = document.querySelector('[data-sidebar-close]');

    if (sidebarToggle && sidebar) {
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.toggle('translate-x-0');
      });
    }

    if (sidebarClose && sidebar) {
      sidebarClose.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
      });
    }
  }

  // ============================================================================
  // RATING SYSTEM
  // ============================================================================

  function initRatingSystem() {
    const ratingContainers = document.querySelectorAll('[data-rating]');

    ratingContainers.forEach(container => {
      const stars = container.querySelectorAll('[data-star]');
      const input = container.querySelector('[data-rating-input]') as HTMLInputElement;

      stars.forEach((star, index) => {
        star.addEventListener('click', () => {
          const rating = index + 1;
          if (input) {
            input.value = rating.toString();
          }

          // Update star display
          stars.forEach((s, i) => {
            if (i < rating) {
              s.classList.add('text-yellow-400');
              s.classList.remove('text-gray-300');
            } else {
              s.classList.add('text-gray-300');
              s.classList.remove('text-yellow-400');
            }
          });
        });

        // Hover effect
        star.addEventListener('mouseenter', () => {
          stars.forEach((s, i) => {
            if (i <= index) {
              s.classList.add('text-yellow-300');
            } else {
              s.classList.remove('text-yellow-300');
            }
          });
        });
      });

      container.addEventListener('mouseleave', () => {
        stars.forEach(s => {
          s.classList.remove('text-yellow-300');
        });
      });
    });
  }

  // ============================================================================
  // DYNAMIC FORM FIELDS
  // ============================================================================

  function initDynamicFields() {
    const addButtons = document.querySelectorAll('[data-add-field]');

    addButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const templateId = button.getAttribute('data-add-field');
        const template = document.getElementById(templateId!) as HTMLTemplateElement;
        const container = button.closest('[data-field-container]');

        if (template && container) {
          const clone = template.content.cloneNode(true);
          container.appendChild(clone);
        }
      });
    });

    // Remove field functionality
    document.addEventListener('click', (e) => {
      const target = e.target as HTMLElement;
      if (target.matches('[data-remove-field]')) {
        e.preventDefault();
        const field = target.closest('[data-field-item]');
        if (field) {
          field.remove();
        }
      }
    });
  }

  // ============================================================================
  // CHART INITIALIZATION (if using Chart.js)
  // ============================================================================

  function initCharts() {
    // This would integrate with Chart.js or other charting libraries
    // Example placeholder for sales charts
    const chartElements = document.querySelectorAll('[data-chart]');

    chartElements.forEach(element => {
      const chartType = element.getAttribute('data-chart');
      const chartData = element.getAttribute('data-chart-data');

      // Initialize charts based on type
      // This is a placeholder - implement with actual charting library
      console.log(`Initialize ${chartType} chart with data:`, chartData);
    });
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
    initCopyButtons();
    initBackToTop();
    initConfirmDialogs();
    initAutoHideAlerts();
    initCharacterCounters();
    initLazyLoading();
    initSidebarToggle();
    initRatingSystem();
    initDynamicFields();
    initCharts();

    console.log('e-TEFA Kompeni app initialized successfully');
  });

  // ============================================================================
  // EXPORTS FOR GLOBAL USE
  // ============================================================================

  (window as any).eTEFA = {
    show,
    hide,
    toggle,
    showToast: (window as any).showToast,
    ajax: (window as any).ajax,
    debounce,
    on
  };

})();
