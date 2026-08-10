<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\app\Models\EmailTemplate;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;

class EmailSettingController extends Controller
{
    use GlobalMailTrait;

    public function email_config()
    {
        $activeTemplateNames = [
            'password_reset',
            'registration_otp',
            'password_reset_otp',
        ];

        $templates = EmailTemplate::whereIn('name', $activeTemplateNames)->orderBy('name')->get();

        return view('globalsetting::email.email_config', compact('templates'));
    }

    /**
     * @param Request $request
     */
    public function update_email_config(Request $request)
    {
        $request->validate([
            'mail_sender_name'  => 'required',
            'mail_host'         => 'required',
            'mail_sender_email' => 'required',
            'mail_username'     => 'required',
            'mail_password'     => 'required',
            'mail_port'         => 'required|numeric',
            'mail_encryption'   => 'required',
        ], [
            'mail_sender_name.required'  => __('Sender name is required'),
            'mail_host.required'         => __('Mail host is required'),
            'mail_sender_email.required' => __('Email is required'),
            'mail_username.required'     => __('Smtp username is required'),
            'mail_password.unique'       => __('Smtp password is required'),
            'mail_port.required'         => __('Mail port is required'),
            'mail_port.numeric'          => __('Mail port must be a number'),
            'mail_encryption.required'   => __('Mail encryption is required'),
        ]);

        Setting::where('key', 'mail_sender_name')->update(['value' => $request->mail_sender_name]);
        Setting::where('key', 'mail_host')->update(['value' => $request->mail_host]);
        Setting::where('key', 'mail_sender_email')->update(['value' => $request->mail_sender_email]);
        Setting::where('key', 'mail_username')->update(['value' => $request->mail_username]);
        Setting::where('key', 'mail_password')->update(['value' => $request->mail_password]);
        Setting::where('key', 'mail_port')->update(['value' => $request->mail_port]);
        Setting::where('key', 'mail_encryption')->update(['value' => $request->mail_encryption]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param $id
     */
    public function edit_email_template($id)
    {

        $template = EmailTemplate::where('id', $id)->firstOrFail();

        $templateName = $template->name;

        $variables = [];

        try {
            $templatesArray = collect(EmailTemplateSeeder::emailTemplates());
            $staticTemplate = $templatesArray->firstWhere('name', $templateName);

            if (!$staticTemplate) {
                throw new Exception("Template '{$templateName}' not found in the static template list.");
            }

            preg_match_all('/{{\s*(.*?)\s*}}/', $staticTemplate['message'], $matches);

            $variables = array_unique($matches[1]);

        } catch (Exception $e) {
            logError("Email Template Error", $e);
        }

        return view('globalsetting::email.template.template', compact('template', 'variables'));
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update_email_template(Request $request, $id)
    {
        $rules = [
            'subject' => 'required',
            'message' => 'required',
        ];
        $customMessages = [
            'subject.required' => __('Subject is required'),
            'message.required' => __('Message is required'),
        ];

        $request->validate($rules, $customMessages);

        $template = EmailTemplate::find($id);
        if ($template) {
            $template->subject = $request->subject;
            $template->message = $request->message;
            $template->save();
            $notification = __('Updated Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('admin.email-configuration')->with($notification);
        } else {
            $notification = __('Something went wrong');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }

    /**
     * @return mixed
     */
    public function test_mail_credentials()
    {
        try {
            $this->sendMail('example@gmail.com', 'Test Email', 'This is a test email');
            $notification = __('Mail Sent Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            return $this->handleMailException($e);
        }
    }
}
