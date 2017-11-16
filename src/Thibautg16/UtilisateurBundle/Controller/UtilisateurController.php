<?php
/*
 * Thibautg16/UtilisateurBundle/Controller/UtilisateurController.php;
 *
 * Copyright 2017 GILLARDEAU Thibaut (aka Thibautg16)
 *
 * Authors :
 *  - Gillardeau Thibaut (aka Thibautg16)
 */

namespace Thibautg16\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Thibautg16\UtilisateurBundle\Entity\Utilisateur;
use Thibautg16\UtilisateurBundle\Form\UtilisateurType;

use Doctrine\Common\Collections\ArrayCollection;

class UtilisateurController extends Controller{

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */
        public function listerAction(){
                $em = $this->getDoctrine()->getManager();

                // on liste les objets
                $listeUtilisateur = $em->getRepository('Thibautg16UtilisateurBundle:Utilisateur')->findAll();

                return $this->render('Thibautg16UtilisateurBundle:Utilisateur:lister.html.twig', array('listeUtilisateur' => $listeUtilisateur));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */        
        public function ajouterAction(Request $request){
                $em = $this->getDoctrine()->getManager();

                // création de l'objet
                $oUtilisateur = new Utilisateur();

                // creation du formulaire
                $form = $this->createForm(UtilisateurType::class, $oUtilisateur);    

                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('username', TextType::class)
                        ->add('email',    TextType::class)
                        ->add('password', RepeatedType::class, array('first_name' => 'password', 'second_name' => 'confirm','type' => PasswordType::class))
                        ->add('active',   CheckboxType::class, array('required' => false))
                        ->add('groupes',  EntityType::class, array('class' => 'Thibautg16UtilisateurBundle:Groupe', 'choice_label' => 'nom', 'multiple' => true))
                        ->add('ajouter',  SubmitType::class)
                ;

                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        if ($form->isValid()) {
                                // On enregistre notre objet
                                $em = $this->getDoctrine()->getManager();
                                //$oUtilisateur->setCreePar($user);
                          
                                $factory = $this->container->get('security.encoder_factory');                              
                                $encoder = $factory->getEncoder($oUtilisateur);
                                $password = $encoder->encodePassword($oUtilisateur->getPassword(), $oUtilisateur->getSalt());
                                $oUtilisateur->setPassword($password);                          

                                $em->persist($oUtilisateur);
                                $em->flush();

                                $request->getSession()->getFlashBag()->add('success', 'Création du compte : '.$oUtilisateur->getUsername().' effectuée avec succès.');

                                // On redirige vers la liste des utilisateurs
                                return $this->redirect($this->generateUrl('thibautg16_utilisateur_lister'));
                        }
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Utilisateur:ajouter.html.twig', array(
                        'form' => $form->createView(), 'utilisateur' => $oUtilisateur));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */  
        public function modifierAction($idUtilisateur, Request $request){
                $em = $this->getDoctrine()->getManager();

                // on récupére l'objet
                $oUtilisateur = $em->getRepository('Thibautg16UtilisateurBundle:Utilisateur')->findOneById($idUtilisateur);

                // On crée le FormBuilder grâce au service form factory
                $form = $this->createForm(UtilisateurType::class, $oUtilisateur);    
                
                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('username',  TextType::class)
                        ->add('email',     TextType::class)
                        ->add('password',  RepeatedType::class, array('first_name' => 'password', 'second_name' => 'confirm','type' => PasswordType::class))
                        ->add('active',    CheckboxType::class, array('required' => false))
                        ->add('groupes',   EntityType::class, array('class' => 'Thibautg16UtilisateurBundle:Groupe', 'choice_label' => 'nom', 'multiple' => true))
                        ->add('modifier',  SubmitType::class)
                ;

                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        // On vérifie que les valeurs entrées sont correctes
                        if ($form->isValid()) {
                                // On enregistre notre objet
                                $em->persist($oUtilisateur);
                                $em->flush();

                                $request->getSession()->getFlashBag()->add('success', 'Modification du compte : '.$oUtilisateur->getUsername().' effectuée avec succès.');

                                // On redirige vers la liste des utilisateurs
                                return $this->redirect($this->generateUrl('thibautg16_utilisateur_lister'));
                        }
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Utilisateur:modifier.html.twig', array(
                        'form' => $form->createView(), 'utilisateur' => $oUtilisateur));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */  
        public function supprimerAction($idUtilisateur, Request $request){
                $em = $this->getDoctrine()->getManager();

                // on récupére l'objet
                $oUtilisateur = $em->getRepository('Thibautg16UtilisateurBundle:Utilisateur')->find($idUtilisateur);

                // on supprime l'objet
                $em->remove($oUtilisateur);
                $em->flush();

                // On confirme la supression de l'utilisateur
                $request->getSession()->getFlashBag()->add('success', 'Suppression du compte : '.$oUtilisateur->getUsername().' effectuée avec succès.');

                // On redirige vers la liste des utilisateurs
                return $this->redirect($this->generateUrl('thibautg16_utilisateur_lister'));
        }
}
