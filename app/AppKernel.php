<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

//            new AppBundle\AppBundle(),
//            new MyApps\WebBundle\MyAppsWebBundle(),
//            new MyApps\CoreBundle\MyAppsCoreBundle(),
//            new MyApps\AdminBundle\MyAppsAdminBundle(),
//            new MyApps\ApiBundle\MyAppsApiBundle(),

//            https://symfony.com/doc/master/bundles/FOSUserBundle/index.html
            new FOS\UserBundle\FOSUserBundle(),
//            https://jmsyst.com/bundles/JMSSerializerBundle
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
//            http://jmsyst.com/bundles/JMSSerializerBundle
            new JMS\SerializerBundle\JMSSerializerBundle(),
//            https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#getting-started
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),

//            https://knpuniversity.com/blog/KnpUOAuth2ClientBundle
            new KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle(),
            new pos\CoreBundle\CoreBundle(),
            new pos\APIBundle\APIBundle(),
            new pos\CMSBundle\CMSBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->setParameter('container.autowiring.strict_mode', true);
            $container->setParameter('container.dumper.inline_class_loader', true);

            $container->addObjectResource($this);
        });
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
