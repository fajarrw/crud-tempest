<?php

namespace App\Controllers;

use App\Repositories\CategoryRepository;
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

final class CategoryController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {}

    #[Get('/items/{id}')]
    public function get(int $id): Response
    {
        $category = $this->categoryRepository->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        return new GenericResponse(
            status: Status::OK,
            body: $category->toApi(),
        );
    }

    #[Post('/items')]
    #[WithoutMiddleware(PreventCrossSiteRequestsMiddleware::class)]
    public function create(Request $request): Response
    {
        $body = $request->body;

        $newCategory = new Category();
        $newCategory->cat_title = (string) ($body['title'] ?? '');
        $newCategory->cat_pages = (int) ($body['pages'] ?? 0);
        $newCategory->cat_subcats = (int) ($body['subcats'] ?? 0);
        $newCategory->cat_files = (int) ($body['files'] ?? 0);

        $id = $this->categoryRepository->create($newCategory);
        $category = $this->categoryRepository->findById($id);

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
        $category = $this->categoryRepository->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        $category->cat_title = (string) ($body['title'] ?? $category->cat_title);
        $category->cat_pages = (int) ($body['pages'] ?? $category->cat_pages);
        $category->cat_subcats = (int) ($body['subcats'] ?? $category->cat_subcats);
        $category->cat_files = (int) ($body['files'] ?? $category->cat_files);

        if (! $this->categoryRepository->update($id, $category)){
            return new GenericResponse(
                status: Status::INTERNAL_SERVER_ERROR,
                body: ['message' => 'Update failed'],
            );
        }

        return new GenericResponse(status: Status::NO_CONTENT);
    }

    #[Delete('/items/{id}')]
    #[WithoutMiddleware(PreventCrossSiteRequestsMiddleware::class)]
    public function delete(int $id): Response
    {
        $category = $this->categoryRepository->findById($id);

        if ($category === null) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Category not found'],
            );
        }

        if (! $this->categoryRepository->delete($id)) {
            return new GenericResponse(
                status: Status::NOT_FOUND,
                body: ['message' => 'Deletion failed'],
            );
        }

        return new GenericResponse(
            status: Status::NO_CONTENT,
        );
    }
}
