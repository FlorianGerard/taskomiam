<?php

// src/Enum/RecipeStatus.php
namespace App\Enum;

enum RecipeStatus: string
{
    case PENDING = 'pending';
    case PRIVATE = 'private';
    case PUBLISHED = 'published';
}
