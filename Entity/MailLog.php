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

/**
 * Mail Log.
 *
 * @ORM\Table(name="mail_log")
 * @ORM\Entity(repositoryClass="Pd\MailerBundle\Repository\MailLogRepository")
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class MailLog
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
     * @var array
     *
     * @ORM\Column(name="mTo", type="array")
     */
    private $to;

    /**
     * @var array
     *
     * @ORM\Column(name="mFrom", type="array")
     */
    private $from;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $body;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=75)
     */
    private $templateId;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=3)
     */
    private $language;

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get mTo.
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * Set To.
     *
     * @return MailLog
     */
    public function setTo(array $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get From.
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * Set From.
     *
     * @return MailLog
     */
    public function setFrom(array $from): self
    {
        $this->from = $from;

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
     * Set subject.
     *
     * @return MailLog
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get body.
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * Set body.
     *
     * @return MailLog
     */
    public function setBody(?array $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set Date.
     *
     * @return MailLog
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get TemplateID.
     *
     * @return string
     */
    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    /**
     * Set TemplateID.
     *
     * @return MailLog
     */
    public function setTemplateId(?string $templateId): self
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * Get language.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return MailLog
     */
    public function setLanguage($language): self
    {
        $this->language = $language;

        return $this;
    }
}
