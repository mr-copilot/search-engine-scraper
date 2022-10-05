<?php


namespace MrCopilot\SearchEngineScraper;

class ErrorException extends \RuntimeException
{
    const ADAPTER_CURL_MISSING = 1;
    const ADAPTER_CURL_UNABLE_INIT_CURL = 2;
    const ADAPTER_CURL_EXECUTE_FAILED = 3;
    const ADAPTER_CURl_INVALID_URL = 4;
    const ADAPTER_CURl_UNSUPPORTED_ENGINE = 5;
    const ADAPTER_CURl_INVALID_KEYWORD = 6;
}