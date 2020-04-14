<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mail Templates.
 *
 * @ORM\Table(name="mail_template")
 * @ORM\Entity(repositoryClass="Pd\MailerBundle\Repository\MailTemplateRepository")
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
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
     * @ORM\Column(type="string", length=50)
     */
    private $templateId;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="text", nullable=true)
     */
    private $template;

    /**
     * @var array|null
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $templateData;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     * @Assert\Language()
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
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get contentId.
     *
     * @return string
     */
    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    /**
     * Set contentId.
     *
     * @return $this
     */
    public function setTemplateId(string $templateId): self
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * Get template.
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * Set template.
     *
     * @return MailTemplate
     */
    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get Template Data.
     */
    public function getTemplateData(): ?array
    {
        return $this->templateData;
    }

    /**
     * Set Template Data.
     *
     * @return $this
     */
    public function setTemplateData(?array $templateData): self
    {
        $this->templateData = $templateData;

        return $this;
    }

    /**
     * Set subject.
     *
     * @return MailTemplate
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }
}
