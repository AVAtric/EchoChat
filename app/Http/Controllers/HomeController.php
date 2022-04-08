<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function chat()
    {
        return view('chat');
    }

    public function verify(Request $request)
    {
        $response = (new \ReCaptcha\ReCaptcha('6Lcbkr4UAAAAAD2AR6psQ0ZuIKsZJa6mieQnkcUk'))
            ->setExpectedAction('chatMessage')
            ->verify($request->input('token'), $request->ip());

        if (!$response->isSuccess()) {
            return response('error')
                ->header('Content-Type', 'text/plain');
        }
        if ($response->getScore() < 0.6) {
            return response('wrong score: ' . $response->getScore())
                ->header('Content-Type', 'text/plain');
        }

        return response('success')
            ->header('Content-Type', 'text/plain');
    }
}
