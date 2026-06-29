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
        private Category $category
    ) {}

    public function findById(int $id): ?Category
    {
        try {
            $row = $this->database->fetchFirst(
                new Query(
                    sql: 'SELECT cat_id, cat_title, cat_pages, cat_subcats, cat_files
                        FROM category
                        WHERE cat_id = ?',
                    bindings: [$id],
                )
            );
        } catch (\Throwable $e) {
            $this->logger->error('DB error in findById: ' . $e->getMessage());
            throw $e;
        }

        if ($row === null) {
            return null;
        }

        return $this->category->fromMap($row);
    }

    public function create(Category $category): int
    {
        try {
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

            if ($id === null) {
                $this->logger->error('Insert succeeded but no last insert ID returned');
                throw new \RuntimeException('Failed to retrieve inserted ID');
            }

            return $id->value;

        } catch (\Throwable $e) {
            $this->logger->error(
                'Error creating category: ' . $e->getMessage(),
                ['exception' => $e]
            );

            throw $e;
        }
    }

    public function update(int $id, Category $category): bool
    {
        try {
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
        } catch (\Exception $e) {
            $this->logger->error("Error updating category with ID {$id}: " . $e->getMessage());
            return false;
        }

        return true;
    }

    public function delete(int $id): bool
    {
        try {
            $this->database->execute(
                new Query(
                    sql: 'DELETE FROM category WHERE cat_id = ?',
                    bindings: [$id],
                )
            );
        } catch (\Exception $e) {
            $this->logger->error("Error deleting category with ID {$id}: " . $e->getMessage());
            return false;
        }

        return true;
    }
}
