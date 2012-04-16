<?php

namespace Ordr\DataBundle\Entity;

use \Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ordr\DataBundle\Entity\OrdrMeta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ordr\DataBundle\Entity\OrdrMetaRepository")
 */
class OrdrMeta
{
  /**
   * @var integer $id
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string $name
   *
   * @ORM\Column(name="name", type="string", length=255)
   * @Assert\NotBlank()
   */
  private $name;

  /**
   * @var date $next_ordr
   *
   * @ORM\Column(name="next_ordr", type="date")
   * @Assert\NotBlank()
   */
  private $next_ordr;

  /**
   * @var string $token
   *
   * @ORM\Column(name="token", type="string", unique=true, length=255)
   */
  private $token;

  /**
   * @var boolean $public
   *
   * @ORM\Column(name="public", type="boolean", nullable=true)
   */
  private $public;

  /**
   * @var string $admin_token
   *
   * @ORM\Column(name="admin_token", type="string", length=255)
   */
  private $admin_token;

  /**
   * @var \Doctrine\Common\Collections\ArrayCollection $ordrs
   *
   * @ORM\OneToMany(targetEntity="Ordr", mappedBy="ordr")
   */
  private $ordrs;

  /**
   * @var datetime $created_at
   *
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $created_at;

  public function __construct()
  {
    $this->ordr = new ArrayCollection();
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
   */
  public function setName($name)
  {
    $this->name = $name;
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
   * Set next_ordr
   *
   * @param Date $nextOrdr
   */
  public function setNextOrdr($nextOrdr)
  {
    $this->next_ordr = $nextOrdr;
  }

  /**
   * Get next_ordr
   *
   * @return date
   */
  public function getNextOrdr()
  {
    return $this->next_ordr;
  }

  /**
   * Set token
   *
   * @param string $token
   */
  public function setToken($token)
  {
    $this->token = $token;
  }

  /**
   * Get token
   *
   * @return string
   */
  public function getToken()
  {
    return $this->token;
  }

  /**
   * Set ordrs
   *
   * @param \Doctrine\Common\Collections\ArrayCollection $ordrs
   */
  public function setOrdrs($ordrs)
  {
    $this->ordrs = $ordrs;
  }

  /**
   * Get ordrs
   *
   * @return \Doctrine\Common\Collections\ArrayCollection
   */
  public function getOrdrs()
  {
    return $this->ordrs;
  }

  /**
   * Add ordrs
   *
   * @param Ordr\DataBundle\Entity\Ordr $ordrs
   */
  public function addOrdr(\Ordr\DataBundle\Entity\Ordr $ordrs)
  {
    $this->ordrs[] = $ordrs;
  }

  function __toString()
  {
    return $this->getName();
  }


  /**
   * Set admin_token
   *
   * @param string $adminToken
   */
  public function setAdminToken($adminToken)
  {
    $this->admin_token = $adminToken;
  }

  /**
   * Get admin_token
   *
   * @return string
   */
  public function getAdminToken()
  {
    return $this->admin_token;
  }

  /**
   * @return boolean
   */
  public function getPublic()
  {
    return $this->public;
  }

  /**
   * @param boolean $public
   */
  public function setPublic($public)
  {
    $this->public = $public;
  }

  /**
   * @return datetime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * @param \DateTime $created_at
   */
  public function setCreatedAt($created_at)
  {
    $this->created_at = $created_at;
  }
}