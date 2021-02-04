<?php 

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Helpers 
{

    
    /**
     * getMethod
     * @param  string $str
     * @return string
     */
    public static function getMethod(string $str):string 
    {
         $needle = '_';
         $method = "";
         if (preg_match( "#{$needle}#", $str) ){
              $array = preg_split("#{$needle}#", $str);
              if ( is_array($array) ) {
                   foreach ($array as $v) {
                        $method .= ucfirst($v);
                   }
              }
              return $method;
         } 
         return ucfirst($str);
    }

     /**
     * Generate a token
     * @param integer $length
     * @return string
     */
    public function generateToken(int $length):string
    {
        $char_to_shuffle =  'azertyuiopqsdfghjklwxcvbnAZERTYUIOPQSDFGHJKLLMWXCVBN1234567890';
        return substr( str_shuffle($char_to_shuffle) , 0 , $length);
    }
        
    /**
     * setJsonMessage
     * @param  string $type
     * @param  string $message
     * @return array
     */
    public function setJsonMessage(string $message, string $type = 'danger') :array
    {
        return ["type" => $type, "content" => $message];
    } 
        
     /**
     * jsonResponse
     *
     * @param  array $data
     * @param  int $status
     * @return JsonResponse
     */
    public function jsonResponse(array $data = [], int $status = Response::HTTP_OK):JsonResponse 
    {
        return new JsonResponse($data, $status);
    }

    public function transformKeys($instance,string $class):array
    {
        $array = [];
        $instance = (array) $instance;

        foreach($instance as $key => $value) {
            $key = str_replace("\X00{$class}\X00", "", $key);
            $array[$key] = $value;
        }

        return $array;
    }

}