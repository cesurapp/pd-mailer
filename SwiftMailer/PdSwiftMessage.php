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

namespace Pd\MailerBundle\SwiftMailer;

class PdSwiftMessage extends \Swift_Message
{
    public function __construct($subject = null, $body = null, $contentType = null, $charset = null)
    {
        parent::__construct($subject, $body, $contentType, $charset);
    }

    /**
     * Set Message Content Template ID.
     *
     * @param string|null $templateId
     *
     * @return $this
     */
    public function setTemplateId(string $templateId = null)
    {
        if (!$this->setHeaderFieldModel('TemplateID', $templateId)) {
            $this->getHeaders()->addTextHeader('TemplateID', $templateId);
        }

        return $this;
    }
}
