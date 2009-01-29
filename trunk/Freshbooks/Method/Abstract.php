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

abstract class Freshbooks_Method_Abstract
{
    protected $_result = null;
    
    protected $_arguments = array();
    
    abstract public function setArgument( $name, $value );
    
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
    
    abstract public function getRequestXML();
    
    public function execute(){
        return Freshbooks_Api::getInstance()->dispatch($this);
    }
    
    public function &getResult(){
        return $this->_result;
    }
    
}