<?php
/**
 * Auth controller.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Form\LoginForm;
use Model\UsersModel;

/**
 * Class AuthController.
 *
 * @package Controller
 * @implements ControllerProviderInterface
 */
class AuthController implements ControllerProviderInterface
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
     * @param Silex\Application $app Silex application
     * @return AlbumsController Result
     */
    public function connect(Application $app)
    {
        $authController = $app['controllers_factory'];
        $authController->match('login', array($this, 'loginAction'))
            ->bind('auth_login');
        $authController->get('logout', array($this, 'logoutAction'))
            ->bind('auth_logout');
        $authController->match('sign_in', array($this, 'signInAction'))
            ->bind('sign_in');
        return $authController;
    }

    /**
     * Login action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function loginAction(Application $app, Request $request)
    {
        $user = array(
            'login' => $app['session']->get('_security.last_username')
        );

        $form = $app['form.factory']->createBuilder(new LoginForm(), $user)
            ->getForm();

        $this->view = array(
            'form' => $form->createView(),
            'error' => $app['security.last_error']($request)
        );

        return $app['twig']->render('auth/login.twig', $this->view);
    }

    /**
     * Logout action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function logoutAction(Application $app, Request $request)
    {
        $app['session']->clear();
        return $app['twig']->render('auth/logout.twig', $this->view);
    }

    /**
     * Sign In action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function signInAction(Application $app, Request $request)
    {
        // default values:
        $data = array(
            'login' => '',
            'password' => '',
            'confirm' => '',
            'role_id' => '2',
        );
        $form = $app['form.factory']
            ->createBuilder(new LoginForm(), $data)->getForm();
        $form->add('confirm', 'password');
        $form->add('role_id', 'hidden');
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            if($data['password'] === $data['confirm']) {
                unset($data['confirm']);
                $data['password'] = $app['security.encoder.digest']->encodePassword($data['password'],'');
                $usersModel = new UsersModel($app);
                $usersModel->signIn($data);
                $app['session']->getFlashBag()->add(
                    'message', array(
                        'type' => 'success', 'content' => 
                        $app['translator']->trans('New user added.')
                               )
                        );
                return $app->redirect(
                    $app['url_generator']->generate('albums_index'), 301
                );
            }
        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('auth/sign_in.twig', $this->view);
    }

}
