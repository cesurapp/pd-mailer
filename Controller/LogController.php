<?php

/**
 * This file is part of the pd-admin pd-mailer package.
 *
 * @package     pd-mailer
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-mailer
 */

namespace Pd\MailerBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pd\MailerBundle\Entity\MailLog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LogController extends AbstractController
{
    /**
     * View Mail Logs.
     *
     * @IsGranted("ROLE_MAIL_LOGGER")
     * @Route(path="/mailer/log", name="mail_log")
     */
    public function logger(Request $request, PaginatorInterface $paginator): Response
    {
        // Get Logs
        $query = $this->getDoctrine()->getRepository(MailLog::class)->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')->getQuery();

        // Get Result
        $logs = $paginator->paginate($query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('pd_mailer.list_count'))
        );

        // Render Page
        return $this->render($this->getParameter('pd_mailer.template_path').'/logger.html.twig', [
            'logs' => $logs,
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * View Log.
     *
     * @IsGranted("ROLE_MAIL_VIEWLOG")
     * @Route(path="/mailer/log/view/{id}", name="mail_log_view")
     */
    public function viewLog(TranslatorInterface $translator, MailLog $log): JsonResponse
    {
        $data = [
            $translator->trans('mail_templateid') => $log->getTemplateId(),
            $translator->trans('mail_to') => implode(PHP_EOL, $log->getTo()),
            $translator->trans('mail_from') => implode(PHP_EOL, $log->getFrom()),
            $translator->trans('mail_subject') => $log->getSubject(),
            $translator->trans('mail_language') => $log->getLanguage(),
            $translator->trans('mail_body') => implode(PHP_EOL, array_map(static function ($key, $val) {
                return $key.': '.$val;
            }, array_keys($log->getBody()), $log->getBody())),
            $translator->trans('date') => date('Y-m-d H:i:s', $log->getDate()->getTimestamp()),
        ];

        // JSON Response
        return $this->json($data);
    }

    /**
     * Delete Logs.
     *
     * @IsGranted("ROLE_MAIL_LOGDELETE")
     * @Route(path="/mailer/log/delete", name="mail_log_delete")
     */
    public function deleteLog(Request $request): Response
    {
        // Not Found
        if (!$request->get('id')) {
            $this->addFlash('error', 'sorry_not_existing');

            return $this->redirectToRoute('mail_log');
        }

        $ids = !\is_array($request->get('id')) ? [$request->get('id')] : $request->get('id');

        // Remove Mail Log
        $em = $this->getDoctrine()->getManager();
        $em->createQueryBuilder('')
            ->delete(MailLog::class, 'log')
            ->add('where', $em->getExpressionBuilder()->in('log.id', ':logId'))
            ->setParameter(':logId', $ids)
            ->getQuery()
            ->execute();

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('mail_log')));
    }

    /**
     * Delete Logs.
     *
     * @IsGranted("ROLE_MAIL_LOGDELETE")
     * @Route(path="/mailer/log/clear", name="mail_log_clear")
     */
    public function clearLog(Request $request): Response
    {
        // Remove Mail Log
        $em = $this->getDoctrine()->getManager();
        $em->createQueryBuilder('')->delete(MailLog::class, 'log')->getQuery()->execute();

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('mail_log')));
    }
}
