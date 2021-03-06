<?php

namespace Marello\Bundle\CatalogBundle\Controller;

use Marello\Bundle\CatalogBundle\Entity\Category;
use Marello\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\SecurityBundle\Annotation as Security;
use Oro\Bundle\UIBundle\Route\Router;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * @Config\Route(
     *      "/{_format}",
     *      name="marello_category_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format"="html"}
     * )
     * @Security\AclAncestor("marello_category_view")
     * @Config\Template
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Config\Route("/create", name="marello_category_create")
     * @Security\AclAncestor("marello_category_create")
     * @Config\Template("MarelloCatalogBundle:Category:update.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->update(new Category());
    }

    /**
     * @Config\Route("/update/{id}", requirements={"id"="\d+"}, name="marello_category_update")
     * @Security\AclAncestor("marello_category_update")
     * @Config\Template("MarelloCatalogBundle:Category:update.html.twig")
     *
     * @param Category $category
     *
     * @return array
     */
    public function updateAction(Category $category)
    {
        return $this->update($category);
    }

    /**
     * @param Category $category
     *
     * @return array
     */
    protected function update(Category $category)
    {
        $handler = $this->get('marello_catalog.category.form.handler');

        if ($handler->process($category)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('marello.catalog.ui.category.saved.message')
            );

            return $this->get('oro_ui.router')->redirect($category);
        }

        return [
            'entity' => $category,
            'form'   => $handler->getFormView(),
        ];
    }

    /**
     * @Config\Route("/view/{id}", requirements={"id"="\d+"}, name="marello_category_view")
     * @Security\AclAncestor("marello_category_view")
     * @Config\Template("MarelloCatalogBundle:Category:view.html.twig")
     *
     * @param Category $category
     *
     * @return array
     */
    public function viewAction(Category $category)
    {
        return [
            'entity' => $category,
        ];
    }
}
