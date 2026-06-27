<?php

namespace App\Controllers;

use App\Models\Category;

use Tempest\Http\Response;
use Tempest\Http\Request;
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
    public function create(Request $request): Response
    {
        $body = $request->body;

        $category = query(Category::class)->create(
            cat_title: (string) ($body['title'] ?? ''),
            cat_pages: (int) ($body['pages'] ?? 0),
            cat_subcats: (int) ($body['subcats'] ?? 0),
            cat_files: (int) ($body['files'] ?? 0),
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
        Request $request,
    ): Response {

        $body = $request->body;
        $category = query(Category::class)->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        query($category)
            ->update(
                cat_title: (string) ($body['title'] ?? $category->cat_title),
                cat_pages: (int) ($body['pages'] ?? $category->cat_pages),
                cat_subcats: (int) ($body['subcats'] ?? $category->cat_subcats),
                cat_files: (int) ($body['files'] ?? $category->cat_files),
            )
            ->execute();

        return new GenericResponse(
            status: Status::NO_CONTENT,
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
