<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use App\Book;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use Helpers; 

    /*
     * Get the user starting from the token, and then return all the related books as output.
     */
    public function index()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        return $currentUser
            ->books()
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
    }

    /*
     * Get the current user with the JWTAuth method, parseToken()->authenticate(). The create the instance and save the relationship with the save method.
     *
     * If everything goes right, return a 'Created 201'. Otherwise return a custom 500 error.
     * Not just the body: Headers are tweaked to follow RESTful standards.
     */
    public function store(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = new Book;

        $book->title = $request->get('title');
        $book->author_name = $request->get('author_name');
        $book->pages_count = $request->get('pages_count');

        if($currentUser->books()->save($book))
            return $this->response->created();
        else
            return $this->response->error('could_not_create_book', 500);
    }
}
