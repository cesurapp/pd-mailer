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
