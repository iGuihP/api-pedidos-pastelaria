<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * Recupera mensagem do erro da exception
     *
     * @param Exception $exception
     * @param array $extra
     * @return array
    */
    protected function getMessageException(Exception $exception, array $extra = []): array
    {
        $messages = [
            'message' => $exception->getMessage()
        ];

        $messages = array_merge(
            $messages,
            $extra
        );

        return $messages;
    }

    /**
     * Retorna o código HTTP de uma exceção.
     *
     * @param Exception $exception A exceção da qual se deseja obter o código HTTP.
     * @return int O código HTTP obtido da exceção.
    */
    public function getHttpCode(Exception $exception): int
    {
        $httpCode = $exception->getCode();

        if(!$httpCode || $httpCode < 100 || !is_int($httpCode)){
            $httpCode = 500;
        }

        return $httpCode;
    }

    /**
     * Valida os parâmetros enviados na requisição.
     *
     * Aqui é feita a validação dos parâmetros da requisição através das regras e parametros enviados.
     *
     * @param array $rules Regras da validação.
     * @param array $params Parâmetros da requisição.
     * @return bool
     * @throws Exception Caso a validação falhe uma Exception será enviada.
    */
    protected function validateParameters(array $rules, array $params): bool
    {
        $validator = Validator::make($params, $rules);

        if($validator->fails()){
            throw new Exception($validator->errors()->first(), 400);
        }

        return true;
    }
}
