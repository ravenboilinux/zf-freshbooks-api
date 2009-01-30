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

abstract class Freshbooks_Method_Abstract
{
    protected $_result = null;
    
    protected $_arguments = array();
    
    abstract public function getMethodName();
    
    public function setArgument( $name, $value ){
        $this->_arguments[$name] = $value;
    }
    
    public function setArguments( $arguments=null ){
        if( !is_array($arguments) ){
            return;
        }
        foreach($arguments as $name => $value ){
            $this->setArgument( $name, $value );
        }
    }
    
    public function setResponse( $response )
    {
        $this->_result = new Freshbooks_Method_Result($this, $response);
    }
    
    public function getRequestXML(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".
               '<request method="'.$this->getMethodName().'">';
        foreach($this->_arguments as $name => $value){
            $xml.=$this->getXMLTag($name, $value);
        }
        $xml.= '</request>';
        return $xml;
    }
    
    public function execute()
    {
        return Freshbooks_Api::getInstance()->dispatch($this);
    }
    
    public function &getResult()
    {
        return $this->_result;
    }
    
    protected function getXMLTag($name,$value)
    {
        $tag = '';
        if( is_array($value) && isset($value[0]) ){
            // this is an associative array.
            foreach($value as $ar){
                $tag.="<{$name}>";
                foreach($ar as $k => $v){
                    $tag.=$this->getXMLTag($k,$v);
                }
                $tag.="</{$name}>";
            }
        }
        elseif( is_array($value) ){
            $tag.="<{$name}>";
            foreach($value as $k => $v){
                $tag.=$this->getXMLTag($k,$v);
            }
            $tag.="</{$name}>";
        }
        else{
            $tag.="<{$name}><![CDATA[{$value}]]></{$name}>";
        }
        return $tag;
    }
    
    public function __get($name){
        switch($name){
            case 'requestXML':
            case 'requestBody':
                return $this->getRequestXML();
                
            case 'methodName':
                return $this->getMethodName();
        }
        return null;
    }
    
}