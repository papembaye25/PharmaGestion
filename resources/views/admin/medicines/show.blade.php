@extends('layouts.admin')

@section('title', $medicine->name)
@section('page-title', 'Détail Médicament')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom
                        d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-capsule me-2 text-success"></i>
                    {{ $medicine->name }}
                </h6>
                <a href="{{ route('admin.medicines.edit', $medicine) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Modifier
                </a>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    {{-- Image --}}
                    <div class="col-md-4 text-center">
                        @if($medicine->image)
                            <img src="{{ Storage::url($medicine->image) }}"
                                 alt="{{ $medicine->name }}"
                                 class="rounded shadow-sm"
                                 style="width:100%; max-height:220px;
                                        object-fit:cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center
                                        justify-content-center"
                                 style="height:220px; font-size:4rem; color:#90A4AE;">
                                <i class="bi bi-capsule"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Infos --}}
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted fw-semibold" width="40%">
                                    Catégorie
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10
                                                 text-primary rounded-pill">
                                        {{ $medicine->category->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Prix</td>
                                <td class="fw-bold text-success">
                                    {{ number_format($medicine->price, 0, ',', ' ') }} F
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Stock actuel</td>
                                <td>
                                    <span class="badge bg-{{ $medicine->is_low_stock ? 'warning text-dark' : 'success' }} fs-6">
                                        {{ $medicine->quantity }} unités
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Seuil alerte</td>
                                <td>{{ $medicine->alert_threshold }} unités</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">
                                    Date d'expiration
                                </td>
                                <td>
                                    <span class="{{ $medicine->is_expired ? 'text-danger fw-bold' : ($medicine->is_expiring_soon ? 'text-warning fw-bold' : '') }}">
                                        {{ $medicine->expiry_date->format('d/m/Y') }}
                                        @if($medicine->is_expired)
                                            <span class="badge bg-danger ms-1">Expiré</span>
                                        @elseif($medicine->is_expiring_soon)
                                            <span class="badge bg-warning text-dark ms-1">
                                                Bientôt
                                            </span>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        </table>

                        @if($medicine->description)
                            <div class="mt-2">
                                <p class="text-muted fw-semibold mb-1">Description</p>
                                <p class="mb-0">{{ $medicine->description }}</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="card-footer bg-white">
                <a href="{{ route('admin.medicines.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
            </div>

        </div>
    </div>
</div>

@endsection