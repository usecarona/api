<?php

/**
 * Arquivo da classe HttpRequest.
 *
 * @package api\components
 * @filesource
 */

/**
 * HttpRequest extende as funcionalidades da classe CHttpRequest.
 *
 * @package api\components
 * @author João Paulo Cruz
 */
class HttpRequest extends CHttpRequest
{

    private $deleteParams;
    private $putParams;
    private $preferredMediaTypes;
    private $preferredCharsets;
    private $preferredContents;
    private $preferredEncodings;

    /**
     * Returns the HEADER parameteres array.
     *
     * {@source}
     * @return array the HEADER array
     */
    public function getRequestHeaders()
    {
        return apache_request_headers();
    }

    /**
     * Returns the named HEADER parameter value.
     *
     * If the HEADER parameter does not exist, the second parameter to this method will be returned.
     *
     * {@source}
     * @param type $name the HEADER parameter name
     * @param type $defaulValue the default parameter value if the HEADER parameter does not exist.
     * @return mixed the HEADER parameter value
     */
    public function getRequestHeader($name, $defaulValue = null)
    {
        $headers = $this->getRequestHeaders();

        return isset($headers[$name]) ? $headers[$name] : $defaulValue;
    }

    /**
     * Returns the GET or POST parameteres array.
     *
     * {@source}
     * @return array the GET or POST array
     */
    public function getParams()
    {
        return empty($_GET) ? $_POST : $_GET;
    }

    /**
     * Returns the GET parameters array.
     *
     * {@source}
     * @return array the GET array
     */
    public function getQueryParams()
    {
        return $_GET;
    }

    /**
     * Returns the POST parameters array.
     *
     * {@source}
     * @return array the POST array
     */
    public function getPostParams()
    {
        return $_POST;
    }

    /**
     * Returns the POST parameters array.
     *
     * {@source}
     * @return array the POST array
     */
    public function getDeleteParams()
    {
        if ($this->deleteParams === null)
            $this->deleteParams = $this->getIsDeleteRequest() ? $this->getRestParams() : array();
        return $this->deleteParams;
    }

    /**
     * Returns the POST parameters array.
     *
     * {@source}
     * @return array the POST array
     */
    public function getPutParams()
    {
        if ($this->putParams === null)
            $this->putParams = $this->getIsPutRequest() ? $this->getRestParams() : array();
        return $this->putParams;
    }

    /**
     * Returns the named DELETE parameter value.
     *
     * If the DELETE parameter does not exist or if the current request is not a DELETE request,
     * the second parameter to this method will be returned.
     *
     * {@source}
     * @param string $name the DELETE parameter name
     * @param mixed $defaultValue the default parameter value if the DELETE parameter does not exist.
     * @return mixed the DELETE parameter value
     */
    public function getDelete($name, $defaultValue = null)
    {
        if ($this->deleteParams === null)
            $this->deleteParams = $this->getIsDeleteRequest() ? $this->getRestParams() : array();
        return isset($this->deleteParams[$name]) ? $this->deleteParams[$name] : $defaultValue;
    }

    /**
     * Returns the named PUT parameter value.
     *
     * If the PUT parameter does not exist or if the current request is not a PUT request,
     * the second parameter to this method will be returned.
     *
     * {@source}
     * @param string $name the PUT parameter name
     * @param mixed $defaultValue the default parameter value if the PUT parameter does not exist.
     * @return mixed the PUT parameter value
     */
    public function getPut($name, $defaultValue = null)
    {
        if ($this->putParams === null)
            $this->putParams = $this->getIsPutRequest() ? $this->getRestParams() : array();
        return isset($this->putParams[$name]) ? $this->putParams[$name] : $defaultValue;
    }

    /**
     * Returns the php://input request parameters.
     *
     * {@source}
     * @return array the request parameters
     */
    public function getRestParams()
    {
        $result = array();
        $contentType = $this->getRequestHeader('Content-Type');

        if (preg_match('/application\/json/', $contentType))
            $result = json_decode(file_get_contents('php://input'), true);
        else
            $result = parent::getRestParams();

        return $result;
    }

    /**
     * Retorna o media type preferido do usuário.
     *
     * {@source}
     * @return mixed
     */
    public function getPreferredMediaType()
    {
        $preferredMediaTypes = $this->getPreferredMediaTypes();
        return !$preferredMediaTypes ? : $preferredMediaTypes[0];
    }

    /**
     * Retorna um array dos media types aceitos pelo usuário em ordem de preferência.
     *
     * {@source}
     * @return array
     */
    public function getPreferredMediaTypes()
    {
        if ($this->preferredMediaTypes === null) {
            $this->preferredMediaTypes = $this->getContentNegotiationFrom('HTTP_ACCEPT', '/([\w\/\+\-\*]+)(?:\s*;\s*q\s*=\s*(\d*\.?\d*))?/');
        }
        return $this->preferredMediaTypes;
    }

    /**
     * Retorna os valores de todos os cabeçalhos de negociação de conteúdo.
     *
     * @return array valores dos cabecalhos de negociação de conteúdo.
     */
    public function getPreferredContents()
    {
        $contents = array(
            'preferredMediaTypes' => array('header' => 'HTTP_ACCEPT', 'pattern' => '/([\w\/\.\+\-\*]+)(?:\s*;\s*q\s*=\s*(\d*\.?\d*))?/'),
            'preferredCharsets' => array('header' => 'HTTP_ACCEPT_CHARSET', 'pattern' => '/([\w\-\*]+)(?:\s*;\s*q\s*=\s*(\d*\.?\d*))?/'),
            'preferredEncodings' => array('header' => 'HTTP_ACCEPT_ENCODING', 'pattern' => '/([\w\*]+)(?:\s*;\s*q\s*=\s*(\d*\.?\d*))?/'),
        );

        if ($this->preferredContents === null)
            foreach ($contents as $property => $params) {
                if ($this->$property === null)
                    $this->$property = $this->getContentNegotiationFrom($params['header'], $params['pattern']);

                $this->preferredContents[$property] = $this->$property;
            }

        return $this->preferredContents;
    }

    /**
     * Extrai os valores de cabeçalhos de negociação de conteúdo de acordo com o padrão estabelecido.
     *
     * {@source}
     * @param string $header nome do cabeçalho do qual deseja extrair o conteúdo.
     * @param string $pattern padrão para pesquisa dos valores e do padrão de classificação dos mesmos.
     * @return array valores ordenados que foram extraídos do cabeçalho.
     */
    protected function getContentNegotiationFrom($header, $pattern)
    {
        $contents = array();

        $n = preg_match_all($pattern, $_SERVER[$header], $matches);

        if (isset($_SERVER[$header]) && $n) {
            for ($i = 0; $i < $n; $i++) {
                $q = $matches[2][$i];
                if ($q === '')
                    $q = 1;
                if ($q === '0')
                    $q = '0.0';
                if ($q)
                    $contents[] = array('qvalue' => (float) $q, 'content' => $matches[1][$i]);
            }

            usort($contents, create_function('$a, $b', 'if ($a["qvalue"] == $b["qvalue"]) { return strcmp($a["content"], $b["content"]); } else return ($a["qvalue"] < $b["qvalue"]) ? 1 : -1;'));
        }

        return $contents;
    }

}

?>
