<?php

namespace Nours\TableBundle\Tests\Factory;


use Nours\TableBundle\Table\Factory\TableFactory;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Nours\TableBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DoctrineORMExtensionTest extends TestCase
{
    /**
     * @var TableFactory
     */
    private $factory;

    public function setUp()
    {
        parent::setUp();

        $this->factory = $this->getTableFactory();
    }

    public function testQueryBuilderOption()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
            'limit' => 15,
        ))->handle();

        $this->assertEquals(1, $table->getPage());
        $this->assertEquals(15, $table->getLimit());
        $this->assertEquals(1, $table->getPages());
        $this->assertEquals(3, $table->getTotal());
        $this->assertCount(3, $table->getData());
        $this->assertTrue($table->getOption('sortable'));
        $this->assertTrue($table->getOption('searchable'));
        $this->assertTrue($table->getOption('filterable'));
    }

    /**
     * The content field is not searchable
     */
    public function testSortByContentThrows()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
            'sort' => 'content',
            'order' => 'DESC'
        ));

        $this->setExpectedException("InvalidArgumentException");

        $table->handle();
    }

    public function testSortByStatus()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
            'sort' => 'status',
            'order' => 'ASC'
        ))->handle();

        $data = $table->getData();

        // Order is scrambled (see FixtureBundle\Fixtures\LoadAll)
        $this->assertCount(3, $data);
        $this->assertEquals(2, $data[0]->getId());
        $this->assertEquals(1, $data[1]->getId());
        $this->assertEquals(3, $data[2]->getId());
    }

    public function testSearchByContentAndSort()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
            'search' => 'post',
            'sort' => 'status',
            'order' => 'ASC'
        ))->handle();

        $data = $table->getData();

        // Order is scrambled (see FixtureBundle\Fixtures\LoadAll)
        $this->assertCount(2, $data);
        $this->assertEquals(2, $data[0]->getId());
        $this->assertEquals(3, $data[1]->getId());
    }

    public function testSortByIsActive()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
            'sort' => 'isActive',
            'order' => 'DESC'
        ))->handle();

        $data = $table->getData();

        $this->assertCount(3, $data);
        $this->assertEquals(3, $data[0]->getId());
        $this->assertEquals(1, $data[1]->getId());
        $this->assertEquals(2, $data[2]->getId());
    }

    public function testSearchByStatus()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
            'search' => 'published'
        ))->handle();

        $data = $table->getData();

        $this->assertCount(1, $data);
        $this->assertEquals(3, $data[0]->getId());
    }

    public function testFilterByStatus()
    {
        $this->loadFixtures();

        $table = $this->createTable('post', array(
        ));

        $table->handle(new Request(array(
            'filter' => array(
                'status' => Post::STATUS_EDITING
            )
        )));

        $data = $table->getData();
        $this->assertCount(1, $data);
        $this->assertEquals(2, $data[0]->getId());
    }

    public function testSortByEmbeddedDate()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_embed', array(
            'sort' => 'date',
            'order' => 'desc'
        ))->handle();

        $data = $table->getData();

        $this->assertCount(3, $data);
        $this->assertEquals(3, $data[0]->getId());
        $this->assertEquals(2, $data[1]->getId());
        $this->assertEquals(1, $data[2]->getId());
    }

    public function testSearchAndSortUsingAssociationField()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_embed', array(
            'search' => 'author2@authorship.org',
            'sort' => 'author_email',
            'order' => 'desc'
        ))->handle();

        $data = $table->getData();

        $this->assertCount(1, $data);
        $this->assertEquals(3, $data[0]->getId());
    }

    public function testSearchCanResultNothing()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_embed', array(
            'search' => 'foobarbaz',
            'sort' => 'author',
            'order' => 'desc'
        ))->handle();

        $data = $table->getData();

        $this->assertCount(0, $data);
    }

    public function testFilterUsingAssociationField()
    {
        $this->loadFixtures();
        $author = $this->getEntityManager()->find('FixtureBundle:Author', 1);

        $table = $this->createTable('post_embed', array(
            'sort' => 'date',
            'order' => 'desc',
            'filter_data' => array(
                'author' => $author
            )
        ));

        $table->handle();

        $data = $table->getData();

        $this->assertCount(2, $data);
        $this->assertEquals(2, $data[0]->getId());
        $this->assertEquals(1, $data[1]->getId());
    }

    public function testFilterUsingAssociationFieldForm()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_embed');

        $table->handle(new Request(array(
            'filter' => array(
                'author' => 2
            )
        )));

        $data = $table->getData();

        $this->assertCount(1, $data);
        $this->assertEquals(3, $data[0]->getId());
    }

    public function testFilterUsingAssociationArrayFieldForm()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_comments');

        $table->handle(new Request(array(
            'filter' => array(
                'comments' => array(2)
            )
        )));

        $data = $table->getData();

        $this->assertCount(1, $data);
        $this->assertEquals(3, $data[0]->getId());

        // Another request
        $table = $this->createTable('post_comments');
        $table->handle(new Request(array(
            'filter' => array(
                'comments' => array(1)
            )
        )));

        $data = $table->getData();

        $this->assertCount(1, $data);
        $this->assertEquals(2, $data[0]->getId());
    }

    public function testDefaultFilterParam()
    {
        $this->loadFixtures();
        $author = $this->getEntityManager()->find('FixtureBundle:Author', 2);

        $table = $this->createTable('post_embed', array(
            'sort' => 'date',
            'order' => 'desc',
            'filter_data' => array(
                'author' => $author
            )
        ));

        // Form must not be submitted, otherwise it will blank default options
        $table->handle(new Request(array(
        )));

        $data = $table->getData();

        $this->assertCount(1, $data);
        $this->assertEquals(3, $data[0]->getId());
    }

    public function testFilterMultipleChoice()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_status');

        // Form must not be submitted, otherwise it will blank default options
        $table->handle(new Request(array(
            'filter' => array(
                'status' => array(Post::STATUS_NEW, Post::STATUS_PUBLISHED)
            )
        )));

        $data = $table->getData();

        $this->assertCount(2, $data);
        $this->assertEquals(1, $data[0]->getId());
        $this->assertEquals(3, $data[1]->getId());
    }

    /**
     * The post_comment_author table, based on post entity, has an association to post author
     * and to author's post (second level).
     */
    public function testTableWithSubAssociations()
    {
        $this->loadFixtures();

        $table = $this->createTable('post_comment_author');

        // Form must not be submitted, otherwise it will blank default options
        $table->handle(new Request(array(
        )));

        $data = $table->getData();

        $this->assertCount(3, $data);
    }
} 