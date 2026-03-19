@extends('layouts.admin')

@section('title', 'Modifier Médicament')
@section('page-title', 'Modifier un Médicament')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-pencil me-2 text-primary"></i>
                    Modifier : {{ $medicine->name }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ route('admin.medicines.update', $medicine) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nom *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $medicine->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Catégorie *</label>
                            <select name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $medicine->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Prix (FCFA) *</label>
                            <input type="number" name="price" step="0.01"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $medicine->price) }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quantité *</label>
                            <input type="number" name="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', $medicine->quantity) }}"
                                   min="0">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Seuil d'alerte</label>
                            <input type="number" name="alert_threshold"
                                   class="form-control @error('alert_threshold') is-invalid @enderror"
                                   value="{{ old('alert_threshold', $medicine->alert_threshold) }}"
                                   min="1">
                            @error('alert_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date d'expiration *</label>
                            <input type="date" name="expiry_date"
                                   class="form-control @error('expiry_date') is-invalid @enderror"
                                   value="{{ old('expiry_date', $medicine->expiry_date->format('Y-m-d')) }}">
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image actuelle + nouvelle image --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- Image actuelle --}}
                            @if($medicine->image)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img id="imagePreview"
                                         src="{{ Storage::url($medicine->image) }}"
                                         class="rounded"
                                         style="width:60px; height:60px; object-fit:cover;">
                                    <small class="text-muted">Image actuelle</small>
                                </div>
                            @else
                                <img id="imagePreview" src="#"
                                     class="mt-2 rounded d-none"
                                     style="width:60px; height:60px; object-fit:cover;">
                            @endif
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $medicine->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Mettre à jour
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
</script>
@endsection