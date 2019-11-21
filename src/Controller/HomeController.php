<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function index(SessionInterface $session, Request $request)
    {
        $refererPage = $session->get('referer');
        switch($refererPage){
            case 'contact':
            case 'realisations':
                $animation = "fade-right";
                break;
            case 'experiences':
            case 'formations':
                $animation = "fade-left";
                break;
            default:
                $animation = "zoom-out";
                break;
        }
        $session->set('referer','home');
        $contactForm = $this->handleContact($request);
        $this->addFlash('success','test');

        return $this->render('home/index.html.twig', [
            'animation' => $animation,
            'form' => $contactForm->createView()
        ]);
    }

    /**
     * @Route("/realisations", name="realisations")
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function realisations(SessionInterface $session, Request $request)
    {
        $refererPage = $session->get('referer');
        switch($refererPage){
            case 'contact':
                $animation = "fade-right";
                break;
            default:
                $animation = "fade-left";
                break;
        }
        $session->set('referer','realisations');

        $contactForm = $this->handleContact($request);

        return $this->render('home/realisations.html.twig', [
            'animation' => $animation,
            'form' => $contactForm->createView()
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function contact(SessionInterface $session, Request $request)
    {
        $animation = 'fade-left';
        $session->set('referer','contact');
        $contactForm = $this->handleContact($request);


        return $this->render('home/contact.html.twig', [
            'animation' => $animation,
            'form' => $contactForm->createView()
        ]);
    }

    /**
     * @Route("/experiences", name="experiences")
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function experiences(SessionInterface $session, Request $request)
    {
        $refererPage = $session->get('referer');
        switch($refererPage){
            case 'formations':
                $animation = "fade-left";
                break;
            default:
                $animation = "fade-right";
                break;
        }
        $session->set('referer','experiences');

        $contactForm = $this->handleContact($request);

        return $this->render('home/experiences.html.twig', [
            'animation' => $animation,
            'form' => $contactForm->createView()
        ]);
    }

    /**
     * @Route("/competences", name="competences")
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function competences(SessionInterface $session, Request $request)
    {
        $animation = "fade-right";
        $session->set('referer','competences');
        $contactForm = $this->handleContact($request);

        return $this->render('home/competences.html.twig', [
            'animation' => $animation,
            'form' => $contactForm->createView()
        ]);
    }

    /**
     * @Route("/legals", name="legals")
     */
    public function legals()
    {
        return $this->render('home/legals.html.twig', [
        ]);
    }

    private function handleContact(Request $request){
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted()){
            if ($contactForm->isValid()){
                $data = $contactForm->getData();
                $content="From: ".$data['nom']." \n Email: ".$data['email']." \n Sujet: ".$data['sujet']." \n Message: ".$data['message'];
                $recipient = "etienne.surleau.pro@gmail.com";
                $mailheader = "From: ".$data['email']." \r\n";
                $envoye = mail($recipient, $data['sujet'], $content, $mailheader);
                if ($envoye){
                    $this->addFlash('success','Le message a été envoyé.');
                }
                else{
                    $this->addFlash('danger',"L'envoi du message a échoué.");
                }
            }
            else{
                $this->addFlash('danger','Le formulaire est invalide.');
            }
        }
        return $contactForm;
    }
}
