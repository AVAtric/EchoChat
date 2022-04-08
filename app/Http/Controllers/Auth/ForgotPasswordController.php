<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReCaptcha\ReCaptcha;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validate the email for the given request.
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    protected function validateEmail(Request $request)
    {
        $response = (new ReCaptcha(env('APP_SECRET_CAPTCHA', '6Lcbkr4UAAAAAD2AR6psQ0ZuIKsZJa6mieQnkcUk')))
            ->setExpectedAction('validateEmail')
            ->setScoreThreshold(0.5)
            ->verify($request->input('captcha'), $request->ip());

        if (!$response->isSuccess())
            throw ValidationException::withMessages(['Bot validation failed']);

        $request->validate(['email' => 'required|email']);
    }
}
