<?php

namespace App\Repositories;

use App\Models\Category;
use Tempest\Database\Database;

final readonly class CategoryRepository
{
    public function __construct(
        private Database $database,
    ) {}

    public function findById(int $id): ?Category
    {
        $row = $this->database->fetchFirst(
            query('category')
                ->select(
                    'cat_id',
                    'cat_title',
                    'cat_pages',
                    'cat_subcats',
                    'cat_files',
                )
                ->where('cat_id = ?', $id),
        );

        if ($row === null) {
            return null;
        }

        return new Category(
            id: (int) $row['cat_id'],
            title: $row['cat_title'],
            pages: (int) $row['cat_pages'],
            subcats: (int) $row['cat_subcats'],
            files: (int) $row['cat_files'],
        );
    }

    public function create(Category $category): Category
    {
        $id = query('category')
            ->insert(
                cat_title: $category->title,
                cat_pages: $category->pages,
                cat_subcats: $category->subcats,
                cat_files: $category->files,
            )
            ->execute($this->database);

        return $this->findById((int) $id);
    }

    public function update(int $id, Category $category): ?Category
    {
        if ($this->findById($id) === null) {
            return null;
        }

        query('category')
            ->update(
                cat_title: $category->title,
                cat_pages: $category->pages,
                cat_subcats: $category->subcats,
                cat_files: $category->files,
            )
            ->where('cat_id = ?', $id)
            ->execute($this->database);

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        if ($this->findById($id) === null) {
            return false;
        }

        query('category')
            ->delete()
            ->where('cat_id = ?', $id)
            ->execute($this->database);

        return true;
    }
}