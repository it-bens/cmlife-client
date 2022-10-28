<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Mock;

use Http\Promise\Promise;
use ITB\CmlifeClient\Connection\DataClient;
use ITB\CmlifeClient\Connection\DataClientInterface;
use ITB\CmlifeClient\Connection\RequestData;
use ITB\CmlifeClient\Exception\AuthenticationException;

final class DataClientMockThatCallsFailure implements DataClientInterface
{
    /**
     * @param DataClient $dataClient
     * @param string $requestDataUriPatternToThrowLogicException
     */
    public function __construct(private readonly DataClient $dataClient, private readonly string $requestDataUriPatternToThrowLogicException)
    {
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
        foreach ($requestDatasets as $requestData) {
            if (1 === preg_match($this->requestDataUriPatternToThrowLogicException, $requestData->uri)) {
                $onRequestFailure(500);
            }
        }

        return $this->dataClient->fetchAllDataAsync($requestDatasets, $onRequestFailure, $onRequestSuccess);
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
        if (1 === preg_match($this->requestDataUriPatternToThrowLogicException, $requestData->uri)) {
            $onRequestFailure(500);
        }

        return $this->dataClient->fetchDataAsync($requestData, $onRequestFailure, $onRequestSuccess);
    }

    /**
     * @param RequestData $requestData
     * @return string
     * @throws AuthenticationException
     */
    public function fetchDataSync(RequestData $requestData): string
    {
        return $this->dataClient->fetchDataSync($requestData);
    }
}
