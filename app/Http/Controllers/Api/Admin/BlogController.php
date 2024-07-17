<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Blog\CreateRequest;
use App\Http\Requests\Blog\ListRequest;
use App\Http\Resources\BlogResource;
use App\Repositories\BlogRepository;
use App\Traits\MediaTrait;
use Illuminate\Support\Facades\DB;

class BlogController extends BaseController
{
    /**
     * This trait provides methods for handling media uploads.
     */
    use MediaTrait;

    /**
     * Property to hold the BlogRepository instance.
     */
    protected $blogRepository;

    /**
     * Constructor to initialize the BlogRepository instance.
     *
     * @param BlogRepository $blogRepository
     */
    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * Display a listing of the blogs.
     *
     * @param ListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListRequest $request)
    {
        $blogs = $this->blogRepository->search($request->validated());

        return $this->successResponse([
            'blogs' => BlogResource::collection($blogs['data']),
            'total' => $blogs['total'],
        ]);
    }

    /**
     * Store a newly created blog in the database.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request)
    {
        DB::beginTransaction();

        try {
            $params = $request->validated();
            $params['user_id'] = auth()->id();
            $upload = $this->uploadMedia($request->image);
            $params['image'] = $upload['url'];
            $blog = $this->blogRepository->create($params);

            $blog->tags()->sync($params['tags']);

            DB::commit();

            return $this->successResponse(new BlogResource($blog));
        } catch (\Throwable $th) {
            logger($th);
            DB::rollBack();

            return $this->failedResponse(__('messages.create.failed'));
        }
    }

    /**
     * Display the specified blog.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $blog = $this->blogRepository->find($id);
            return $this->successResponse(new BlogResource($blog));
        } catch (\Throwable $th) {
            logger($th);
            return $this->failedResponse(__('messages.not_found'));
        }
    }

    /**
     * Update the specified blog.
     *
     * @param int $id
     * @param \App\Http\Requests\CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, CreateRequest $request)
    {
        DB::beginTransaction();

        try {
            $blog = $this->blogRepository->find($id);
            $params = $request->validated();
            $params['user_id'] = auth()->id();
            if (!empty($request->image)) {
                $upload = $this->uploadMedia($request->image);
                $params['image'] = $upload['url'];
                $image = $blog->getRawOriginal('image');
                $this->removeMedia($image);
            } else {
                unset($params['image']);
            }

            $blog->tags()->sync($params['tags']);
            $blog->update($params);

            DB::commit();

            return $this->successResponse(new BlogResource($blog));
        } catch (\Throwable $th) {
            logger($th);
            DB::rollBack();

            return $this->failedResponse(__('messages.create.failed'));
        }
    }

    /**
     * Delete the specified blog.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $blog = $this->blogRepository->find($id);
            $image = $blog->getRawOriginal('image');
            $blog->tags()->detach();
            $blog->delete();

            $this->removeMedia($image);

            DB::commit();

            return $this->successResponse([], __('messages.delete.success'));
        } catch (\Throwable $th) {
            logger($th);
            DB::rollBack();

            return $this->failedResponse(__('messages.not_found'));
        }
    }
}
