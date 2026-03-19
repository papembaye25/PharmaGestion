@extends('layouts.admin')

@section('title', 'Nouvelle Catégorie')
@section('page-title', 'Nouvelle Catégorie')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Créer une catégorie
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf

                    {{-- Nom --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nom <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Ex: Antibiotiques"
                            autofocus
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Description <span class="text-muted">(optionnel)</span>
                        </label>
                        <textarea
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="3"
                            placeholder="Décrivez cette catégorie..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer
                        </button>
                        <a href="{{ route('admin.categories.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection