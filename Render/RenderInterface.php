<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Kerem APAYDIN <kerem@apaydin.me>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Render;

/**
 * Render Interface.
 *
 * @author Kerem APAYDIN <kerem@apaydin.me>
 */
interface RenderInterface
{
    public function render(string $templateID, string $language, &$message);
}
