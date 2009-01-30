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

class Freshbooks_Method_Impl extends Freshbooks_Method_Abstract
{
    protected $_methodName;
    
    protected $_argumentMap = array();
    
    public function getMethodName()
    {
        if( !isset($this->_methodName) ){
            $parts = explode('_', get_class($this));
            array_shift($parts);
            foreach($parts as $k => $part){
                $parts[$k] = strtolower(substr($part,0,1)).substr($part,1);
            }
            $this->_methodName = implode('.', $parts);
        }
        return $this->_methodName;
    }
    
    public function setArgument($name, $value)
    {
        if( !$this->validArgument($name,$this->_argumentMap)){
            return false;
        }
        
        $this->cleanArgument($name,$value,$this->_argumentMap);
        $this->_arguments[$name] = $value;
        
        return true;
    }
    
    protected function validArgument($name,$map)
    {
        return in_array($name, array_keys($map));
    }
    
    protected function cleanArgument($name, &$value, $map)
    {
        if( is_string($map[$name]) ){
            switch($map[$name]){
                case 'int':
                    $value = intval($value);
                    break;
                case 'boolean':
                    $value = $value ? 'true' : 'false';
                    break;
            }
        }
        elseif( is_array($map[$name]) ){
            if( !is_array($value) ){
                $value = array();
            }
            else{
                foreach($value as $key => $val){
                    if( !$this->validArgument($key,$map[$name]) ){
                        unset($value[$key]);
                    }
                    else{
                        $this->cleanArgument($key,$value[$key],$map[$name]);
                    }
                }
            }
        }
    }
    
    public function __call($fn, $arguments)
    {
        if( strpos($fn,'set') === 0 ){
            $name = substr($fn,3);
            $name = strtolower(substr($name,0,1)).substr($name,1);
            $this->setArgument($name, $arguments[0]);
        }
        return null;
    }
    
}