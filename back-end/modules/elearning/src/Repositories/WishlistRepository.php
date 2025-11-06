<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Wishlist;
use Newnet\Core\Repositories\BaseRepository;

class WishlistRepository extends BaseRepository
{
    public function __construct(Wishlist $model)
    {
        parent::__construct($model);
    }
}
