<?php
/*
 * Thibautg16/UtilisateurBundle/Controller/RomeController.php;
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

use Thibautg16\UtilisateurBundle\Entity\Role;
use Thibautg16\UtilisateurBundle\Form\RoleType;

use Doctrine\Common\Collections\ArrayCollection;

class RoleController extends Controller{

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */
        public function listerAction(){
                $em = $this->getDoctrine()->getManager();

                // on liste les objets
                $listeRole = $em->getRepository('Thibautg16UtilisateurBundle:Role')->findAll();

                return $this->render('Thibautg16UtilisateurBundle:Role:lister.html.twig', array('listeRole' => $listeRole));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */
        public function ajouterAction(Request $request){
                $em = $this->getDoctrine()->getManager();

                // création de l'objet
                $oRole = new Role();

                // creation du formulaire
                $form = $this->createForm(RoleType::class, $oRole);  

                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('nom',     TextType::class)
                        ->add('ajouter', SubmitType::class)
                ;

                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        if ($form->isValid()) {
                                // On enregistre notre objet                       
                                $em->persist($oRole);
                                $em->flush();

                                // On informe l'utilisateur de la réussite de la création de l'objet
                                $request->getSession()->getFlashBag()->add('success', 'Création du role : '.$oRole->getNom().' effectuée avec succès.');

                                // On redirige vers la liste des roles
                                return $this->redirect($this->generateUrl('thibautg16_role_lister'));
                        }
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Role:ajouter.html.twig', array(
                          'form' => $form->createView(), 'role' => $oRole));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */        
        public function modifierAction($idRole, Request $request){
                $em = $this->getDoctrine()->getManager();
   
                // on récupére l'objet
                $oRole= $em->getRepository('Thibautg16UtilisateurBundle:Role')->findOneById($idRole);

                // creation du formulaire
                $form = $this->createForm(RoleType::class, $oRole);  

                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('nom',      TextType::class)
                        ->add('modifier', SubmitType::class)
                ;

                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        if ($form->isValid()) {
                                // On enregistre notre objet
                                $em->persist($oRole);
                                $em->flush();

                                $request->getSession()->getFlashBag()->add('success', 'Modification du Role : '.$oRole->getNom().' effectuée avec succès.');

                                // On redirige vers la liste des roles
                                return $this->redirect($this->generateUrl('thibautg16_role_lister'));
                        }
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Role:modifier.html.twig', array(
                        'form' => $form->createView(), 'role' => $oRole));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */  
        public function supprimerAction($idRole, Request $request){
                $em = $this->getDoctrine()->getManager();

                // on récupére l'objet
                $oRole = $em->getRepository('Thibautg16UtilisateurBundle:Role')->findOneById($idRole);
                        
                // on supprime l'objet
                $em->remove($oRole);
                $em->flush();

                // on confirme la supression du role
                $request->getSession()->getFlashBag()->add('success', 'Suppression du role : '.$oRole->getNom().' effectuée avec succès.');

                // on redirige vers la liste des roles
                return $this->redirect($this->generateUrl('thibautg16_role_lister'));
        }
}

