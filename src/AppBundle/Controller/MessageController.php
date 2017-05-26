<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Message controller.
 *
 * @Route("message")
 */
class MessageController extends Controller
{
    /**
     * Lists all message entities.
     *
     * @Route("/", name="message_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $messages = $user->getMessages();

        $sendMessages = [];
        $gotMessages = [];
        foreach ($messages as $message) {
            if ($message->getAccess() == 1 || $message->getAccess() == 3) {
                if ($message->getSender()) {
                    $sendMessages[] = $message;
                } else {
                    $gotMessages[] = $message;
                }
            }
        }

        return $this->render('@App/Message/showUserMes.html.twig', array(
            'messages' => $sendMessages,
            'gotmessages' => $gotMessages,
        ));
    }

    /**
     * Creates a new message entity.
     *
     * @Route("/new/{id}", name="message_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {
        $message = new Message();
        $message->setUser($this->getUser());

        $research = $this->getDoctrine()->getRepository('AppBundle:Research')->find($id);
        $message->setResearch($research);
        $time = new \DateTime();
        $message->setTime($time);
        $message->setSender(true);

        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('message_show', array('id' => $message->getId()));
        }

        return $this->render('AppBundle:Message:new.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
            'research_title' => $research->getTitle(),
        ));
    }

    /**
     * Creates a new message entity.
     *
     * @Route("/new/from/{research_id}/to/{user_id}")
     * @Method({"GET", "POST"})
     */
    public function newResMesAction(Request $request, $research_id, $user_id)
    {
        $message = new Message();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($user_id);
        $message->setUser($user);

        $research = $this->getDoctrine()->getRepository('AppBundle:Research')->find($research_id);
        $message->setResearch($research);
        $time = new \DateTime();
        $message->setTime($time);

        $message->setSender(false);

        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('message_show', array('id' => $message->getId()));
        }

        return $this->render('@App/Message/newResMes.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
            'research_title' => $research->getTitle(),
        ));
    }

    /**
     * Finds and displays a message entity.
     *
     * @Route("/{id}", name="message_show")
     * @Method("GET")
     */
    public function showAction(Message $message)
    {
        return $this->render('@App/Message/show.html.twig', array(
            'message' => $message,
        ));
    }


    /**
     * @Route("new/to/{id}")
     * @Template("@App/Message/newUsResMes.html.twig")
     */

    public function newUsResMesAction(Request $request, $id)
    {
        $loggedUser = $this->getUser();
        $message = new Message();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $message->setUser($user);
        $time = new \DateTime();
        $message->setTime($time);

        $message->setSender(false);

        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $researchId = $request->request->get('research');
            $research = $this->getDoctrine()->getRepository('AppBundle:Research')->find($researchId);
            $message->setResearch($research);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('message_show', array('id' => $message->getId()));
        }


        return ['logged_user' => $loggedUser,
            'form' => $form->createView(), 'message' => $message];

    }

    /**
     * @Route("/delete/{id}")
     * @Method("POST")
     */
    public function hideUserAction(Message $message)
    {
        $access = $message->getAccess();
        if ($access == 3) {
            $message->setAccess(2);
        } else {
            $message->setAccess(0);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();


        return $this->redirectToRoute('message_index');
    }

}
