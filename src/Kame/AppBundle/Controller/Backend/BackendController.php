<?php
/**
 * Created with PhpStorm.
 *
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 * @link   http://aimei.ch/developers/Ashura
 */

namespace Kame\AppBundle\Controller\Backend;


use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BackendController
 *
 * @Route("/backend")
 *
 * @package Kame\AppBundle\Controller\Backend
 */
class BackendController extends Controller
{
    /**
     * @param $request Request
     *
     * @Route("/", name="backend_default")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return new Response("Backend");
    }
}
