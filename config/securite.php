<?php

declare(strict_types=1);

function demarrerSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function jetonCsrf(): string
{
    if (!isset($_SESSION['jeton_csrf'])) {
        $_SESSION['jeton_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['jeton_csrf'];
}

function verifierJetonCsrf($jeton): bool
{
    return is_string($jeton)
        && isset($_SESSION['jeton_csrf'])
        && hash_equals($_SESSION['jeton_csrf'], $jeton);
}