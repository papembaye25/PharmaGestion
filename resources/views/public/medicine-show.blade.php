@extends('layouts.public')

@section('title', $medicine->name)

@section('content')

<section class="py-5">
    <div class="container">

        <div class="row g-4">

            {{-- Image --}}
            <div class="col-md-4">
                @if($medicine->image)
                    <img src="{{ Storage::url($medicine->image) }}"
                         alt="{{ $medicine->name }}"
                         class="rounded shadow-sm w-100"
                         style="max-height:320px; object-fit:cover;">
                @else
                    <div class="rounded bg-light d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="height:320px; font-size:5rem; color:#90A4AE;">
                        <i class="bi bi-capsule"></i>
                    </div>
                @endif
            </div>

            {{-- Infos --}}
            <div class="col-md-8">

                <span class="badge bg-success bg-opacity-10
                             text-success rounded-pill mb-2">
                    {{ $medicine->category->name }}
                </span>

                <h2 class="fw-bold mb-3">{{ $medicine->name }}</h2>

                <h3 class="text-success fw-bold mb-3">
                    {{ number_format($medicine->price, 0, ',', ' ') }} F
                </h3>

                @if($medicine->description)
                    <p class="text-muted mb-4">{{ $medicine->description }}</p>
                @endif

                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="text-muted small">
                        <i class="bi bi-box me-1"></i>
                        Stock disponible :
                        <strong>{{ $medicine->quantity }} unités</strong>
                    </span>
                </div>

                <button class="btn btn-success btn-lg px-5"
                        id="btn_{{ $medicine->id }}"
                        onclick="addToCart(
                            {{ $medicine->id }},
                            '{{ addslashes($medicine->name) }}',
                            {{ $medicine->price }}
                        )">
                    <i class="bi bi-cart-plus me-2"></i>Ajouter au panier
                </button>

                <a href="{{ route('catalogue') }}"
                   class="btn btn-outline-secondary btn-lg ms-2">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>

            </div>
        </div>
    </div>
</section>

@endsection