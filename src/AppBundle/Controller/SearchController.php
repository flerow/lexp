<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Method("POST")
     * @Route("/gen")
     */
    public function setPathAction(Request $request)
    {
        $localization = $request->request->get('localization', 'all');
        $tags = $request->request->get('tags');
        if (!$tags) {
            $tags = 'all';
        }
        if (!$localization) {
            $localization = 'all';
        }

        return $this->redirectToRoute('app_search_showfound', ['localization' => $localization, 'tags' => $tags]);
    }

    /**
     * @Route("/show/{localization}/{tags}")
     * @Template("AppBundle:User:showResearches.html.twig")
     */
    public function showFoundAction($localization, $tags)
    {
        if (!($localization == 'all') && !($tags == 'all')) {
            $tags = explode(',', $tags);
            foreach ($tags as $i => $tag) {
                $tags[$i] = trim($tag);
            }
            $researches = $this->getDoctrine()->getRepository('AppBundle:Research')->findByLocalizationTags($localization, $tags);

        } else if (!($localization == 'all')) {
            $researches = $this->getDoctrine()->getRepository('AppBundle:Research')->findBy(['localization' => $localization]);
        } else if (!($tags == 'all')) {
            $researches = $this->getDoctrine()->getRepository('AppBundle:Research')->findByTags($tags);
        } else {
            $researches = $this->getDoctrine()->getRepository('AppBundle:Research')->findAll();
        }
        return ['researches' => $researches];
    }


    /**
     * @Method("POST")
     * @Route("/genUs")
     */
    public function setPathUsAction(Request $request)
    {
        $localization = $request->request->get('localization', 'all');
        $tags = $request->request->get('tags');
        if (!$tags) {
            $tags = 'all';
        }
        if (!$localization) {
            $localization = 'all';
        }

        return $this->redirectToRoute('app_search_showfoundus', ['localization' => $localization, 'tags' => $tags]);
    }


    /**
     * @Route("/show/users/{localization}/{tags}")
     * @Template("AppBundle:Manager:showUsers.html.twig")
     */
    public function showFoundUsAction($localization, $tags)
    {
        if (!($localization == 'all') && !($tags == 'all')) {
            $tags = explode(',', $tags);
            foreach ($tags as $i => $tag) {
                $tags[$i] = trim($tag);
            }
            $query = "SELECT u FROM AppBundle:User u JOIN u.tags t WHERE t.name IN (:tags) AND u.localization = :localization";
            $users = $this->getDoctrine()->getEntityManager()
                ->createQuery($query)->setParameter('localization', $localization)->setParameter('tags', $tags)->getResult();

        } else if (!($localization == 'all')) {
            $users = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(['localization' => $localization]);
        } else if (!($tags == 'all')) {
            $query = "SELECT u FROM AppBundle:User u JOIN u.tags t WHERE t.name IN (:tags)";
            $users = $this->getDoctrine()->getEntityManager()
                ->createQuery($query)->setParameter('tags', $tags)->getResult();
        } else {
            $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        }
        return ['users' => $users];
    }
}
