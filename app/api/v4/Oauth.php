<?php
/**
 * @author     Martin Høgh <mh@mapcentia.com>
 * @copyright  2013-20204 MapCentia ApS
 * @license    http://www.gnu.org/licenses/#AGPL  GNU AFFERO GENERAL PUBLIC LICENSE 3
 *
 */

namespace app\api\v4;

use app\exceptions\GC2Exception;
use app\inc\Input;
use app\models\Session;
use Exception;
use TypeError;

/**
 * Class Oauth
 * @package app\api\v4
 */
#[AcceptableMethods(['POST', 'HEAD', 'OPTIONS'])]
class Oauth extends AbstractApi
{
    public Session $session;

    public function __construct()
    {
    }

    /**
     * @return array<string, array<string, mixed>|bool|string|int>
     *
     * @OA\Post(
     *   path="/api/v4/oauth",
     *   tags={"OAuth"},
     *   summary="Create token",
     *   @OA\RequestBody(
     *     description="OAuth password grant parameters",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         type="object",
     *         @OA\Property(property="grant_type",type="string", example="password"),
     *         @OA\Property(property="username",type="string", example="user@example.com"),
     *         @OA\Property(property="password",type="string", example="1234Luggage"),
     *         @OA\Property(property="database",type="string", example="roads"),
     *         @OA\Property(property="client_id",type="string", example="xxxxxxxxxx"),
     *         @OA\Property(property="client_secret",type="string", example="xxxxxxxxxx")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Operation status",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         type="object",
     *         @OA\Property(property="access_token",type="string", example="MTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZjI3"),
     *         @OA\Property(property="token_type",type="string", example="bearer"),
     *         @OA\Property(property="expires_in",type="integer",  example=3600),
     *         @OA\Property(property="refresh_token",type="string", example="IwOGYzYTlmM2YxOTQ5MGE3YmNmMDFkNTVk"),
     *         @OA\Property(property="scope",type="string", example="sql")
     *       )
     *     )
     *   )
     * )
     */
    public function post_index(): array
    {
        $this->session = new Session();
        $data = json_decode(Input::getBody(), true) ?: [];
        if (!empty($data["username"]) && !empty($data["password"])) {
            try {
                return $this->session->start($data["username"], $data["password"], "public", $data["database"], true);
            } catch (Exception $exception) {
                return [
                    "error" => "invalid_request",
                    "error_description" => $exception->getMessage(),
                    "code" => 500
                ];
            }
        } else {
            return [
                "error" => "invalid_request",
                "error_description" => "Username or password parameter was not provided",
                "code" => 400
            ];
        }
    }

    /**
     */
    public function get_index(): array
    {
    }

    /**
     */
    public function put_index(): array
    {
    }

    /**
     */
    public function delete_index(): array
    {
    }

    public function validate(): void
    {
        // TODO: Implement validateUser() method.
    }
}