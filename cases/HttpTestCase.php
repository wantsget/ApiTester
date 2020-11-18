<?php

declare(strict_types=1);

namespace ApiTester\Cases;

use Throwable;
use Inhere\Console\IO\Output;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class HttpTestCase extends TestCase
{

    protected $client;
    protected $output;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client(['base_uri' => API_HOST, 'http_errors' => false]);
        $this->output = new Output();
    }

    public function post(string $uri, array $data = [], array $headers = [])
    {
        try {
            $response = $this->client->post($uri, [
                'form_params' => $data,
                'headers' => $headers,
            ]);
            return $this->getResponse($response);
        } catch (Throwable $throwable) {
            if (isset($response) && $response instanceof ResponseInterface && $response->getBody()) {
                return $this->getResponse($response);
            }
            $this->output->error("Guzzle Error: {$uri} {$throwable->getMessage()}");
            exit();
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    public function getResponse(ResponseInterface $response)
    {
        $response = json_decode($response->getBody()->getContents(), true);
        if(!is_array($response)) {
            $this->output->error("ApiTester\Cases getResponse Error: " . $response->getBody()->getContents());
            return [];
        }
        return $response;
    }

}