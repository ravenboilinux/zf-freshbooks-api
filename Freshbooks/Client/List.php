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
require_once( dirname(__FILE__).'/Abstract.php' );

class Freshbooks_Client_List extends Freshbooks_Method_Impl
{
    protected $_argumentMap = array(
        'email'         => 'string',
        'username'      => 'string',
        'page'          => 'int',
        'per_page'      => 'int'
    );
    
}