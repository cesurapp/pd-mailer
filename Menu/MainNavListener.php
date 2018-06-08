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
