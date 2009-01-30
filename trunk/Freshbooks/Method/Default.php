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
 * @package    Freshbooks_Request
 * @copyright  Copyright (c) 2008 Owl Watch Consulting Services, LLC.
 */

class Freshbooks_Method_Default extends Freshbooks_Method_Abstract
{
    
    protected $_methodName;
    
    public function __construct($methodName=''){
        $this->_methodName = $methodName;
    }
    
    public function setArgument( $name, $value ){
        $this->_arguments[$name] = $value;
    }
    
    public function getRequestXML(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".
               '<request method="'.$this->_methodName.'">';
        foreach($this->_arguments as $name => $value){
            $xml.=$this->getXMLTag($name, $value);
        }
        $xml.= '</request>';
        return $xml;
    }
    
    protected function getXMLTag($name,$value){
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
        }
        return null;
    }
    
}