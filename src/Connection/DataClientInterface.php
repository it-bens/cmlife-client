<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Connection;

use Http\Promise\Promise;
use ITB\CmlifeClient\Exception\AuthenticationException;

interface DataClientInterface
{
    /**
     * @param RequestData[] $requestDatasets
     * @param callable $onRequestFailure
     * @param callable $onRequestSuccess
     * @return Promise[]
     * @throws AuthenticationException
     */
    public function fetchAllDataAsync(array $requestDatasets, callable $onRequestFailure, callable $onRequestSuccess): array;

    /**
     * @param RequestData $requestData
     * @param callable $onRequestFailure
     * @param callable $onRequestSuccess
     * @return Promise
     * @throws AuthenticationException
     */
    public function fetchDataAsync(RequestData $requestData, callable $onRequestFailure, callable $onRequestSuccess): Promise;

    /**
     * @param RequestData $requestData
     * @return string
     * @throws AuthenticationException
     */
    public function fetchDataSync(RequestData $requestData): string;
}
