<?php

namespace App\Helpers;

class Helpers
{
    static function image_placeholder(): string
    {
        return "https://fastly.picsum.photos/id/866/200/300.jpg?hmac=rcadCENKh4rD6MAp6V_ma-AyWv641M4iiOpe1RyFHeI";
    }

    static function all_provinces(): array
    {
        // Include and return the provinces array
        return include __DIR__ . "/_masterData.php";
    }
}
