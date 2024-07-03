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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'home';
$route['admin'] = 'admin/dashboard';
$route['admin/login'] = 'home/login';

$route['api/v1/products'] = 'api/v1/apiproduct';
$route['api/v1/product/(:num)']['GET'] = 'api/v1/apiproduct/$1';
$route['api/v1/product/search']['POST'] = 'api/v1/apiproduct/search';

$route['api/v1/categories'] = 'api/v1/apicategory';
$route['api/v1/category/(:num)']['GET'] = 'api/v1/apicategory/$1';

$route['api/v1/transaction/filters']['GET'] = 'api/v1/apitransaction/filter';
$route['api/v1/transactions/(:num)/(:any)']['GET'] = 'api/v1/apitransaction/$1/$2';
$route['api/v1/transaction/(:num)/(:any)/(:num)']['GET'] = 'api/v1/apitransaction/$1/$2/$3';
$route['api/v1/transaction/confirm/(:num)']['GET'] = 'api/v1/apitransaction/confirm/$1';

$route['api/v1/transaction/update_status']['PUT'] = 'api/v1/apitransaction/update_status';
$route['api/v1/transaction/upload_image']['POST'] = 'api/v1/apitransaction/upload_image';
$route['api/v1/transaction/checkout']['POST'] = 'api/v1/apitransaction/checkout';

$route['api/v1/customer/(:num)']['GET'] = 'api/v1/apicustomer/$1';
$route['api/v1/customer/update_profile']['PUT'] = 'api/v1/apicustomer/update_profile';

$route['api/v1/carts/(:num)']['GET'] = 'api/v1/apicart/$1';
$route['api/v1/cart/insert']['POST'] = 'api/v1/apicart/insert';
$route['api/v1/cart/update']['PUT'] = 'api/v1/apicart/update_qty';
$route['api/v1/cart/delete/(:num)']['GET'] = 'api/v1/apicart/delete/$1';

$route['api/v1/customer/login']['POST'] = 'api/v1/apicustomer/login';
$route['api/v1/customer/register']['POST'] = 'api/v1/apicustomer/register';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
