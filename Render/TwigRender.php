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

use Doctrine\ORM\EntityManagerInterface;
use Pd\MailerBundle\Entity\MailTemplate;

class TwigRender implements RenderInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $defaultLanguage;

    /**
     * TwigRender constructor.
     *
     * @param \Twig_Environment      $twig
     * @param EntityManagerInterface $entityManager
     * @param string                 $defaultLanguage
     */
    public function __construct(\Twig_Environment $twig, EntityManagerInterface $entityManager, string $defaultLanguage)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->defaultLanguage = $defaultLanguage;
    }

    public function render(string $templateID, string $language, \Swift_Mime_SimpleMessage &$message)
    {
        // Find Template
        $template = $this->entityManager
            ->getRepository(MailTemplate::class)
            ->findOneBy(['templateId' => $templateID, 'status' => true, 'language' => $language]);

        if ((null === $template) && ($language !== $this->defaultLanguage)) {
            return $this->render($templateID, $this->defaultLanguage, $message);
        }

        // Render Template
        if (null !== $template) {
            // Render Body
            try {
                $message->setBody(
                    $this->twig->createTemplate($template->getTemplate())->render(unserialize($message->getBody())),
                    $message->getContentType(),
                    $message->getCharset());
            } catch (\Exception $e) {
            }

            // Set Header
            if (!empty($template->getFromName()) && !empty($template->getFromEmail())) {
                $message->setFrom($template->getFromEmail(), $template->getFromName());
            }

            // Set Subject
            if (!empty($template->getSubject())) {
                $message->setSubject($template->getSubject());
            }

            return true;
        }

        return false;
    }
}
