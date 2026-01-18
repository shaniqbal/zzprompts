<?php
/**
 * Front Page Template - Modern V1
 * 
 * Loads the Modern V1 homepage layout.
 * 
 * @package zzprompts
 * @version 1.0.0
 * @layout Modern V1
 */

defined('ABSPATH') || exit;

get_header();

// Load Homepage Template
get_template_part('template-parts/home/hero', 'home');

get_footer();
