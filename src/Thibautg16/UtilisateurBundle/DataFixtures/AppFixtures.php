<?php
/*
 * Thibautg16/UtilisateurBundle/DataFixtures/AppFixtures.php;
 *
 * Copyright 2017-2018 GILLARDEAU Thibaut (aka @Thibautg16)
 *
 * Authors :
 *  - Gillardeau Thibaut (aka @Thibautg16)
 */

namespace Thibautg16\UtilisateurBundle\DataFixtures;

use Thibautg16\UtilisateurBundle\Entity\Utilisateur;
use Thibautg16\UtilisateurBundle\Entity\Groupe;
use Thibautg16\UtilisateurBundle\Entity\Role;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class AppFixtures extends Fixture{

    private $container;

    public function __construct(Container $container) {

        $this->container = $container;
    }

    public function load(ObjectManager $manager){
        // Create basic roles
        $oRole1 = new Role();
        $oRole1->setNom('ROLE_ROLE_ADMIN');
        $manager->persist($oRole1);

        $oRole2 = new Role();
        $oRole2->setNom('ROLE_GROUPE_ADMIN');
        $manager->persist($oRole2);

        $oRole3 = new Role();
        $oRole3->setNom('ROLE_UTILISATEUR_ADMIN');
        $manager->persist($oRole3);

        // Create basic groupes
        $oGroupe = new Groupe();
        $oGroupe->setNom('GROUPE_UTILISATEUR_ADMIN');
        $oGroupe->addRolesGroupe($oRole1);
        $oGroupe->addRolesGroupe($oRole2);
        $oGroupe->addRolesGroupe($oRole3);
        $manager->persist($oGroupe);

        // Create first user
        $oUtilisateur = new Utilisateur();
        $oUtilisateur->setUsername('admin');

        // gestion mdp
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($oUtilisateur);
        $password = $encoder->encodePassword('admin_1234', $oUtilisateur->getSalt());
        $oUtilisateur->setPassword($password);

        $oUtilisateur->setEmail('admin@admin.com');
        $oUtilisateur->setActive(1);
        $oUtilisateur->addGroupe($oGroupe);
        $manager->persist($oUtilisateur);

        // save objects
        $manager->flush();

    }
}