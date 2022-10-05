<?php

include_once(dirname(__FILE__) . '/Process.php');


interface AdapterInterface
{
    /**
     * Executes the search request
     * @param $url
     * @param $header
     * @param $userAgent
     * @return string
     */
    public function executeRequest($url, $header, $userAgent);

    /**
     * @param $html
     * @param $engine
     * @param $keyword
     * @return array
     */
    public function parseHTMLtoArray($html, $engine, $keyword);
}