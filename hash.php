<?php
// Defina a sua senha master de 8 ou mais caracteres
$senha_pura = 'adminadm'; // <-- TROQUE AQUI PELA SENHA QUE DESEJA
$hash_seguro = password_hash($senha_pura, PASSWORD_DEFAULT);
    
echo "O HASH SEGURO é: <strong>" . $hash_seguro . "</strong>";
?>