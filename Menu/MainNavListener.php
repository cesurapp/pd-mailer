<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Menu;

use Pd\MenuBundle\Event\PdMenuEvent;

/**
 * Add Menu to Main Navigation.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class MainNavListener
{
    /**
     * @var string
     */
    private $navName;

    public function __construct(string $navName)
    {
        $this->navName = $navName;
    }

    public function onCreate(PdMenuEvent $event)
    {
        // Get Menu Items
        $menu = $event->getMenu();

        $menu[$this->navName]
            ->addChild('nav_mail_manager', 30)
            ->setLabel('nav_mail_manager')
            ->setRoute('admin_mail_list')
            ->setRoles(['ROLE_MAIL_TEMPLATELIST']);
    }
}
