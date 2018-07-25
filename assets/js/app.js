const $ = require('jquery');
require('bootstrap');

$(document).ready(function() {
    // get current URL path and assign 'active' class
    let pathname = window.location.pathname;
    $('.navbar-nav > li > a[href="'+pathname+'"]').parent().addClass('active');
})