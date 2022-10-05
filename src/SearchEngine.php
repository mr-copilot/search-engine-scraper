<?php
include_once(dirname(__FILE__) . '/Adapter/Process.php');
include_once (dirname(__FILE__).'/ErrorException.php');

use MrCopilot\SearchEngineScraper as MS;


class SearchEngine
{
    protected $adapter;
    protected $engine;
    private $compatibleEngines = ['google.com', 'google.ae'];
    public $queryParam;
    public $startIndex;
    public $resultCount;
    public $keyword;

    function __construct($startIndex = 0, $resultCount = 10, $queryParam = 'q'){
      $this->adapter = new MS\Process();
      $this->startIndex = $startIndex;
      $this->resultCount = $resultCount;
      $this->queryParam = $queryParam;
    }

    public function setEngine($engine){
        $this->validateEngine($engine);
        $this->engine = $engine;
    }

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getEngine($engine)
    {
        return $this->engine;
    }

    protected function validateEngine($engine)
    {
        if(empty($this->engine) && !in_array($engine, $this->compatibleEngines)){
            throw new MS\ErrorException(
                'Invalid Engine',
                MS\ErrorException::ADAPTER_CURl_UNSUPPORTED_ENGINE
            );
        }
    }

    protected function generateSearchUrl($keyword)
    {
        $q = [
            'q' => strtolower($keyword),
            'start' => $this->startIndex,
            'num' => $this->resultCount
        ];

        return 'https://www.' . $this->engine . '/search?'.http_build_query($q) ;
    }
    public function search($keywords)
    {
        if(empty($keywords) || !is_array($keywords))
        {
            throw new MS\ErrorException(
              'no keyword passed',
              MS\ErrorException::ADAPTER_CURl_INVALID_KEYWORD
            );
        }
        $results = [];
        foreach ($keywords as $keyword)
        {
            $this->keyword = $keyword;
            $url = $this->generateSearchUrl($keyword);
            // these can be taken from helpers
            $header = array('Accept-Language: en-us,en;q=0.7,bn-bn;q=0.3','Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5');
            $userAgent = 'Mozilla/4.8 [en] (Windows NT 5.1; U)';
            $output = $this->getAdapter()->executeRequest($url,$header, $userAgent);
            $requiredResult = $this->getResponse($output);
            if(!empty($requiredResult) && is_array($requiredResult))
            {
                $results[]=$requiredResult;
            } else {
                throw new MS\ErrorException(
                  'Empty results received',
                  MS\ErrorException::ADAPTER_CURL_EXECUTE_FAILED
                );
            }

        }
        return new ArrayIterator($results);
    }

    public function getResponse($output)
    {
        return $this->getAdapter()->parseHTMLtoArray($output, $this->engine, $this->keyword);
    }


}