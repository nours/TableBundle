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
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;

/**
 * Class LoadAll
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class LoadAll extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setContent('content');

        $manager->persist($post);

        $post = new Post();
        $post->setContent('second post');
        $post->setStatus(Post::STATUS_EDITING);

        $manager->persist($post);

        $post = new Post();
        $post->setContent('third post');
        $post->setStatus(Post::STATUS_PUBLISHED);
        $post->setActive(true);

        $manager->persist($post);

        $manager->flush();
    }
}