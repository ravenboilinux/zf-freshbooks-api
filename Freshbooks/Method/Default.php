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

class Freshbooks_Method_Default extends Freshbooks_Method_Abstract
{
    
    protected $_methodName;
    
    public function __construct($methodName=''){
        $this->_methodName = $methodName;
    }
    
    public function getMethodName(){
        return $this->_methodName;
    }
    
}