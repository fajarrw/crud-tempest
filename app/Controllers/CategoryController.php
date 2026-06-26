<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\CategoryRequest;

use Tempest\Http\Response;
use Tempest\Http\GenericResponse;
use Tempest\Http\Status;

use Tempest\Router\Delete;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Put;
use Tempest\Router\WithoutMiddleware;
use Tempest\Router\PreventCrossSiteRequestsMiddleware;

use function Tempest\Database\query;

final class CategoryController
{
    #[Get('/items/{id}')]
    public function get(int $id): Response
    {
        $category = query(Category::class)->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        return new GenericResponse(
            status: Status::FOUND,
            body: $category->toApi(),
        );
    }

    #[Post('/items')]
    #[WithoutMiddleware(PreventCrossSiteRequestsMiddleware::class)]
    public function create(CategoryRequest $request): Response
    {
        $category = query(Category::class)->create(
            cat_title: $request->title,
            cat_pages: $request->pages,
            cat_subcats: $request->subcats,
            cat_files: $request->files1,
        );

        return new GenericResponse(
            status: Status::CREATED,
            body: $category->toApi(),
        );
    }

    #[Put('/items/{id}')]
    #[WithoutMiddleware(PreventCrossSiteRequestsMiddleware::class)]
    public function update(
        int $id,
        CategoryRequest $request,
    ): Response {

        $category = query(Category::class)->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        query($category)
            ->update(
                cat_title: $request->title,
                cat_pages: $request->pages,
                cat_subcats: $request->subcats,
                cat_files: $request->files1,
            )
            ->execute();

        $updated = query(Category::class)->findById($id);

        return new GenericResponse(
            status: Status::CREATED,
            body: $updated->toApi(),
        );
    }

    #[Delete('/items/{id}')]
    #[WithoutMiddleware(PreventCrossSiteRequestsMiddleware::class)]
    public function delete(int $id): Response
    {
        $category = query(Category::class)->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        query($category)
            ->delete()
            ->execute();

        return new GenericResponse(
            status: Status::NO_CONTENT,
        );
    }
}