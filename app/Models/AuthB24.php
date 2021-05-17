<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class AuthB24 extends Model
{
    // use HasFactory;

    /**
     * 
     * #TODO: реализовать адекватную авторизацию в Б24
     * 
     * 
     */
    private static $instances = [];
    protected function __construct(){
        $request = new Request;
        if(isset($_REQUEST['AUTH_ID'])){
            
            foreach ($_REQUEST as $key => $value) {
                // Request::session()->put( $key, $value);
            }
            // $_COOKIE = $_REQUEST;
        }
        // var_dump($_COOKIE);
        // exit;
    }
    protected function __clone(){}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    public static function getInstance(): AuthB24
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }
    public function callMethod($method,$params = []){
        // #TODO написать обработчик ошибок.
        // var_dump($_REQUEST);
        // exit;
 	
        $params['auth'] = $_REQUEST['AR_AUTH']['AUTH_ID'];
   
        $ch = curl_init('https://'.$_REQUEST['AR_AUTH']['DOMAIN'].'/rest/' . $method);
    

        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
         
        // Или предать массив строкой: 
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);	
        return json_decode($html);
    }
}
