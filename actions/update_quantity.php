<?php
session_start();

if (
    isset($_GET['id']) &&
    isset($_GET['action'])
) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if (isset($_SESSION['cart'][$id])) {

        if ($action === 'increase') {

            $_SESSION['cart'][$id]['quantity']++;

        }

        if ($action === 'decrease') {

            $_SESSION['cart'][$id]['quantity']--;

            if ($_SESSION['cart'][$id]['quantity'] <= 0) {

                unset($_SESSION['cart'][$id]);
            }
        }
    }
}

header("Location: ../cart.php");
exit;