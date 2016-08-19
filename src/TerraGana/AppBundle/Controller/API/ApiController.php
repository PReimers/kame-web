<?php
/**
 * Created with PhpStorm.
 *
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 *
 * @link   http://aimei.ch/developers/Ashura
 */
namespace TerraGana\AppBundle\Controller\API;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController.
 *
 * @Route("/api")
 */
class ApiController extends FOSRestController
{
    /**
     * @Route("/users", name="api_get_users")
     *
     * @return Response
     */
    public function getUsersAction()
    {
        return new Response('Just do it', 200);
    }
}
