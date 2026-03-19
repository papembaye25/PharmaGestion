# 💊 PharmaGestion+

> Plateforme web complète de gestion de pharmacie avec commande en ligne

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

---

## 📋 Table des matières

- [À propos](#-à-propos)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du projet](#-structure-du-projet)
- [Base de données](#-base-de-données)
- [Captures d'écran](#-captures-décran)
- [Auteur](#-auteur)

---

## 🎯 À propos

**PharmaGestion+** est une application web développée avec Laravel permettant à une pharmacie de gérer efficacement ses opérations quotidiennes tout en offrant une interface moderne aux clients pour commander en ligne.

Le projet répond à une problématique réelle : de nombreuses pharmacies gèrent encore leurs stocks et ventes de façon manuelle, ce qui engendre des erreurs, des ruptures de stock et une mauvaise expérience client.

### Deux espaces distincts :

| Espace | Accès | Description |
|---|---|---|
| 🔒 **Admin** | Pharmacien connecté | Gestion complète de l'officine |
| 🌐 **Public** | Tout visiteur | Consultation et commande en ligne |

---

## ✨ Fonctionnalités

### Interface Administration

- 📊 **Dashboard** — Statistiques en temps réel (médicaments, ventes, commandes, alertes)
- 💊 **Médicaments** — CRUD complet avec upload d'image et pagination
- 🏷️ **Catégories** — Gestion des familles thérapeutiques
- 📦 **Stock** — Suivi des quantités avec alertes automatiques
- 🧾 **Ventes** — Enregistrement en caisse avec décrément automatique du stock
- 🛒 **Commandes** — Traitement des commandes avec workflow de statuts
- 🔔 **Alertes** — Médicaments expirés + stocks faibles en temps réel

### Interface Publique

- 🏠 **Accueil** — Bannière, catégories et médicaments vedettes
- 🔍 **Catalogue** — Recherche et filtres par catégorie avec pagination
- 🛒 **Panier** — Panier persistant (localStorage) avec bouton flottant
- 📱 **Commande** — Formulaire client avec paiement à la livraison ou WhatsApp
- 💬 **WhatsApp** — Message pré-rempli automatiquement (Wave / Orange Money)

---

## 🛠️ Technologies

### Backend
- **PHP 8.3** — Langage serveur
- **Laravel 13** — Framework MVC
- **Eloquent ORM** — Gestion des relations base de données
- **Laravel Auth** — Authentification sécurisée

### Frontend
- **Bootstrap 5.3** — Framework CSS responsive
- **Bootstrap Icons** — Bibliothèque d'icônes
- **JavaScript ES6+** — Panier dynamique et interactions
- **Blade** — Moteur de templates Laravel

### Base de données
- **MySQL 8** — SGBD relationnel
- **Migrations Laravel** — Versioning de la base de données

### Environnement
- **Laragon** — Environnement de développement local
- **Git / GitHub** — Versioning et collaboration

---

## 📦 Prérequis

Avant d'installer le projet, assure-toi d'avoir :

```
✅ PHP >= 8.1
✅ Composer
✅ MySQL 8+
✅ Node.js (optionnel)
✅ Git
```

---

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/TON_USERNAME/PharmaGestion.git
cd PharmaGestion
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Copier le fichier d'environnement

```bash
cp .env.example .env
```

### 4. Générer la clé d'application

```bash
php artisan key:generate
```

### 5. Configurer la base de données

Crée une base de données MySQL nommée `pharmagestion`, puis modifie le fichier `.env` :

```env
APP_NAME=PharmaGestion
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmagestion
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Exécuter les migrations

```bash
php artisan migrate
```

### 7. Créer le compte administrateur

```bash
php artisan db:seed
```

### 8. Créer le lien symbolique pour les images

```bash
php artisan storage:link
```

### 9. Lancer le serveur

```bash
php artisan serve
```

L'application est accessible sur `http://127.0.0.1:8000` 🎉

---

## ⚙️ Configuration

### Compte administrateur par défaut

```
Email    : admin@pharma.com
Password : admin123
```

> ⚠️ **Important** : Changez ces identifiants en production !

### Numéro WhatsApp de la pharmacie

Dans `resources/views/public/catalogue.blade.php`:

```javascript s
const PHARMACY_WHATSAPP = '221777002552'; // on peut mettre le numero c'est a dire il est modifiable
```

### Seuil d'alerte stock

Le seuil est configurable **par médicament** lors de la création ou modification. La valeur par défaut est `10 unités`.

---

## 💻 Utilisation

### Accès Admin

```
URL : http://127.0.0.1:8000/login
```

1. Connecte-toi avec `admin@pharma.com` / `admin123`
2. Commence par créer des **catégories**
3. Ajoute des **médicaments** avec images
4. Gère les **commandes** reçues depuis l'interface publique

### Accès Public (Client)

```
URL : http://127.0.0.1:8000
```

1. Parcours le **catalogue**
2. Ajoute des médicaments au **panier**
3. Remplis tes **informations de contact**
4. Choisis le mode de paiement :
   - 💵 **Livraison** → Confirme directement
   - 💬 **WhatsApp** → Message pré-rempli envoyé au pharmacien

---

## 📁 Structure du projet

```
PharmaGestion/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   ├── DashboardController.php
│   │       │   ├── CategoryController.php
│   │       │   ├── MedicineController.php
│   │       │   ├── OrderController.php
│   │       │   ├── SaleController.php
│   │       │   └── StockController.php
│   │       ├── Public/
│   │       │   ├── HomeController.php
│   │       │   ├── CatalogController.php
│   │       │   └── OrderController.php
│   │       └── AuthController.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Medicine.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Sale.php
│       └── SaleItem.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── admin.blade.php
│       │   └── public.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── categories/
│       │   ├── medicines/
│       │   ├── orders/
│       │   ├── sales/
│       │   └── stock/
│       ├── public/
│       │   ├── home.blade.php
│       │   ├── catalogue.blade.php
│       │   └── medicine-show.blade.php
│       └── auth/
│           └── login.blade.php
└── routes/
    └── web.php
```

---

## 🗄️ Base de données

### Schéma des tables

```
users          → Compte administrateur (pharmacien)
categories     → Familles thérapeutiques
medicines      → Médicaments (stock, prix, expiration)
orders         → Commandes clients
order_items    → Lignes de commande
sales          → Ventes en caisse
sale_items     → Lignes de vente
```

### Relations principales

```
Category    ──< Medicine
Medicine    ──< OrderItem >── Order
Medicine    ──< SaleItem  >── Sale
User        ──< Sale
```

---

## 🔒 Sécurité

- **Authentification** — Sessions Laravel avec middleware `auth`
- **Mots de passe** — Hashage bcrypt via `Hash::make()`
- **Protection CSRF** — Token `@csrf` sur tous les formulaires POST
- **Validation** — Validation côté serveur sur toutes les entrées
- **Route Model Binding** — Accès sécurisé aux ressources
- **Transactions DB** — Intégrité des données lors des ventes

---

## 📸 Captures d'écran

| Page | Description |
|---|---|
| `/login` | Page de connexion administrateur |
| `/admin/dashboard` | Tableau de bord avec statistiques |
| `/admin/medicines` | Liste des médicaments avec filtres |
| `/admin/stock` | Gestion du stock avec alertes |
| `/admin/orders` | Traitement des commandes |
| `/` | Page d'accueil publique |
| `/catalogue` | Catalogue avec panier |

---

## 🚀 Déploiement

### Variables d'environnement production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-domaine.com

DB_CONNECTION=mysql
DB_HOST=ton-host-mysql
DB_DATABASE=pharmagestion
DB_USERNAME=ton-user
DB_PASSWORD=ton-password
```

### Commandes de déploiement

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

---

## 👨‍💻 Auteur

**Ton Nom**

- GitHub : [@papembaye25](https://github.com/papembaye25)
- Email : gayepapembaye5@gmail.com

---

## 📄 Licence

Ce projet est développé dans le cadre d'un projet académique.

---

<div align="center">

**PharmaGestion+** — Développé avec bootsrap et Laravel

</div>