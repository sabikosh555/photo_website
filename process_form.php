<?php
if ($_SERVER["REQUEST_METHOD"] = "POST") {
    $name = htmlspecialchars($_POST['name']);
    $age = (int)$_POST['age'];

    echo "Your name: $name<br>" ;
    echo "Your age: $age" ;
}