<?php
/**
 * Power Framework
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 *
 * @package  Power
 * @author   Core Engine
 * @license  GPL-2.0-or-later
 * @link     www.daniellane.eu
 */

/**
 * Calls the init.php file, but only if the child theme has not called it first.
 *
 * This method allows the child theme to load
 * the framework so it can use the framework
 * components immediately.
 */
require_once __DIR__ . '/lib/init.php';
