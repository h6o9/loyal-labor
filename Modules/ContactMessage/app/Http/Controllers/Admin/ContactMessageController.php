<?php

namespace Modules\ContactMessage\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ContactMessage\app\Models\ContactMessage;
use Yajra\DataTables\Facades\DataTables;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('contact.message.view');

        $query = ContactMessage::orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('name_decoded', function ($message) {
                    return html_decode($message->name);
                })
                ->addColumn('email_link', function ($message) {
                    return '<a href="mailto:' . html_decode($message->email) . '">' . html_decode($message->email) . '</a>';
                })
                ->addColumn('created_at_formatted', function ($message) {
                    return formattedDateTime($message->created_at);
                })
                ->addColumn('action', function ($message) {
                    $html = '<a href="' . route('admin.contact-message', $message->id) . '" class="btn btn-success btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                    if (checkAdminHasPermission('contact.message.delete')) {
                        $html .= ' <a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.contact-message-delete', $message->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['email_link', 'action'])
                ->make(true);
        }

        return view('contactmessage::index');
    }

    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('contact.message.view');

        $message = ContactMessage::findOrFail($id);

        return view('contactmessage::show', ['message' => $message]);
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('contact.message.delete');
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        $notification = __('Deleted successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.contact-messages')->with($notification);
    }
}
