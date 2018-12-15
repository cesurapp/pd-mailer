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

namespace Pd\MailerBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pd\MailerBundle\Entity\MailLog;
use Pd\MailerBundle\Entity\MailTemplate;
use Pd\MailerBundle\Form\TemplateForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Mail Manager.
 *
 * @author Kerem APAYDIN <kerem@apaydin.me>
 */
class MailController extends AbstractController
{
    /**
     * List Mail Templates.
     *
     * @param Request $request
     *
     * @IsGranted("ROLE_MAIL_TEMPLATELIST")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        // Get Query
        $query = $this->getDoctrine()
            ->getRepository(MailTemplate::class)
            ->createQueryBuilder('m');

        // Get Result
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('pd_mailer.list_count'))
        );

        // Set Back URL
        $this->get('session')->set('backUrl', $request->getRequestUri());

        // Render Page
        return $this->render('@PdMailer/list.html.twig', [
            'templates' => $pagination,
        ]);
    }

    /**
     * Add Templates.
     *
     * @param Request $request
     * @param MailLog $mailLog
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEADD")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTemplate(Request $request, MailLog $mailLog = null)
    {
        // Create New Mail Log
        if (null === $mailLog) {
            $mailLog = new MailLog();
        }

        // Create Mail Template
        $template = new MailTemplate();
        $template->setTemplateId($mailLog->getTemplateId() ?? ' ');
        $template->setSubject($mailLog->getSubject());

        // Create Form
        $form = $this->createForm(TemplateForm::class, $template, ['container' => $this->container]);

        // Handle Request
        $form->handleRequest($request);

        // Submit & Valid Form
        if ($form->isSubmitted() && $form->isValid()) {
            // Add object
            $template->setTemplateData($mailLog->getBody());

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            // Message
            $this->addFlash('success', 'changes_saved');

            // Return Edit Page
            $this->redirectToRoute('admin_mail_template_edit', ['id' => $template->getId()]);
        }

        // Render Page
        return $this->render('@PdMailer/template.html.twig', [
            'form' => $form->createView(),
            'objects' => @unserialize($mailLog->getBody()),
            'title' => 'mail_manager_template_add',
            'description' => 'mail_manager_template_add_desc',
        ]);
    }

    /**
     * Edit Templates.
     *
     * @param Request      $request
     * @param MailTemplate $mailTemplate
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEEDIT")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTemplate(Request $request, MailTemplate $mailTemplate)
    {
        // Create Form
        $form = $this->createForm(TemplateForm::class, $mailTemplate, ['container' => $this->container]);

        // Handle Request
        $form->handleRequest($request);

        // Submit & Valid Form
        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailTemplate);
            $em->flush();

            // Message
            $this->addFlash('success', 'changes_saved');
        }

        // Render Page
        return $this->render('@PdMailer/template.html.twig', [
            'form' => $form->createView(),
            'objects' => @unserialize($mailTemplate->getTemplateData()),
            'title' => 'mail_manager_template_edit',
            'description' => 'mail_manager_template_edit_desc',
        ]);
    }

    /**
     * Delete Templates.
     *
     * @param Request      $request
     * @param MailTemplate $mailTemplate
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEDELETE")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTemplate(Request $request, MailTemplate $mailTemplate)
    {
        // Not Found
        if (null === $mailTemplate) {
            $this->addFlash('error', 'sorry_not_existing');

            return $this->redirectToRoute('admin_mail_list');
        }

        // Remove Template
        $em = $this->getDoctrine()->getManager();
        $em->remove($mailTemplate);
        $em->flush();

        // Redirect Back
        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('admin_mail_list'));
    }

    /**
     * Active/Deactive Templates.
     *
     * @param Request      $request
     * @param MailTemplate $mailTemplate
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEACTIVE")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activeTemplate(Request $request, MailTemplate $mailTemplate)
    {
        // Set Status
        $mailTemplate->setStatus(!$mailTemplate->getStatus());

        // Save
        $em = $this->getDoctrine()->getManager();
        $em->persist($mailTemplate);
        $em->flush();

        // Message
        $this->addFlash('success', 'changes_saved');

        // Redirect Back
        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('admin_mail_list'));
    }

    /**
     * View Mail Logs.
     *
     * @param Request $request
     *
     * @IsGranted("ROLE_MAIL_LOGGER")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logger(Request $request, PaginatorInterface $paginator)
    {
        // Get Logs
        $query = $this->getDoctrine()
            ->getRepository(MailLog::class)
            ->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->getQuery();

        // Get Result
        $mailLog = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('pd_mailer.list_count'))
        );

        // Render Page
        return $this->render('@PdMailer/logger.html.twig', [
            'maillogs' => $mailLog,
        ]);
    }

    /**
     * View Log.
     *
     * @param MailLog $log
     *
     * @IsGranted("ROLE_MAIL_VIEWLOG")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLog(MailLog $log)
    {
        // Get Log Manager
        $trans = $this->get('translator');

        $data = [
            $trans->trans('mail_templateid') => $log->getTemplateId(),
            $trans->trans('mail_mid') => $log->getMailId(),
            $trans->trans('mail_to') => implode(PHP_EOL, $this->implodeKeyValue($log->getTo(), ' -> ')),
            $trans->trans('mail_from') => implode(PHP_EOL, $this->implodeKeyValue($log->getFrom(), ' -> ')),
            $trans->trans('mail_subject') => $log->getSubject(),
            $trans->trans('mail_language') => $log->getLanguage(),
            $trans->trans('mail_content_type') => $log->getContentType(),
            $trans->trans('date') => date('Y-m-d H:i:s', $log->getDate()->getTimestamp()),
            $trans->trans('mail_reply_to') => $log->getReplyTo(),
            $trans->trans('mail_header') => '<code>'.str_replace(PHP_EOL, '<br/>', htmlspecialchars($log->getHeader())).'</code>',
            $trans->trans('mail_status') => $log->getStatus().' = '.$this->swiftEventFilter($log->getStatus()),
            $trans->trans('mail_exception') => str_replace(PHP_EOL, '<br/>', htmlspecialchars($log->getException())),
        ];

        // JSON Response
        return $this->json($data);
    }

    /**
     * Delete Logs.
     *
     * @param Request $request
     * @param $mailLog
     *
     * @IsGranted("ROLE_MAIL_LOGDELETE")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteLog(Request $request, $mailLog)
    {
        // Not Found
        if (null === $mailLog && !$request->request->has('id')) {
            $this->addFlash('error', 'sorry_not_existing');

            return $this->redirectToRoute('admin_mail_logger');
        }

        // Convert Array
        $mailLog = $request->request->has('id') ? $request->request->get('id') : [$mailLog];

        // Remove Mail Log
        if ($mailLog) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->createQueryBuilder('')
                ->delete(MailLog::class, 'log')
                ->add('where', $em->getExpressionBuilder()->in('log.id', ':logId'))
                ->setParameter(':logId', $mailLog)
                ->getQuery()
                ->execute();
        }

        // Redirect Back
        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('admin_mail_logger'));
    }

    /**
     * Array Key => Value Implode.
     *
     * @param array  $array
     * @param string $glue
     *
     * @return array
     */
    private function implodeKeyValue(array $array, $glue = ' - '): array
    {
        $imploded = [];

        // Imlode Key => Value
        foreach ($array as $key => $value) {
            $imploded[] = "{$key}{$glue}{$value}";
        }

        return $imploded;
    }

    /**
     * Swift Event.
     *
     * @param $event
     *
     * @return string
     */
    private function swiftEventFilter($event): string
    {
        switch ($event) {
            case \Swift_Events_SendEvent::RESULT_SUCCESS:
                return $this->get('translator')->trans('RESULT_SUCCESS');
            case \Swift_Events_SendEvent::RESULT_FAILED:
                return $this->get('translator')->trans('RESULT_FAILED');
            case \Swift_Events_SendEvent::RESULT_SPOOLED:
                return $this->get('translator')->trans('RESULT_SPOOLED');
            case \Swift_Events_SendEvent::RESULT_PENDING:
                return $this->get('translator')->trans('RESULT_PENDING');
            case \Swift_Events_SendEvent::RESULT_TENTATIVE:
                return $this->get('translator')->trans('RESULT_TENTATIVE');
            case -1:
                return $this->get('translator')->trans('RESULT_DELETED');
        }

        return '';
    }
}
