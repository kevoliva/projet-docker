<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\MailType;
use App\Repository\MailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/mail")
 */
class MailController extends AbstractController
{
    /**
     * @Route("/", name="mail_index", methods={"GET"})
     */
    public function index(MailRepository $mailRepository): Response
    {
        return $this->render('mail/index.html.twig', [
            'mails' => $mailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mail_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mail);
            $entityManager->flush();

            $email = (new Email())
            ->from('docker@ynov.com')
            ->to($mail->getDestinataire())
            ->subject($mail->getSujet())
            ->text($mail->getCorps());
        $mailer->send($email);

            return $this->redirectToRoute('mail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mail/new.html.twig', [
            'mail' => $mail,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="mail_show", methods={"GET"})
     */
    public function show(Mail $mail): Response
    {
        return $this->render('mail/show.html.twig', [
            'mail' => $mail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mail_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mail $mail): Response
    {
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mail/edit.html.twig', [
            'mail' => $mail,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="mail_delete", methods={"POST"})
     */
    public function delete(Request $request, Mail $mail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mail_index', [], Response::HTTP_SEE_OTHER);
    }
}
