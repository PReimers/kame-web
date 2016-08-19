<?php
/**
 * Created with PhpStorm.
 *
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 *
 * @link   http://aimei.ch/developers/Ashura
 */
namespace TerraGana\AppBundle\Controller\Backend;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BackendController
 *
 * @Route("/backend")
 */
class BackendController extends Controller
{
    /**
     * @Route("/", name="backend_default")
     *
     * @return Response
     */
    public function indexAction()
    {
        return new Response('Backend');
    }
}
