<?php 

namespace App\Services;

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
    

}