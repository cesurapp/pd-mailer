<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Render;

/**
 * Render Interface.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
interface RenderInterface
{
    public function render(string $templateID, string $language, &$message);
}
