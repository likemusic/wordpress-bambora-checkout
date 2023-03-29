<?php
/*
Plugin Name: Bambora Checkout Payment Integration
Description: The Bambora Checkout Payment Integration.
Plugin URI: https://github.com/likemusic/bambora-checkout
Author: Valery Ivashchanka
Author URI: https://github.com/likemusic/
*/

require_once 'vendor/autoload.php';

use Likemusic\Wordpress\Bambora\Checkout\Core\BamboraCheckoutCorePlugin;

new BamboraCheckoutCorePlugin();
