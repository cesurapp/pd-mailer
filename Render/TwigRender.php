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

use Doctrine\ORM\EntityManagerInterface;
use Pd\MailerBundle\Entity\MailTemplate;
use Twig\Environment;

/**
 * Twig Render Class.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class TwigRender implements RenderInterface
{
    /**
     * @var Environment
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
     */
    public function __construct(Environment $twig, EntityManagerInterface $entityManager, string $defaultLanguage)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * Render Template.
     *
     * @param \Swift_Mime_SimpleMessage $message
     *
     * @throws \Throwable
     *
     * @return bool
     */
    public function render(string $templateID, string $language, &$message)
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
