<?php

namespace pos\CoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="pos\CoreBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


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
     * @ORM\ManyToOne(targetEntity="pos\CoreBundle\Entity\Retailer", cascade={"persist"}, inversedBy="user")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $retailer;

    /**
     * @return mixed
     */
    public function getRetailer()
    {
        return $this->retailer;
    }

    /**
     * @param mixed $retailer
     */
    public function setRetailer($retailer = null)
    {
        $this->retailer = $retailer;
    }

}

