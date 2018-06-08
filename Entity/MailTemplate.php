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

namespace Pd\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MailTemplate.
 *
 * @ORM\Table(name="mail_template", uniqueConstraints={@ORM\UniqueConstraint(name="mailId_lang", columns={"templateId", "language"})})
 * @ORM\Entity(repositoryClass="Pd\MailerBundle\Repository\MailTemplateRepository")
 * @UniqueEntity(fields={"language", "templateId"})
 */
class MailTemplate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="templateId", type="string", length=50, nullable=true)
     */
    private $templateId;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="fromName", type="string", length=100, nullable=true)
     */
    private $fromName;

    /**
     * @var string
     *
     * @ORM\Column(name="fromEmail", type="string", length=100, nullable=true)
     * @Assert\Email()
     */
    private $fromEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="text", nullable=true)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="templateData", type="text", nullable=true)
     */
    private $templateData;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=3, nullable=false)
     */
    private $language;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject.
     *
     * @param string $subject
     *
     * @return MailTemplate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set template.
     *
     * @param string $template
     *
     * @return MailTemplate
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set Template Data.
     *
     * @param string $templateData
     *
     * @return MailTemplate
     */
    public function setTemplateData($templateData)
    {
        $this->templateData = $templateData;

        return $this;
    }

    /**
     * Get Template Data.
     *
     * @return string
     */
    public function getTemplateData()
    {
        return $this->templateData;
    }

    /**
     * Set status.
     *
     * @param bool $status
     *
     * @return MailTemplate
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set contentId.
     *
     * @param string $templateId
     *
     * @return MailTemplate
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * Get contentId.
     *
     * @return string
     */
    public function getContentId()
    {
        return $this->templateId;
    }

    /**
     * Set fromName.
     *
     * @param string $fromName
     *
     * @return MailTemplate
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get fromName.
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Set fromEmail.
     *
     * @param string $fromEmail
     *
     * @return MailTemplate
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * Get fromEmail.
     *
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return MailTemplate
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
