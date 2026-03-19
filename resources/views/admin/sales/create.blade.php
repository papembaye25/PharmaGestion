@extends('layouts.admin')

@section('title', 'Nouvelle Vente')
@section('page-title', 'Enregistrer une Vente')

@section('content')

<div class="row g-4">

    {{-- Colonne gauche : sélection médicaments --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-capsule me-2 text-success"></i>
                    Sélectionner les médicaments
                </h6>
            </div>
            <div class="card-body">

                {{-- Recherche médicament --}}
                <input type="text"
                       id="searchMedicine"
                       class="form-control mb-3"
                       placeholder="Rechercher un médicament...">

                {{-- Liste des médicaments --}}
                <div style="max-height: 450px; overflow-y: auto;">
                    <table class="table table-hover align-middle">
                        <thead style="background:#f8f9fa; position:sticky; top:0;">
                            <tr>
                                <th>Médicament</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Prix</th>
                                <th class="text-center">Ajouter</th>
                            </tr>
                        </thead>
                        <tbody id="medicinesList">
                            @foreach($medicines as $medicine)
                                <tr class="medicine-row">
                                    <td>
                                        <span class="fw-semibold medicine-name">
                                            {{ $medicine->name }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $medicine->category->name }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $medicine->is_low_stock ? 'warning text-dark' : 'success' }}">
                                            {{ $medicine->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($medicine->price, 0, ',', ' ') }} F
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success"
                                                onclick="addToCart(
                                                    {{ $medicine->id }},
                                                    '{{ addslashes($medicine->name) }}',
                                                    {{ $medicine->price }},
                                                    {{ $medicine->quantity }}
                                                )">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Colonne droite : panier --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-cart me-2 text-primary"></i>
                    Panier
                </h6>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('admin.sales.store') }}"
                      id="saleForm">
                    @csrf

                    {{-- Articles du panier --}}
                    <div id="cartItems">
                        <p class="text-muted text-center py-3" id="emptyCart">
                            <i class="bi bi-cart-x" style="font-size:2rem;"></i>
                            <br>Aucun article ajouté
                        </p>
                    </div>

                    {{-- Conteneur caché pour les inputs du formulaire --}}
                    <div id="formInputs"></div>

                    <hr>

                    {{-- Total --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold fs-5">Total :</span>
                        <span class="fw-bold fs-4 text-success" id="totalDisplay">
                            0 F
                        </span>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-3">
                        <label class="form-label small text-muted">
                            Notes (optionnel)
                        </label>
                        <textarea name="notes" rows="2"
                                  class="form-control form-control-sm"
                                  placeholder="Remarques sur la vente..."></textarea>
                    </div>

                    {{-- Bouton valider --}}
                    <button type="submit"
                            class="btn btn-success w-100"
                            id="submitBtn"
                            disabled>
                        <i class="bi bi-check-circle me-2"></i>
                        Valider la vente
                    </button>

                    <a href="{{ route('admin.sales.index') }}"
                       class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left me-2"></i>Annuler
                    </a>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Panier stocké en mémoire
    let cart = {};

    /**
     * Ajouter un médicament au panier
     */
    function addToCart(id, name, price, stock) {
        if (cart[id]) {
            // Déjà dans le panier → augmenter la quantité
            if (cart[id].quantity >= stock) {
                alert('Stock insuffisant pour ' + name);
                return;
            }
            cart[id].quantity++;
        } else {
            cart[id] = { id, name, price, stock, quantity: 1 };
        }
        renderCart();
    }

    /**
     * Changer la quantité d'un article
     */
    function updateQty(id, qty) {
        qty = parseInt(qty);
        if (qty < 1) {
            removeFromCart(id);
            return;
        }
        if (qty > cart[id].stock) {
            alert('Stock maximum : ' + cart[id].stock);
            document.getElementById('qty_' + id).value = cart[id].stock;
            return;
        }
        cart[id].quantity = qty;
        renderCart();
    }

    /**
     * Supprimer un article du panier
     */
    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    /**
     * Afficher le panier et mettre à jour le formulaire
     */
    function renderCart() {
        const cartDiv    = document.getElementById('cartItems');
        const formInputs = document.getElementById('formInputs');
        const emptyCart  = document.getElementById('emptyCart');
        const submitBtn  = document.getElementById('submitBtn');
        const totalDiv   = document.getElementById('totalDisplay');

        const items = Object.values(cart);

        if (items.length === 0) {
            cartDiv.innerHTML = `
                <p class="text-muted text-center py-3" id="emptyCart">
                    <i class="bi bi-cart-x" style="font-size:2rem;"></i>
                    <br>Aucun article ajouté
                </p>`;
            formInputs.innerHTML = '';
            totalDiv.textContent = '0 F';
            submitBtn.disabled = true;
            return;
        }

        // Construire l'affichage du panier
        let cartHTML   = '';
        let inputsHTML = '';
        let total      = 0;

        items.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            cartHTML += `
                <div class="d-flex align-items-center gap-2 mb-2 p-2
                            rounded border bg-light">
                    <div class="flex-fill">
                        <div class="fw-semibold small">${item.name}</div>
                        <div class="text-muted" style="font-size:0.75rem;">
                            ${item.price.toLocaleString()} F × ${item.quantity}
                            = <strong>${subtotal.toLocaleString()} F</strong>
                        </div>
                    </div>
                    <input type="number"
                           id="qty_${item.id}"
                           class="form-control form-control-sm"
                           style="width:65px;"
                           value="${item.quantity}"
                           min="1"
                           max="${item.stock}"
                           onchange="updateQty(${item.id}, this.value)">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="removeFromCart(${item.id})">
                        <i class="bi bi-x"></i>
                    </button>
                </div>`;

            // Inputs cachés pour le formulaire
            inputsHTML += `
                <input type="hidden"
                       name="medicines[]"
                       value="${item.id}">
                <input type="hidden"
                       name="quantities[]"
                       value="${item.quantity}">`;
        });

        cartDiv.innerHTML    = cartHTML;
        formInputs.innerHTML = inputsHTML;
        totalDiv.textContent = total.toLocaleString() + ' F';
        submitBtn.disabled   = false;
    }

    /**
     * Recherche dans la liste des médicaments
     */
    document.getElementById('searchMedicine').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        document.querySelectorAll('.medicine-row').forEach(row => {
            const name = row.querySelector('.medicine-name').textContent.toLowerCase();
            row.style.display = name.includes(search) ? '' : 'none';
        });
    });
</script>
@endsection