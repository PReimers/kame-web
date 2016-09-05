<?php
/**
 * Created with PhpStorm.
 *
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 *
 * @link   http://aimei.ch/developers/Ashura
 */
namespace TerraGana\AppBundle\Controller\API;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiController.
 *
 * @Route("/api")
 */
class ApiController extends FOSRestController
{
    /**
     * Get all games.
     *
     * @ApiDoc(section="game",resource=true,description="Get all games",tags={"demo" = "#000000"},
     *  requirements={
     *     {
     *         "name"="user",
     *         "dataType"="integer",
     *         "requirement"="\d+",
     *         "description"="User-ID"
     *     }
     *  }
     * )
     *
     * @Get("/games", name="api_games")
     *
     * @return JsonResponse
     */
    public function getGamesAction()
    {
        return new JsonResponse([
            'demo' => true,
        ]);
    }

    /**
     * Get all Kuaine games.
     *
     * @ApiDoc(section="game/kuaine",resource=true,description="Get all Kuaine games",tags={"demo" = "#000000"},
     *  requirements={
     *     {
     *         "name"="user",
     *         "dataType"="integer",
     *         "requirement"="\d+",
     *         "description"="User-ID"
     *     }
     *  }
     * )
     *
     * @Get("/games/kuaine", name="api_games_kuaine")
     *
     * @return JsonResponse
     */
    public function getGamesKuaineAction()
    {
        return new JsonResponse([
            'demo' => true,
        ]);
    }
}
