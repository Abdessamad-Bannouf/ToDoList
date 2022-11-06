# ToDoList


# Installation du projet :  
  
    ● Cloner le projet : git clone https://github.com/Abdessamad-Bannouf/ToDoList.git
    
    ● Installer le gestionnaire de dépendance : composer  
    
    ● Lancer la commande : php bin/console doctrine:database:create  
    
    ● Lancer la commande : php bin/console make:migration  
    
    ● Lancer la commande : php bin/console doctrine:migrations:migrate  

    ● Lancer la commande : php bin/console doctrine:fixtures:load
    
    ● Aller sur l'url : https://127.0.0.1:8000


# Lancer les tests : 

    Lancer la commande : vendor/bin/phpunit --coverage-html public/test-coverage




# Authentification :  

    ● Pour se connecter en tant qu'utilisateur: 
        email => user@test.com, mot de passe => user
    
    ● Pour se connecter en tant qu'administrateur: 
        email => admin@test.com, mot de passe => admin