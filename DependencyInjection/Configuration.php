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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pd_mailer');

        // Set Configuration
        $rootNode
            ->children()
                ->booleanNode('logger_active')->defaultTrue()->end()
                ->booleanNode('template_active')->defaultTrue()->end()
                ->scalarNode('sender_address')->defaultValue('pdadmin@example.com')->end()
                ->scalarNode('sender_name')->defaultValue('pdAdmin')->end()
                ->integerNode('list_count')
                    ->beforeNormalization()->ifString()->then(function ($val) { return (int) $val; })->end()
                ->end()
                ->arrayNode('active_language')->scalarPrototype()->end()->defaultValue(['en'])->end()
            ->end();

        return $treeBuilder;
    }
}
