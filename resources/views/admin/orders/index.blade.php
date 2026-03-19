@extends('layouts.admin')

@section('title', 'Commandes')
@section('page-title', 'Gestion des Commandes')

@section('content')

{{-- Filtre par statut --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.orders.index') }}"
              class="d-flex align-items-center gap-3">

            <span class="fw-semibold small text-muted">Filtrer :</span>

            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
                Toutes
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'en_attente']) }}"
               class="btn btn-sm {{ request('status') == 'en_attente' ? 'btn-warning' : 'btn-outline-warning' }}">
                En attente
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'validee']) }}"
               class="btn btn-sm {{ request('status') == 'validee' ? 'btn-primary' : 'btn-outline-primary' }}">
                Validées
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'livree']) }}"
               class="btn btn-sm {{ request('status') == 'livree' ? 'btn-success' : 'btn-outline-success' }}">
                Livrées
            </a>

        </form>
    </div>
</div>

{{-- Compteur --}}
<div class="mb-3">
    <p class="text-muted mb-0">{{ $orders->total() }} commande(s) trouvée(s)</p>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th class="text-center">Articles</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Statut</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 text-muted">#{{ $order->id }}</td>

                        <td class="fw-semibold">{{ $order->client_name }}</td>

                        <td>
                            {{-- Bouton WhatsApp direct --}}
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->client_phone) }}"
                               target="_blank"
                               class="text-success text-decoration-none">
                                <i class="bi bi-whatsapp me-1"></i>
                                {{ $order->client_phone }}
                            </a>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill">
                                {{ $order->items->count() }}
                            </span>
                        </td>

                        <td class="text-end fw-bold">
                            {{ number_format($order->total, 0, ',', ' ') }} F
                        </td>

                        <td class="text-center">
                            {!! $order->status_badge !!}
                        </td>

                        <td>
                            <span class="small">
                                {{ $order->created_at->format('d/m/Y') }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $order->created_at->format('H:i') }}
                            </small>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size:2rem;"></i>
                            <p class="mt-2">Aucune commande reçue.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
@endif

@endsection