<?php

namespace PostBundle;

use PostBundle\DependencyInjection\PostBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PostBundle extends Bundle
{
    /**
     * @return PostBundleExtension
     */
    public function getContainerExtension()
    {
        return new PostBundleExtension();
    }
}
