<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PharmaGestion+') — Pharmacie en ligne</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>
        :root {
            --vert:  #2E7D32;
            --bleu:  #1976D2;
            --rouge: #D32F2F;
        }
        body { background: #f8f9fa; }

        /* Navbar */
        .navbar-brand span { color: var(--vert); font-weight: 700; }
        .nav-link:hover    { color: var(--vert) !important; }

        /* Bouton panier fixe */
        #cartBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--vert);
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            font-size: 1.4rem;
            color: white;
            transition: all 0.3s;
        }
        #cartBtn:hover { transform: scale(1.1); background: #1B5E20; }
        #cartCount {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--rouge);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Cards médicaments */
        .medicine-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .medicine-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .medicine-card img {
            height: 180px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }
        .medicine-card .no-image {
            height: 180px;
            background: linear-gradient(135deg, #e8f5e9, #e3f2fd);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px 12px 0 0;
            font-size: 3rem;
            color: #90A4AE;
        }

        /* Footer */
        footer {
            background: #1B2631;
            color: rgba(255,255,255,0.7);
        }
        footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        footer a:hover { color: white; }
    </style>

    @yield('styles')
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="bi bi-capsule text-success fs-4"></i>
            <span>PharmaGestion+</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'text-success fw-semibold' : 'text-dark' }}"
                       href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalogue*') ? 'text-success fw-semibold' : 'text-dark' }}"
                       href="{{ route('catalogue') }}">
                        <i class="bi bi-grid me-1"></i>Catalogue
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a href="{{ route('catalogue') }}"
                       class="btn btn-success btn-sm px-3">
                        <i class="bi bi-bag me-1"></i>Commander
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

{{-- ── Contenu principal ── --}}
<main>
    {{-- Messages flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-0 rounded-0"
             role="alert">
            <div class="container">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-0 rounded-0"
             role="alert">
            <div class="container">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

{{-- ── Bouton panier flottant ── --}}
<button id="cartBtn" onclick="toggleCart()" title="Mon panier">
    <i class="bi bi-cart"></i>
    <span id="cartCount" style="display:none;">0</span>
</button>

{{-- ── Mini panier offcanvas ── --}}
<div class="offcanvas offcanvas-end" id="cartOffcanvas" tabindex="-1">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">
            <i class="bi bi-cart me-2 text-success"></i>Mon panier
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">

        <div id="offcanvasCartItems" class="flex-fill">
            <p class="text-muted text-center mt-4" id="emptyCartMsg">
                <i class="bi bi-cart-x" style="font-size:2.5rem;"></i>
                <br>Votre panier est vide
            </p>
        </div>

        <div id="cartFooter" style="display:none;">
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                <span>Total :</span>
                <span class="text-success" id="cartTotal">0 F</span>
            </div>
            <a href="{{ route('catalogue') }}#order-form"
               class="btn btn-success w-100"
               onclick="saveCartAndGo()">
                <i class="bi bi-check-circle me-2"></i>Passer la commande
            </a>
        </div>

    </div>
</div>

{{-- ── Footer ── --}}
<footer class="mt-5 py-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h6 class="text-white fw-bold mb-3">
                    <i class="bi bi-capsule me-2"></i>PharmaGestion+
                </h6>
                <p class="small mb-0">
                    Votre pharmacie de confiance. Médicaments de qualité,
                    livraison rapide.
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-bold mb-3">Navigation</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('catalogue') }}">Catalogue</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-bold mb-3">Contact</h6>
                <p class="small mb-1">
                    <i class="bi bi-whatsapp me-2 text-success"></i>
                    +221 77 000 00 00
                </p>
                <p class="small mb-0">
                    <i class="bi bi-geo-alt me-2"></i>
                    Dakar, Sénégal
                </p>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center small mb-0">
            &copy; {{ date('Y') }} PharmaGestion+ — Tous droits réservés
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    {{-- Panier stocké dans localStorage du navigateur --}}
    let cart = JSON.parse(localStorage.getItem('pharma_cart') || '[]');

    function saveCart() {
        localStorage.setItem('pharma_cart', JSON.stringify(cart));
        updateCartUI();
    }

    function addToCart(id, name, price) {
        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        saveCart();

        // Feedback visuel
        const btn = document.getElementById('btn_' + id);
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check"></i> Ajouté';
            btn.classList.replace('btn-success', 'btn-outline-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-cart-plus"></i> Ajouter';
                btn.classList.replace('btn-outline-success', 'btn-success');
            }, 1500);
        }
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        saveCart();
        renderOffcanvasCart();
    }

    function updateCartUI() {
        const count = cart.reduce((sum, i) => sum + i.quantity, 0);
        const badge = document.getElementById('cartCount');
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
        renderOffcanvasCart();
    }

    function renderOffcanvasCart() {
        const container  = document.getElementById('offcanvasCartItems');
        const emptyMsg   = document.getElementById('emptyCartMsg');
        const cartFooter = document.getElementById('cartFooter');
        const totalEl    = document.getElementById('cartTotal');

        if (!container) return;

        if (cart.length === 0) {
            container.innerHTML = `
                <p class="text-muted text-center mt-4" id="emptyCartMsg">
                    <i class="bi bi-cart-x" style="font-size:2.5rem;"></i>
                    <br>Votre panier est vide
                </p>`;
            if (cartFooter) cartFooter.style.display = 'none';
            return;
        }

        let html  = '';
        let total = 0;

        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            html += `
                <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded border">
                    <div class="flex-fill">
                        <div class="fw-semibold small">${item.name}</div>
                        <div class="text-muted" style="font-size:0.75rem;">
                            ${item.price.toLocaleString()} F × ${item.quantity}
                        </div>
                    </div>
                    <span class="fw-bold small text-success">
                        ${subtotal.toLocaleString()} F
                    </span>
                    <button onclick="removeFromCart(${item.id})"
                            class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x"></i>
                    </button>
                </div>`;
        });

        container.innerHTML  = html;
        if (cartFooter) cartFooter.style.display = 'block';
        if (totalEl)    totalEl.textContent = total.toLocaleString() + ' F';
    }

    function toggleCart() {
        const offcanvas = new bootstrap.Offcanvas(
            document.getElementById('cartOffcanvas')
        );
        offcanvas.show();
        renderOffcanvasCart();
    }

    function saveCartAndGo() {
        saveCart();
    }

    // Initialiser au chargement
    updateCartUI();
</script>

@yield('scripts')

</body>
</html>