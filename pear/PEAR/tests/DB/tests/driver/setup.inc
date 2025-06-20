<?php

/**
 * Establishes the include_path, DSN's and connection $options
 *
 * If this file is named "setup.inc.cvs," it is the original one from CVS.
 * Please do the following:
 *   1) Make a copy of this file named "setup.inc".
 *   2) Then, in the copy, edit the $dsns array as appropriate for your
 *      environment.
 *   3) At least one element of the $dsns array needs to be uncommented.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Database
 * @package    DB
 * @author     Daniel Convissor <danielc@php.net>
 * @copyright  1997-2007 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    $Id: setup.inc 315483 2011-08-25 15:46:09Z danielc $
 * @link       http://pear.php.net/package/DB
 */

if (!defined('PATH_SEPARATOR')) {
    if (stristr(PHP_OS, 'WIN')) {
        /**
         * The string used to delimit elements of the path.
         */
        define('PATH_SEPARATOR', ';');
    } else {
        /**
         * The string used to delimit elements of the path.
         */
        define('PATH_SEPARATOR', ':');
    }
}

/*
 * If the path to your PEAR installation is found in the left hand
 * portion of the if() expression below, that means this file has
 * come from the PEAR installer.  Therefore, let's use the
 * installed version of DB, which should be found via the
 * computer's default include_path.  Add '.' to the include_path
 * to ensure '.' is in there.
 * 
 * If the path has not been substituted in the if() expression,
 * this file has likely come from a CVS checkout or a .tar file.
 * Therefore, we'll assume the tests should use the version of
 * DB that has come from there as well.
 */
if ('/www/job2014.godohosting.com/pear/PEAR' != '@'.'include_path'.'@') {
    ini_set('include_path', ini_get('include_path')
            . PATH_SEPARATOR . '.'
    );
} else {
    ini_set('include_path', realpath(dirname(__FILE__) . '/../..')
            . PATH_SEPARATOR . '.' . PATH_SEPARATOR
            . ini_get('include_path')
    );
}

/**
 * Grab the PEAR DB classes.
 */
require_once 'DB.php';

// Options used when connecting
$options = array(
    //'optimize' => 'portability',
    'portability' => DB_PORTABILITY_ALL,
    'debug' => 2,
);

$dbasedsn = array(
    'phptype'  => 'dbase',
    'database' => '/path/and/name/of/dbase/file',
    'mode'     => 2,
    'fields'   => array(
        array('a', 'N', 5, 0),
        array('b', 'C', 40),
        array('c', 'C', 255),
        array('d', 'C', 20),
    ),
);

/*
 * Uncomment at least one of the following elements.
 * When running the .phpt tests, the first uncommented element is used.
 * When running the multiconnect.php test, all uncommented elements are used.
 */
$dsns = array(
    // 'dbase'    => $dbasedsn,
    // 'fbsql'    => 'fbsql://_system:@/db',
    // 'firebird' => 'ibase(firebird)://SYSDBA:masterkey@//opt/interbase/examples/employee.gdb?dialect=3',
    // 'ifx'      => 'ifx://user:pw@localhost/db',
    // 'msql'     => 'msql:///db',

    // It's advisable to use only one of the following at a time:
    // 'mssql'    => 'mssql://sa@somehost/pubs',
    // 'sybase'   => 'sybase://sa@somehost/pubs',

    // 'mysql'    => 'mysql://root@localhost/test',
    // 'mysqli'   => 'mysqli://root@localhost/test',
    // 'oci8'     => 'oci8://system:manager@',
    // 'access'   => 'odbc(access)://admin@/SystemDsnName',
    // 'db2'      => 'odbc(db2)://db2inst1:XXXX@/SAMPLE',
    // 'pgsql'    => 'pgsql://postgres@localhost/test',
    'sqlite'   => 'sqlite://dummy:@localhost/' . getcwd() . DIRECTORY_SEPARATOR . 'test.db?mode=0644',
);
