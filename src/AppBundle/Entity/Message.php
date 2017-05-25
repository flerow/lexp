<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Research", inversedBy="messages")
     * @ORM\JoinColumn(name="research_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $research;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="messages")
     */
    private $user;

    /**
     * @ORM\Column(name="sender", type="boolean")
     */
    private $sender;

    /**
     * @ORM\Column(name="access", type="integer")
     */
    private $access;

    public function __construct()
    {
        $this->access=3;
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
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Message
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set research
     *
     * @param \AppBundle\Entity\Research $research
     * @return Message
     */
    public function setResearch(\AppBundle\Entity\Research $research = null)
    {
        $this->research = $research;

        return $this;
    }

    /**
     * Get research
     *
     * @return \AppBundle\Entity\Research 
     */
    public function getResearch()
    {
        return $this->research;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set sender
     *
     * @param boolean $sender
     * @return Message
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return boolean 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set access
     *
     * @param integer $access
     * @return Message
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return integer 
     */
    public function getAccess()
    {
        return $this->access;
    }
}
