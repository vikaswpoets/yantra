<?php

add_action('footer-scripts',function(){
});
add_action('site-header',function(){
    echo "\n<script src='".site_url()."/js/yantra.js'></script>";
});