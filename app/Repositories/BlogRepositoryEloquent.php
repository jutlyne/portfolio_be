<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\BlogRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class BlogRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class BlogRepositoryEloquent extends BaseRepository implements BlogRepository
{
    /**
     * Define the columns to be selected from the database for a blog.
     */
    protected $selectColumns = [
        'id',
        'title',
        'slug',
        'short_text',
        'image',
        'headings',
        'created_at',
        'updated_at',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Blog::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Search for blogs based on given conditions.
     *
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($conditions)
    {
        $query = $this->with(['tags'])
            ->when(
                isset($conditions['title']),
                function ($query) use ($conditions) {
                    return $query->where('title', 'LIKE', '%' . $conditions['title'] . '%');
                }
            )
            ->when(
                isset($conditions['start_date']),
                function ($query) use ($conditions) {
                    return $query->where('created_at', '>=', $conditions['start_date']);
                }
            )
            ->when(
                isset($conditions['end_date']),
                function ($query) use ($conditions) {
                    return $query->where('created_at', '<=', $conditions['end_date']);
                }
            )
            ->when(
                isset($conditions['tag']),
                function ($query) use ($conditions) {
                    return $query->whereHas('tags', function ($q) use ($conditions) {
                        $q->where('tags.id', $conditions['tag']);
                    });
                }
            );

        $totalRecords = $query->count();

        $results = $query->skip($conditions['skip'])
            ->take($conditions['limit'])
            ->get($this->selectColumns);

        return [
            'total' => $totalRecords,
            'data' => $results,
        ];
    }
}
