<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Blog\ListRequest;
use App\Http\Requests\Blog\UploadFileRequest;
use App\Http\Resources\BlogResource;
use App\Http\Resources\MediaResource;
use App\Repositories\BlogRepository;
use App\Repositories\MediaRepository;
use App\Traits\MediaTrait;
use Illuminate\Http\Response;

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
     * Display the specified blog.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        try {
            $blog = $this->blogRepository->where('slug', $slug)->first();
            return $this->successResponse(new BlogResource($blog));
        } catch (\Throwable $th) {
            logger($th);
            return $this->findSimilarBlogBySlug($slug);
        }
    }

    /**
     * Attempt to find a similar blog by partially matching the slug.
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    protected function findSimilarBlogBySlug($slug)
    {
        $parts = explode('-', $slug);
        $number = (int) array_pop($parts);
        if ($number) {
            $restOfString = implode('-', $parts);
            $blog = $this->blogRepository->where('slug', 'LIKE', $restOfString . '-%')->get();
            if (count($blog) == 1) {
                return $this->successResponse(new BlogResource($blog[0]));
            }
        }

        return $this->failedResponse(__('messages.not_found'), Response::HTTP_NOT_FOUND);
    }

    /**
     * Handle the file upload and store the media record.
     *
     * @param \App\Http\Requests\UploadFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(UploadFileRequest $request)
    {
        $upload = $this->uploadMedia($request->file);
        $mediaRepository = app(MediaRepository::class);
        $media = $mediaRepository->create($upload);

        return $this->successResponse(new MediaResource($media));
    }
}
