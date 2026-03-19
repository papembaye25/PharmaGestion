@extends('layouts.public')

@section('title', 'Catalogue')

@section('content')

{{-- ── En-tête page ── --}}
<section style="background: linear-gradient(135deg, #1B5E20, #1976D2);
                padding: 40px 0;">
    <div class="container text-white text-center">
        <h2 class="fw-bold mb-2">
            <i class="bi bi-grid me-2"></i>Notre Catalogue
        </h2>
        <p class="opacity-75 mb-0">
            Trouvez vos médicaments et ajoutez-les au panier
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">

            {{-- ── Colonne gauche : filtres ── --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold border-bottom">
                        <i class="bi bi-funnel me-2 text-success"></i>Filtres
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('catalogue') }}">

                            {{-- Recherche --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Rechercher
                                </label>
                                <input type="text" name="search"
                                       class="form-control form-control-sm"
                                       placeholder="Nom du médicament..."
                                       value="{{ request('search') }}">
                            </div>

                            {{-- Catégories --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Catégorie
                                </label>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('catalogue', request()->except('category_id')) }}"
                                       class="btn btn-sm text-start
                                       {{ !request('category_id') ? 'btn-success' : 'btn-outline-secondary' }}">
                                        Toutes les catégories
                                    </a>
                                    @foreach($categories as $category)
                                        <a href="{{ route('catalogue', array_merge(request()->all(), ['category_id' => $category->id])) }}"
                                           class="btn btn-sm text-start
                                           {{ request('category_id') == $category->id ? 'btn-success' : 'btn-outline-secondary' }}">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-search me-1"></i>Rechercher
                            </button>

                            @if(request('search') || request('category_id'))
                                <a href="{{ route('catalogue') }}"
                                   class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                    <i class="bi bi-x me-1"></i>Réinitialiser
                                </a>
                            @endif

                        </form>
                    </div>
                </div>

                {{-- ── Formulaire commande ── --}}
                <div class="card border-0 shadow-sm mt-4" id="order-form">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-bag-check me-2"></i>Passer la commande
                    </div>
                    <div class="card-body">

                        {{-- Résumé panier --}}
                        <div id="orderCartSummary" class="mb-3">
                            <p class="text-muted text-center small">
                                <i class="bi bi-cart-x"></i><br>
                                Panier vide
                            </p>
                        </div>

                        <hr>

                        <form method="POST" action="{{ route('public.order.store') }}"
                              id="orderForm">
                            @csrf

                            {{-- Inputs cachés panier --}}
                            <div id="orderFormInputs"></div>

                            <div class="mb-2">
                                <label class="form-label small fw-semibold">
                                    Nom complet *
                                </label>
                                <input type="text" name="client_name"
                                       class="form-control form-control-sm
                                       @error('client_name') is-invalid @enderror"
                                       value="{{ old('client_name') }}"
                                       placeholder="Votre nom">
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label small fw-semibold">
                                    Téléphone *
                                </label>
                                <input type="text" name="client_phone"
                                       class="form-control form-control-sm
                                       @error('client_phone') is-invalid @enderror"
                                       value="{{ old('client_phone') }}"
                                       placeholder="77 000 00 00">
                                @error('client_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label small fw-semibold">
                                    Adresse *
                                </label>
                                <textarea name="client_address" rows="2"
                                          class="form-control form-control-sm
                                          @error('client_address') is-invalid @enderror"
                                          placeholder="Votre adresse de livraison">{{ old('client_address') }}</textarea>
                                @error('client_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Paiement *
                                </label>
                                <select name="payment_method"
                                        class="form-select form-select-sm"
                                        id="paymentMethod"
                                        onchange="updateWhatsAppBtn()">
                                    <option value="livraison">
                                        💵 Paiement à la livraison
                                    </option>
                                    <option value="whatsapp">
                                        💬 WhatsApp (Wave / Orange Money)
                                    </option>
                                </select>
                            </div>

                            {{-- Bouton commander normal --}}
                            <button type="submit"
                                    id="submitOrderBtn"
                                    class="btn btn-success w-100 mb-2"
                                    disabled>
                                <i class="bi bi-check-circle me-2"></i>
                                Confirmer la commande
                            </button>

                            {{-- Bouton WhatsApp (visible si paiement WhatsApp) --}}
                            <a href="#"
                               id="whatsappBtn"
                               target="_blank"
                               class="btn btn-outline-success w-100 d-none"
                               onclick="submitViaWhatsApp(event)">
                                <i class="bi bi-whatsapp me-2"></i>
                                Commander via WhatsApp
                            </a>

                        </form>
                    </div>
                </div>

            </div>

            {{-- ── Colonne droite : liste médicaments ── --}}
            <div class="col-md-9">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">
                        {{ $medicines->total() }} médicament(s) disponible(s)
                    </p>
                </div>

                @if($medicines->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-search" style="font-size:3rem;"></i>
                        <p class="mt-3">Aucun médicament trouvé.</p>
                        <a href="{{ route('catalogue') }}"
                           class="btn btn-outline-success btn-sm">
                            Voir tout le catalogue
                        </a>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($medicines as $medicine)
                            <div class="col-md-4 col-6">
                                <div class="card medicine-card h-100">

                                    @if($medicine->image)
                                        <img src="{{ Storage::url($medicine->image) }}"
                                             alt="{{ $medicine->name }}">
                                    @else
                                        <div class="no-image">
                                            <i class="bi bi-capsule"></i>
                                        </div>
                                    @endif

                                    <div class="card-body p-3">
                                        <span class="badge bg-success bg-opacity-10
                                                     text-success rounded-pill
                                                     small mb-1">
                                            {{ $medicine->category->name }}
                                        </span>
                                        <h6 class="fw-bold mb-1 mt-1">
                                            {{ $medicine->name }}
                                        </h6>
                                        @if($medicine->description)
                                            <p class="text-muted small mb-2"
                                               style="font-size:0.75rem;">
                                                {{ Str::limit($medicine->description, 60) }}
                                            </p>
                                        @endif
                                        <div class="d-flex justify-content-between
                                                    align-items-center">
                                            <span class="text-success fw-bold">
                                                {{ number_format($medicine->price, 0, ',', ' ') }} F
                                            </span>
                                            <small class="text-muted">
                                                Stock: {{ $medicine->quantity }}
                                            </small>
                                        </div>
                                        <button id="btn_{{ $medicine->id }}"
                                                class="btn btn-success btn-sm w-100 mt-2"
                                                onclick="addToCart(
                                                    {{ $medicine->id }},
                                                    '{{ addslashes($medicine->name) }}',
                                                    {{ $medicine->price }}
                                                )">
                                            <i class="bi bi-cart-plus"></i> Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($medicines->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medicines->appends(request()->query())->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Numéro WhatsApp de la pharmacie
    const PHARMACY_WHATSAPP = '221770000000';

    // Mettre à jour le résumé panier dans le formulaire
    function updateOrderForm() {
        const summary    = document.getElementById('orderCartSummary');
        const formInputs = document.getElementById('orderFormInputs');
        const submitBtn  = document.getElementById('submitOrderBtn');

        if (!summary) return;

        if (cart.length === 0) {
            summary.innerHTML = `
                <p class="text-muted text-center small">
                    <i class="bi bi-cart-x"></i><br>Panier vide
                </p>`;
            if (formInputs) formInputs.innerHTML = '';
            if (submitBtn)  submitBtn.disabled = true;
            return;
        }

        let html       = '';
        let inputsHTML = '';
        let total      = 0;

        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            html += `
                <div class="d-flex justify-content-between small mb-1">
                    <span>${item.name} × ${item.quantity}</span>
                    <span class="fw-bold">${subtotal.toLocaleString()} F</span>
                </div>`;
            inputsHTML += `
                <input type="hidden" name="medicines[]" value="${item.id}">
                <input type="hidden" name="quantities[]" value="${item.quantity}">`;
        });

        html += `
            <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                <span>Total</span>
                <span class="text-success">${total.toLocaleString()} F</span>
            </div>`;

        summary.innerHTML    = html;
        if (formInputs) formInputs.innerHTML = inputsHTML;
        if (submitBtn)  submitBtn.disabled = false;

        updateWhatsAppBtn();
    }

    // Afficher/masquer le bouton WhatsApp
    function updateWhatsAppBtn() {
        const method      = document.getElementById('paymentMethod');
        const submitBtn   = document.getElementById('submitOrderBtn');
        const whatsappBtn = document.getElementById('whatsappBtn');

        if (!method) return;

        if (method.value === 'whatsapp') {
            if (submitBtn)   submitBtn.classList.add('d-none');
            if (whatsappBtn) whatsappBtn.classList.remove('d-none');
        } else {
            if (submitBtn)   submitBtn.classList.remove('d-none');
            if (whatsappBtn) whatsappBtn.classList.add('d-none');
        }
    }

    // Soumettre via WhatsApp
    function submitViaWhatsApp(e) {
        e.preventDefault();

        const name    = document.querySelector('[name="client_name"]').value;
        const phone   = document.querySelector('[name="client_phone"]').value;
        const address = document.querySelector('[name="client_address"]').value;

        if (!name || !phone || !address) {
            alert('Veuillez remplir vos informations de contact.');
            return;
        }

        if (cart.length === 0) {
            alert('Votre panier est vide.');
            return;
        }

        // Construire le message WhatsApp
        let message = `*Nouvelle commande PharmaGestion+*\n\n`;
        message += `*Client :* ${name}\n`;
        message += `*Téléphone :* ${phone}\n`;
        message += `*Adresse :* ${address}\n\n`;
        message += `*Articles commandés :*\n`;

        let total = 0;
        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            message += `• ${item.name} × ${item.quantity} = ${subtotal.toLocaleString()} F\n`;
        });

        message += `\n*Total : ${total.toLocaleString()} F*`;
        message += `\n\n_Paiement : Wave / Orange Money_`;

        // Ouvrir WhatsApp avec le message pré-rempli
        const url = `https://wa.me/${PHARMACY_WHATSAPP}?text=${encodeURIComponent(message)}`;
        window.open(url, '_blank');

        // Aussi enregistrer en BDD
        document.getElementById('orderForm').submit();
    }

    // Écouter les changements du panier
    const originalSaveCart = saveCart;
    function saveCart() {
        localStorage.setItem('pharma_cart', JSON.stringify(cart));
        updateCartUI();
        updateOrderForm();
    }

    // Initialiser
    updateOrderForm();
</script>
@endsection