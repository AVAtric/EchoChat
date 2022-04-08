@extends('layouts.app')

@section('content')
    <section class="section is-fullheight">
        <div class="container">
            <div class="columns is-multiline">
                <div class="card card column is-half is-offset-one-quarter">
                    <header class="card-header">
                        <p class="card-header-title">
                            {{ __('Verify Your Email Address') }}
                        </p>
                    </header>
                    <div class="card-content">
                        @if (session('resent'))
                            <article class="message is-success">
                                <div class="message-body">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            </article>
                        @endif
                        <div class="content">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form method="POST" action="{{ route('verification.resend') }}" id="resend">
                                @csrf
                            </form>
                            <a href="#" class="card-footer-item" onclick="event.preventDefault();
                                                     document.getElementById('resend').submit();">
                                {{ __('click here to request another') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
