@extends('layouts.admin')

@section('title', 'Commande #' . $order->id)
@section('page-title', 'Détail de la Commande')

@section('content')

<div class="row g-4">

    {{-- Colonne gauche : infos client + statut --}}
    <div class="col-md-4">

        {{-- Infos client --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person me-2 text-primary"></i>
                    Informations client
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <small class="text-muted">Nom</small><br>
                    <span class="fw-semibold">{{ $order->client_name }}</span>
                </p>
                <p class="mb-2">
                    <small class="text-muted">Téléphone</small><br>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->client_phone) }}"
                       target="_blank" class="text-success text-decoration-none fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i>
                        {{ $order->client_phone }}
                    </a>
                </p>
                <p class="mb-2">
                    <small class="text-muted">Adresse</small><br>
                    <span>{{ $order->client_address }}</span>
                </p>
                <p class="mb-0">
                    <small class="text-muted">Paiement</small><br>
                    @if($order->payment_method == 'whatsapp')
                        <span class="badge bg-success">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp (Wave/OM)
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="bi bi-cash me-1"></i>Paiement à la livraison
                        </span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Changer le statut --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-arrow-repeat me-2 text-warning"></i>
                    Changer le statut
                </h6>
            </div>
            <div class="card-body">

                {{-- Statut actuel --}}
                <p class="mb-3">
                    <small class="text-muted">Statut actuel</small><br>
                    {!! $order->status_badge !!}
                </p>

                <form method="POST"
                      action="{{ route('admin.orders.updateStatus', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <option value="en_attente"
                                {{ $order->status == 'en_attente' ? 'selected' : '' }}>
                                ⏳ En attente
                            </option>
                            <option value="validee"
                                {{ $order->status == 'validee' ? 'selected' : '' }}>
                                ✓ Validée
                            </option>
                            <option value="livree"
                                {{ $order->status == 'livree' ? 'selected' : '' }}>
                                ✔ Livrée
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>
                        Mettre à jour
                    </button>
                </form>

            </div>
        </div>

    </div>

    {{-- Colonne droite : articles commandés --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom
                        d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-bag me-2 text-success"></i>
                    Commande #{{ $order->id }}
                </h6>
                <small class="text-muted">
                    {{ $order->created_at->format('d/m/Y à H:i') }}
                </small>
            </div>
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="ps-4">Médicament</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Prix unit.</th>
                            <th class="text-end pe-4">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    {{ $item->medicine->name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->quantity }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} F
                                </td>
                                <td class="text-end pe-4 fw-bold">
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} F
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <td colspan="3"
                                class="fw-bold text-end ps-4">TOTAL</td>
                            <td class="fw-bold text-end pe-4 fs-5">
                                {{ number_format($order->total, 0, ',', ' ') }} F
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Notes --}}
            @if($order->notes)
                <div class="card-footer bg-white">
                    <small class="text-muted">
                        <i class="bi bi-chat-left-text me-1"></i>
                        Notes : {{ $order->notes }}
                    </small>
                </div>
            @endif

        </div>

        <div class="mt-3">
            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour aux commandes
            </a>
        </div>
    </div>

</div>

@endsection