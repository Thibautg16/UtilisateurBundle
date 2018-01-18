<?php
/*
 * Thibautg16/UtilisateurBundle/Entity/GroupeRepository.php;
 *
 * Copyright 2017 GILLARDEAU Thibaut (aka Thibautg16)
 *
 * Authors :
 *  - Gillardeau Thibaut (aka Thibautg16)
 */
 

namespace Thibautg16\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="Thibautg16\UtilisateurBundle\Repository\RoleRepository")
 */
class Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Groupe", mappedBy="roles_groupe")
     */
    private $groupes_role;    


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Role
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
     * Constructor
     */
    public function __construct()
    {
        $this->groupes_role = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add groupesRole
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Groupe $groupesRole
     *
     * @return Role
     */
    public function addGroupesRole(\Thibautg16\UtilisateurBundle\Entity\Groupe $groupesRole)
    {
        $this->groupes_role[] = $groupesRole;

        return $this;
    }

    /**
     * Remove groupesRole
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Groupe $groupesRole
     */
    public function removeGroupesRole(\Thibautg16\UtilisateurBundle\Entity\Groupe $groupesRole)
    {
        $this->groupes_role->removeElement($groupesRole);
    }

    /**
     * Get groupesRole
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupesRole()
    {
        return $this->groupes_role;
    }
}
