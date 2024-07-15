<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MediaRepository;
use App\Models\Media;
use App\Validators\MediaValidator;

/**
 * Class MediaRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MediaRepositoryEloquent extends BaseRepository implements MediaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Media::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
