<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PharmaGestion+') — Admin</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>
        /* ── Variables couleurs ── */
        :root {
            --vert:   #2E7D32;
            --bleu:   #1976D2;
            --rouge:  #D32F2F;
            --sidebar-width: 260px;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #1B2631;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s;
            overflow-y: auto;      /* ← scroll si contenu trop long */
            height: 100vh;         /* ← hauteur fixe pour activer le scroll */
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            background: var(--vert);
            text-align: center;
        }
        .sidebar-brand h5 {
            color: white;
            margin: 0;
            font-weight: 700;
        }
        .sidebar-brand small {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
        }
        .nav-section {
            padding: 0.5rem 1rem;
            color: rgba(255,255,255,0.4);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 1rem;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            margin: 2px 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: var(--vert);
            color: white;
        }
        #sidebar .nav-link i {
            width: 20px;
            font-size: 1rem;
        }

        /* ── Contenu principal ── */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #F8F9FA;
        }

        /* ── Navbar top ── */
        #topbar {
            background: white;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        /* ── Badges alertes ── */
        .alert-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.65rem;
        }

        /* ── Page content ── */
        .page-content {
            padding: 1.5rem;
        }

        /* ── Cards stats ── */
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
    </style>

    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-brand">
        <i class="bi bi-capsule" style="font-size:2rem; color:white;"></i>
        <h5 class="mt-1">PharmaGestion+</h5>
        <small>Espace Administration</small>
    </div>

    {{-- Navigation --}}
    <nav class="mt-2">

        <div class="nav-section">Principal</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Pharmacie</div>

        <a href="{{ route('admin.medicines.index') }}"
           class="nav-link {{ request()->routeIs('admin.medicines.*') ? 'active' : '' }}">
            <i class="bi bi-capsule"></i> Médicaments
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Catégories
        </a>

        <a href="{{ route('admin.stock.index') }}"
           class="nav-link {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i> Stock
        </a>

        <div class="nav-section">Transactions</div>

        <a href="{{ route('admin.sales.index') }}"
           class="nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Ventes
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> Commandes
            {{-- Badge commandes en attente --}}
            @php
                $pending = \App\Models\Order::where('status', 'en_attente')->count();
            @endphp
            @if($pending > 0)
                <span class="badge bg-danger ms-auto">{{ $pending }}</span>
            @endif
        </a>

        <div class="nav-section">Compte</div>

        {{-- Bouton déconnexion --}}
        <form method="POST" action="{{ route('logout') }}" class="px-2 mt-2 mb-4">
            @csrf
            <button type="submit"
                    class="btn w-100 text-start d-flex align-items-center gap-2"
                    style="background: rgba(211,47,47,0.15);
                        color: #ff8a80;
                        border: 1px solid rgba(211,47,47,0.3);
                        border-radius: 8px;
                        padding: 0.6rem 1rem;
                        font-size: 0.9rem;
                        transition: all 0.2s;">
                <i class="bi bi-box-arrow-left"></i> Déconnexion
            </button>
       </form>

    </nav>
</div>

{{--CONTENU PRINCIPAL --}}
<div id="main-content">

    {{-- Navbar top --}}
    <div id="topbar">
        <div>
            {{-- Titre de la page courante --}}
            <h6 class="mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-3">
            {{-- Nom du pharmacien connecté --}}
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>
                {{ Auth::user()->name }}
            </span>
        </div>
    </div>

    {{-- Messages flash (succès / erreur) --}}
    <div class="page-content pb-0">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Contenu de la page --}}
    <div class="page-content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Scripts supplémentaires par page --}}
@yield('scripts')

</body>
</html>