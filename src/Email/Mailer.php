<?php


namespace App\Email;


use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

class Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(Swift_Mailer $mailer , Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'confirmation.html.twig',
            [
                'user' => $user
            ]
        );

        $message = (new Swift_Message('Confirmation de votre compte ImmoBest'))
            ->setFrom('tembschan@gmail.com')
            //->setTo($user->getEmail())
            ->setTo("tembschan@gmail.com")
            ->setBody($body,'text/html');

        $this->mailer->send($message);
    }
}