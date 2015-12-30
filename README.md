# Thibautg16UtilisateurBundle

**//!\\ Attention : ce module est en cours de développement, il n'est actuellement pas complètement fonctionnel //!\\**

### Prérequis
- php 5.3.9

## Installation Thibautg16UtilisateurBundle
### Installation à l'aide de composer

1. Ajouter ``thibautg16/utilisateur-bundle`` comme dépendance de votre projet dans le fichier ``composer.json`` :

        {
          "require": {
            "thibautg16/utilisateur-bundle": "dev-master"
          }
        }

3. Installer vos dépendances :

        php composer.phar install

4. Ajouter le Bundle dans votre kernel :

        <?php
        // app/AppKernel.php
        
        public function registerBundles(){
            $bundles = array(
              // ...
              new Thibautg16\UtilisateurBundle\Thibautg16UtilisateurBundle(),
            );
        }

5. Ajouter les routes du bundle à votre projet en ajoutant dans votre fichier app/config/routing.yml :

        Thibautg16UtilisateurBundle:
            resource: "@Thibautg16UtilisateurBundle/Resources/config/routing.yml"
            prefix:   /
            
6. Ajouter les informations pour la sécurité dans le fichier "app/config/security.yml" :

        # app/config/security.yml       

        security:
        encoders:
                Thibautg16\UtilisateurBundle\Entity\Utilisateur:
                algorithm:   sha512
                iterations: 1
                encode_as_base64: false
        
        providers:       
                main:
                entity: { class: Thibautg16\UtilisateurBundle\Entity\Utilisateur, property:username }
        
        firewalls:
                dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false
        
                # On crée un pare-feu uniquement pour le formulaire
                main_login:
                # Cette expression régulière permet de prendre /login (mais pas /login_check !)
                pattern:   ^/login$
                # On autorise alors les anonymes sur ce pare-feu
                anonymous: true 
        
                main:
                pattern:   ^/
                anonymous: false
                provider:  main
                form_login:
                login_path: login
                check_path: login_check
                logout:
                path:   logout
                target: /login