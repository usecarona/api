<?php

/**
 * Arquivo da classe HttpResponse.
 *
 * @package api\components
 * @filesource
 */

/**
 * HttpResponse funcionará como uma classe que empacota funcionalidades básicas para
 * a geração de uma resposta HTTP.
 *
 * @package api\components
 * @author João Paulo Cruz
 */
class HttpResponse extends CApplicationComponent
{

    /**
     * Armazena os códigos de retorno padrões do HTTP.
     *
     * @var array
     */
    protected static $codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    /**
     * Armazena os tipos de retornos suportados pelo sistema.
     *
     * @var array
     */
    protected static $typeApplication = [
        '*/*' => 'sendResponseJson',
        'application/json' => 'sendResponseJson',
        'application/xml' => 'sendResponseXml',
    ];

    /**
     * Recupera a mensagem referente ao código do status.
     *
     * {@source }
     * @param int $status Código da mensagem de status que deseja recuperar.
     * @return string Mensagem do código de status enviado como parâmetro.
     */
    public function getStatusCodeMessage($status = 200)
    {
        return self::$codes[$status] ? : null;
    }

    /**
     * Envia o código de status da resposta no cabeçalho.
     *
     * {@source }
     * @param int $status O código de status que deseja enviar.
     */
    public function setStatusHeader($status = 200)
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::t('rest', $this->getStatusCodeMessage($status));
        header($status_header);
    }

    /**
     * Wrapper para a função header do php.
     *
     * {@source}
     * @param string $field nome do campo do cabeçalho http
     * @param string $value valor do cabeçalho http
     * @param boolean $replace
     */
    public function setHeader($field, $value, $replace = true)
    {
        $status_header = $field . ': ' . $value;
        header($status_header, $replace);
    }

    /**
     * Envia a resposta da requisição no formato desejado.
     *
     * {@source}
     * @throws CHttpException
     * @param $response dado que será enviado.
     */
    public function sendResponse($response)
    {
        if (is_null($response)) {
            throw new CHttpException(404, Yii::t('rest', $this->getStatusCodeMessage(404)));
        }

        $mediaTypes = Yii::app()->request->getPreferredMediaTypes();

        foreach ($mediaTypes as $mediaType) {
            if ($mediaType['qvalue'] == 0) {
                $response = array(
                    'code' => 406,
                    'message' => Yii::t('rest', $this->getStatusCodeMessage(406)),
                    'type' => 'CHttpException'
                );

                break;
            }

            $typeResponse = self::$typeApplication[$mediaType['content']];
            if ($typeResponse) {
                echo $this->{$typeResponse}($response);
                Yii::app()->end();
            }
        }

        echo $this->sendResponseJson($response);
    }

    /**
     * Envia resposta em formato JSON.
     *
     * {@source}
     * @param array $response
     */
    protected function sendResponseJson($response)
    {
        return CJSON::encode($response);
    }

    /**
     * Envia resposta em formato XML.
     *
     * {@source}
     * @param array $response
     */
    protected function sendResponseXml($response)
    {
        $root = Yii::app()->getController()->getId();
        $xml = CXML::createXml($root, $response);
        return $xml->saveXML();
    }

}
