<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Kerem APAYDIN <kerem@apaydin.me>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\DependencyInjection;

use Pd\MailerBundle\Entity\MailLog;
use Pd\MailerBundle\Entity\MailTemplate;
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
                ->scalarNode('mail_log_class')->defaultValue(MailLog::class)->end()
                ->scalarNode('mail_template_class')->defaultValue(MailTemplate::class)->end()
                ->scalarNode('mail_template_type')->defaultValue(TemplateForm::class)->end()
                ->booleanNode('template_active')->defaultTrue()->end()
                ->scalarNode('sender_address')->defaultValue('pdadmin@example.com')->end()
                ->scalarNode('sender_name')->defaultValue('pdAdmin')->end()
                ->integerNode('list_count')
                ->beforeNormalization()->ifString()->then(function ($val) {
                    return (int) $val;
                })->end()
                ->end()
                ->arrayNode('active_language')->scalarPrototype()->end()->defaultValue(['en'])->end()
                ->scalarNode('menu_root_name')->defaultValue('main_menu')->end()
                ->scalarNode('menu_name')->defaultValue('nav_config')->end()
                ->scalarNode('base_template')->defaultValue('Admin/base.html.twig')->end()
            ->end();

        return $treeBuilder;
    }
}
