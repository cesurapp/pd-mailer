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

namespace Pd\MailerBundle\Menu;

use Pd\MenuBundle\Builder\ItemInterface;
use Pd\MenuBundle\Builder\Menu;

class MailerMenu extends Menu
{
    /**
     * Mail Manager Custom Menus.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function createMenu(array $options = []): ItemInterface
    {
        // Create Root
        $menu = $this->createRoot('mail_manager')->setChildAttr([
            'class' => 'nav nav-pills',
            'data-parent' => 'admin_mail_list',
        ]);

        // Create Menu Items
        $menu
            ->addChild('nav_mail_template')
            ->setLabel('nav_mail_template')
            ->setRoute('admin_mail_list')
            ->setRoles(['ADMIN_MAIL_LIST'])
            // Logger
            ->addChildParent('nav_mail_logger')
            ->setLabel('nav_mail_logger')
            ->setRoute('admin_mail_logger')
            ->setRoles(['ADMIN_MAIL_LIST']);

        return $menu;
    }
}
