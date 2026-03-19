@extends('layouts.admin')

@section('title', 'Médicaments')
@section('page-title', 'Gestion des Médicaments')

@section('content')

{{-- Barre de recherche et filtre --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.medicines.index') }}"
              class="row g-2 align-items-end">

            <div class="col-md-5">
                <label class="form-label fw-semibold small">Rechercher</label>
                <input type="text" name="search"
                       class="form-control"
                       placeholder="Nom du médicament..."
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold small">Catégorie</label>
                <select name="category_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                <a href="{{ route('admin.medicines.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>

        </form>
    </div>
</div>

{{-- Bouton ajouter + compteur --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">
        {{ $medicines->total() }} médicament(s) trouvé(s)
    </p>
    <a href="{{ route('admin.medicines.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nouveau médicament
    </a>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-3">Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th class="text-center">Prix</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Expiration</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr>
                        {{-- Image --}}
                        <td class="ps-3">
                            @if($medicine->image)
                                <img src="{{ Storage::url($medicine->image) }}"
                                     alt="{{ $medicine->name }}"
                                     class="rounded"
                                     style="width:45px; height:45px; object-fit:cover;">
                            @else
                                <div class="rounded bg-light d-flex align-items-center
                                            justify-content-center"
                                     style="width:45px; height:45px;">
                                    <i class="bi bi-capsule text-muted"></i>
                                </div>
                            @endif
                        </td>

                        {{-- Nom --}}
                        <td class="fw-semibold">{{ $medicine->name }}</td>

                        {{-- Catégorie --}}
                        <td>
                            <span class="badge bg-primary bg-opacity-10
                                         text-primary rounded-pill">
                                {{ $medicine->category->name }}
                            </span>
                        </td>

                        {{-- Prix --}}
                        <td class="text-center fw-semibold">
                            {{ number_format($medicine->price, 0, ',', ' ') }} F
                        </td>

                        {{-- Stock avec badge coloré selon le niveau --}}
                        <td class="text-center">
                            @if($medicine->is_expired)
                                <span class="badge bg-danger">Expiré</span>
                            @elseif($medicine->is_low_stock)
                                <span class="badge bg-warning text-dark">
                                    {{ $medicine->quantity }} ⚠️
                                </span>
                            @else
                                <span class="badge bg-success">
                                    {{ $medicine->quantity }}
                                </span>
                            @endif
                        </td>

                        {{-- Date expiration --}}
                        <td class="text-center">
                            @if($medicine->is_expired)
                                <span class="text-danger small fw-semibold">
                                    <i class="bi bi-x-circle me-1"></i>
                                    {{ $medicine->expiry_date->format('d/m/Y') }}
                                </span>
                            @elseif($medicine->is_expiring_soon)
                                <span class="text-warning small fw-semibold">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $medicine->expiry_date->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-muted small">
                                    {{ $medicine->expiry_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="text-center">
                            <a href="{{ route('admin.medicines.edit', $medicine) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.medicines.destroy', $medicine) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce médicament ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size:2rem;"></i>
                            <p class="mt-2">Aucun médicament trouvé.</p>
                            <a href="{{ route('admin.medicines.create') }}"
                               class="btn btn-success btn-sm">
                                Ajouter le premier médicament
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($medicines->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $medicines->links() }}
    </div>
@endif

@endsection