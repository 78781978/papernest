(function () {
  'use strict';
  if (typeof papernestShop === 'undefined') return;

  /* ---------------------------------------------------------- Add to cart --- */
  document.querySelectorAll('[data-add-to-cart]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var productId = btn.getAttribute('data-product-id');
      var pdInfo = btn.closest('.pd-info');
      var activeChip = pdInfo ? pdInfo.querySelector('.variant-chip.active') : null;
      var variantIndex = activeChip ? activeChip.getAttribute('data-variant-index') : '0';
      var qtyInput = btn.closest('.qty-row') ? btn.closest('.qty-row').querySelector('.qty-input input') : null;
      var qty = qtyInput ? parseInt(qtyInput.value || '1', 10) : 1;
      var feedback = document.querySelector('.papernest-cart-feedback');

      btn.disabled = true;

      var body = new URLSearchParams({
        action: 'papernest_cart_add',
        nonce: papernestShop.nonce,
        product_id: productId,
        variant: variantIndex || '0',
        qty: qty
      });

      fetch(papernestShop.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: body })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          btn.disabled = false;
          if (res.success) {
            document.querySelectorAll('.cart-count').forEach(function (el) { el.textContent = res.data.count; });
            if (feedback) feedback.textContent = papernestShop.i18n.added;
          } else if (feedback) {
            feedback.textContent = (res.data && res.data.message) ? res.data.message : papernestShop.i18n.error;
          }
        })
        .catch(function () {
          btn.disabled = false;
          if (feedback) feedback.textContent = papernestShop.i18n.error;
        });
    });
  });

  /* --------------------------------------------------------- Checkout form --- */
  var shippingGroup = document.querySelector('[data-role="shipping-method"]');
  var paymentGroup = document.querySelector('[data-role="payment-method"]');
  var shippingInput = document.getElementById('shipping_method');
  var paymentInput = document.getElementById('payment_method');

  function updateSummary() {
    var method = shippingInput ? shippingInput.value : 'paczkomat';
    var costEl = document.querySelector('.papernest-shipping-cost');
    var totalEl = document.querySelector('.papernest-order-total');
    if (costEl) costEl.textContent = costEl.getAttribute('data-' + method) || costEl.textContent;
    if (totalEl) {
      var itemsTotal = parseFloat(totalEl.getAttribute('data-items-total') || '0');
      var shipCost = parseFloat(totalEl.getAttribute('data-' + method) || '0');
      var total = (itemsTotal + shipCost).toFixed(2).replace('.', ',');
      totalEl.textContent = total + ' zł';
    }
  }

  function toggleAddressFields() {
    var method = shippingInput ? shippingInput.value : 'paczkomat';
    document.querySelectorAll('[data-show-when]').forEach(function (el) {
      el.hidden = el.getAttribute('data-show-when') !== method;
    });
  }

  if (shippingGroup && shippingInput) {
    shippingGroup.addEventListener('click', function (e) {
      var chip = e.target.closest('.variant-chip');
      if (!chip) return;
      shippingInput.value = chip.getAttribute('data-value');
      toggleAddressFields();
      updateSummary();

      // "Za pobraniem" only makes sense with a courier, not a locker.
      if (paymentGroup) {
        var codChip = paymentGroup.querySelector('[data-requires="kurier"]');
        if (codChip) {
          var allowed = shippingInput.value === 'kurier';
          codChip.disabled = !allowed;
          if (!allowed && codChip.classList.contains('active')) {
            codChip.classList.remove('active');
            paymentGroup.querySelector('.variant-chip:not([data-requires])').classList.add('active');
            paymentInput.value = 'przelew';
          }
        }
      }
    });
    toggleAddressFields();
  }

  if (paymentGroup && paymentInput) {
    paymentGroup.addEventListener('click', function (e) {
      var chip = e.target.closest('.variant-chip');
      if (!chip || chip.disabled) return;
      paymentInput.value = chip.getAttribute('data-value');
    });
  }
})();
