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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiController.
 *
 * @Route("/api")
 */
class ApiController extends FOSRestController
{
    /**
     * Get a List of Users.
     *
     * @ApiDoc(resource=true,description="Get all Users")
     *
     * @Route("/users", name="api_get_users")
     *
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function getUsersAction()
    {
        return new JsonResponse([
            'abc123' => [
                'name' => 'user1',
            ],
            'def456' => [
                'name' => 'user2',
            ],
            '123abc' => [
                'name' => 'user3',
            ],
        ]);
    }
}
