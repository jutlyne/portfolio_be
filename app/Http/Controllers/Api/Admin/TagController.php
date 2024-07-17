<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Repositories\TagRepository;

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

    public function index()
    {
        $tags = $this->tagRepository->get();

        return $this->successResponse($tags);
    }
}
