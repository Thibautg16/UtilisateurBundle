<?php
/*
 * Thibautg16/UtilisateurBundle/Entity/Groupe.php;
 *
 * Copyright 2015 GILLARDEAU Thibaut (aka Thibautg16)
 *
 * Authors :
 *  - Gillardeau Thibaut (aka Thibautg16)
 */
 
namespace Thibautg16\UtilisateurBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Thibautg16\UtilisateurBundle\Entity\GroupeRepository")
 */
class Groupe
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(name="nom", type="string", length=50) */
    private $nom;

    /** @ORM\ManyToMany(targetEntity="Utilisateur", mappedBy="groupes") */
    private $users;
 
    /** @ORM\ManyToMany(targetEntity="Role", inversedBy="groupes_role") */
    private $roles_groupe;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->roles_groupe = new ArrayCollection();
    }

   /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string nom
     * @return Groupe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * Add users
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Utilisateur $users
     * @return Groupe
     */
    public function addUser(\Thibautg16\UtilisateurBundle\Entity\Utilisateur $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Utilisateur $users
     */
    public function removeUser(\Thibautg16\UtilisateurBundle\Entity\Utilisateur $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add rolesGroupe
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Role $rolesGroupe
     *
     * @return Groupe
     */
    public function addRolesGroupe(\Thibautg16\UtilisateurBundle\Entity\Role $rolesGroupe)
    {
        $this->roles_groupe[] = $rolesGroupe;

        return $this;
    }

    /**
     * Remove rolesGroupe
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Role $rolesGroupe
     */
    public function removeRolesGroupe(\Thibautg16\UtilisateurBundle\Entity\Role $rolesGroupe)
    {
        $this->roles_groupe->removeElement($rolesGroupe);
    }

    /**
     * Get rolesGroupe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRolesGroupe()
    {
        return $this->roles_groupe;
    }
}
