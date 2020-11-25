<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use App\Repository\CustomerRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerController extends AbstractController
{
    /**
     * @Route("/email", name="send_mail_test")
     */
    public function sendTestEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('lionelrenier82@gmail.com')
            ->to($this->getUser()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
        try{
            $mailer->send($email);
        } catch(TransportExceptionInterface $e) {
            if($e->getCode() === 550)
            $this->addFlash('mailerror', 'mail sender incorrect');
        }
        // ...
        $this->addFlash('mailsent', 'mail envoyé');
        return $this->redirectToRoute('customer_board');
    }

    /**
     * @Route("/email/subscribe/{customer}", name="send_subscribe_mail")
     */
    public function sendSubscribeEmail(MailerInterface $mailer, CustomerRepository $customerRepo, $customer)
    {   
        // dd($customer);
        $requestedCustomer = explode('-', $customer);
        $subscribedCustomer = $customerRepo->find($requestedCustomer[0]);
        $email = (new TemplatedEmail())
            ->from('lionelrenier82@gmail.com')
            ->to($subscribedCustomer->getEmail())
            ->bcc('lionelrenier82@gmail.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Thanks for signing up!')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'user' => $subscribedCustomer
            ]);

        try{
            $mailer->send($email);
            return $this->redirectToRoute('security_login');
        } catch(TransportExceptionInterface $e) {
            if($e->getCode() === 550)
            $this->addFlash('mailerror', 'mail sender incorrect');
        }
       
        return $this->redirectToRoute('customer_board');
    }
}