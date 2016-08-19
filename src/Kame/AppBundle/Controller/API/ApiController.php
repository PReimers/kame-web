<?php
/**
 * Created with PhpStorm.
 *
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 * @link   http://aimei.ch/developers/Ashura
 */

namespace Kame\AppBundle\Controller\API;


use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 *
 * @Route("/api")
 *
 * @package Kame\AppBundle\Controller\API
 */
class ApiController extends FOSRestController
{
    /**
     * @param $request Request
     *
     * @Route("/users", name="api_get_users")
     *
     * @return Response
     */
    public function getUsersAction(Request $request)
    {
        return new Response("Just do it", 200);
    }
}
