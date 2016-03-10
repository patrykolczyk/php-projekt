<?php
/**
 * Data controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class DataController implements ControllerProviderInterface
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
        $dataController = $app['controllers_factory'];
        $dataController->get('/', array($this, 'indexAction'))->bind('/data');
        $dataController->get('/index', array($this, 'indexAction'));
        $dataController->get('/index/', array($this, 'indexAction')); 
        $dataController->get('/view/{id}', array($this, 'viewAction'))->bind('/data/view');
        return $dataController;
    }


    private function _getData()
    {
        return array(
           0 => array(
               'name' => 'John',
               'email' => 'john@example.com',
           ),
           1 => array(
               'name' => 'Mark',
               'email' => 'mark@example.com',
           ),
           2 => array(
	       'name' => 'David',
	       'email' => 'david@example.com',
	   ),
	);

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
        $view['data'] = $this->_getData();
        return $app['twig']->render('data/index.twig', $view);
    }

    public function viewAction(Application $app, Request $request)
    {
        $view = array();
	$id = $request->get('id', null);
	$data = $this->_getData();
        $view['item'] = isset($data[$id])?$data[$id]:array();
        return $app['twig']->render('data/view.twig', $view);
    }


}
