@extends('layouts.admin')

@section('title', 'Ventes')
@section('page-title', 'Gestion des Ventes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">{{ $sales->total() }} vente(s) au total</p>
    <a href="{{ route('admin.sales.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle vente
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Date</th>
                    <th>Enregistrée par</th>
                    <th class="text-center">Articles</th>
                    <th class="text-end pe-4">Total</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td class="ps-4 text-muted">{{ $sale->id }}</td>
                        <td>
                            <span class="fw-semibold">
                                {{ $sale->created_at->format('d/m/Y') }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $sale->created_at->format('H:i') }}
                            </small>
                        </td>
                        <td>{{ $sale->user->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill">
                                {{ $sale->items->count() }}
                            </span>
                        </td>
                        <td class="text-end pe-4 fw-bold text-success">
                            {{ number_format($sale->total, 0, ',', ' ') }} F
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.sales.show', $sale) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt" style="font-size:2rem;"></i>
                            <p class="mt-2">Aucune vente enregistrée.</p>
                            <a href="{{ route('admin.sales.create') }}"
                               class="btn btn-success btn-sm">
                                Enregistrer la première vente
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($sales->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $sales->links() }}
    </div>
@endif

@endsection