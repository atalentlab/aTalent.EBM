<?php

namespace App\Http\Controllers\Main;

use App\Http\Requests\Main\RegisterUserRequest;
use App\Http\Requests\Main\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscriber;
use App\Jobs\SubscribeToNewsletter;
use Illuminate\Support\Facades\View;
use App\Traits\RegistersUsers;

class FormController extends Controller
{
    use RegistersUsers;

    public function submitRegisterForm(RegisterUserRequest $request)
    {
        $user = $this->registerUser($request);

        $view = 'main.partials.form-status.register-success';

        return response()->json([
            'body' => view($view)->render(),
        ]);
    }

    public function submitNewsletterSubscriptionForm(StoreNewsletterSubscriptionRequest $request)
    {
        $subscriber = new NewsletterSubscriber;
        $subscriber->email = $request->input('email');
        $subscriber->save();

        SubscribeToNewsletter::dispatch($subscriber, 'newsletter-form');

        $view = 'main.partials.form-status.subscribe-success';

        return response()->json([
            'body' => view($view)->render(),
        ]);
    }
}
