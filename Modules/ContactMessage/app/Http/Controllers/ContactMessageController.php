<?php

namespace Modules\ContactMessage\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CustomRecaptcha;
use App\Traits\GlobalMailTrait;
use Exception;
use App\Http\Requests\AntiBotFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\ContactMessage\app\Models\ContactMessage;

class ContactMessageController extends Controller
{
    use GlobalMailTrait;

    /**
     * @param Request $request
     */
    public function store(AntiBotFormRequest $request)
    {
        $setting = Cache::get('setting');

        $request->validate([
            'name'            => 'required',
            'email'           => 'required',
            'subject'         => 'required',
            'message'         => 'required|string|max:5000',
            'phone'           => 'nullable|string|max:20',
            'recaptcha_token' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha] : '',
        ], [
            'name.required'            => __('Name is required'),
            'email.required'           => __('Email is required'),
            'subject.required'         => __('Subject is required'),
            'message.required'         => __('Message is required'),
            'phone.max'                => __('Phone number should not exceed 20 characters'),
            'recaptcha_token.required' => __('Please complete the recaptcha to submit the form'),

        ]);

        try {
            DB::beginTransaction();

            $new_message          = new ContactMessage;
            $new_message->name    = $request->name;
            $new_message->email   = $request->email;
            $new_message->subject = $request->subject;
            $new_message->message = $request->message;
            $new_message->phone   = $request->phone;
            $new_message->save();

            //mail send
            $str_replace = [
                'name'    => $new_message->name,
                'email'   => $new_message->email,
                'phone'   => $new_message->phone,
                'subject' => $new_message->subject,
                'message' => $new_message->message,
            ];
            [$subject, $message] = $this->fetchEmailTemplate('contact_mail', $str_replace);
            $this->sendMail($setting->contact_message_receiver_mail, $subject, $message);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            logError("Sending Contact mail failed", $e);

            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => __('Failed to send contact message. Please try again later.'),
            ]);
        }

        return redirect()->back()->with([
            'alert-type' => 'success',
            'message'    => __('Contact message sent successfully'),
        ]);
    }
}
