<?php
/**
 * Hello controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class HelloController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return HelloController Result
     */
    public function connect(Application $app)
    {
        $helloController = $app['controllers_factory'];
        $helloController->get('/{name}', array($this, 'indexAction'));
	$helloController->get('/{name}/', array($this, 'indexAction'));
        return $helloController;
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
        $view = array();
        $view['name'] = (string)$request->get('name', '');
        return $app['twig']->render('hello/index.twig', $view);
    }

}
