<?php

namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use UserBundle\DependencyInjection\UserBundleExtension;

class UserBundle extends Bundle
{
    /**
     * @return UserBundleExtension
     */
    public function getContainerExtension()
    {
        return new UserBundleExtension();
    }
}