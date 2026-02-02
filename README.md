# Test Technique - Développeur Full Stack Laravel

## 🎯 Objectif

Ce test technique a pour objectif d'évaluer vos compétences en développement full stack avec Laravel 10, Livewire 3, Inertia.js (Vue), et les outils modernes associés.

**Durée estimée :** 4-6 heures

## 📋 Contexte du Projet

**TaskFlow Pro** est une startup française qui développe des outils SaaS de gestion de projets. Vous êtes recruté comme développeur full stack pour refondre le module de gestion de tâches de leur plateforme existante.

### Situation Actuelle

L'entreprise utilise un système legacy développé il y a 5 ans qui présente des limitations critiques :
- Performance dégradée (temps de chargement >3s)
- Interface obsolète et non responsive
- Pas d'API pour intégration mobile
- Difficultés de scalabilité

### Votre Mission

Développer un **nouveau module de gestion de tâches** moderne qui permettra :
- D'améliorer la productivité des utilisateurs de 60%
- De permettre l'intégration mobile (app prévue phase 2)
- De supporter 10 000 utilisateurs simultanés
- De réduire le temps de chargement à moins de 1 seconde

### Contexte de Développement

Vous travaillez en équipe avec un Product Manager, un Designer UX, et un Lead Developer. Vous avez accès aux maquettes Figma et à la documentation de l'API existante.

**Consultez [PROJECT_SCENARIO.md](./PROJECT_SCENARIO.md) pour le scénario complet du projet.**

### ⚠️ Important - Restrictions IA

**Ce test technique est conçu pour évaluer vos compétences réelles.** 

**L'utilisation d'IA (ChatGPT, Copilot, Cursor AI, etc.) pour générer le code est STRICTEMENT INTERDITE** et entraînera une disqualification immédiate.

Consultez [AI_RESTRICTIONS.md](./AI_RESTRICTIONS.md) pour plus de détails.

## 🛠️ Stack Technique Requise

- **Backend :** Laravel 10
- **Frontend :** Livewire 3 et Inertia.js avec Vue 3
- **Styling :** TailwindCSS
- **Build Tool :** Vite
- **Base de données :** MySQL
- **Tests :** PHPUnit 10
- **Code Quality :** Pint (PSR-12) et Larastan
- **Routes :** Ziggy pour les routes JavaScript

## 📦 Prérequis

- PHP 8.2+
- Composer
- Node.js 18+ et npm
- MySQL 8.0+
- Git

## 🚀 Installation

1. Cloner le dépôt (ou créer un nouveau projet Laravel)
2. Installer les dépendances :
   ```bash
   composer install
   npm install
   ```
3. Configurer l'environnement :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configurer la base de données dans `.env`
5. Lancer les migrations et seeders :
   ```bash
   php artisan migrate --seed
   ```
6. Démarrer les serveurs de développement :
   ```bash
   # Terminal 1 - Laravel
   php artisan serve
   
   # Terminal 2 - Vite
   npm run dev
   ```

## 📝 Tâches à Réaliser

Consultez le fichier [SPECIFICATIONS.md](./SPECIFICATIONS.md) pour les détails complets des fonctionnalités à implémenter.

**Contexte métier :** Vous développez le MVP (Minimum Viable Product) qui sera déployé en production dans 6 semaines. Le code doit être de qualité production, testé, et maintenable.

### Fonctionnalités Principales

1. **Authentification** (déjà fournie)
   - Système d'authentification Laravel Breeze avec Inertia.js

2. **Gestion des Tâches** (à développer)
   - CRUD complet des tâches
   - Catégorisation des tâches
   - Système de priorités
   - Filtres et recherche

3. **Interface Utilisateur**
   - Page Livewire pour la gestion rapide des tâches
   - Page Inertia.js (Vue) pour la vue détaillée
   - Design responsive avec TailwindCSS

4. **API REST**
   - Endpoints pour l'intégration mobile/future

5. **Tests**
   - Tests Feature et Unit
   - Utilisation de factories et RefreshDatabase

## ✅ Critères d'Évaluation

Consultez le fichier [EVALUATION.md](./EVALUATION.md) pour les critères détaillés.

### Points Clés

- ✅ Architecture et organisation du code
- ✅ Respect des conventions Laravel
- ✅ Qualité et couverture des tests
- ✅ Interface utilisateur et UX
- ✅ Gestion des erreurs et validation
- ✅ Performance et optimisations
- ✅ Code quality (PSR-12, Larastan)

## 📤 Livraison

1. Créer un dépôt Git (GitHub, GitLab, etc.)
2. Commiter votre code avec des messages clairs
3. Envoyer le lien du dépôt avec :
   - Instructions de setup
   - Notes sur les choix techniques
   - Points bloquants ou améliorations possibles

## 📚 Ressources

- [Documentation Laravel 10](https://laravel.com/docs/10.x)
- [Livewire 3 Documentation](https://livewire.laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)

## ❓ Questions ?

N'hésitez pas à poser des questions si quelque chose n'est pas clair. Nous préférons que vous posiez des questions plutôt que de faire des suppositions.

---

**Bon courage ! 🚀**
# test-fullstack-laravel
