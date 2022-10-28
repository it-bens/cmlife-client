<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Connection;

use Http\Promise\Promise;
use ITB\CmlifeClient\Authentication\AuthenticatorInterface;
use ITB\CmlifeClient\Exception\AuthenticationException;
use LogicException;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class DataClient implements DataClientInterface
{
    private readonly HttplugClient $httpClient;

    /**
     * @param AuthenticatorInterface $authenticator
     * @param HttpClientInterface $httpClient
     */
    public function __construct(private readonly AuthenticatorInterface $authenticator, HttpClientInterface $httpClient)
    {
        $this->httpClient = new HttplugClient($httpClient);
    }

    /**
     * @param AuthenticatorInterface $authenticator
     * @return DataClient
     */
    public static function createWithDefaultHttpClient(AuthenticatorInterface $authenticator): DataClient
    {
        $requestHttpClient = HttpClient::create()->withOptions(
            ['base_uri' => 'https://my.uni-bayreuth.de', 'headers' => ['Content-Type' => 'application/json']]
        );

        return new self($authenticator, $requestHttpClient);
    }

    /**
     * @param RequestData[] $requestDatasets
     * @param callable $onRequestFailure
     * @param callable $onRequestSuccess
     * @return Promise[]
     * @throws AuthenticationException
     */
    public function fetchAllDataAsync(array $requestDatasets, callable $onRequestFailure, callable $onRequestSuccess): array
    {
        $promises = [];
        foreach ($requestDatasets as $requestData) {
            $request = $this->httpClient->createRequest(
                $requestData->method,
                $requestData->uri,
                $this->authenticator->getAuthenticationHeaders(),
                $requestData->body
            );

            try {
                $promises[] = $this->httpClient->sendAsyncRequest($request)
                    ->then(
                        function (Response $response) use ($onRequestFailure, $onRequestSuccess) {
                            $httpResponse = (new HttpFoundationFactory())->createResponse($response);
                            if (!$httpResponse->isOk()) {
                                $onRequestFailure($httpResponse->getStatusCode());
                                return;
                            }

                            $content = $httpResponse->getContent();
                            $onRequestSuccess($content);
                        },
                        function (Throwable $exception) {
                            throw new RuntimeException('The request failed with an exception.', previous: $exception);
                        }
                    );
            } catch (Throwable $exception) {
                throw new LogicException('Async requests should not lead to an exception before promises are resolved.', previous: $exception);
            }
        }

        return $promises;
    }

    /**
     * @param RequestData $requestData
     * @param callable $onRequestFailure
     * @param callable $onRequestSuccess
     * @return Promise
     * @throws AuthenticationException
     */
    public function fetchDataAsync(RequestData $requestData, callable $onRequestFailure, callable $onRequestSuccess): Promise
    {
        $request = $this->httpClient->createRequest(
            $requestData->method,
            $requestData->uri,
            $this->authenticator->getAuthenticationHeaders(),
            $requestData->body
        );

        try {
            return $this->httpClient->sendAsyncRequest($request)
                ->then(
                    function (Response $response) use ($onRequestFailure, $onRequestSuccess) {
                        $httpResponse = (new HttpFoundationFactory())->createResponse($response);
                        if (!$httpResponse->isOk()) {
                            $onRequestFailure($httpResponse->getStatusCode());
                            return;
                        }

                        $content = $httpResponse->getContent();
                        $onRequestSuccess($content);
                    },
                    function (Throwable $exception) {
                        throw new RuntimeException('The request failed with an exception.', previous: $exception);
                    }
                );
        } catch (Throwable $exception) {
            throw new LogicException('Async requests should not lead to an exception before promises are resolved.', previous: $exception);
        }
    }

    /**
     * @param RequestData $requestData
     * @return string
     * @throws AuthenticationException
     */
    public function fetchDataSync(RequestData $requestData): string
    {
        $request = $this->httpClient->createRequest(
            $requestData->method,
            $requestData->uri,
            $this->authenticator->getAuthenticationHeaders(),
            $requestData->body
        );

        try {
            $response = $this->httpClient->sendRequest($request);

            return $response->getBody()->getContents();
        } catch (ClientExceptionInterface $exception) {
            throw new RuntimeException('The request failed with an exception.', previous: $exception);
        }
    }
}
