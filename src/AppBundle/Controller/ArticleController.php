<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @Route("/articles/{id}", name="article_show")
     */
    public function showAction(Article $article)
    {
        $data = $this->get('jms_serializer')->serialize($article, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/articles", name="article_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $article = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Article', 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/articles", name="article_list", methods={"GET"})
     */
    public function listAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        $data = $this->get('jms_serializer')->serialize($articles, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
}
