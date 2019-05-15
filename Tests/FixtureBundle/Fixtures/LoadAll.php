<?php
/*
 * This file is part of RestAdminBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\RestAdminBundle\Tests\FixtureBundle\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Author;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Comment;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Searchable;

/**
 * Class LoadAll
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class LoadAll extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $author = new Author();
        $author->setName('author 1');
        $author->setLastname('Foo');
        $author->setEmail('author@authorship.org');

        $manager->persist($author);

        $author2 = new Author();
        $author2->setName('author 2');
        $author2->setLastname('Bar');
        $author2->setEmail('author2@authorship.org');

        $manager->persist($author2);

        $date = new \DateTime();

        // First post
        $post = new Post($author);
        $post->setContent('content');
        $post->getEmbed()->setDate(clone $date);

        $manager->persist($post);
        $date->add(new \DateInterval('P1D'));

        // Second post
        $post = new Post($author);
        $post->setContent('second post');
        $post->setStatus(Post::STATUS_EDITING);
        $post->getEmbed()->setDate(clone $date);

        $comment = new Comment($post);
        $comment->setComment('comment1');
        $manager->persist($comment);

        $manager->persist($post);
        $date->add(new \DateInterval('P1D'));

        // Third post
        $post = new Post($author2);
        $post->setContent('third post');
        $post->setStatus(Post::STATUS_PUBLISHED);
        $post->setActive(true);
        $post->getEmbed()->setDate(clone $date);

        $comment = new Comment($post);
        $comment->setComment('comment2');
        $manager->persist($comment);
        $comment = new Comment($post);
        $comment->setComment('comment3');
        $manager->persist($comment);

        $manager->persist($post);

        // Searchables
        $searchable = new Searchable();
        $searchable->setSearchBegin("_foo_");

        $searchable2 = new Searchable();
        $searchable2->setSearchInside("_bar_");

        $searchable3 = new Searchable();
        $searchable3->setSearchEnd("_baz_");

        $searchable4 = new Searchable();
        $searchable4->setSearchWord("Lorem ipsum dolor sit amet");

        $manager->persist($searchable);
        $manager->persist($searchable2);
        $manager->persist($searchable3);
        $manager->persist($searchable4);

        $manager->flush();
    }
}