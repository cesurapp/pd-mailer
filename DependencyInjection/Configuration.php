<?php

/**
 * This file is part of the pdAdmin pdMailer package.
 *
 * @package     pdMailer
 *
 * @author      Ramazan APAYDIN <iletisim@ramazanapaydin.com>
 * @copyright   Copyright (c) 2018 Ramazan APAYDIN
 * @license     LICENSE
 *
 * @link        https://github.com/rmznpydn/pd-mailer
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
                ->integerNode('list_count')->defaultValue(30)->end()
                ->arrayNode('active_language')->scalarPrototype()->end()->defaultValue(['en'])->end()
            ->end();

        return $treeBuilder;
    }
}
