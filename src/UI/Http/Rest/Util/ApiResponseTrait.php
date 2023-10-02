<?php

namespace App\UI\Http\Rest\Util;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    /**
     * Genera una respuesta JSON exitosa.
     *
     * @param mixed $data Datos a incluir en la respuesta.
     * @param int $statusCode Código de estado HTTP (por defecto, 200 OK).
     * @return JsonResponse
     */
    public function successResponse($data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(['data' => $data], $statusCode);
    }

    /**
     * Genera una respuesta JSON de error.
     *
     * @param string $message Mensaje de error.
     * @param int $statusCode Código de estado HTTP (por defecto, 400 Bad Request).
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse(['error' => $message], $statusCode);
    }
}