<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Door;
use App\Http\Resources\DoorResource;

class DoorController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = Door::latest()->paginate(5);

        //return collection of posts as a resource
        return new DoorResource(true, 'List Data Posts', $posts);
    }
}
