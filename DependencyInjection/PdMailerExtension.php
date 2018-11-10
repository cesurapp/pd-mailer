<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 *
 * @license     LICENSE
 * @author      Kerem APAYDIN <kerem@apaydin.me>
 *
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class PdMailerExtension extends Extension
{
    /**
     * Load Bundle Config and Services.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load Configuration
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Set Parameters
        $container->setParameter('pd_mailer.logger_active', $config['logger_active']);
        $container->setParameter('pd_mailer.template_active', $config['template_active']);
        $container->setParameter('pd_mailer.sender_address', $config['sender_address']);
        $container->setParameter('pd_mailer.sender_name', $config['sender_name']);
        $container->setParameter('pd_mailer.list_count', $config['list_count']);
        $container->setParameter('pd_mailer.active_language', $config['active_language']);

        // Load Services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
