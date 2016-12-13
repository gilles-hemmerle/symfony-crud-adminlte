<?php

namespace AppBundle\EventListener;


use AppBundle\Model\MenuItemModel;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;

class MenuItemListener {

    private $securityContext = null;

    public function __construct($securityContext) {
        $this->securityContext = $securityContext;
    }


    public function onSetupMenu(SidebarMenuEvent $event) {

        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }

    }

    protected function getMenu(Request $request) {
        // Build your menu here by constructing a MenuItemModel array
        $menuItems = array();

        if ($this->securityContext->getToken()) {
            if ($this->securityContext->isGranted('ROLE_ADMIN')) {
                $menuItems = array(
                    // $user = new MenuItemModel('UserEntry', 'User Management', '', array(/* options */), 'iconclasses fa fa-users'),
                    // $articles = new MenuItemModel('ArticlesList', 'Articles', 'admin_article_index', array(/* options */), 'iconclasses fa fa-newspaper-o'),
                    // $tags = new MenuItemModel('TagsList', 'Tags', 'admin_tag_index', array(/* options */), 'fa fa-tags'),  
                );
                
            } else {
                $menuItems = array();
            }
        }

        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    protected function activateByRoute($route, $items) {

        foreach($items as $item) {
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            }
            else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }

}