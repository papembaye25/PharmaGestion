@extends('layouts.admin')

@section('title', 'Nouveau Médicament')
@section('page-title', 'Nouveau Médicament')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Ajouter un médicament
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ route('admin.medicines.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Nom --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">
                                Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Ex: Paracétamol 500mg"
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Catégorie --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Catégorie <span class="text-danger">*</span>
                            </label>
                            <select name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">Sélectionner...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Prix --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Prix (FCFA) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="price" step="0.01"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}"
                                   placeholder="0">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Quantité --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Quantité en stock <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', 0) }}"
                                   min="0">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Seuil alerte --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Seuil d'alerte stock
                                <i class="bi bi-info-circle text-muted"
                                   title="Alerte si stock ≤ ce seuil"
                                   data-bs-toggle="tooltip"></i>
                            </label>
                            <input type="number" name="alert_threshold"
                                   class="form-control @error('alert_threshold') is-invalid @enderror"
                                   value="{{ old('alert_threshold', 10) }}"
                                   min="1">
                            @error('alert_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date expiration --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Date d'expiration <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="expiry_date"
                                   class="form-control @error('expiry_date') is-invalid @enderror"
                                   value="{{ old('expiry_date') }}">
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Image <span class="text-muted">(optionnel)</span>
                            </label>
                            <input type="file" name="image" id="imageInput"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Aperçu image --}}
                            <img id="imagePreview" src="#" alt="Aperçu"
                                 class="mt-2 rounded d-none"
                                 style="width:80px; height:80px; object-fit:cover;">
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Description <span class="text-muted">(optionnel)</span>
                            </label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Composition, posologie, indications...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer
                        </button>
                        <a href="{{ route('admin.medicines.index') }}"
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

@section('scripts')
<script>
    // Aperçu de l'image avant upload
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Activer les tooltips Bootstrap
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
            .forEach(el => new bootstrap.Tooltip(el));
</script>
@endsection