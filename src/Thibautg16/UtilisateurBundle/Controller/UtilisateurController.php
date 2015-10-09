<?php
/*
 * Thibautg16/UtilisateurBundle/Controller/UtilisateurController.php;
 *
 * Copyright 2015 GILLARDEAU Thibaut (aka Thibautg16)
 *
 * Authors :
 *  - Gillardeau Thibaut (aka Thibautg16)
 */

namespace Thibautg16\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Thibautg16\UtilisateurBundle\Entity\Utilisateur;

use Doctrine\Common\Collections\ArrayCollection;

class UtilisateurController extends Controller{

        public function listeAction(){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        /****** On liste tous les services ******/
                        // On récupère tous les services actuellement en BDD
                        $listeUtilisateur = $em
                                ->getRepository('Thibautg16UtilisateurBundle:Utilisateur')
                                ->findAll()
                        ;

                        return $this->render('Thibautg16UtilisateurBundle:Utilisateur:liste.html.twig', array('listeUtilisateur' => $listeUtilisateur));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
    }

        public function ajouterAction(Request $request){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        // Création de l'objet Services
                        $oUser = new Utilisateur();

                        // On crée le FormBuilder grâce au service form factory
                        $formBuilder = $this->get('form.factory')->createBuilder('form', $oUser);

                        // On ajoute les champs de l'entité que l'on veut à notre formulaire
                        $formBuilder
                          ->add('username',     'text')
                          ->add('email',        'text')
                          ->add('password', 'repeated', array('first_name' => 'password', 'second_name' => 'confirm','type' => 'password' ))
                          ->add('Active',       'checkbox', array('required' => false))
                          ->add('Ajouter',      'submit')
                        ;

                        // À partir du formBuilder, on génère le formulaire
                        $form = $formBuilder->getForm();

                        // On fait le lien Requête <-> Formulaire
                        $form->handleRequest($request);

                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {

                          // On enregistre notre objet $oServices dans la base de données
                          $em = $this->getDoctrine()->getManager();
                          //$oUser->setCreePar($user);
                          
                          $oGroupe = $em->getRepository('Thibautg16UtilisateurBundle:Groupe')->findOneByNom('ADMIN'); 
			  $oUser->addGroupe($oGroupe);
			  $oUser->setRoles('ROLE_ADMIN'); 

                          $em->persist($oUser);
                          $em->flush();

                          $request->getSession()->getFlashBag()->add('success', 'Création du compte : '.$oUser->getUsername().' effectuée avec succès.');

                          // On redirige vers la page de visualisation de l'annonce nouvellement créée
                          return $this->redirect($this->generateUrl('thibautg16_utilisateurs_liste'));
                        }

                        // À ce stade, le formulaire n'est pas valide car :
                        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
                        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
                        return $this->render('Thibautg16UtilisateurBundle:Utilisateur:ajouter.html.twig', array(
                          'form' => $form->createView(), 'utilisateur' => $oUser));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function modifierAction($idUtilisateur, Request $request){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        /****** On recherche les informations sur le service demandé ******/
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // Récupération de l'objet iplb déjà existant, d'id $idIplb.
                        $oUtilisateur = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('Thibautg16UtilisateurBundle:Utilisateur')
                          ->find($idUtilisateur)
                        ;

                        // On crée le FormBuilder grâce au service form factory
                        $formBuilder = $this->get('form.factory')->createBuilder('form', $oUtilisateur);

                        // On ajoute les champs de l'entité que l'on veut à notre formulaire
                        $formBuilder
                          ->add('username',     'text')
                          ->add('email',        'text')
                          ->add('Active',       'checkbox', array('required' => false))
                          ->add('Ajouter',      'submit')
                        ;

                        // À partir du formBuilder, on génère le formulaire
                        $form = $formBuilder->getForm();

                        // On fait le lien Requête <-> Formulaire
                        // À partir de maintenant, la variable $produit contient les valeurs entrées dans le formulaire par le visiteur
                        $form->handleRequest($request);

                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {

                          // On l'enregistre notre objet $oServices dans la base de données
                          $em = $this->getDoctrine()->getManager();
                          $em->persist($oUtilisateur);
                          $em->flush();

                          $request->getSession()->getFlashBag()->add('success', 'Modification du compte : '.$oUtilisateur->getUsername().' effectuée avec succès.');

                          // On redirige vers la page de visualisation de l'annonce nouvellement créée
                          return $this->redirect($this->generateUrl('thibautg16_utilisateurs_liste'));
                        }

                        // À ce stade, le formulaire n'est pas valide car :
                        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
                        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
                        return $this->render('Thibautg16UtilisateurBundle:Utilisateur:modifier.html.twig', array(
                          'form' => $form->createView(), 'utilisateur' => $oUtilisateur));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function supprimerAction($idUtilisateur, Request $request){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // Récupération de l'objet iplb déjà existant, d'id $idIplb.
                        $oUtilisateur = $em->getRepository('Thibautg16UtilisateurBundle:Utilisateur')
                                ->find($idUtilisateur)
                        ;

                        // On supprimer le service $idServices
                        $em->remove($oUtilisateur);
                        $em->flush();

                        // On confirme la supression de l'utilisateur
                        $request->getSession()->getFlashBag()->add('success', 'Suppression du compte : '.$oUtilisateur->getUsername().' effectuée avec succès.');

                        // On redirige vers la liste des services
                        return $this->redirect($this->generateUrl('thibautg16_utilisateurs_liste'));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }
}
