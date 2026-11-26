<?php

/*
| Configuración propia del Turnero.
| Los valores salen del .env para que cada instalación (cada negocio) tenga los suyos.
*/

return [

    // Datos del primer administrador, usados por AdminSeeder al instalar.
    'admin' => [
        'name' => env('ADMIN_NAME', 'Administrador'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
