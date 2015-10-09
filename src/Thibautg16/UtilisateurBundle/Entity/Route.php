<?php
/*
 * Thibautg16/UtilisateurBundle/Entity/Route.php;
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
 * Route
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Route {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(name="nom", type="string", length=100) */
    private $nom;

    /** @ORM\Column(name="route", type="string", length=255, unique=true) */
    private $route;

    /** @ORM\ManyToMany(targetEntity="Groupe", mappedBy="routes") */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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
     * @return Route
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
     * Set route
     *
     * @param string route
     * @return Route    
     */
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Add groupes
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Groupe $groupes
     * @return Route
     */
    public function addGroupe(\Thibautg16\UtilisateurBundle\Entity\Groupe $groupes)
    {
        $this->groupes[] = $groupes;

        return $this;
    }

    /**
     * Remove groupes
     *
     * @param \Thibautg16\UtilisateurBundle\Entity\Groupe $groupes
     */
    public function removeGroupe(\Thibautg16\UtilisateurBundle\Entity\Groupe $groupes)
    {
        $this->groupes->removeElement($groupes);
    }

    /**
     * Get groupes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupes()
    {
        return $this->groupes;
    }
}
