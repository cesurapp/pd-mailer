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
use Pd\MailerBundle\Entity\MailTemplate;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Mail Manager.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class TemplateController extends AbstractController
{
    /**
     * List Mail Templates.
     *
     * @IsGranted("ROLE_MAIL_TEMPLATE")
     * @Route(path="/mailer/template", name="mail_template")
     */
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        // Get Query
        $query = $this->getDoctrine()->getRepository(MailTemplate::class)->createQueryBuilder('m');

        // Get Result
        $pagination = $paginator->paginate($query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('pd_mailer.list_count'))
        );

        // Render Page
        return $this->render($this->getParameter('pd_mailer.template_path').'/list.html.twig', [
            'templates' => $pagination,
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * Add Templates.
     *
     * @param null $id
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEADD")
     * @Route(path="/mailer/template/add", name="mail_template_add")
     */
    public function addTemplate(Request $request, ParameterBagInterface $bag): Response
    {
        $template = new MailTemplate();

        // Find Template
        if ($request->get('id')) {
            $log = $this->getDoctrine()->getRepository(MailLog::class)
                ->findOneBy(['id' => $request->get('id')]);

            if ($log) {
                $template->setTemplateId($log->getTemplateId() ?? ' ');
                $template->setSubject($log->getSubject());
                $template->setTemplateData($log->getBody());
            }
        }

        // Create Form
        $form = $this->createForm($this->getParameter('pd_mailer.mail_template_type'), $template, ['parameters' => $bag]);

        // Handle Request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            // Message
            $this->addFlash('success', 'changes_saved');

            // Return Edit Page
            $this->redirectToRoute('mail_template_edit', ['id' => $template->getId()]);
        }

        // Render Page
        return $this->render($this->getParameter('pd_mailer.template_path').'/template.html.twig', [
            'form' => $form->createView(),
            'objects' => isset($log) ? $log->getBody() : ['empty' => ''],
            'title' => 'mail_manager_template_add',
            'description' => 'mail_manager_template_add_desc',
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * Edit Templates.
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEEDIT")
     * @Route(path="/mailer/template/edit/{id}", name="mail_template_edit")
     */
    public function editTemplate(Request $request, ParameterBagInterface $bag, MailTemplate $template): Response
    {
        // Create Form
        $form = $this->createForm($this->getParameter('pd_mailer.mail_template_type'), $template, ['parameters' => $bag]);

        // Handle Request
        $form->handleRequest($request);

        // Submit & Valid Form
        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            // Message
            $this->addFlash('success', 'changes_saved');
        }

        // Render Page
        return $this->render($this->getParameter('pd_mailer.template_path').'/template.html.twig', [
            'form' => $form->createView(),
            'objects' => $template->getTemplateData() ?? ['empty' => ''],
            'title' => 'mail_manager_template_edit',
            'description' => 'mail_manager_template_edit_desc',
            'base_template' => $this->getParameter('pd_mailer.base_template'),
        ]);
    }

    /**
     * Delete Templates.
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEDELETE")
     * @Route(path="/mailer/template/delete/{id}", name="mail_template_delete")
     */
    public function deleteTemplate(Request $request, MailTemplate $template): Response
    {
        // Remove Template
        $em = $this->getDoctrine()->getManager();
        $em->remove($template);
        $em->flush();

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('mail_template')));
    }

    /**
     * Active/Deactive Templates.
     *
     * @param $id
     *
     * @IsGranted("ROLE_MAIL_TEMPLATEACTIVE")
     * @Route(path="/mailer/template/activate/{id}", name="mail_template_activate")
     */
    public function activeTemplate(Request $request, MailTemplate $template): Response
    {
        // Set Status
        $template->setStatus(!$template->getStatus());

        // Save
        $em = $this->getDoctrine()->getManager();
        $em->persist($template);
        $em->flush();

        // Message
        $this->addFlash('success', 'changes_saved');

        // Redirect Back
        return $this->redirect($request->headers->get('referer', $this->generateUrl('mail_template')));
    }

    /**
     * Preview Template.
     *
     * @Route(path="/mailer/template/preview", name="mail_template_preview")
     */
    public function previewTemplate(Request $request): JsonResponse
    {
        $data = $request->request->all();

        // Render
        $content = $this->get('twig')->createTemplate($data['template'] ?? '')->render($data);

        // Response
        return $this->json([
            'content' => $content,
        ]);
    }
}
