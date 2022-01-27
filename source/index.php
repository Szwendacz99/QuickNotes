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
router::post('new', 'NoteController');
router::post('delete', 'NoteController');
router::post('nickname', 'SecurityController');
router::post('noteinfo', 'NoteController');
router::post('tagnote', 'NoteController');
router::post('untagnote', 'NoteController');
router::post('newtag', 'NoteController');

Router::run($path);
