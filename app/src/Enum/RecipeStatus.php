<?php

// src/Enum/RecipeStatus.php
namespace App\Enum;

enum RecipeStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case REJECTED = 'rejected';
}
