<!-- Add to Cart Modal (Bottom Sheet) -->
<div id="addToCartModal" class="fixed inset-0 z-50 hidden overflow-auto">
    <!-- Overlay -->
    <div id="modalOverlay" class="fixed inset-0 bg-black/50 transition-opacity duration-300"></div>

    <!-- Bottom Sheet Container -->
    <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl max-h-[90vh] overflow-y-auto"
        id="modalContent">
        <!-- Close Button -->
        <div class="sticky top-0 bg-white rounded-t-3xl flex justify-center pb-3 pt-2">
            <button id="modalClose" type="button" class="p-2 rounded-full hover:bg-secondary transition-colors">
                <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3">
                    </path>
                </svg>
            </button>
        </div>

        <div class="px-4 pb-8">
            <!-- Product Info -->
            <div class="mb-6">
                <!-- Product Image -->
                <img id="modalProductImage"
                    class="w-full h-64 object-cover rounded-xl mb-4 bg-accent">

                <!-- Product Details -->
                <h3 id="modalProductName" class="text-xl font-bold text-foreground mb-2"></h3>

                <!-- Category & Stock -->
                <div class="flex items-center gap-4 mb-4">
                    <span id="modalProductCategory"
                        class="text-sm px-3 py-1 bg-primary/10 text-primary rounded-full"></span>
                    <span id="modalProductStock" class="text-sm font-medium text-green-600"></span>
                </div>

                <!-- Price -->
                <p id="modalProductPrice" class="text-2xl font-bold text-primary mb-6"></p>

                <!-- Description -->
                <p id="modalProductDescription" class="text-sm text-muted-foreground"></p>
            </div>

            <!-- Quantity Selection -->
            <div class="mb-8">
                <label class="text-sm font-semibold text-foreground mb-3 block">Quantity</label>

                <div class="flex items-center gap-3 mb-4">
                    <!-- Decrement Button -->
                    <button id="qtyDecrement" type="button"
                        class="h-10 w-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-100 active:bg-gray-200 transition-colors font-semibold">
                        −
                    </button>

                    <!-- Quantity Input -->
                    <input id="qtyInput" type="number" value="1" min="0" max="999"
                        class="flex-1 h-10 rounded-lg border border-gray-300 px-3 text-center font-semibold focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">

                    <!-- Increment Button -->
                    <button id="qtyIncrement" type="button"
                        class="h-10 w-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-100 active:bg-gray-200 transition-colors font-semibold">
                        +
                    </button>
                </div>

                <!-- Stock Warning -->
                <div id="stockWarning" class="hidden text-sm text-destructive bg-destructive/10 border border-destructive/30 rounded-lg p-3">
                    <p id="stockWarningText"></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 sticky bottom-0 bg-white pt-4">
                <!-- Cancel Button -->
                <button id="modalCancel" type="button"
                    class="flex-1 rounded-lg border border-gray-300 px-6 py-3 font-semibold text-foreground hover:bg-gray-50 transition-colors">
                    Cancel
                </button>

                <!-- Add to Cart Button -->
                <button id="addToCartBtn" type="button" disabled
                    class="flex-1 rounded-lg bg-primary text-primary-foreground px-6 py-3 font-semibold hover:bg-primary/90 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all">
                    Add to Cart
                </button>

                <!-- Buy Now Button -->
                <button id="buyNowBtn" type="button" disabled
                    class="flex-1 rounded-lg border-2 border-primary text-primary px-6 py-3 font-semibold hover:bg-primary/5 disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed transition-all">
                    Buy Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addToCartModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('modalContent');
    const modalClose = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');

    const qtyInput = document.getElementById('qtyInput');
    const qtyIncrement = document.getElementById('qtyIncrement');
    const qtyDecrement = document.getElementById('qtyDecrement');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const buyNowBtn = document.getElementById('buyNowBtn');
    const stockWarning = document.getElementById('stockWarning');
    const stockWarningText = document.getElementById('stockWarningText');

    let currentProductId = null;
    let maxStock = 999;

    // ─── Modal State Management ───────────────────────────
    function openModal(productData) {
        currentProductId = productData.id;
        maxStock = productData.stock;

        // Update modal content
        document.getElementById('modalProductImage').src =
            productData.image || 'https://via.placeholder.com/400x300?text=No+Image';
        document.getElementById('modalProductName').textContent = productData.name;
        document.getElementById('modalProductCategory').textContent = productData.category;
        document.getElementById('modalProductStock').textContent = `${productData.stock} in stock`;
        document.getElementById('modalProductPrice').textContent =
            `Rp ${new Intl.NumberFormat('id-ID').format(productData.price)}`;
        document.getElementById('modalProductDescription').textContent = productData.description;

        // Reset quantity
        qtyInput.value = 1;
        qtyInput.max = maxStock;
        updateButtonStates();

        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalOverlay.classList.add('opacity-100');
            modalContent.style.transform = 'translateY(0)';
        }, 10);

        // Focus on quantity input
        qtyInput.focus();
    }

    function closeModal() {
        modalOverlay.classList.remove('opacity-100');
        modalContent.style.transform = 'translateY(100%)';

        setTimeout(() => {
            modal.classList.add('hidden');
            currentProductId = null;
            stockWarning.classList.add('hidden');
        }, 300);
    }

    // ─── Quantity Management ───────────────────────────────
    function updateButtonStates() {
        const qty = parseInt(qtyInput.value) || 0;
        const isValid = qty > 0 && qty <= maxStock;

        addToCartBtn.disabled = !isValid;
        buyNowBtn.disabled = !isValid;

        // Stock warning
        if (qty > maxStock && qty > 0) {
            stockWarning.classList.remove('hidden');
            stockWarningText.textContent = `Only ${maxStock} items available`;
        } else {
            stockWarning.classList.add('hidden');
        }
    }

    qtyInput.addEventListener('input', function () {
        let value = parseInt(this.value) || 0;

        if (value > maxStock) {
            this.value = maxStock;
        }
        if (value < 0) {
            this.value = 0;
        }

        updateButtonStates();
    });

    qtyIncrement.addEventListener('click', function () {
        let value = parseInt(qtyInput.value) || 0;
        if (value < maxStock) {
            qtyInput.value = value + 1;
            updateButtonStates();
        }
    });

    qtyDecrement.addEventListener('click', function () {
        let value = parseInt(qtyInput.value) || 0;
        if (value > 0) {
            qtyInput.value = value - 1;
            updateButtonStates();
        }
    });

    // ─── Modal Controls ────────────────────────────────────
    modalClose.addEventListener('click', closeModal);
    modalCancel.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    // Prevent closing when clicking on modal content
    modalContent.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    // ─── Form Submission ───────────────────────────────────
    addToCartBtn.addEventListener('click', async function () {
        if (!currentProductId) return;

        const qty = parseInt(qtyInput.value);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = 'Adding...';

            const response = await fetch('{{ route("checkout.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${currentProductId}&quantity=${qty}`
            });

            const data = await response.json();

            if (data.status === 'success') {
                closeModal();
                window.location.href = data.redirect_url || '{{ route("cart.index") }}';
            } else {
                showToast(data.message || 'Error adding to cart', 'error', 3000);
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            showToast('Error adding to cart', 'error', 3000);
        } finally {
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = 'Add to Cart';
        }
    });

    buyNowBtn.addEventListener('click', async function () {
        if (!currentProductId) return;

        const qty = parseInt(qtyInput.value);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            buyNowBtn.disabled = true;
            buyNowBtn.textContent = 'Processing...';

            const response = await fetch('{{ route("checkout.buy-now") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${currentProductId}&quantity=${qty}`
            });

            const data = await response.json();

            if (data.status === 'success') {
                closeModal();
                window.location.href = data.redirect_url || '{{ route("checkout") }}';
            } else {
                showToast(data.message || 'Error processing buy now', 'error', 3000);
            }
        } catch (error) {
            console.error('Buy now error:', error);
            showToast('Error processing buy now', 'error', 3000);
        } finally {
            buyNowBtn.disabled = false;
            buyNowBtn.textContent = 'Buy Now';
        }
    });

    // ─── Expose openModal globally ─────────────────────────
    window.openAddToCartModal = openModal;
});

// Toast notification utility
function showToast(message, type = 'info', duration = 3000) {
    const toastId = 'toast-' + Date.now();
    const toastColors = {
        success: 'bg-green-50 text-green-800 border-green-200',
        error: 'bg-red-50 text-red-800 border-red-200',
        info: 'bg-blue-50 text-blue-800 border-blue-200'
    };

    const toastHTML = `
        <div id="${toastId}" class="fixed bottom-4 right-4 ${toastColors[type] || toastColors.info} border rounded-lg px-4 py-3 shadow-lg z-40 animate-slide-in max-w-sm">
            ${message}
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', toastHTML);
    const toastEl = document.getElementById(toastId);

    setTimeout(() => {
        toastEl.classList.add('animate-slide-out');
        setTimeout(() => toastEl.remove(), 300);
    }, duration);
}
</script>

<style>
@keyframes slideDown {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(100%);
        opacity: 0;
    }
}

#addToCartModal:not(.hidden) {
    display: block;
}

#modalContent {
    transform: translateY(100%);
    transition: transform 0.3s ease-out;
}

#modalOverlay {
    opacity: 0;
    transition: opacity 0.3s ease-out;
}

.animate-slide-in {
    animation: slideDown 0.3s ease-out;
}

.animate-slide-out {
    animation: slideOut 0.3s ease-out;
}
</style>
