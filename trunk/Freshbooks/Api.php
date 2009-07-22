<?php
/**
 * Freshbooks Api
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Freshbooks
 * @package    Freshbooks_Api
 * @copyright  Copyright (c) 2008 Owl Watch Consulting Services, LLC.
 */

require_once( dirname(__FILE__).'/Method/Default.php' );

class Freshbooks_Api
{
    /**
     * The instance of the singleton
     *
     * @var Freshbooks_Api
     */
    protected static $_instance = null;
    
    protected $_userAgent = "Freshbooks API Implementation for Zend Framework";
    
    protected $_poweredBy = "Owl Watch Consulting Services, LLC";
    
    protected $_apiUri = "";
    
    protected $_apiUriPath = ""
    
    protected $_token = "";
    
    protected $_lastResponse = null;
    
    protected $_error = false;
    
    protected $_client = null;
    
    protected $_clientType='zend';
    
    protected function __construct()
    {
    }
    
    public static function &getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function setAccountInformation($apiUri, $token)
    {
        $this->_apiUri = $apiUri;
        $this->_token = $token;
    }
    
    public function call($methodName, $arguments=null)
    {
        if( empty($this->_apiUri) ){
            $this->_error = "No API URI specified - Required";
            return false;
        }
        
        $method = null;
        
        $parts = explode(".", $methodName);
        $path = dirname(__FILE__);
        foreach($parts as &$part){
            $part = ucfirst($part);
            $path.='/'.$part;
        }
        array_unshift($parts,"Freshbooks");
        
        $className = implode('_', $parts);
        if( @class_exists($className)){
            // see if the file is here...
            if(file_exists($path.'.php')){
                require_once($path.'.php');
            }
            $method = new $className();
        }
        else{
            $method = new Freshbooks_Method_Default($methodName);
        }
        $method->setArguments( $arguments );
        return $this->dispatch( $method );
    }
    
    public function dispatch(&$method){
        
        $client =& $this->getClient();
        if( $this->_clientType == 'zend'){
            $client->setUri( $this->_apiUri );
            $client->setRawData( $method->getRequestXML() );
            $client->setAuth( $this->_token, 'XXX');
            $method->setResponse( $client->request() );
        }
        else{
            $client->post( $this->_apiUriPath, $method->getRequestXML() )
            $method->setResponse( $client->getResponse() );
        }
        return $method->getResult();
        
    }
    
    protected function &getClient()
    {
        if( $this->_client == null ){
            if( class_exists('Zend_Http_Client')){
                $this->_clientType = 'zend';
                $this->_client = new Zend_Http_Client();
                $this->_client->setConfig(array(
                    'timeout'       => 20,
                    'useragent'     => $this->_userAgent,
                ));
                $this->_client->setHeaders(array(
                    'X-Powered-By'  => $this->_poweredBy
                ));
                $this->_client->setMethod(Zend_Http_Client::POST);
            }
            else{
                require_once dirname(__FILE__).'/../Http/Client.php';
                $this->_clientType = 'willison';
                // we need to parse this uri
                $parts = parse_url( $this->_apiUri );
                $this->_apiUriPath = $parts['path'];
                $this->_client = new Http_Client($parts['host']);
                $this->_client->setUserAgent($this->_userAgent);
                $this->_client->timeout = 20;
                $this->_client->setAuthorization($this->_token,'XXX');
                
            }
        }
        return $this->_client;
    }
    
    public function getError()
    {
        return $this->_error;
    }
}