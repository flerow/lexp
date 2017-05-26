<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Research;
use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ManagerController extends Controller
{
    /**
     *
     * @Route("/manager/new", name="research_new")
     * @Method({"GET", "POST"})
     * @Template("@App/Manager/newResearch.html.twig")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $research = new Research();
        $user = $this->getUser();
        $research->setUser($user);
        $form = $this->createForm('AppBundle\Form\ResearchType', $research);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($research);
            $em->flush();

            return $this->redirectToRoute('app_manager_showresearches');
        }

        return ['research' => $research,
            'form' => $form->createView()];
    }

    /**
     * @Route("/manager/base")
     * @Template("@App/Manager/showUsers.html.twig")
     */

    public function showBaseAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        return ['users' => $users];
    }

    /**
     * @Route("/manager/researches")
     * @Template("@App/Manager/researches.html.twig")
     */

    public function showResearches()
    {
        $user = $this->getUser();
        $researches = $this->getDoctrine()->getRepository('AppBundle:Research')->findBy(['user' => $user]);
        return ['researches' => $researches];
    }

    /**
     * @Route("/manager/edit/{id}", name="research_edit")
     * @Template("@App/Manager/editResearch.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $research = $this->getDoctrine()->getRepository('AppBundle:Research')->find($id);
        $editForm = $this->createForm('AppBundle\Form\ResearchType', $research);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_manager_showresearches');
        }

        $tagName = $request->request->get('tag')['name'];

        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['name' => $tagName]);
        if ($research->hasTag($tag)) {
            throw $this->createAccessDeniedException('badanie ma juz taki tag');
        }


        if (!$tag) {
            $tag = new Tag();
            $formTag = $this->createForm(TagType::class, $tag);
            $formTag->handleRequest($request);
            $tag->addResearch($research);

            if ($formTag->isSubmitted() && $formTag->isValid()) {
                $em->persist($tag);
                $em->flush();

                return $this->redirectToRoute('research_edit', ['id' => $research->getId()]);
            }
        } else {
            $formTag = $this->createForm(TagType::class, $tag);
            $formTag->handleRequest($request);
            $tag->addResearch($research);

            if ($formTag->isSubmitted() && $formTag->isValid()) {
                $em->flush();

                return $this->redirectToRoute('research_edit', ['id' => $research->getId()]);
            }
        }


        return ['form' => $editForm->createView(), 'formTag' => $formTag->createView(), 'research' => $research];
    }

    /**
     * @Route("/manager/delete/{id}")
     * @Method("POST")
     */
    public function removeResearch(Research $research, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($research);
        $em->flush();

        return $this->redirectToRoute('app_manager_showresearches');
    }

    /**
     * @Route("manager/hidemess/{id}")
     */
    public function hideMessage(Message $message)
    {
        $access = $message->getAccess();
        if ($access == 3) {
            $message->setAccess(1);
        } else {
            $message->setAccess(0);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();


        return $this->redirectToRoute('app_manager_showresearches');
    }

    /**
     * @Route("/manager/deltag/{id}/res/{resId}")
     * @Method("POST")
     */
    public function delTagAction(Request $request, Tag $tag, $resId)
    {
        $research = $this->getDoctrine()->getRepository('AppBundle:Research')->find($resId);
        $tag->removeResearch($research);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('research_edit', ['id'=>$resId]);
    }

}
