Create a Symfony CRUD application with AdminLTE template
========================================================

This repository allows you to create a fresh new install of symfony including some bundles to start developping a CRUD application based on the AdminLTE template.

The list of bundles added to the project are the following :


Start a project
---------------

Run the following command and you'll be able to run your initial symfony project

    composer create-project --prefer-dist --stability=dev gilles-hemmerle/symfony-crud-adminlte:master myApp
    cd myApp
    php app/console doctrine:schema:update --force


Then create a user to test the project
    php app/console fos:user:create user1
    php app/console fos:user:promote user1 ROLE_ADMIN


Create a CRUD application (exemple)
-----------------------------------

Let's create an article and a tag CRUD 

### First, Create the two entities

* Create the Article entity

    <?php
    // src/AppBundle/Entity/Article.php

    namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * Articles
     *
     * @ORM\Table(name="article")
     * @ORM\Entity
     *
     */
    class Article
    {
        /**
         * @var string
         *
         * @ORM\Column(name="slug", type="string", length=120, nullable=false)
         */
        private $slug;

        /**
         * @var string
         *
         * @ORM\Column(name="article_title", type="string", length=120, nullable=false)
         */
        private $articleTitle;

        /**
         * @var string
         *
         * @ORM\Column(name="article_content", type="text")
         */
        private $articleContent;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="articles")
         * @ORM\JoinTable(name="articles_tags")
         */
        private $tags;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->tags = new \Doctrine\Common\Collections\ArrayCollection();

        }

        public function __toString() {
            return $this->articleTitle;
        }
    }

* Then run the following command to generate the getter / setters

    php app/console generate:doctrine:entities AppBundle:Article --no-backup


* Create the Tag entity 


    <?php

    namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * Articles
     *
     * @ORM\Table(name="tag")
     * @ORM\Entity
     *
     */
    class Tag
    {
        /**
         * @var string
         *
         * @ORM\Column(name="tag", type="string", length=45, nullable=false)
         */
        private $tag;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Article", mappedBy="tags")
         */
        private $articles;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->articles = new \Doctrine\Common\Collections\ArrayCollection();

        }

        public function __toString() {
            return $this->tag;
        }
    }

* Then run the following command to generate the getter / setters

    php app/console generate:doctrine:entities AppBundle:Tag --no-backup

### Secondly, generate the CRUD
    
    php app/console doctrine:generate:crud --entity=AppBundle:Tag --route-prefix=/admin/tag --with-write --overwrite
    php app/console doctrine:generate:crud --entity=AppBundle:Article --route-prefix=/admin/article --with-write --overwrite

You are now able to show your pages with the followin urls :
* /admin/tag
* /admin/article

Manage the menu
---------------

Now that you have created the CRUD pages. You will need a sidebar to show your pages to your users. This is achieved in the src/AppBundle/EventListener/MenuItemListener.php file.

The entries for the article and tag sections are already in. You'll just have to uncomment them in order to show them in the sidebar. You can have more information in the documentation of [https://github.com/avanzu/AdminThemeBundle/blob/master/Resources/docs/sidebar_navigation.md](the Avanzu bundle).

