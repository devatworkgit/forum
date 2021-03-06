<?php
/**
 * Created by PhpStorm.
 * User: Quy
 * Date: 11/25/2015
 * Time: 2:59 PM
 */

namespace Drupal\custom_breadcrumb\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Link;

class CustomNodeBreadcrumbBuilder implements BreadcrumbBuilderInterface {
    use StringTranslationTrait;

    /**
     * @inheritDoc
     */
    public function applies(RouteMatchInterface $route_match) {
        $parameters = $route_match->getParameters()->all();
        if (isset($parameters['node'])) {
            return $parameters['node']->getType() == 'article'
            || $parameters['node']->getType() == 'blog'
            || $parameters['node']->getType() == 'product'
            || $parameters['node']->getType() == 'page';
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function build(RouteMatchInterface $route_match) {
        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(['route']);

        $links[] = Link::createFromRoute($this->t('Home'), '<front>');
        $node = $route_match->getParameter('node');
        $links[] = Link::createFromRoute($node->title->value, 'entity.node.canonical', array('node' => $node->id()));

        return $breadcrumb->setLinks($links);
    }
}