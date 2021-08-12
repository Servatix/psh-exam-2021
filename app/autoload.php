<?php
spl_autoload_register(
    fn($class) => require 'classes/' . preg_replace_callback(
        '/[[:upper:]]/',
        fn($m) => ($m[0][1]?'_':'').lcfirst($m[0][0]),
        $class, flags: PREG_OFFSET_CAPTURE
    ) . '.php'
);