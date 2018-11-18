<?php

namespace App\Exceptions\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;

trait ApiException
{
    /**
     * Trata as exceções da API
     *
     * @param [type] $request
     * @param [type] $exception
     * @return void
     */
    protected function getJsonException($request, $exception)
    {
        if($exception instanceof ModelNotFoundException) {
            return $this->notFoundException();
        }
          
        if($exception instanceof HttpException) {; 
            return $this->httpException($exception);
        }

        if($exception instanceof ValidationException) {
            return $this->validationException($exception);
        }

        return $this->genericException();
    }

    /**
     * Retorna o erro 404
     *
     * @return void
     */
    protected function notFoundException()
    {
        return $this->getResponse(
            "Recurso nao encontrado", "01", 404
        );
    }

    /**
     * Retorna o erro 500
     *
     * @return void
     */
    protected function genericException()
    {
        return $this->getResponse(
            "Erro interno no servidor", "02", 500
        );
    }

    /**
     * Retorna o erro de http
     *
     * @return void
     */
    protected function httpException($exception)
    {
        return $this->getResponse(
            "Verbo http nao permitido", "03", $exception->getStatusCode()
        );
    }

    /**
     * Retorna o erro de validacao
     *
     * @return void
     */
    protected function validationException($exception)
    {
        return response()->json($exception->errors(), $exception->status);
    }

    /**
     * Mostra a resposta em json
     *
     * @param [type] $message
     * @param [type] $code
     * @param [type] $status
     * @return void
     */
    protected function getResponse($message, $code, $status)
    {
        return response()->json([
            "errors" => [
                [
                    'status' => $status,
                    'code' => $code, //Erro da propria api
                    'message' => $message
                ]
            ]
        ], $status);
    }
}