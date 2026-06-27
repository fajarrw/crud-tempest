<?php

namespace App\Models;

use Tempest\Database\PrimaryKey;
use Tempest\Database\Table;

#[Table('category')]
final class Category
{
    public ?PrimaryKey $cat_id = null;
    public string $cat_title;
    public int $cat_pages;
    public int $cat_subcats;
    public int $cat_files;

    public function toApi(): array
    {
        return [
            'id' => $this->cat_id?->value,
            'title' => $this->cat_title,
            'pages' => $this->cat_pages,
            'subcats' => $this->cat_subcats,
            'files' => $this->cat_files,
        ];
    }
}
