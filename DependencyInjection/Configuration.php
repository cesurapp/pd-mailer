<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\DependencyInjection;

use Pd\MailerBundle\Form\TemplateForm;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('pd_mailer');
        $rootNode = $treeBuilder->getRootNode();

        // Set Configuration
        $rootNode
            ->children()
                ->booleanNode('logger_active')->defaultTrue()->end()
                ->scalarNode('mail_template_type')->defaultValue(TemplateForm::class)->end()
                ->booleanNode('template_active')->defaultTrue()->end()
                ->integerNode('list_count')
                    ->beforeNormalization()->ifString()->then(static function ($val) {
                        return (int) $val;
                    })->end()
                ->end()
                ->arrayNode('active_language')->scalarPrototype()->end()->defaultValue(['en'])->end()
                ->scalarNode('base_template')->defaultValue('Admin/base.html.twig')->end()
                ->scalarNode('template_path')->defaultValue('@PdMailer')->end()
            ->end();

        return $treeBuilder;
    }
}
