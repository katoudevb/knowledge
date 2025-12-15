### Knowledge Learning – Plateforme E-learning

## Pré-requis
- PHP 8.1 ou version supérieure
- Composer (gestionnaire de dépendances PHP)
- MySQL 8 ou base de données compatible
- Symfony CLI (optionnel mais recommandé)
- Node.js & npm (pour les assets front-end, optionnel si vous utilisez Webpack Encore)


## Installation
# Cloner le repository
- git clone https://github.com/katoudevb/knowledge.git
- cd knowledge-learning

# Installer les dépendances PHP
- composer install

# Configurer les variables d’environnement
- Copier .env en .env.local
- Modifier les paramètres de la base de données et du mailer dans .env.local

- DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/knowledge_learning"
- MAILER_DSN=smtp://utilisateur:motdepasse@smtp.exemple.com:port

# Créer la base de données et le schéma
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate

# Charger les données initiales (optionnel)
- Vous pouvez créer les thèmes, cursus, leçons et un compte administrateur via des fixtures (si implémentées)

- php bin/console doctrine:fixtures:load

## Lancer l’application
# Démarrer le serveur Symfony
- symfony server:start

# Accéder à l’application
- Front-end : http://127.0.0.1:8000/
- Back-office (admin) : http://127.0.0.1:8000/admin

# Accès à l'application 
- Administrateur :
    admin@example.com
    Admin123@

- Client :
    client@example.com
    Client123@

- Base de données : 
    knowledge_user
    tzVz6j[IVUlAiEyZ

## Tests
- Exécuter les tests unitaires et fonctionnels avec PHPUnit :

- php bin/phpunit
- Fichier environnement : .env.test.local (contient les clés pour Stripe)

- Les tests couvrent : l’inscription des utilisateurs, la connexion, les achats, la validation des leçons et les repositories.

## Structure du projet
- src/Entity/ → Entités Doctrine
- src/Repository/ → Composants d’accès aux données
- src/Controller/ → Contrôleurs et routes
- src/Service/ → Logique métier
- templates/ → Templates Twig pour le front-end
- tests/ → Tests unitaires et fonctionnels

## Fonctionnalités
- Inscription et activation des comptes par email
- Gestion des rôles (ROLE_ADMIN, ROLE_CLIENT)
- Achat de cursus ou de leçons (mode sandbox avec Stripe)
- Suivi de progression des leçons et génération de certifications
- Front-end minimaliste pour la démonstration
- Stockage sécurisé des mots de passe et protection CSRF

## Système d'achat 
- Carte de payeemnt factice : 4242 4242 4242 4242

## Licence

- Ce projet est destiné à des fins académiques et n’est pas destiné à un usage commercial.

### Auteur 
- Nom : Kat
- Rôle : Développeuse Web Full Stack
- Projet académique : Conception et développement de la plateforme e-learning « Knowledge Learning »