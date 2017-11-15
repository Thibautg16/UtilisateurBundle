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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Thibautg16\UtilisateurBundle\Entity\Utilisateur;
use Thibautg16\UtilisateurBundle\Form\UtilisateurType;

use Doctrine\Common\Collections\ArrayCollection;

class UtilisateurController extends Controller{

        /**
         * @Security("has_role('ROLE_UTILISATEUR_ADMIN')")
         */
        public function listerAction(){
                $em = $this->getDoctrine()->getManager();
                $listeUtilisateur = $em->getRepository('UtilisateurBundle:Utilisateur')->findAll();

                return $this->render('UtilisateurBundle:Utilisateur:lister.html.twig', array('listeUtilisateur' => $listeUtilisateur));
        }

        /**
         * @Security("has_role('ROLE_UTILISATEUR_ADMIN')")
         */        
        public function ajouterAction(Request $request){
                $em = $this->getDoctrine()->getManager();

                // Création de l'objet
                $oUtilisateur = new Utilisateur();

                // On crée le FormBuilder grâce au service form factory
                $form = $this->createForm(UtilisateurType::class, $oUtilisateur);    

                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('username', TextType::class)
                        ->add('email',    TextType::class)
                        ->add('password', RepeatedType::class, array('first_name' => 'password', 'second_name' => 'confirm','type' => PasswordType::class))
                        ->add('Active',   CheckboxType::class, array('required' => false))
                        ->add('Groupes',  EntityType::class, array('class' => 'Thibautg16UtilisateurBundle:Groupe', 'property' => 'nom', 'multiple' => true))
                        ->add('Ajouter',  SubmitType::class)
                ;


                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        if ($form->isValid()) {

                        // On enregistre notre objet $oServices dans la base de données
                        $em = $this->getDoctrine()->getManager();
                        //$oUtilisateur->setCreePar($user);
                          
                        $factory = $this->container->get('security.encoder_factory');                              
                        $encoder = $factory->getEncoder($oUtilisateur);
                        $password = $encoder->encodePassword($oUtilisateur->getPassword(), $oUtilisateur->getSalt());
                        $oUtilisateur->setPassword($password);                          
			$oUtilisateur->setRoles('ROLE_UTILISATEUR'); 

                        $em->persist($oUtilisateur);
                        $em->flush();

                        $request->getSession()->getFlashBag()->add('success', 'Création du compte : '.$oUtilisateur->getUsername().' effectuée avec succès.');

                        // On redirige vers la page de visualisation de l'annonce nouvellement créée
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_lister'));
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Utilisateur:ajouter.html.twig', array('form' => $form->createView(), 'utilisateur' => $oUtilisateur));
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
                          return $this->redirect($this->generateUrl('thibautg16_utilisateur_liste'));
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
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_liste'));
                }
                // Ici, $user est une instance de notre classe User mais n'est pas Admin
                else{
                        return $this->redirect($this->generateUrl('thibautg16_utilisateur_homepage'));
                }
        }
}
