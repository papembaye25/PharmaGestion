<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaGestion+ — Connexion</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1976D2 0%, #2E7D32 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header {
            background: linear-gradient(135deg, #2E7D32, #1976D2);
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .btn-login {
            background: #2E7D32;
            border: none;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #1B5E20;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            {{-- Message succès (ex: après logout) --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card login-card">

                {{-- En-tête --}}
                <div class="login-header">
                    <i class="bi bi-capsule" style="font-size: 3rem;"></i>
                    <h2 class="mt-2 mb-0 fw-bold">PharmaGestion+</h2>
                    <p class="mb-0 opacity-75">Espace Administration</p>
                </div>

                {{-- Formulaire --}}
                <div class="card-body p-4">
                    <h5 class="text-center text-muted mb-4">Connexion</h5>

                    <form method="POST" action="{{ route('login.post') }}">
                        
                        @csrf

                        {{-- Champ Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="admin@pharma.com"
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Mot de passe --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock me-1"></i>Mot de passe
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Se souvenir de moi --}}
                        <div class="mb-4 form-check">
                            <input type="checkbox"
                                   name="remember"
                                   class="form-check-input"
                                   id="remember">
                            <label class="form-check-label text-muted" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        {{-- Bouton connexion --}}
                        <button type="submit" class="btn btn-login w-100 text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </button>

                    </form>
                </div>
            </div>

            <p class="text-center text-white mt-3 opacity-75">
                <small>PharmaGestion+ &copy; {{ date('Y') }}</small>
            </p>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>