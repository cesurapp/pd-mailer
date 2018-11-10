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

namespace Pd\MailerBundle\Menu;

use Pd\MenuBundle\Builder\ItemInterface;
use Pd\MenuBundle\Builder\Menu;

/**
 * Mail Menu.
 *
 * @author Kerem APAYDIN <kerem@apaydin.me>
 */
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
            ->setRoles(['ROLE_MAIL_LIST'])
            // Logger
            ->addChildParent('nav_mail_logger')
            ->setLabel('nav_mail_logger')
            ->setRoute('admin_mail_logger')
            ->setRoles(['ROLE_MAIL_LIST']);

        return $menu;
    }
}
