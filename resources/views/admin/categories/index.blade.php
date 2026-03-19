@extends('layouts.admin')

@section('title', 'Catégories')
@section('page-title', 'Gestion des Catégories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">
        {{ $categories->count() }} catégorie(s) au total
    </p>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle catégorie
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th class="text-center">Médicaments</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="text-muted">
                            {{ $category->description ?? '—' }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill">
                                {{ $category->medicines_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{-- Bouton Modifier --}}
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- Bouton Supprimer --}}
                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $category) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Affiché si aucune catégorie --}}
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size:2rem;"></i>
                            <p class="mt-2">Aucune catégorie pour l'instant.</p>
                            <a href="{{ route('admin.categories.create') }}"
                               class="btn btn-success btn-sm">
                                Créer la première catégorie
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection