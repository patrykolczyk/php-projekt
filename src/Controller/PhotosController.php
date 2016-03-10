<?php
/**
 * Photos controller.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Controller;

use Model\ImagesModel;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\PhotosModel;
use Form\PhotoForm;

/**
 * Class PhotoController.
 *
 * @category Epi
 * @package Controller
 * @implements ControllerProviderInterface
 */
class PhotosController implements ControllerProviderInterface
{
    /**
     * Data for view.
     *
     * @access protected
     * @var array $view
     */
    protected $view = array();

    /**
     * Routing settings.
     *
     * @access public
     * @param Application $app Silex application
     * @return PhotosController Result
     */
    public function connect(Application $app)
    {
        $photosController = $app['controllers_factory'];
        $photosController->match('/add', array($this, 'addAction'))
                         ->bind('photos_add');
        $photosController->post('/add', array($this, 'addAction'));
        return $photosController;
    }

    /**
     * Add action.
     *
     * @access public
     * @param Application $app Silex application
     * @param Request $request Request object
     * @return string Output
     */
    public function addAction(Application $app, Request $request)
    {
        // default values:
        $data = array();
        $form = $app['form.factory']
            ->createBuilder(new PhotoForm(), $data)->getForm();

        if ($request->isMethod('POST')) {

            $form->bind($request);

            if ($form->isValid()) {

                try {
                    $files = $request->files->get($form->getName());

                    $mediaPath = dirname(dirname(dirname(__FILE__))).'/web/upload';
                    $photosModel = new PhotosModel($app);
                    $photosModel->saveImage($files, $mediaPath);

                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'success',
                            'content' => $app['translator']
                                ->trans('File successfully uploaded.')
                        )
                    );

                } catch (Exception $e) {
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'error',
                            'content' => $app['translator']
                                ->trans('Can not upload file.')
                        )
                    );
                }

            } else {
                $app['session']->getFlashBag()->add(
                    'message',
                    array(
                        'type' => 'error',
                        'content' => $app['translator']
                            ->trans('Form contains invalid data.')
                    )
                );
            }

        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('photos/add.twig', $this->view);
    }


}
