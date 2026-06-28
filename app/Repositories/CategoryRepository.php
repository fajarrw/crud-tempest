<?php

namespace App\Repositories;

use App\Models\Category;
use Tempest\Database\Database;
use Tempest\Database\PrimaryKey;
use Tempest\Database\Query;

final readonly class CategoryRepository
{
    public function __construct(
        private Database $database,
    ) {}

    public function findById(int $id): ?Category
    {
        $row = $this->database->fetchFirst(
            new Query(
                sql: 'SELECT cat_id, cat_title, cat_pages, cat_subcats, cat_files
                    FROM category
                    WHERE cat_id = ?',
                bindings: [$id],
            )
        );

        if ($row === null) {
            return null;
        }

        $category = new Category();
        $category->cat_id = PrimaryKey::tryFrom($row['cat_id']);
        $category->cat_title = $row['cat_title'];
        $category->cat_pages = (int) $row['cat_pages'];
        $category->cat_subcats = (int) $row['cat_subcats'];
        $category->cat_files = (int) $row['cat_files'];

        return $category;    
    }

    public function create(Category $category): Category
    {
        $this->database->execute(
            new Query(
                sql: 'INSERT INTO category (cat_title, cat_pages, cat_subcats, cat_files)
                    VALUES (?, ?, ?, ?)',
                bindings: [
                    $category->cat_title,
                    $category->cat_pages,
                    $category->cat_subcats,
                    $category->cat_files,
                ],
            )
        );

        $id = $this->database->getLastInsertId();

        return $this->findById($id->value);
    }

    public function update(int $id, Category $category): ?Category
    {
        if ($this->findById($id) === null) {
            return null;
        }

        $this->database->execute(
            new Query(
                sql: 'UPDATE category
                    SET cat_title = ?, cat_pages = ?, cat_subcats = ?, cat_files = ?
                    WHERE cat_id = ?',
                bindings: [
                    $category->cat_title,
                    $category->cat_pages,
                    $category->cat_subcats,
                    $category->cat_files,
                    $id,
                ],
            )
        );

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        if ($this->findById($id) === null) {
            return false;
        }

        $this->database->execute(
            new Query(
                sql: 'DELETE FROM category WHERE cat_id = ?',
                bindings: [$id],
            )
        );

        return true;
    }
}
