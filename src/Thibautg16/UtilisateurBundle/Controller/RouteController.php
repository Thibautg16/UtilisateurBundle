<?php
/*
 * Thibautg16/UtilisateurBundle/Controller/RouteController.php;
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

use Thibautg16\UtilisateurBundle\Entity\Route;

use Doctrine\Common\Collections\ArrayCollection;

class RouteController extends Controller{

        public function listeAction(){
                // On vérifie si l'utilisateur est autorisé à consulter cette page
                if($this->get('security.authorization_checker')->isGranted('ROLE_UTILISATEUR_ADMIN')) {
                        /****** On liste tous les services ******/
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // On récupère tous les services actuellement en BDD
                        $listeRoute = $em->getRepository('Thibautg16UtilisateurBundle:Route')->findAll();

                        return $this->render('Thibautg16UtilisateurBundle:Route:liste.html.twig', array('listeRoute' => $listeRoute));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function ajouterAction(Request $request){
                // On récupére les informations de l'utilisateur
                $user = $this->getUser();

                // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
                if (null === $user) {
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
                // Ici, $user est une instance de notre classe User et a le role d'Admin
                elseif($user) {
                        // Création de l'objet Route
                        $oRoute = new Route();

                        // On crée le FormBuilder grâce au service form factory
                        $formBuilder = $this->get('form.factory')->createBuilder('form', $oRoute);

                        // On ajoute les champs de l'entité que l'on veut à notre formulaire
                        $formBuilder
                          ->add('nom',     'text')
                          ->add('route',    'text')
                          ->add('Ajouter',      'submit')
                        ;

                        // À partir du formBuilder, on génère le formulaire
                        $form = $formBuilder->getForm();

                        // On fait le lien Requête <-> Formulaire
                        $form->handleRequest($request);

                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {

                          // On enregistre notre objet $oRoute dans la base de données
                          $em = $this->getDoctrine()->getManager();                         
                          $em->persist($oRoute);
                          $em->flush();

                          // On informe l'utilisateur de la réussite de la création du route
                          $request->getSession()->getFlashBag()->add('success', 'Création de la route : '.$oRoute->getNom().' effectuée avec succès.');

                          // On redirige vers la page de visualisation de l'annonce nouvellement créée
                          return $this->redirect($this->generateUrl('thibautg16_route_liste'));
                        }

                        // À ce stade, le formulaire n'est pas valide car :
                        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
                        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
                        return $this->render('Thibautg16UtilisateurBundle:Route:ajouter.html.twig', array(
                          'form' => $form->createView(), 'route' => $oRoute));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function modifierAction($idRoute, Request $request){
                // On récupére les informations de l'utilisateur
                $user = $this->getUser();

                // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
                if (null === $user) {
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
                // Ici, $user est une instance de notre classe User et est un Admin
                elseif($user) {
                        /****** On recherche les informations sur le service demandé ******/
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // Récupération de l'objet route déjà existant, d'id $idRoute.
                        $oRoute= $em->getRepository('Thibautg16UtilisateurBundle:Route')->findOneById($idRoute);

                        // On crée le FormBuilder grâce au service form factory
                        $formBuilder = $this->get('form.factory')->createBuilder('form', $oRoute);

                        // On ajoute les champs de l'entité que l'on veut à notre formulaire
                        $formBuilder
                          ->add('nom',     'text')
                          ->add('route',    'text')
                          ->add('Modifier',      'submit')
                        ;

                        // À partir du formBuilder, on génère le formulaire
                        $form = $formBuilder->getForm();

                        // On fait le lien Requête <-> Formulaire
                        // À partir de maintenant, la variable $produit contient les valeurs entrées dans le formulaire par le visiteur
                        $form->handleRequest($request);

                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {

                          // On l'enregistre notre objet $oRoute dans la base de données
                          $em->persist($oRoute);
                          $em->flush();

                          $request->getSession()->getFlashBag()->add('success', 'Modification de la Route : '.$oRoute->getNom().' effectuée avec succès.');

                          // On redirige vers la page de visualisation des routes
                          return $this->redirect($this->generateUrl('thibautg16_route_liste'));
                        }

                        // À ce stade, le formulaire n'est pas valide car :
                        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
                        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
                        return $this->render('Thibautg16UtilisateurBundle:Route:modifier.html.twig', array(
                          'form' => $form->createView(), 'route' => $oRoute));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }

        public function supprimerAction($idRoute, Request $request){
                // On récupére les informations de l'utilisateur
                $user = $this->getUser();

                // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
                if (null === $user) {
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
                // Ici, $user est une instance de notre classe User et est un Admin
                elseif($user) {
                        // On prépare la connexion avec la bdd
                        $em = $this->getDoctrine()->getManager();

                        // Récupération de l'objet Route déjà existant, d'id $idRoute.
                        $oRoute = $em->getRepository('Thibautg16UtilisateurBundle:Route')->findOneById($idRoute);
                        
                        // On supprimer la route d'$idRoute
                        $em->remove($oRoute);
                        $em->flush();

                        // On confirme la supression de la route
                        $request->getSession()->getFlashBag()->add('success', 'Suppression de la route : '.$oRoute->getNom().' effectuée avec succès.');

                        // On redirige vers la liste des routes
                        return $this->redirect($this->generateUrl('thibautg16_route_liste'));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }
}
