<?php
require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn()) {
    redirect('auth/login.php');
}
