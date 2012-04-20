<?php

namespace Ordr\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ordr\DataBundle\Entity\Ordr
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ordr\DataBundle\Entity\OrdrRepository")
 */
class Ordr
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
   * @var string $username
   *
   * @ORM\Column(name="username", type="string", length=255)
   * @Assert\NotBlank()
   */
  private $username;

  /**
   * @var decimal $amount
   *
   * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
   * @Assert\NotBlank()
   */
  private $amount;

  /**
   * @var decimal $price
   *
   * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
   * @Assert\NotBlank()
   */
  private $price;

  /**
   * @var text $extra
   *
   * @ORM\Column(name="extra", type="text")
   * @Assert\NotBlank()
   */
  private $extra;

  /**
   * @var boolean $public
   *
   * @ORM\Column(name="public", type="boolean")
   */
  private $public;

  /**
   * @var boolean $checked
   *
   * @ORM\Column(name="checked", type="boolean", nullable=true)
   */
  private $checked;

  /**
   * @var \DateTime $created_at
   *
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $created_at;

  /**
   * @var string $session
   *
   * @ORM\Column(name="session", type="string", length=255)
   */
  private $session;

  /**
   * @ORM\ManyToOne(targetEntity="OrdrMeta", inversedBy="ordrs")
   * @ORM\JoinColumn(name="ordr_id", referencedColumnName="id")
   */
  protected $ordr;


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
   * Set username
   *
   * @param string $username
   */
  public function setUsername($username)
  {
    $this->username = $username;
  }

  /**
   * Get username
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Set amount
   *
   * @param decimal $amount
   */
  public function setAmount($amount)
  {
    $this->amount = $amount;
  }

  /**
   * Get amount
   *
   * @return decimal
   */
  public function getAmount()
  {
    return $this->amount;
  }

  /**
   * Set extra
   *
   * @param text $extra
   */
  public function setExtra($extra)
  {
    $this->extra = $extra;
  }

  /**
   * Get extra
   *
   * @return text
   */
  public function getExtra()
  {
    return $this->extra;
  }

  /**
   * Set public
   *
   * @param boolean $public
   */
  public function setPublic($public)
  {
    $this->public = $public;
  }

  /**
   * Get public
   *
   * @return boolean
   */
  public function getPublic()
  {
    return $this->public;
  }

  /**
   * Set created_at
   *
   * @param \DateTime $createdAt
   */
  public function setCreatedAt(\DateTime $createdAt)
  {
    $this->created_at = $createdAt;
  }

  /**
   * Get created_at
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * Set session
   *
   * @param string $session
   */
  public function setSession($session)
  {
    $this->session = $session;
  }

  /**
   * Get session
   *
   * @return string
   */
  public function getSession()
  {
    return $this->session;
  }

  /**
   * Set ordr
   *
   * @param \Ordr\DataBundle\Entity\OrdrMeta $ordr
   */
  public function setOrdr(\Ordr\DataBundle\Entity\OrdrMeta $ordr)
  {
    $this->ordr = $ordr;
  }

  /**
   * Get ordr
   *
   * @return \Ordr\DataBundle\Entity\OrdrMeta
   */
  public function getOrdr()
  {
    return $this->ordr;
  }

  /**
   * @return boolean
   */
  public function getChecked()
  {
    return $this->checked;
  }

  /**
   * @param boolean $checked
   */
  public function setChecked($checked)
  {
    $this->checked = $checked;
  }

  /**
   * @return \Ordr\DataBundle\Entity\decimal
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * @param \Ordr\DataBundle\Entity\decimal $price
   */
  public function setPrice($price)
  {
    $this->price = $price;
  }
}