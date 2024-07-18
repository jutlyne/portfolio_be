<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Tag\CreateRequest;
use App\Http\Resources\TagResource;
use App\Repositories\TagRepository;
use Illuminate\Http\JsonResponse;

class TagController extends BaseController
{
    /**
     * Property to hold the TagRepository instance.
     */
    protected $tagRepository;

    /**
     * Constructor to initialize the TagRepository instance.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Retrieve a list of all tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $tags = $this->tagRepository->get();

        return $this->successResponse(TagResource::collection($tags));
    }

    /**
     * Create a new tag.
     *
     * @param \App\Http\Requests\CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $tag = $this->tagRepository->create($request->validated());

        return $this->successResponse(TagResource::make($tag));
    }

    /**
     * Update a tag partially with the given ID.
     *
     * @param int $id
     * @param \App\Http\Requests\CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePatch($id, CreateRequest $request)
    {
        try {
            $tag = $this->tagRepository->update($request->validated(), $id);

            return $this->successResponse(TagResource::make($tag));
        } catch (\Throwable $th) {
            logger($th);

            return $this->failedResponse(__('messages.not_found'));
        }
    }

    /**
     * Delete a tag with the given ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            $this->tagRepository->where('id', $id)->delete();

            return $this->successResponse([], __('messages.delete.success'));
        } catch (\Throwable $th) {
            logger($th);

            return $this->failedResponse(__('messages.not_found'));
        }
    }
}
