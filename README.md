## CONFIGURATION PROJET ##

- Installation du projet

       composer install


- Configuration de la base de données
    Aller dans le fichier .env.local et modifier les paramètres de connexion à la base de données

    DATABASE_URL="mysql://[USER]:[PASSWORD]@[ADRESSE_IP]:[PORTS]/[Nom_BDD]?serverVersion=5.7"
    Exemple :
       DATABASE_URL="mysql://root:root@127.0.0.1:3306/Quote-machine?serverVersion=5.7"


- Création de la base de données, exécution des migrations et chargement des fixtures
    
       composer cs

- Lancement du serveur de développement
    
        symfony server:start

- Script Composer :
    
    php-cs-fixer:
        composer cs
    lancement de la base de donnée:
        composer db