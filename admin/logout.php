<?php
// [WEEK 6: Session Destruction]
session_start();
require_once '../classes/User.php';
User::logout();
?>