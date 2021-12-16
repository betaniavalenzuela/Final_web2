<?php
require_once './libs/RouterClass.php';
//require_once 'api/ProductoApiController.php';
require_once 'api/ComentarioApiController.php';



$router= new Router();
//armo tabla de ruteo
/**
tenga en cuenta los siguientes casos de uso:
Como cliente quiero poder ver mis datos personales
Como cliente quiero poder modificar mis datos personales
c)Como cliente quiero poder ver un listado de mis tarjetas

d)Como cliente quiero poder el estado actual de mi cuenta
e)Como cliente quiero poder ver mi historial de actividades dado un intervalo de dos fechas
f)Como cliente quiero poder dar de baja una tarjeta

 */

$router->addRoute('clientes/:ID','GET','YPFApiController','getcliente');
$router->addRoute('clientes/:ID','PUT','YPFApiController','updateCliente');
$router->addRoute('clientes/tarjetas/:ID', 'GET', 'YPFApiController', 'getTarjetas');
$router->addRoute('clientes/actividad/:ID', 'GET', 'YPFApiController', 'getActividad');
$router->addRoute('clientes/actividades/:ID', 'GET', 'YPFApiController', 'getActividades');
$router->addRoute('clientes/tarjetas/:ID', 'delete', 'YPFApiController', 'deleteTarjeta');

$router->route($_REQUEST['resource'],  $_SERVER['REQUEST_METHOD']);


¿Qué cambios se deben realizar en el sistema para integrar estos requerimientos a través de una API REST? 
los cambios que deben realizarse son:

*Diseñar un nuevo router (API ROUTER) para que procese los llamados a los nuevos servicios.
Procesa llamados del tipo “api/recurso/:params”
Rutea según recurso y verbo


*se debe modificar el .htaccess para redirigir la solicitud a un router-api.php
