## Description

**Quote-machine** est une application web de gestion de citations construite avec **Symfony** (PHP). Elle permet aux utilisateurs d'interagir avec des citations en fonction de leurs rôles. Les administrateurs peuvent créer, modifier et supprimer des citations, tandis que les utilisateurs lambda peuvent les lire et les liker.

## Configuration du Projet

### Prérequis

- PHP 7.4 ou supérieur
- Composer
- Symfony CLI
- Serveur de base de données MySQL

### Installation du projet

1. **Cloner le dépôt** :
    
    ```bash
    bash
    Copier le code
    git clone https://github.com/votre-utilisateur/quote-machine.git
    cd quote-machine
    
    ```
    
2. **Installer les dépendances** :
    
    ```bash
    bash
    Copier le code
    composer install
    
    ```
    
3. **Configurer la base de données** :
    - Ouvrir le fichier `.env.local` et modifier les paramètres de connexion à la base de données en fonction de votre configuration :
        
        ```
        env
        Copier le code
        DATABASE_URL="mysql://[USER]:[PASSWORD]@[ADRESSE_IP]:[PORT]/[Nom_BDD]?serverVersion=5.7"
        
        ```
        
    - Exemple :
        
        ```
        env
        Copier le code
        DATABASE_URL="mysql://root:root@127.0.0.1:3306/quote_machine?serverVersion=5.7"
        
        ```
        
4. **Création de la base de données, exécution des migrations et chargement des fixtures** :
    
    ```bash
    bash
    Copier le code
    composer db
    
    ```
    
5. **Lancer le serveur de développement** :
    
    ```bash
    bash
    Copier le code
    symfony server:start
    
    ```
    

### Commandes utiles

- **php-cs-fixer** : Vérifie et corrige la qualité du code selon les standards PHP.
    
    ```bash
    bash
    Copier le code
    composer cs
    
    ```
    
- **Création et mise à jour de la base de données** :
    
    ```bash
    bash
    Copier le code
    composer db
    
    ```
    

## Fonctionnalités

- **Authentification et rôles utilisateurs** : Les utilisateurs peuvent s'inscrire, se connecter, et se voir attribuer un rôle (Admin ou Utilisateur lambda).
- **Gestion des citations pour les Admins** :
    - Créer, modifier et supprimer des citations.
- **Interactions pour les Utilisateurs lambda** :
    - Parcourir les citations et les "liker".
- **Filtrage des citations** : Trier les citations par popularité (nombre de likes) et par date de publication.

## Améliorations futures

Les améliorations envisagées incluent :

- **Système de tags** : Permettre le classement des citations par thèmes.
- **Partage sur les réseaux sociaux** : Intégrer un bouton de partage des citations.
- **Notifications** : Informer les utilisateurs de nouvelles citations ajoutées par les admins.

## Auteur

Ce projet a été réalisé par **Mustapha**. Pour toute question ou suggestion, n'hésitez pas à me contacter !
