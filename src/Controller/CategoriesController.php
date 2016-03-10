<?php
/**
 * Categories controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\CategoriesModel;
use Form\CategoryForm;


/**
 * Class CategoriesController.
 *
 * @package Controller
 * @implements ControllerProviderInterface
 */
class CategoriesController implements ControllerProviderInterface
{

    /**
     * Data for view.
     *
     * @access protected
     * @var array $_view
     */
    protected $_view = array();

    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return CategoriesController Result
     */
    public function connect(Application $app)
    {
        $categoriesController = $app['controllers_factory'];
	$categoriesController->get('/', array($this, 'indexAction'));
        $categoriesController->match('/add', array($this, 'addAction'))
            ->bind('categories_add');
        $categoriesController->match('/add/', array($this, 'addAction'));
        $categoriesController->match('/edit/{id}', array($this, 'editAction'))
            ->bind('categories_edit');
        $categoriesController->match('/edit/{id}/', array($this, 'editAction'));
        $categoriesController->match('/delete/{id}', array($this, 'deleteAction'))
            ->bind('categories_delete');
        $categoriesController->match('/delete/{id}/', array($this, 'deleteAction'));
        $categoriesController->get('/view/{id}', array($this, 'viewAction'))
            ->bind('categories_view');
        $categoriesController->get('/view/{id}/', array($this, 'viewAction'));
        $categoriesController->get('/index', array($this, 'indexAction'));
        $categoriesController->get('/index/', array($this, 'indexAction'));
        $categoriesController->get('/{page}', array($this, 'indexAction'))
                         ->value('page', 1)->bind('categories_index');
        return $categoriesController;
    }

    /**
     * Index action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function indexAction(Application $app, Request $request)
    {
//$a = array();
        $categoriesModel = new CategoriesModel($app);
//b = $categoriesModel->countRecords(3);
//foreach($b as $value) {
//$a[$value['id']] = $value['name']; }
//var_dump($b);
//if($b['counter'] > 0) echo "lol";
        $this->_view['categories'] = $categoriesModel->getAll();
        return $app['twig']->render('categories/index.twig', $this->_view);
    }


    /**
     * Add action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function addAction(Application $app, Request $request)
    {
        // default values:
        $data = array(
            'name' => 'Name',
        );
        $form = $app['form.factory']
            ->createBuilder(new CategoryForm(), $data)->getForm();
        $form->remove('id');
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $categoriesModel = new CategoriesModel($app);
            $categoriesModel->saveCategory($data);
            $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' => 
                    $app['translator']->trans('New category added.')
    )
);
            return $app->redirect(
                $app['url_generator']->generate('categories_index'), 301
            );
        }

        $this->_view['form'] = $form->createView();

        return $app['twig']->render('categories/add.twig', $this->_view);
    }

    /**
     * Edit action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function editAction(Application $app, Request $request)
    {

        $categoriesModel = new CategoriesModel($app);
        $id = (int) $request->get('id', 0);
        $category = $categoriesModel->getCategory($id);
        if (count($category)) {
            $form = $app['form.factory']
                ->createBuilder(new CategoryForm(), $category)->getForm();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $categoriesModel = new CategoriesModel($app);
                $categoriesModel->saveCategory($data);
                $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' =>
                    $app['translator']->trans('Category edited.')
                           )
                );
                return $app->redirect(
                    $app['url_generator']->generate('categories_index'), 301
                );
            }

            $this->_view['id'] = $id;
            $this->_view['form'] = $form->createView();

        } else {
            return $app->redirect(
                $app['url_generator']->generate('categories_add'), 301
            );
        }

        return $app['twig']->render('categories/edit.twig', $this->_view);
    }

    /**
     * Delete action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function deleteAction(Application $app, Request $request)
    {

        $categoriesModel = new CategoriesModel($app);
        $id = (int) $request->get('id', 0);
        $category = $categoriesModel->getCategory($id);
        $this->_view = $categoriesModel->countRecords($id);

        if (count($category)) {
            $form = $app['form.factory']
                ->createBuilder(new CategoryForm(), $category)->getForm();
            $form->remove('name');
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $categoriesModel = new CategoriesModel($app);
                $categoriesModel->deleteCategory($data);
                $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' =>
                    $app['translator']->trans('Category deleted.')
                           )
                );
                return $app->redirect(
                    $app['url_generator']->generate('categories_index'), 301
                );
            }

            $this->_view['id'] = $id;
            $this->_view['form'] = $form->createView();

        } else {
            return $app->redirect(
                $app['url_generator']->generate('categories_add'), 301
            );
        }

        return $app['twig']->render('categories/delete.twig', $this->_view);
    }

}
