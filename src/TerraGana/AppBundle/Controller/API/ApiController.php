<?php
/**
 * Created with PhpStorm.
 *
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 *
 * @link   http://aimei.ch/developers/Ashura
 */
namespace TerraGana\AppBundle\Controller\API;

use DateTime;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TerraGana\AppBundle\Document\User;

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
     * @ApiDoc(section="user",resource=true,description="Get all Users",tags={"stable" = "#000088","dev" = "#ff9900"})
     *
     * @Get("/users", name="api_users")
     *
     * @return JsonResponse
     */
    public function getUsersAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $users = $dm->getRepository('TerraGanaAppBundle:User')->findAll();

        $user_array = [];
        foreach ($users as $user) {
            $user_array[$user->getId()] = [
                'googleId' => $user->getGoogleId(),
                'username' => $user->getUsername(),
                'email'    => $user->getEmail(),
                'created'  => $user->getCreatedAt(),
                'updated'  => $user->getUpdatedAt(),
            ];
        }

        return new JsonResponse($user_array);
    }

    /**
     * Sign in or create user.
     *
     * @ApiDoc(section="user",resource=true,description="Sign in",tags={"dev" = "#ff9900"},
     *  requirements={
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="E-Mail of User"
     *      }
     *  },
     *  parameters={
     *      {"name"="id", "dataType"="string", "required"=false, "description"="User-Id"},
     *      {"name"="googleId", "dataType"="string", "required"=false, "description"="Google-Id"},
     *  }
     * )
     *
     * @Post("/user/signIn", name="api_user_signIn")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userSignInAction(Request $request)
    {
        $json = json_decode($request->getContent());
        $id = (isset($json->id) ? $json->id : null);
        $googleId = (isset($json->googleId) ? $json->googleId : null);
        $email = (isset($json->email) ? $json->email : null);

        $dm = $this->get('doctrine_mongodb')->getManager();

        /* @var User */
        $user = $dm->getRepository('TerraGanaAppBundle:User')->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setGoogleId($googleId);
            $user->setEmail($email);
            $user->setCreatedAt(new DateTime('now'));
            $user->setUpdatedAt(new DateTime('now'));

            $dm->persist($user);
            $dm->flush();

            return new JsonResponse([
                $user->getId() => [
                    'googleId' => $user->getGoogleId(),
                    'username' => $user->getUsername(),
                    'email'    => $user->getEmail(),
                    'created'  => $user->getCreatedAt(),
                    'updated'  => $user->getUpdatedAt(),
                ],
            ], 201);
        } else {
            if ($user->getId() == $id) {
                $user->setUpdatedAt(new DateTime('now'));

                $dm->persist($user);
                $dm->flush();

                return new JsonResponse([
                    $user->getId() => [
                        'googleId' => $user->getGoogleId(),
                        'username' => $user->getUsername(),
                        'email'    => $user->getEmail(),
                        'created'  => $user->getCreatedAt(),
                        'updated'  => $user->getUpdatedAt(),
                    ],
                ], 200);
            } elseif ($user->getGoogleId() == $googleId) {
                $user->setUpdatedAt(new DateTime('now'));

                $dm->persist($user);
                $dm->flush();

                return new JsonResponse([
                    $user->getId() => [
                        'googleId' => $user->getGoogleId(),
                        'username' => $user->getUsername(),
                        'email'    => $user->getEmail(),
                        'created'  => $user->getCreatedAt(),
                        'updated'  => $user->getUpdatedAt(),
                    ],
                ], 200);
            } else {
                return new JsonResponse([], 404);
            }
        }
    }

    /**
     * Delete an existing User.
     *
     * @ApiDoc(section="user",resource=true,description="Delete User",tags={"demo" = "#000000", "testing" = "#ff0000"})
     *
     * @Delete("/user/delete/{id}", name="api_user_delete")
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function userDeleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $dm->getRepository('TerraGanaAppBundle:User')->find($id);

        if (!$user) {
            return new JsonResponse([
                'message' => 'User not found',
            ], 404);
        } else {
            $user->setUpdatedAt(new DateTime('now'));
            $user->setDeletedAt(new DateTime('now'));
            $user->getDeletedAt(); // For CoverageTest Only
            $dm->remove($user);
            $dm->flush();

            return new JsonResponse([
                'message' => 'User deleted',
            ], 200);
        }
    }

    /**
     * Edit user data.
     *
     * @ApiDoc(section="user",resource=true,description="Edit user",tags={"demo" = "#000000", "testing" = "#ff0000"},
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=false, "description"="E-Mail of user"},
     *      {"name"="googleId", "dataType"="string", "required"=false, "description"="Google-Id"},
     *      {"name"="username", "dataType"="string", "required"=false, "description"="Username"},
     *  }
     * )
     *
     * @Post("/user/edit/{id}", name="api_user_edit")
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function userEditAction(Request $request, $id)
    {
        $json = json_decode($request->getContent());
        $dm = $this->get('doctrine_mongodb')->getManager();

        /* @var User */
        $user = $dm->getRepository('TerraGanaAppBundle:User')->find($id);

        if ($user) {
            if (isset($json->googleId)) {
                $user->setGoogleId($json->googleId);
            }
            if (isset($json->email)) {
                $user->setEmail($json->email);
            }
            if (isset($json->username)) {
                $user->setUsername($json->username);
            }
            $user->setUpdatedAt(new DateTime('now'));

            $dm->persist($user);
            $dm->flush();

            return new JsonResponse([
                $user->getId() => [
                    'googleId' => $user->getGoogleId(),
                    'username' => $user->getUsername(),
                    'email'    => $user->getEmail(),
                    'created'  => $user->getCreatedAt(),
                    'updated'  => $user->getUpdatedAt(),
                ],
            ], 200);
        } else {
            return new JsonResponse([], 404);
        }
    }

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
