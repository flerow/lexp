<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
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
     * @Assert\NotBlank(message="Wypełnij to pole")
     * @Assert\Length(max="20", maxMessage="W tym pole może być tylko 20 znaków, wpisuj tagi pojedynczo!")
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="tags")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Research", inversedBy="tags")
     */
    private $researches;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->researches = new ArrayCollection();

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
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Tag
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
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
     * Add researches
     *
     * @param \AppBundle\Entity\Research $researches
     * @return Tag
     */
    public function addResearch(\AppBundle\Entity\Research $researches)
    {
        $this->researches[] = $researches;

        return $this;
    }

    /**
     * Remove researches
     *
     * @param \AppBundle\Entity\Research $researches
     */
    public function removeResearch(\AppBundle\Entity\Research $researches)
    {
        $this->researches->removeElement($researches);
    }

    /**
     * Get researches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResearches()
    {
        return $this->researches;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
