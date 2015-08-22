# Thibautg16UtilisateurBundle

**//!\\ Attention : ce module est en cours de développement, il n'est actuellement pas complètement fonctionnel //!\\**

### Prérequis
- php 5.3.3

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