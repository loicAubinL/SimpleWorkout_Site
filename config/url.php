<?php

declare(strict_types=1);

function urlBase(): string
{
    return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
}

function rediriger(string $chemin = ''): never
{
    header('Location: ' . urlBase() . $chemin);
    exit;
}