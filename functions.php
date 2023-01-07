<?php
/*
 * By Marco Nagel 2023
 * Licenced Creative Commons Attribution-ShareAlike 4.0 International
 * CC-BY SA 4.0
 * https://creativecommons.org/licenses/by-sa/4.0/legalcode
 * https://creativecommons.org/licenses/by-sa/4.0/deed.en
 */

/* 
 * wp connect functions.php file
 * 
 * See Theme info in the style.css
 * 
 * theme namespaces
 * ----------------
 * \lib -> classes library
 */
//we need to die if no ABSPATH defined
if (!defined('ABSPATH')) { die("Forbidden."); }

//require libs
require_once 'lib/WPCCore.php';

//start up core
$WPCCore = new \lib\WPCCore();
