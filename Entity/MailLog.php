<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Kerem APAYDIN <kerem@apaydin.me>
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
 * @author Kerem APAYDIN <kerem@apaydin.me>
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
     * @var string
     *
     * @ORM\Column(name="mailId", type="string", length=130, unique=true)
     */
    private $mailId;

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
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=75)
     */
    private $contentType;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="replyTo", type="array", nullable=true)
     */
    private $replyTo;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="text")
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="exception", type="text", nullable=true)
     */
    private $exception;

    /**
     * @var string
     *
     * @ORM\Column(name="templateId", type="string", nullable=true, length=50)
     */
    private $templateId;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=5)
     */
    private $language;

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
     * Set mId.
     *
     * @param string $mailId
     *
     * @return MailLog
     */
    public function setMailId($mailId)
    {
        $this->mailId = $mailId;

        return $this;
    }

    /**
     * Get mId.
     *
     * @return string
     */
    public function getMailId()
    {
        return $this->mailId;
    }

    /**
     * Set mTo.
     *
     * @param array $to
     *
     * @return MailLog
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get mTo.
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set mFrom.
     *
     * @param array $from
     *
     * @return MailLog
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get mFrom.
     *
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set subject.
     *
     * @param string $subject
     *
     * @return MailLog
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
     * Set body.
     *
     * @param string $body
     *
     * @return MailLog
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set contentType.
     *
     * @param string $contentType
     *
     * @return MailLog
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set Date.
     *
     * @param \DateTimeInterface $date
     *
     * @return MailLog
     */
    public function setDate(\DateTimeInterface $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set replyTo.
     *
     * @param string $replyTo
     *
     * @return MailLog
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * Get replyTo.
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set header.
     *
     * @param string $header
     *
     * @return MailLog
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header.
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return MailLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set exception.
     *
     * @param string $exception
     *
     * @return MailLog
     */
    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * Add Exception.
     *
     * @param $exception
     *
     * @return MailLog
     */
    public function addException($exception)
    {
        $this->exception .= $exception;

        return $this;
    }

    /**
     * Get exception.
     *
     * @return string
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Set contentId.
     *
     * @param string $templateId
     *
     * @return MailLog
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
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return MailLog
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
