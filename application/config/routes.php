<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['404_override'] = 'pages/home'; //ez vajon hogy működik?
$route['default_controller'] = 'pages/home';
//$route['test'] = 'pages/test';
$route['rss'] = 'articles/rss';
$route['search/(.+)']['get'] = 'articles/search/$1';
$route['search']['post'] = 'articles/search';
$route['meta/(.+)/(.+)'] = 'articles/meta/$1/$2';
$route['author/(.+)'] = 'articles/author_search/$1';
$route['calendar'] = 'pages/calendar';
$route['users/(.+)'] = 'users/$1';
$route['admin/(.+)'] = 'admin/$1';
$route['(:num)/(:num)/(:num)/(.+)']['get'] = 'articles/view/$4';
//$route['(:num)/(:num)/(:num)/(.+)']['post'] = 'articles/article_comment/$4';
$route['articles/get_(.+)/(.+)'] = 'articles/get_$1/$2';
$route['articles/(.+)'] = 'articles/view/$1'; //joker-route
$route['(.+)/(.+)/(.+)/(.+)/(.+)'] = 'articles/view/$5';
$route['(.+)'] = 'articles/category_list/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
