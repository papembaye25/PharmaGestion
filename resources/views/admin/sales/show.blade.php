@extends('layouts.admin')

@section('title', 'Détail Vente')
@section('page-title', 'Détail de la Vente')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom
                        d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-receipt me-2 text-success"></i>
                    Vente #{{ $sale->id }}
                </h6>
                <small class="text-muted">
                    {{ $sale->created_at->format('d/m/Y à H:i') }}
                </small>
            </div>
            <div class="card-body">

                {{-- Infos vente --}}
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Enregistrée par</small>
                        <p class="fw-semibold mb-0">{{ $sale->user->name }}</p>
                    </div>
                    @if($sale->notes)
                        <div class="col-6">
                            <small class="text-muted">Notes</small>
                            <p class="mb-0">{{ $sale->notes }}</p>
                        </div>
                    @endif
                </div>

                <hr>

                {{-- Articles --}}
                <table class="table align-middle">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th>Médicament</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Prix unit.</th>
                            <th class="text-end">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $item->medicine->name }}
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} F
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} F
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <td colspan="3" class="fw-bold text-end">TOTAL</td>
                            <td class="fw-bold text-end fs-5">
                                {{ number_format($sale->total, 0, ',', ' ') }} F
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-3">
                    <a href="{{ route('admin.sales.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Retour
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection