<?php

namespace Modules\NewsLetter\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MailSenderService;
use Illuminate\Http\Request;
use Modules\NewsLetter\app\Models\NewsLetter;
use Yajra\DataTables\Facades\DataTables;

class NewsLetterController extends Controller
{
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('newsletter.view');

        $query = NewsLetter::where('status', 'verified')->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('email_decoded', function ($item) {
                    return html_decode($item->email);
                })
                ->addColumn('subscribed_at', function ($item) {
                    return formattedDateTime($item->created_at);
                })
                ->addColumn('action', function ($item) {
                    if (!checkAdminHasPermission('newsletter.delete')) {
                        return '';
                    }

                    return '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="'
                        . route('admin.subscriber-delete', $item->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('newsletter::index');
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('newsletter.mail');

        return view('newsletter::create');
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('newsletter.delete');
        $newsletter = NewsLetter::find($id);
        $newsletter->delete();

        $notification = __('Deleted successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function store(Request $request)
    {
        checkAdminHasPermissionAndThrowException('newsletter.mail');
        $request->validate([
            'subject' => 'required',
            'description' => 'required',
        ], [
            'subject.required' => __('Subject is required'),
            'description.required' => __('Description is required'),
        ]);

        $newsletterCount = NewsLetter::select('id')->orderBy('id', 'desc')->where('status', 'verified')->count();

        if ($newsletterCount > 0) {
            $email_list = NewsLetter::select('email')->orderBy('id', 'desc')->where('status', 'verified')->get();
            (new MailSenderService)->SendBulkEmail($email_list, $request->subject, $request->description);

            $notification = __('Mail Sent Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            $notification = __('The email cannot be sent because no subscribers were found.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return redirect()->back()->with($notification);
    }
}
