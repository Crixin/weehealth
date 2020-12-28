<?php

namespace App\Classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;

/**
 * Class RESTServices
 * @package App\Classes
 */
class RESTServices
{

    /**
     * @var Client
     */
    private $HTTP_CLIENT = null;


    /**
     * RESTGed constructor.
     */
    public function __construct()
    {
        // Essa instanciação é necessária para todos os métodos POST
        $this->HTTP_CLIENT = new Client([
            'headers' => [
                'Cookie: ' => 'CXSSID=' . env('GED_USER_TOKEN')
            ]
        ]);
    }


    /**
     * Do a GET request
     * @param string $_url Resource to fetch
     * @param array $_tokenHeader The header needed to make requests
     * @param array $_parameters Ordered array with additional parameters (e.g. ['João', 'Desenvolvedor'])
     * @return JSON with a response object and the http code
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $_url, array $_parameters = array())
    {
        $cont = 0;
        $params = "?";
        foreach ($_parameters as $key => $prm) {
            if ($cont === 0) {
                $params .= $key . "=" . $prm;
            } else {
                $params .= "&" . $key . "=" . $prm;
            }
            $cont++;
        }

        try {
            $response = $this->HTTP_CLIENT->get($_url . $params);

            $body = $response->getBody()->getContents();

            if (json_decode($body)) {
                return ['error' => false, 'response' => json_decode($body)];
            } else {
                return ['error' => false, 'response' => $body];
            }
        } catch (RequestException $e) {
            return ['error' => true, 'response' => $e->getMessage()];
        }
    }


    public function post(string $_url, array $_body, bool $mostra = false)
    {
        try {
            $response = $this->HTTP_CLIENT->post($_url, [
                RequestOptions::JSON =>  $_body
            ]);

            Log::debug("Status: " . $response->getStatusCode());
            Log::debug("Resposta para requisição [$_url]: " . $response->getReasonPhrase());
            
            $body = $response->getBody()->getContents();

            if (json_decode($body)) {
                return ['error' => false, 'response' => json_decode($body)];
            } else {
                return ['error' => false, 'response' => $body];
            }
        } catch (RequestException $e) {
            ///dd($e);
            return ['error' => true, 'response' => $e->getMessage()];
        }
    }


    public function put(string $_url, array $_body)
    {
        try {
            $response = $this->HTTP_CLIENT->put($_url, [
                RequestOptions::JSON =>  $_body
            ]);

            Log::debug("Status: " . $response->getStatusCode());
            Log::debug("Resposta para requisição [$_url]: " . $response->getReasonPhrase());
            
            $body = $response->getBody()->getContents();

            if (json_decode($body)) {
                return ['error' => false, 'response' => json_decode($body)];
            } else {
                return ['error' => false, 'response' => $body];
            }
        } catch (RequestException $e) {
            return ['error' => true, 'response' => $e->getMessage()];
        }
    }


    public function delete(string $_url)
    {
        try {
            $response = $this->HTTP_CLIENT->delete($_url);
            return ['error' => false, 'response' => $response];
        } catch (RequestException $e) {
            return ['error' => true, 'response' => $e->getMessage()];
        }
    }


    //// Médotos de pesquisa, inserção, update...

    public function buscaInfoArea(string $_idArea, string $_params = "")
    {
        return $this->get(env("GED_URL") . "/area/" . $_idArea . $_params);
    }

    public function pesquisaRegistro(array $_params = [])
    {
        return $this->post(env("GED_URL") . "/registro/pesquisa", $_params);
    }

    public function pesquisaDocumento(array $_params = [])
    {
        return $this->post(env("GED_URL") . "/documento/pesquisa", $_params);
    }

    public function buscaRegistros(array $_params)
    {
        return $this->post(env("GED_URL") . "/registro/pesquisa", $_params);
    }


    public function getDocumento(string $documento, array $_params = [])
    {
        return $this->get(env("GED_URL") . "/documento/" . $documento, $_params);
    }

    public function postDocumento(array $_params)
    {
        return $this->post(env("GED_URL") . "/documento", $_params);
    }


    public function getNomeArea(string $area)
    {
        return $this->get(env("GED_URL") . "/area/" . $area . "/nome");
    }


    public function getAreaAreas()
    {
        return $this->get(env("GED_URL") . "/area/areas");
    }


    public function getRegistro(string $registro, array $_params = [])
    {
        return $this->get(env("GED_URL") . "/registro/" . $registro, $_params);
    }


    public function putRegistro(array $registro)
    {
        return $this->put(env('GED_URL') . "/registro/", $registro);
    }


    public function postRegistro(array $registro)
    {
        return $this->post(env('GED_URL') . "/registro/", $registro);
    }

    public function postIndice(string $_area, array $_params)
    {
        return $this->post(env('GED_URL') . "/indice/" . $_area, $_params);
    }
}
