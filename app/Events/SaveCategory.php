<?php

namespace App\Events;

use App\Model\Guess\Category;
use Illuminate\Queue\SerializesModels;

class SaveCategory
{
    use SerializesModels;

    public $category;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
}
