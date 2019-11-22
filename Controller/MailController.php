<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Kerem APAYDIN <kerem@apaydin.me>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pd\MailerBundle\Entity\MailTemplate;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

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
     * @param PaginatorInterface $paginator
     *
     * @IsGranted("ROLE_MAIL_TEMPLATELIST")
     *
     * @return Response
     */
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        // Get Query
        $query = $this->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_template_class'))
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
        return $this->render($this->getParameter('pd_mailer.template_path') . '/list.html.twig', [
            'templates' => $pagination,
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * Add Templates.
     *
     * @param Request $request
     * @param ParameterBagInterface $bag
     * @param null $id
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEADD")
     *
     * @return Response
     */
    public function addTemplate(Request $request, ParameterBagInterface $bag, $id = null): Response
    {
        // Find Template
        $mailLog = $this
            ->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_log_class'))
            ->findOneBy(['id' => $id]);

        // Create New Mail Log
        if (null === $mailLog) {
            $class = $this->getParameter('pd_mailer.mail_log_class');
            $mailLog = new $class();
        }

        // Create Mail Template
        $class = $this->getParameter('pd_mailer.mail_template_class');
        $template = new $class();
        $template->setTemplateId($mailLog->getTemplateId() ?? ' ');
        $template->setSubject($mailLog->getSubject());

        // Create Form
        $form = $this->createForm($this->getParameter('pd_mailer.mail_template_type'), $template, ['parameters' => $bag]);

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
        return $this->render($this->getParameter('pd_mailer.template_path') . '/template.html.twig', [
            'form' => $form->createView(),
            'objects' => @unserialize($mailLog->getBody()),
            'title' => 'mail_manager_template_add',
            'description' => 'mail_manager_template_add_desc',
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * Edit Templates.
     *
     * @param Request $request
     * @param ParameterBagInterface $bag
     * @param $id
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEEDIT")
     *
     * @return Response
     */
    public function editTemplate(Request $request, ParameterBagInterface $bag, $id): Response
    {
        // Find Template
        $mailTemplate = $this
            ->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_template_class'))
            ->findOneBy(['id' => $id]);
        if (!$mailTemplate) {
            throw $this->createNotFoundException();
        }
        // Create Form
        $form = $this->createForm($this->getParameter('pd_mailer.mail_template_type'), $mailTemplate, ['parameters' => $bag]);

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
        return $this->render($this->getParameter('pd_mailer.template_path') . '/template.html.twig', [
            'form' => $form->createView(),
            'objects' => @unserialize($mailTemplate->getTemplateData()),
            'title' => 'mail_manager_template_edit',
            'description' => 'mail_manager_template_edit_desc',
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * Delete Templates.
     *
     * @param Request $request
     * @param $id
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEDELETE")
     *
     * @return Response
     */
    public function deleteTemplate(Request $request, $id): Response
    {
        // Find Template
        $mailTemplate = $this
            ->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_template_class'))
            ->findOneBy(['id' => $id]);

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
        return $this->redirect($request->headers->get('referer', $this->generateUrl('admin_mail_list')));
    }

    /**
     * Preview Template
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function previewTemplate(Request $request)
    {
        $data = $request->request->all();
        $template = $data['template'] ?? '';
        unset($data['templated']);

        // Render
        $content = $this->get('twig')->createTemplate($template)->render($data);

        // Response
        return $this->json([
            'content' => $content
        ]);
    }

    /**
     * Active/Deactive Templates.
     *
     * @param Request $request
     * @param $id
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEACTIVE")
     *
     * @return Response
     */
    public function activeTemplate(Request $request, $id): Response
    {
        // Find Template
        $mailTemplate = $this
            ->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_template_class'))
            ->findOneBy(['id' => $id]);
        if (!$mailTemplate) {
            throw $this->createNotFoundException();
        }
        // Set Status
        $mailTemplate->setStatus(!$mailTemplate->getStatus());

        // Save
        $em = $this->getDoctrine()->getManager();
        $em->persist($mailTemplate);
        $em->flush();

        // Message
        $this->addFlash('success', 'changes_saved');

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('admin_mail_list')));
    }

    /**
     * View Mail Logs.
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     *
     * @IsGranted("ROLE_MAIL_LOGGER")
     *
     * @return Response
     */
    public function logger(Request $request, PaginatorInterface $paginator): Response
    {
        // Get Logs
        $query = $this->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_log_class'))
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
        return $this->render($this->getParameter('pd_mailer.template_path') . '/logger.html.twig', [
            'maillogs' => $mailLog,
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * View Log.
     *
     * @param TranslatorInterface $translator
     * @param $id
     *
     * @IsGranted("ROLE_MAIL_VIEWLOG")
     *
     * @return JsonResponse
     */
    public function viewLog(TranslatorInterface $translator, $id): JsonResponse
    {
        // Find Template
        $log = $this
            ->getDoctrine()
            ->getRepository($this->getParameter('pd_mailer.mail_log_class'))
            ->findOneBy(['id' => $id]);
        if (!$log) {
            throw $this->createNotFoundException();
        }

        $data = [
            $translator->trans('mail_templateid') => $log->getTemplateId(),
            $translator->trans('mail_mid') => $log->getMailId(),
            $translator->trans('mail_to') => implode(PHP_EOL, $this->implodeKeyValue($log->getTo(), ' -> ')),
            $translator->trans('mail_from') => implode(PHP_EOL, $this->implodeKeyValue($log->getFrom(), ' -> ')),
            $translator->trans('mail_subject') => $log->getSubject(),
            $translator->trans('mail_language') => $log->getLanguage(),
            $translator->trans('mail_content_type') => $log->getContentType(),
            $translator->trans('date') => date('Y-m-d H:i:s', $log->getDate()->getTimestamp()),
            $translator->trans('mail_reply_to') => $log->getReplyTo(),
            $translator->trans('mail_header') => '<code>' . str_replace(PHP_EOL, '<br/>', htmlspecialchars($log->getHeader())) . '</code>',
            $translator->trans('mail_status') => $log->getStatus() . ' = ' . $this->swiftEventFilter($translator, $log->getStatus()),
            $translator->trans('mail_exception') => str_replace(PHP_EOL, '<br/>', htmlspecialchars($log->getException())),
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
     * @return Response
     */
    public function deleteLog(Request $request, $mailLog): Response
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
            $em = $this->getDoctrine()->getManager();
            $em->createQueryBuilder('')
                ->delete($this->getParameter('pd_mailer.mail_log_class'), 'log')
                ->add('where', $em->getExpressionBuilder()->in('log.id', ':logId'))
                ->setParameter(':logId', $mailLog)
                ->getQuery()
                ->execute();
        }

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('admin_mail_logger')));
    }

    /**
     * Delete Logs.
     *
     * @param Request $request
     *
     * @IsGranted("ROLE_MAIL_LOGDELETE")
     *
     * @return Response
     */
    public function deleteAllLog(Request $request): Response
    {
        // Remove Mail Log
        $em = $this->getDoctrine()->getManager();
        $em->createQueryBuilder('')
            ->delete($this->getParameter('pd_mailer.mail_log_class'), 'log')
            ->getQuery()
            ->execute();

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('admin_mail_logger')));
    }

    /**
     * Array Key => Value Implode.
     *
     * @param array $array
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
     * @param TranslatorInterface $translator
     * @param $event
     *
     * @return string
     */
    private function swiftEventFilter(TranslatorInterface $translator, $event): string
    {
        switch ($event) {
            case \Swift_Events_SendEvent::RESULT_SUCCESS:
                return $translator->trans('RESULT_SUCCESS');
            case \Swift_Events_SendEvent::RESULT_FAILED:
                return $translator->trans('RESULT_FAILED');
            case \Swift_Events_SendEvent::RESULT_SPOOLED:
                return $translator->trans('RESULT_SPOOLED');
            case \Swift_Events_SendEvent::RESULT_PENDING:
                return $translator->trans('RESULT_PENDING');
            case \Swift_Events_SendEvent::RESULT_TENTATIVE:
                return $translator->trans('RESULT_TENTATIVE');
            case -1:
                return $translator->trans('RESULT_DELETED');
        }

        return '';
    }
}
