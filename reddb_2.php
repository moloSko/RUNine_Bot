<?php

require "lidrs/rb.php";

R::setup('mysql:host=ip;dbname=name','login', 'password');

if(!R::testConnection()) echo('Нет подключения - Проверьте : host, namebd, login_db, password_bd.');