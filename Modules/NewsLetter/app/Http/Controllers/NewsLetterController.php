<?php

namespace Modules\NewsLetter\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\NewsLetter\app\Models\NewsLetter;

class NewsLetterController extends Controller
{
    use GlobalMailTrait;

    public function newsletter_request(Request $request)
    {

        $request->validate([
            'email' => 'required|unique:news_letters',
        ], [
            'email.required' => __('Email is required'),
            'email.unique' => __('Email already exist'),
        ]);

        $newsletter = new NewsLetter;
        $newsletter->email = $request->email;
        $newsletter->verify_token = Str::random(100);
        $newsletter->save();

        [$subject, $message] = $this->fetchEmailTemplate('subscribe_notification');
        $link = [__('CONFIRM YOUR EMAIL') => route('newsletter-verification', $newsletter->verify_token)];

        $this->sendMail($newsletter->email, $subject, $message, $link);

        return response()->json(['message' => __('A verification link has been send to your email, please verify it and getting our newsletter')]);

    }

    public function newsletter_verification($token)
    {
        $newsletter = NewsLetter::where('verify_token', $token)->first();

        if ($newsletter) {
            $newsletter->verify_token = null;
            $newsletter->status = 'verified';
            $newsletter->save();

            $notification = __('Newsletter verification successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('website.home')->with($notification);

        } else {
            $notification = __('Newsletter verification failed for invalid token');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('website.home')->with($notification);
        }

    }
}
