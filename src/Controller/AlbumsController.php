<?php
/**
 * Albums controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\AlbumsModel;
use Form\AlbumForm;

/**
 * Class AlbumsController.
 *
 * @package Controller
 * @implements ControllerProviderInterface
 */
class AlbumsController implements ControllerProviderInterface
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
     * @return AlbumsController Result
     */
    public function connect(Application $app)
    {
        $albumsController = $app['controllers_factory'];
	$albumsController->get('/', array($this, 'indexAction'));
        $albumsController->match('/add', array($this, 'addAction'))
            ->bind('albums_add');
        $albumsController->match('/add/', array($this, 'addAction'));
        $albumsController->match('/edit/{id}', array($this, 'editAction'))
            ->bind('albums_edit');
        $albumsController->match('/edit/{id}/', array($this, 'editAction'));
        $albumsController->match('/delete/{id}', array($this, 'deleteAction'))
            ->bind('albums_delete');
        $albumsController->match('/delete/{id}/', array($this, 'deleteAction'));
        $albumsController->get('/view/{id}', array($this, 'viewAction'))
            ->bind('albums_view');
        $albumsController->get('/view/{id}/', array($this, 'viewAction'));
        $albumsController->get('/index', array($this, 'indexAction'));
        $albumsController->get('/index/', array($this, 'indexAction'));
        $albumsController->get('/{page}', array($this, 'indexAction'))
                         ->value('page', 1)->bind('albums_index');
        return $albumsController;
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
        $pageLimit = 3;
        $page = (int) $request->get('page', 1);
        $albumsModel = new AlbumsModel($app);
        $this->_view = array_merge(
        $this->_view, $albumsModel->getPaginatedAlbums($page, $pageLimit));
        return $app['twig']->render('albums/index.twig', $this->_view);
    }

    /**
     * View action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function viewAction(Application $app, Request $request)
    {
        try {
            $id = (int)$request->get('id', null);
            $albumsModel = new AlbumsModel($app);
            $this->view['album'] = $albumsModel->getAlbumToView($id);
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Album not found'));
        }
        return $app['twig']->render('albums/view.twig', $this->view);
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
            'title' => 'Title',
            'artist' => 'Artist',
        );
        $form = $app['form.factory']
            ->createBuilder(new AlbumForm($app), $data)->getForm();
        $form->remove('id');
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $albumsModel = new AlbumsModel($app);
            $albumsModel->saveAlbum($data);
            $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' => 
                    $app['translator']->trans('New album added.')
    )
);


            return $app->redirect(
                $app['url_generator']->generate('albums_index'), 301
            );

        }

        $this->_view['form'] = $form->createView();

        return $app['twig']->render('albums/add.twig', $this->_view);
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

        $albumsModel = new AlbumsModel($app);
        $id = (int) $request->get('id', 0);
        $album = $albumsModel->getAlbum($id);
        if (count($album)) {
            $form = $app['form.factory']
                ->createBuilder(new AlbumForm($app), $album)->getForm();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $albumsModel = new AlbumsModel($app);
                $albumsModel->saveAlbum($data);
                $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' =>
                    $app['translator']->trans('Album edited.')
                           )
                );
                return $app->redirect(
                    $app['url_generator']->generate('albums_index'), 301
                );
            }

            $this->_view['id'] = $id;
            $this->_view['form'] = $form->createView();

        } else {
            return $app->redirect(
                $app['url_generator']->generate('albums_add'), 301
            );
        }

        return $app['twig']->render('albums/edit.twig', $this->_view);
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

        $albumsModel = new AlbumsModel($app);
        $id = (int) $request->get('id', 0);
        $album = $albumsModel->getAlbum($id);

        if (count($album)) {
            $form = $app['form.factory']
                ->createBuilder(new AlbumForm($app), $album)->getForm();
            $form->remove('title');
            $form->remove('artist');
            $form->remove('category_id');
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $albumsModel = new AlbumsModel($app);
                $albumsModel->deleteAlbum($data);
                $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' =>
                    $app['translator']->trans('Album deleted.')
                           )
                );
                return $app->redirect(
                    $app['url_generator']->generate('albums_index'), 301
                );
            }

            $this->_view['id'] = $id;
            $this->_view['form'] = $form->createView();

        } else {
            return $app->redirect(
                $app['url_generator']->generate('albums_add'), 301
            );
        }

        return $app['twig']->render('albums/delete.twig', $this->_view);
    }

}
