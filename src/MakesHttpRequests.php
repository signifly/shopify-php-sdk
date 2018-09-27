<?php

namespace Signifly\Shopify;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Signifly\Shopify\Exceptions\NotFoundException;
use Signifly\Shopify\Exceptions\ValidationException;
use Signifly\Shopify\Exceptions\FailedActionException;

trait MakesHttpRequests
{
    /**
     * @param  string $uri
     *
     * @return mixed
     */
    public function get(string $uri)
    {
        return $this->request('GET', $uri);
    }

    /**
     * @param  string $uri
     * @param  array $payload
     *
     * @return mixed
     */
    public function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * @param  string $uri
     * @param  array $payload
     *
     * @return mixed
     */
    public function put(string $uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    /**
     * @param  string $uri
     * @param  array $payload
     *
     * @return mixed
     */
    public function delete(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * @param  string $verb
     * @param  string $uri
     * @param  array $payload
     *
     * @return mixed
     */
    protected function request(string $verb, string $uri, array $payload = [])
    {
        $response = $this->client->request($verb, $uri,
            empty($payload) ? [] : ['json' => $payload]
        );

        if (!in_array($response->getStatusCode(), [200, 201])) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 422) {
            $errors = json_decode((string) $response->getBody(), true);
            throw new ValidationException(is_array($errors) ? $errors : []);
        }

        if ($response->getStatusCode() === 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() === 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getBody());
    }
}
