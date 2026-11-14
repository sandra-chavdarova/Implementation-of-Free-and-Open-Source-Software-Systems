<?php
try {
    echo bin2hex(random_bytes(32));  // random_bytes генерира случајни бајтови, bin2hex ги претвора во хексадецимален формат
} catch (Exception $e) {
    echo "Error generating secret key";
}