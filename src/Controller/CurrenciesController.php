<?php
/**
 * Currencies controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class CurrenciesController implements ControllerProviderInterface
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
        $currenciesController = $app['controllers_factory'];
        $currenciesController->get('/', array($this, 'indexAction'))->bind('/currencies');
        $currenciesController->get('/index', array($this, 'indexAction'));
        $currenciesController->get('/index/', array($this, 'indexAction')); 
        $currenciesController->get('/view/{kod_waluty}', array($this, 'viewAction'))->bind('/currencies/view');
        return $currenciesController;
    }


  
	/**
 	* Demo data loader.
	 *
 	* @return array Demo data
 	*/
	private function _dataLoader()
	{
    return
        array(
            array(
                'nazwa_waluty' => 'bat (Tajlandia)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'THB',
                'kurs_sredni'  => '0,1173',
            ),
            array(
                'nazwa_waluty' => 'dolar amerykański',
                'przelicznik'  => '1',
                'kod_waluty'   => 'USD',
                'kurs_sredni'  => '3,8180',
            ),
            array(
                'nazwa_waluty' => 'dolar australijski',
                'przelicznik'  => '1',
                'kod_waluty'   => 'AUD',
                'kurs_sredni'  => '2,9671',
            ),
            array(
                'nazwa_waluty' => 'dolar Hongkongu',
                'przelicznik'  => '1',
                'kod_waluty'   => 'HKD',
                'kurs_sredni'  => '0,4923',
            ),
            array(
                'nazwa_waluty' => 'dolar kanadyjski',
                'przelicznik'  => '1',
                'kod_waluty'   => 'CAD',
                'kurs_sredni'  => '3,0310',
            ),
            array(
                'nazwa_waluty' => 'dolar nowozelandzki',
                'przelicznik'  => '1',
                'kod_waluty'   => 'NZD',
                'kurs_sredni'  => '2,8945',
            ),
            array(
                'nazwa_waluty' => 'dolar singapurski',
                'przelicznik'  => '1',
                'kod_waluty'   => 'SGD',
                'kurs_sredni'  => '2,7742',
            ),
            array(
                'nazwa_waluty' => 'euro',
                'przelicznik'  => '1',
                'kod_waluty'   => 'EUR',
                'kurs_sredni'  => '4,1287',
            ),
            array(
                'nazwa_waluty' => 'forint (Węgry)',
                'przelicznik'  => '100',
                'kod_waluty'   => 'HUF',
                'kurs_sredni'  => '1,3581',
            ),
            array(
                'nazwa_waluty' => 'frank szwajcarski',
                'przelicznik'  => '1',
                'kod_waluty'   => 'CHF',
                'kurs_sredni'  => '3,9055',
            ),
            array(
                'nazwa_waluty' => 'funt szterling',
                'przelicznik'  => '1',
                'kod_waluty'   => 'GBP',
                'kurs_sredni'  => '5,6834',
            ),
            array(
                'nazwa_waluty' => 'hrywna (Ukraina)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'UAH',
                'kurs_sredni'  => '0,1642',
            ),
            array(
                'nazwa_waluty' => 'jen (Japonia)',
                'przelicznik'  => '100',
                'kod_waluty'   => 'JPY',
                'kurs_sredni'  => '3,1822',
            ),
            array(
                'nazwa_waluty' => 'korona czeska',
                'przelicznik'  => '1',
                'kod_waluty'   => 'CZK',
                'kurs_sredni'  => '0,1504',
            ),
            array(
                'nazwa_waluty' => 'korona duńska',
                'przelicznik'  => '1',
                'kod_waluty'   => 'DKK',
                'kurs_sredni'  => '0,5539',
            ),
            array(
                'nazwa_waluty' => 'korona islandzka',
                'przelicznik'  => '100',
                'kod_waluty'   => 'ISK',
                'kurs_sredni'  => '2,7728',
            ),
            array(
                'nazwa_waluty' => 'korona norweska',
                'przelicznik'  => '1',
                'kod_waluty'   => 'NOK',
                'kurs_sredni'  => '0,4761',
            ),
            array(
                'nazwa_waluty' => 'korona szwedzka',
                'przelicznik'  => '1',
                'kod_waluty'   => 'SEK',
                'kurs_sredni'  => '0,4438',
            ),
            array(
                'nazwa_waluty' => 'kuna (Chorwacja)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'HRK',
                'kurs_sredni'  => '0,5402',
            ),
            array(
                'nazwa_waluty' => 'lej rumuński',
                'przelicznik'  => '1',
                'kod_waluty'   => 'RON',
                'kurs_sredni'  => '0,9303',
            ),
            array(
                'nazwa_waluty' => 'lew (Bułgaria)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'BGN',
                'kurs_sredni'  => '2,1109',
            ),
            array(
                'nazwa_waluty' => 'lira turecka',
                'przelicznik'  => '1',
                'kod_waluty'   => 'TRY',
                'kurs_sredni'  => '1,4811',
            ),
            array(
                'nazwa_waluty' => 'nowy izraelski szekel',
                'przelicznik'  => '1',
                'kod_waluty'   => 'ILS',
                'kurs_sredni'  => '0,9478',
            ),
            array(
                'nazwa_waluty' => 'peso chilijskie',
                'przelicznik'  => '100',
                'kod_waluty'   => 'CLP',
                'kurs_sredni'  => '0,6043',
            ),
            array(
                'nazwa_waluty' => 'peso filipińskie',
                'przelicznik'  => '1',
                'kod_waluty'   => 'PHP',
                'kurs_sredni'  => '0,0851',
            ),
            array(
                'nazwa_waluty' => 'peso meksykańskie',
                'przelicznik'  => '1',
                'kod_waluty'   => 'MXN',
                'kurs_sredni'  => '0,2541',
            ),
            array(
                'nazwa_waluty' => 'rand (RPA)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'ZAR',
                'kurs_sredni'  => '0,3162',
            ),
            array(
                'nazwa_waluty' => 'real (Brazylia)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'BRL',
                'kurs_sredni'  => '1,1823',
            ),
            array(
                'nazwa_waluty' => 'ringgit (Malezja)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'MYR',
                'kurs_sredni'  => '1,0355',
            ),
            array(
                'nazwa_waluty' => 'rubel rosyjski',
                'przelicznik'  => '1',
                'kod_waluty'   => 'RUB',
                'kurs_sredni'  => '0,0640',
            ),
            array(
                'nazwa_waluty' => 'rupia indonezyjska',
                'przelicznik'  => '10000',
                'kod_waluty'   => 'IDR',
                'kurs_sredni'  => '2,9417',
            ),
            array(
                'nazwa_waluty' => 'rupia indyjska',
                'przelicznik'  => '100',
                'kod_waluty'   => 'INR',
                'kurs_sredni'  => '6,1252',
            ),
            array(
                'nazwa_waluty' => 'won południowokoreański',
                'przelicznik'  => '100',
                'kod_waluty'   => 'KRW',
                'kurs_sredni'  => '0,3428',
            ),
            array(
                'nazwa_waluty' => 'yuan renminbi (Chiny)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'CNY',
                'kurs_sredni'  => '0,6164',
            ),
            array(
                'nazwa_waluty' => 'SDR (MFW)',
                'przelicznik'  => '1',
                'kod_waluty'   => 'XDR',
                'kurs_sredni'  => '5,2765',
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
        $view['data'] = $this->_dataLoader();
        return $app['twig']->render('currencies/index.twig', $view);
    }

    public function viewAction(Application $app, Request $request)
    {
        $view = array();
	$id = $request->get('kod_waluty', null);
	$data = $this->_dataLoader();
        $view['item'] = $this->_getCurrenciesCode($data,$id);
        return $app['twig']->render('currencies/view.twig', $view);
    }

    private function _getCurrenciesCode($currencies,$model) {
	foreach ($currencies as $data) {
	  if($data['kod_waluty'] == $model)
    	    return $data;
	}
        return null;
    }
}
