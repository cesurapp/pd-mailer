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

namespace Pd\MailerBundle\Render;

interface RenderInterface
{
    public function render(string $templateID, string $language, \Swift_Mime_SimpleMessage &$message);
}
