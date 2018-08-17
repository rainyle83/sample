<?php

namespace MyApps\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyAppsAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
