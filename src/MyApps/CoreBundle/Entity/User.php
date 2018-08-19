<?php

namespace MyApps\CoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser {
    /**
     * @var int
     */
    protected $id;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}

