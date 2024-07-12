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
     * Define the columns to be selected from the database for a blog.
     */
    protected $selectColumns = [
        'id',
        'title',
        'short_text',
        'image',
        'created_at',
        'updated_at',
    ];

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
        $blogs = $this->blogRepository
            ->with(['tags'])
            ->skip($request->skip)
            ->take($request->limit)
            ->get();

        return $this->successResponse(BlogResource::collection($blogs));
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
            $tags = [];
            $params = $request->validated();
            $params['user_id'] = auth()->id();
            $upload = $this->uploadMedia($request->image);
            $params['image'] = $upload['url'];
            $blog = $this->blogRepository->create($params);

            foreach ($params['tags'] as $tagId) {
                $tags[] = [
                    'blog_id' => $blog->id,
                    'tag_id' => $tagId
                ];
            }

            DB::table('blog_tags')->insert($tags);

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
}
