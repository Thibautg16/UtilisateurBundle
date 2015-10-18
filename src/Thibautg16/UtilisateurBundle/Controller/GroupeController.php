<?php
/*
 * Thibautg16/UtilisateurBundle/Controller/GroupeController.php;
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

use Thibautg16\UtilisateurBundle\Entity\Groupe;

use Doctrine\Common\Collections\ArrayCollection;

class GroupeController extends Controller{

        public function listeAction(){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        /****** On liste tous les services ******/
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // On récupère tous les services actuellement en BDD
                        $listeGroupe = $em
                                ->getRepository('Thibautg16UtilisateurBundle:Groupe')
                                ->findAll()
                        ;

                        return $this->render('Thibautg16UtilisateurBundle:Groupe:liste.html.twig', array('listeGroupe' => $listeGroupe));
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
                        // Création de l'objet Groupe
                        $oGroupe = new Groupe();

                        // On crée le FormBuilder grâce au service form factory
                        $formBuilder = $this->get('form.factory')->createBuilder('form', $oGroupe);

                        // On ajoute les champs de l'entité que l'on veut à notre formulaire
                        $formBuilder
                          ->add('nom',     'text')
                          ->add('role',    'text')
                          ->add('Ajouter',      'submit')
                        ;

                        // À partir du formBuilder, on génère le formulaire
                        $form = $formBuilder->getForm();

                        // On fait le lien Requête <-> Formulaire
                        $form->handleRequest($request);

                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {

                          // On enregistre notre objet $oGroupe dans la base de données
                          $em = $this->getDoctrine()->getManager();                         
                          $em->persist($oGroupe);
                          $em->flush();

                          // On informe l'utilisateur de la réussite de la création du groupe
                          $request->getSession()->getFlashBag()->add('success', 'Création du groupe : '.$oGroupe->getNom().' effectuée avec succès.');

                          // On redirige vers la page de visualisation de l'annonce nouvellement créée
                          return $this->redirect($this->generateUrl('thibautg16_groupe_liste'));
                        }

                        // À ce stade, le formulaire n'est pas valide car :
                        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
                        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
                        return $this->render('Thibautg16UtilisateurBundle:Groupe:ajouter.html.twig', array(
                          'form' => $form->createView(), 'groupe' => $oGroupe));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function modifierAction($idGroupe, Request $request){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        /****** On recherche les informations sur le service demandé ******/
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // Récupération de l'objet groupe déjà existant, d'id $idGroupe.
                        $oGroupe= $em->getRepository('Thibautg16UtilisateurBundle:Groupe')->findOneById($idGroupe);

                        // On crée le FormBuilder grâce au service form factory
                        $formBuilder = $this->get('form.factory')->createBuilder('form', $oGroupe);

                        // On ajoute les champs de l'entité que l'on veut à notre formulaire
                        $formBuilder
                          ->add('nom',     'text')
                          ->add('role',    'text')
                          ->add('Modifier',      'submit')
                        ;

                        // À partir du formBuilder, on génère le formulaire
                        $form = $formBuilder->getForm();

                        // On fait le lien Requête <-> Formulaire
                        // À partir de maintenant, la variable $produit contient les valeurs entrées dans le formulaire par le visiteur
                        $form->handleRequest($request);

                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {

                          // On l'enregistre notre objet $oGroupe dans la base de données
                          $em->persist($oGroupe);
                          $em->flush();

                          $request->getSession()->getFlashBag()->add('success', 'Modification du groupe : '.$oGroupe->getNom().' effectuée avec succès.');

                          // On redirige vers la page de visualisation des groupes
                          return $this->redirect($this->generateUrl('thibautg16_groupe_liste'));
                        }

                        // À ce stade, le formulaire n'est pas valide car :
                        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
                        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
                        return $this->render('Thibautg16UtilisateurBundle:Groupe:modifier.html.twig', array(
                          'form' => $form->createView(), 'groupe' => $oGroupe));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function supprimerAction($idGroupe, Request $request){
                $em = $this->getDoctrine()->getManager();
                // On vérifie si l'utilisateur (via les groupes) est autorisé à consulter cette page
                if($em->getRepository('Thibautg16UtilisateurBundle:Groupe')->GroupeAutoriseRoute($this->getUser(), $this->container->get('request')->get('_route')) == TRUE){
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // Récupération de l'objet Groupe déjà existant, d'id $idGroupe.
                        $oGroupe = $em->getRepository('Thibautg16UtilisateurBundle:Groupe')->findOneById($idGroupe);
                        
                        // On supprimer le groupe $idGroupe
                        $em->remove($oGroupe);
                        $em->flush();

                        // On confirme la supression du groupe
                        $request->getSession()->getFlashBag()->add('success', 'Suppression du groupe : '.$oGroupe->getNom().' effectuée avec succès.');

                        // On redirige vers la liste des groupes
                        return $this->redirect($this->generateUrl('thibautg16_groupe_liste'));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }
}
