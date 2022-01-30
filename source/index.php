<?php

$path = trim($_SERVER["REQUEST_URI"], "/");

require_once "core/Routing.php";
require_once "core/Database.php";

Router::get('', 'DefaultController');
Router::get('editor', 'DefaultController');
Router::post('login', 'SecurityController');
Router::post('register', 'SecurityController');
Router::post('logout', 'SecurityController');
Router::post('note', 'NoteController');
Router::post('save', 'NoteController');
Router::post('new', 'NoteController');
Router::post('delete', 'NoteController');
Router::post('nickname', 'SecurityController');
Router::post('noteinfo', 'NoteController');
Router::post('tagnote', 'NoteController');
Router::post('untagnote', 'NoteController');
Router::post('newtag', 'NoteController');
Router::post('notesbytags', 'NoteController');
Router::post('share', 'NoteController');
Router::post('finduser', 'DefaultController');
Router::post('noteshares', 'NoteController');
Router::post('unshare', 'NoteController');
Router::get('shares', 'DefaultController');

Router::run($path);
