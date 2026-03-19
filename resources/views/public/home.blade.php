@extends('layouts.public')

@section('title', 'Accueil')

@section('content')

{{-- ── Bannière hero ── --}}
<section style="background: linear-gradient(135deg, #1B5E20 0%, #1976D2 100%);
                padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 text-white">
                <span class="badge bg-white text-success mb-3 px-3 py-2">
                    <i class="bi bi-capsule me-1"></i>Pharmacie en ligne
                </span>
                <h1 class="display-4 fw-bold mb-3">
                    Votre santé,<br>notre priorité
                </h1>
                <p class="lead mb-4 opacity-75">
                    Commandez vos médicaments en ligne et recevez-les
                    rapidement. Paiement à la livraison ou via Wave/Orange Money.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('catalogue') }}"
                       class="btn btn-white btn-lg px-4"
                       style="background:white; color:#2E7D32; font-weight:600;">
                        <i class="bi bi-grid me-2"></i>Voir le catalogue
                    </a>
                    <a href="{{ route('catalogue') }}"
                       class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-whatsapp me-2"></i>Commander via WhatsApp
                    </a>
                </div>
            </div>
            <div class="col-md-5 text-center d-none d-md-block">
                <i class="bi bi-capsule"
                   style="font-size:12rem; color:rgba(255,255,255,0.15);"></i>
            </div>
        </div>
    </div>
</section>

{{-- ── Avantages ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-shield-check text-success"
                       style="font-size:2.5rem;"></i>
                    <h6 class="fw-bold mt-3">Médicaments certifiés</h6>
                    <p class="text-muted small mb-0">
                        Tous nos produits sont vérifiés et certifiés
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-truck text-primary"
                       style="font-size:2.5rem;"></i>
                    <h6 class="fw-bold mt-3">Livraison rapide</h6>
                    <p class="text-muted small mb-0">
                        Livraison à domicile dans les meilleurs délais
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-whatsapp text-success"
                       style="font-size:2.5rem;"></i>
                    <h6 class="fw-bold mt-3">Paiement flexible</h6>
                    <p class="text-muted small mb-0">
                        Wave, Orange Money ou paiement à la livraison
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-headset text-warning"
                       style="font-size:2.5rem;"></i>
                    <h6 class="fw-bold mt-3">Support disponible</h6>
                    <p class="text-muted small mb-0">
                        Notre équipe répond à toutes vos questions
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Catégories ── --}}
@if($categories->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">
            <i class="bi bi-tags me-2 text-success"></i>
            Nos catégories
        </h2>
        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-md-3 col-6">
                    <a href="{{ route('catalogue', ['category_id' => $category->id]) }}"
                       class="card border-0 shadow-sm text-decoration-none
                              text-dark medicine-card p-3 d-block text-center">
                        <i class="bi bi-capsule text-success"
                           style="font-size:2rem;"></i>
                        <p class="fw-semibold mb-0 mt-2">{{ $category->name }}</p>
                        <small class="text-muted">
                            {{ $category->medicines_count }} produits
                        </small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Médicaments vedettes ── --}}
@if($medicines->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-star me-2 text-warning"></i>
                Produits disponibles
            </h2>
            <a href="{{ route('catalogue') }}"
               class="btn btn-outline-success btn-sm">
                Voir tout <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($medicines as $medicine)
                <div class="col-md-4 col-lg-2 col-6">
                    <div class="card medicine-card h-100">

                        {{-- Image --}}
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
                                         text-success rounded-pill small mb-1">
                                {{ $medicine->category->name }}
                            </span>
                            <h6 class="fw-bold mb-1 mt-1">
                                {{ $medicine->name }}
                            </h6>
                            <p class="text-success fw-bold mb-2">
                                {{ number_format($medicine->price, 0, ',', ' ') }} F
                            </p>
                            <button id="btn_{{ $medicine->id }}"
                                    class="btn btn-success btn-sm w-100"
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
    </div>
</section>
@endif

@endsection