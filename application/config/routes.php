<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = "home";

// Public website routes (keep these above the generic CMS/POS routes).
$route['rooms'] = 'home/rooms';
$route['rooms/(:num)'] = 'home/room/$1';
$route['booknow'] = 'home/booknow';
$route['booknow/thank-you'] = 'home/thank_you';
$route['booknow/(:num)'] = 'home/booknow/$1';
$route['booknow/check-availability'] = 'home/check_availability';
$route['booknow/reserved-dates/(:num)'] = 'home/reserved_dates/$1';
$route['cms/navigations'] = 'cms/navigations/index';
$route['cms/navigations/save'] = 'cms/navigations/save';
$route['cms/footer'] = 'cms/footer/index';
$route['cms/footer/save'] = 'cms/footer/save';
$route['booknow/submit'] = 'home/submit_booking';
$route['pages/(:any)'] = 'home/page/$1';
$route['terminal/prints'] = 'terminal/prints';
$route['terminal/poll'] = 'terminal/poll';
$route['terminal/kitchen'] = 'terminal/kitchen';
$route['terminal/kitchen/poll'] = 'terminal/poll';
$route['api/prints/pending'] = 'terminal/api_pending';
$route['api/prints/update-status'] = 'terminal/api_update_status';
$route['404_override'] = '';


$route['pos/(:any)'] = 'pos/$1';
$route['cms/(:any)'] = 'cms/$1';

//$route['pos/(:any)/(:any)(.*)'] = 'pos/$1/$2$3'; /* Controller only */ 


//$route['website/(:any)/(:any)(.*)'] = '$1/$2$3'; /* Controller only */ $route['website/(:any)'] = '$1';
//$route['time/(:any)/(:any)(.*)'] = '$1/$2$3'; /* Controller only */ $route['time/(:any)'] = '$1';
