<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Mailer;

use Doctrine\ORM\EntityManagerInterface;
use Pd\MailerBundle\Entity\MailLog;
use Pd\MailerBundle\Entity\MailTemplate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Listener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ParameterBagInterface
     */
    private $bag;
    /**
     * @var RequestStack
     */
    private $request;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig, ParameterBagInterface $bag, RequestStack $request)
    {
        $this->em = $em;
        $this->bag = $bag;
        $this->request = $request;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [MessageEvent::class => 'onMessage'];
    }

    public function onMessage(MessageEvent $event): void
    {
        $email = $event->getMessage();
        if (!$email instanceof Email) {
            return;
        }

        // Create Email Log
        if ($this->bag->get('pd_mailer.logger_active')) {
            $this->addLog($email);
        }

        // Mailer Template
        if ($this->bag->get('pd_mailer.template_active')) {
            $this->renderTemplate($email);
        }

        // Convert Mail Html Body
        if (\is_array($email->getHtmlBody())) {
            $email->html(json_encode($email->getHtmlBody()));
        }
    }

    /**
     * Add Log to DB.
     */
    private function addLog(Email $email): void
    {
        // Get Template ID
        $template = $email->getHeaders()->get('template');
        $locale = $email->getHeaders()->get('locale')->getBodyAsString();

        // Create Log
        $log = new MailLog();
        $log
            ->setFrom(array_map(static function (Address $addr) {
                return $addr->toString();
            }, $email->getFrom()))
            ->setTo(array_map(static function (Address $addr) {
                return $addr->toString();
            }, $email->getTo()))
            ->setSubject($email->getSubject())
            ->setBody(\is_array($email->getHtmlBody()) ? $email->getHtmlBody() : [])
            ->setDate(new \DateTime())
            ->setLanguage($locale ?? ($this->request->getCurrentRequest() ? $this->request->getCurrentRequest()->getLocale() : 'en'))
            ->setTemplateId($template ? $template->getBodyAsString() : '');

        // Save
        $this->em->persist($log);
        $this->em->flush();
    }

    /**
     * Render Template.
     */
    private function renderTemplate(Email $email): void
    {
        if ($templateId = $email->getHeaders()->get('template')) {
            $bodyData = $email->getHtmlBody();
            $locale = $email->getHeaders()->get('locale')->getBodyAsString();

            // Check Array
            if (\is_array($bodyData)) {
                $template = $this->em
                    ->getRepository(MailTemplate::class)
                    ->findOneBy([
                        'templateId' => $templateId->getBody(),
                        'status' => true,
                        'language' => $locale ?? ($this->request->getCurrentRequest() ? $this->request->getCurrentRequest()->getLocale() : 'en'),
                    ]);

                if ($template) {
                    // Set Body Content
                    $email->html($this->twig->createTemplate($template->getTemplate())->render($bodyData));

                    // Set Subject
                    if ($template->getSubject()) {
                        $email->subject($template->getSubject());
                    }
                } else {
                    $email->html(json_encode($bodyData));
                }
            }

            // Remove Template Header
            $email->getHeaders()->remove('template');
        }
    }
}
