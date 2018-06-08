<?php

namespace Pd\MailerBundle\Menu;

use Pd\MenuBundle\Event\PdMenuEvent;

class MainNavListener
{
    public function onCreate(PdMenuEvent $event)
    {
        // Get Menu Items
        $menu = $event->getMenu();

        $menu['nav_config']
            ->addChild('nav_mail_manager', 30)
            ->setLabel('labellll')
            ->setRoute('admin_mail_list');
    }
}
