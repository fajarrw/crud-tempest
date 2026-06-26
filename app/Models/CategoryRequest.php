<?php

namespace App\Models;

use Tempest\Http\IsRequest;
use Tempest\Http\Request;

final class CategoryRequest implements Request
{
    use IsRequest;

    public string $title;

    public int $pages;

    public int $subcats;

    public int $files1;
}