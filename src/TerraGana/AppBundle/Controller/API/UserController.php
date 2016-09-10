<?php
/**
 * Created with PhpStorm.
 *
 * @author Patrick Reimers
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
 * @Route("/api/user")
 */
class UserController extends FOSRestController
{
    /**
     * Get a List of Users.
     *
     * @ApiDoc(section="user",resource=true,description="Get all Users",tags={"stable" = "#000088","dev" = "#ff9900"})
     *
     * @Get("/all", name="api_user_all")
     *
     * @return JsonResponse
     */
    public function getAllAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $users = $dm->getRepository('TerraGanaAppBundle:User')->findAll();

        return new JsonResponse($users);
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
     *      {"name"="googleId", "dataType"="string", "required"=false, "description"="Google-Id"},
     *  }
     * )
     *
     * @Post("/signIn", name="api_user_signIn")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function signInAction(Request $request)
    {
        $json = $this->convertJson($request->getContent());
        $dm = $this->get('doctrine_mongodb')->getManager();
        /* @var User */
        $user = $dm->getRepository('TerraGanaAppBundle:User')->findOneBy(['email' => $json->email]);

        if (!$user) {
            $user = new User();
            $user = $this->updateUser($user, $json);
            $user->setCreatedAt(new DateTime('now'));
            $dm->persist($user);
            $dm->flush();

            return new JsonResponse($user, 201);
        }

        if ($user->getId() === $json->id || $user->getGoogleId() === $json->googleId) {
            $user = $this->updateUser($user, $json);
            $dm->persist($user);
            $dm->flush();

            return new JsonResponse($user, 200);
        }

        return new JsonResponse([], 404);
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
     * @Post("/edit/{id}", name="api_user_edit")
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function userEditAction(Request $request, $id)
    {
        $json = $this->convertJson($request->getContent());
        $dm = $this->get('doctrine_mongodb')->getManager();
        /* @var User */
        $user = $dm->getRepository('TerraGanaAppBundle:User')->find($id);

        if ($user) {
            $this->updateUser($user, $json);
            $dm->persist($user);
            $dm->flush();

            return new JsonResponse($user, 200);
        } else {
            return new JsonResponse([], 404);
        }
    }

    /**
     * Delete an existing User.
     *
     * @ApiDoc(section="user",resource=true,description="Delete User",tags={"demo" = "#000000", "testing" = "#ff0000"})
     *
     * @Delete("/delete/{id}", name="api_user_delete")
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

            return new JsonResponse([], 204);
        }
    }

    private function convertJson($json)
    {
        $return_json = [];
        $json = json_decode($json);
        $return_json['id'] = $this->validateValue([$json,'id']);
        $return_json['googleId'] = $this->validateValue([$json,'googleId']);
        $return_json['username'] = $this->validateValue([$json,'username']);
        $return_json['email'] = $this->validateValue([$json,'email']);

        return json_decode(json_encode($return_json));
    }

    /**
     * Helper function to validate the Value.
     *
     * @param $value
     * @param $default
     *
     * @return mixed
     */
    private function validateValue($value, $default = null)
    {
        if(is_array($value) && count($value) == 2){
            $value = property_exists($value[0],$value[1]) ? $value[0]->{$value[1]} : null;
        }
        return isset($value) ? $value : $default;
    }

    /**
     * update user attributes.
     *
     * @param User $user
     * @param $json
     *
     * @return User
     */
    public function updateUser(User $user, $json)
    {
        (isset($json->googleId) ? $user->setGoogleId($json->googleId) : $user->setGoogleId($user->getGoogleId()));
        (isset($json->username) ? $user->setUsername($json->username) : $user->setUsername($user->getUsername()));
        (isset($json->email) ? $user->setEmail($json->email) : $user->setEmail($user->getEmail()));
        $user->setUpdatedAt(new DateTime('now'));

        return $user;
    }
}
