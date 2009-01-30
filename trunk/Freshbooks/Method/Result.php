<?php
/**
 * Freshbooks Api
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 * 
 * @category   Freshbooks
 * @package    Freshbooks_Api
 * @copyright  Copyright (c) 2008 Owl Watch Consulting Services, LLC.
 */

class Freshbooks_Method_Result
{
    protected $_method = null;
    
    protected $_response = null;
    
    protected $_responseXML = null;
    
    protected $_data = null;
    
    public function __construct(&$method, $response){
        $this->_method =& $method;
        $this->_response = $response;
    }
    
    public function isError(){
        return $this->_response->isError() || $this->data->attributes()->status=='fail';
    }
    
    public function getError(){
        if( $this->_response->isError() ){
            return $this->_response->getStatus().': '.$this->_response->getMessage();
        }else{
            return $this->data->message;
        }
    }
    
    public function &getMethod(){
        return $this->_method;
    }
    
    public function __get($name){
        switch($name){
            case 'responseBody':
            case 'responseXML':
                return $this->getResponseBody();
                
            case 'requestXML':
                return $this->getMethod()->getRequestXML();
            
            case 'responseXMLElement':
            case 'data':
                return $this->getResponseXMLElement();
                
            case 'method':
                return $this->getMethod();
        }
        return null;
    }
    
    protected function getResponseXMLElement(){
        if( $this->_responseXML == null ){
            $root = new SimpleXMLElement( $this->getResponseBody() );
            $this->_responseXML = $root;
        }
        return $this->_responseXML;
    }
    
    public function getResponseBody(){
        return $this->_response->getBody();
    }
    
    public function __toString(){
        return $this->responseXML;
    }
    
    public function debug(){
        return '<pre>'.htmlentities($this->requestXML)."\n".htmlentities($this->responseXML).'</pre>';
    }
    
}