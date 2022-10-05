<?php
namespace MrCopilot\SearchEngineScraper;

include_once (dirname(__FILE__).'/AdapterInterface.php');

use MrCopilot\SearchEngineScraper as MS;


class Process implements \AdapterInterface
{
  public function executeRequest($url, $header, $userAgent)
  {
      // TODO: Implement executeRequest() method.
      if (!function_exists('curl_init')) {
         throw new MS\ErrorException(
             'cURL is missing, cURL is not installed or missing',
             MS\ErrorException::ADAPTER_CURL_MISSING
         );
      }

      $ch = @curl_init();
      if (!$ch) {
         throw new MS\ErrorException(
         'Unable to create cURL handle',
             MS\ErrorException::ADAPTER_CURL_UNABLE_INIT_CURL
         );
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $response = @curl_exec($ch);
      if ($response === false)
      {
          throw new MS\ErrorException(
              'Search request failed. curl_exec() returned FALSE. error - ' . curl_error($ch),
              MS\ErrorException::ADAPTER_CURL_EXECUTE_FAILED
          );
      }

      return $response;

  }

  public function parseHTMLtoArray($html, $engine, $keyword)
  {
      // TODO: Implement parseHTMLtoArray() method.
      $dom = new \DOMDocument();
      @$dom->loadHTML($html);
      $links = $dom->getElementsByTagName('a');

      $results = [];
      $i = 0;
      foreach ($links as $link){

          $item = $link->getAttribute('href');
          $url_pattern = '/url?q=';
          if(strpos($item, $url_pattern) !== false){

              $item = str_ireplace($url_pattern, '', $item);
              $url = strtok($item,'&');
              if (!filter_var($url, FILTER_VALIDATE_URL) === false && strpos($url,$engine) === false) {
                  $contentArr = array_values(array_filter(explode('  ', $link->parentNode->parentNode->textContent)));
                  //print_r($contentArr);
                  if(!empty($contentArr[0]) && strlen($contentArr[0])<75 && !empty($contentArr[2])){
                      $results[$i]['keyword'] = $keyword;
                      $results[$i]['url'] = $url;
                      $results[$i]['title'] = str_replace('...','', $contentArr[0]);
                      $results[$i]['desc'] = str_replace('...','', $contentArr[2]);
                      $results[$i]['rank'] = $i+1;
                      $i++;
                  }
              }
          }
      }
      return $results;
  }
}