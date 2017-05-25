<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * @Route("/modify")
     * @Template("AppBundle:Profile:modify.html.twig")
     */
    public function modifyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $em->flush();
            $this->addFlash(
                'notice',
                'Zapisano!'
            );

            return $this->redirectToRoute('app_profile_modify');
        }

        $tagName = $request->request->get('tag')['name'];
        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['name' => $tagName]);
        if ($user->hasTag($tag)) {
            throw $this->createAccessDeniedException('masz juz taki tag');
        }
        if (!$tag) {
            $tag = new Tag();
            $formTag = $this->createForm(TagType::class, $tag);
            $formTag->handleRequest($request);
            $tag->addUser($user);

            if ($formTag->isSubmitted() && $formTag->isValid()) {
                $em->persist($tag);
                $em->flush();

                return $this->redirectToRoute('app_profile_modify');
            }
        } else {
            $formTag = $this->createForm(TagType::class, $tag);
            $formTag->handleRequest($request);
            $tag->addUser($user);

            if ($formTag->isSubmitted() && $formTag->isValid()) {
                $em->flush();

                return $this->redirectToRoute('app_profile_modify');
            }
        }

        return [
            'formUser' => $formUser->createView(),
            'user' => $user,
            'formTag' => $formTag->createView()
        ];
    }

    /**
     * @Route("/profile/lethim")
     */
    public function lethimAction(Request $request)
    {
        $user = $this->getUser();
        $user->addRole('ROLE_RESEARCHER');
        $em =  $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('fos_user_security_login');
    }
}
