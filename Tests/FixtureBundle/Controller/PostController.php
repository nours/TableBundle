<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\FixtureBundle\Controller;

use Nours\TableBundle\Tests\FixtureBundle\Entity\Author;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="post_index")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $table = $this->get('nours_table.factory')->createTable('post', array(
            'url' => $this->generateUrl('post_index'),
            'data' => $this->getData()
        ));

        $table->handle($request);

        if ($request->isXmlHttpRequest()) {
            return new Response($this->get('jms_serializer')->serialize($table->createView(), 'json'), 200, array(
                'Content-Type' => 'application/json'
            ));
        }

        return $this->render('post/index.html.twig', array(
            'table' => $table->createView()
        ));
    }

    /**
     * @Route("/table-theme", name="post_table_theme")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableThemeAction(Request $request)
    {
        $table = $this->get('nours_table.factory')->createTable('post', array(
            'url' => $this->generateUrl('post_index'),
            'data' => $this->getData()
        ));

        $table->handle($request);

        if ($request->isXmlHttpRequest()) {
            return new Response($this->get('jms_serializer')->serialize($table->createView(), 'json'), 200, array(
                'Content-Type' => 'application/json'
            ));
        }

        return $this->render('post/table_theme.html.twig', array(
            'table' => $table->createView()
        ));
    }


    private function getData()
    {
        $content = "<p>Lorem Ipsum 1234567890. Lorem Ipsum 1234567890. Lorem Ipsum 1234567890. Lorem Ipsum 1234567890. Lorem Ipsum 1234567890.</p>";

        $author = new Author();
        $post = new Post($author);
        $post->setContent($content);

        return array($post);
    }
}