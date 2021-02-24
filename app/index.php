<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\System;
(System::system()) ? require_once __DIR__ . '/Routes/Web.php' : $msj = 'Sistema apagado';
if (!empty($msj)) :
    echo $msj;
endif;