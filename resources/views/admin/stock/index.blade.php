@extends('layouts.admin')

@section('title', 'Stock')
@section('page-title', 'Gestion du Stock')

@section('content')

{{-- ── Cartes résumé ── --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-boxes text-primary" style="font-size:2rem;"></i>
            <h4 class="fw-bold mt-2 mb-0">{{ $medicines->count() }}</h4>
            <small class="text-muted">Total médicaments</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-exclamation-triangle text-warning"
               style="font-size:2rem;"></i>
            <h4 class="fw-bold mt-2 mb-0 text-warning">
                {{ $low_stock->count() }}
            </h4>
            <small class="text-muted">Stock faible</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-calendar-x text-danger" style="font-size:2rem;"></i>
            <h4 class="fw-bold mt-2 mb-0 text-danger">
                {{ $expired->count() }}
            </h4>
            <small class="text-muted">Expirés</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-clock-history text-warning" style="font-size:2rem;"></i>
            <h4 class="fw-bold mt-2 mb-0 text-warning">
                {{ $expiring_soon->count() }}
            </h4>
            <small class="text-muted">Expirent bientôt</small>
        </div>
    </div>

</div>

{{-- ── Alertes stock faible ── --}}
@if($low_stock->count() > 0)
<div class="card border-warning border-0 shadow-sm mb-4">
    <div class="card-header bg-warning text-white fw-bold">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Stock faible — {{ $low_stock->count() }} médicament(s)
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#fff8e1;">
                <tr>
                    <th class="ps-4">Médicament</th>
                    <th>Catégorie</th>
                    <th class="text-center">Stock actuel</th>
                    <th class="text-center">Seuil alerte</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($low_stock as $medicine)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $medicine->name }}</td>
                        <td>{{ $medicine->category->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark fw-bold">
                                {{ $medicine->quantity }}
                            </span>
                        </td>
                        <td class="text-center text-muted">
                            {{ $medicine->alert_threshold }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.medicines.edit', $medicine) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil me-1"></i>Réapprovisionner
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Alertes expiration ── --}}
@if($expired->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-danger text-white fw-bold">
        <i class="bi bi-calendar-x me-2"></i>
        Médicaments expirés — {{ $expired->count() }}
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#ffebee;">
                <tr>
                    <th class="ps-4">Médicament</th>
                    <th>Catégorie</th>
                    <th class="text-center">Date expiration</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expired as $medicine)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $medicine->name }}</td>
                        <td>{{ $medicine->category->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-danger">
                                {{ $medicine->expiry_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="text-center">{{ $medicine->quantity }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.medicines.edit', $medicine) }}"
                               class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-pencil me-1"></i>Modifier
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Expirent bientôt ── --}}
@if($expiring_soon->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning text-white fw-bold">
        <i class="bi bi-clock-history me-2"></i>
        Expirent dans 30 jours — {{ $expiring_soon->count() }}
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#fff8e1;">
                <tr>
                    <th class="ps-4">Médicament</th>
                    <th>Catégorie</th>
                    <th class="text-center">Date expiration</th>
                    <th class="text-center">Jours restants</th>
                    <th class="text-center">Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiring_soon as $medicine)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $medicine->name }}</td>
                        <td>{{ $medicine->category->name }}</td>
                        <td class="text-center">
                            {{ $medicine->expiry_date->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">
                                {{ Carbon\Carbon::today()->diffInDays($medicine->expiry_date) }}j
                            </span>
                        </td>
                        <td class="text-center">{{ $medicine->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Tableau complet du stock ── --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom fw-bold">
        <i class="bi bi-table me-2 text-primary"></i>
        État complet du stock
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4">Médicament</th>
                    <th>Catégorie</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Seuil alerte</th>
                    <th class="text-center">Expiration</th>
                    <th class="text-center">État</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $medicine->name }}</td>
                        <td>{{ $medicine->category->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $medicine->quantity <= $medicine->alert_threshold ? 'warning text-dark' : 'success' }}">
                                {{ $medicine->quantity }}
                            </span>
                        </td>
                        <td class="text-center text-muted">
                            {{ $medicine->alert_threshold }}
                        </td>
                        <td class="text-center">
                            <span class="small {{ $medicine->is_expired ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ $medicine->expiry_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($medicine->is_expired)
                                <span class="badge bg-danger">Expiré</span>
                            @elseif($medicine->is_expiring_soon)
                                <span class="badge bg-warning text-dark">Bientôt</span>
                            @elseif($medicine->is_low_stock)
                                <span class="badge bg-warning text-dark">Stock faible</span>
                            @else
                                <span class="badge bg-success">OK</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection