{{-- Cette page utilise le layout admin --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- ── Cartes statistiques ── --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 small">Médicaments</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_medicines'] }}</h3>
                    </div>
                    <i class="bi bi-capsule" style="font-size:2.5rem; opacity:0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 small">Catégories</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_categories'] }}</h3>
                    </div>
                    <i class="bi bi-tags" style="font-size:2.5rem; opacity:0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 small">Commandes en attente</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['pending_orders'] }}</h3>
                    </div>
                    <i class="bi bi-bag" style="font-size:2.5rem; opacity:0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 small">Ventes aujourd'hui</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['today_sales'] }}</h3>
                    </div>
                    <i class="bi bi-receipt" style="font-size:2.5rem; opacity:0.5;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Alertes ── --}}
<div class="row g-3">

    {{-- Stock faible --}}
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white fw-bold">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Stock faible
                <span class="badge bg-white text-warning ms-2">
                    {{ $low_stock->count() }}
                </span>
            </div>
            <div class="card-body p-0">
                @if($low_stock->isEmpty())
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Aucun stock faible
                    </p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($low_stock as $medicine)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $medicine->name }}</span>
                                <span class="badge bg-warning text-dark">
                                    {{ $medicine->quantity }} restants
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Médicaments expirés --}}
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-calendar-x me-2"></i>
                Médicaments expirés
                <span class="badge bg-white text-danger ms-2">
                    {{ $expired->count() }}
                </span>
            </div>
            <div class="card-body p-0">
                @if($expired->isEmpty())
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Aucun médicament expiré
                    </p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($expired as $medicine)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $medicine->name }}</span>
                                <span class="badge bg-danger">
                                    {{ $medicine->expiry_date->format('d/m/Y') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection