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
                $oGroupe = new Groupe();

                // creation du formulaire
                $form = $this->createForm(GroupeType::class, $oGroupe);  

                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('nom',     TextType::class)
                        ->add('role',    TextType::class)
                        ->add('ajouter', SubmitType::class)
                ;

                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        if ($form->isValid()) {
                                // On enregistre notre objet                       
                                $em->persist($oGroupe);
                                $em->flush();

                                // On informe l'utilisateur de la réussite de la création de l'objet
                                $request->getSession()->getFlashBag()->add('success', 'Création du groupe : '.$oGroupe->getNom().' effectuée avec succès.');

                                // On redirige vers la liste des groupes
                                return $this->redirect($this->generateUrl('thibautg16_groupe_lister'));
                        }
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Groupe:ajouter.html.twig', array(
                          'form' => $form->createView(), 'groupe' => $oGroupe));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */        
        public function modifierAction($idGroupe, Request $request){
                $em = $this->getDoctrine()->getManager();
   
                // on récupére l'objet
                $oGroupe= $em->getRepository('Thibautg16UtilisateurBundle:Groupe')->findOneById($idGroupe);

                // creation du formulaire
                $form = $this->createForm(GroupeType::class, $oGroupe);  

                // On ajoute les champs de l'entité que l'on veut à notre formulaire
                $form
                        ->add('nom',      TextType::class)
                        ->add('role',     TextType::class)
                        ->add('modifier', SubmitType::class)
                ;

                // On fait le lien Requête <-> Formulaire
                $form->handleRequest($request);

                // On vérifie que les valeurs entrées sont correctes
                if ($form->isSubmitted()) {
                        if ($form->isValid()) {
                                // On enregistre notre objet
                                $em->persist($oGroupe);
                                $em->flush();

                                $request->getSession()->getFlashBag()->add('success', 'Modification du groupe : '.$oGroupe->getNom().' effectuée avec succès.');

                                // On redirige vers la liste des groupes
                                return $this->redirect($this->generateUrl('thibautg16_groupe_lister'));
                        }
                }

                // Le formulaire n'est pas valide, donc on l'affiche de nouveau
                return $this->render('Thibautg16UtilisateurBundle:Groupe:modifier.html.twig', array(
                        'form' => $form->createView(), 'groupe' => $oGroupe));
        }

        /**
         * @Security("has_role('ROLE_SUPERADMIN')")
         */  
        public function supprimerAction($idGroupe, Request $request){
                $em = $this->getDoctrine()->getManager();

                // on récupére l'objet
                $oGroupe = $em->getRepository('Thibautg16UtilisateurBundle:Groupe')->findOneById($idGroupe);
                        
                // on supprimer l'objet'
                $em->remove($oGroupe);
                $em->flush();

                // on confirme la supression du groupe
                $request->getSession()->getFlashBag()->add('success', 'Suppression du groupe : '.$oGroupe->getNom().' effectuée avec succès.');

                // on redirige vers la liste des groupes
                return $this->redirect($this->generateUrl('thibautg16_groupe_lister'));
        }
}
