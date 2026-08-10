<?php

namespace Modules\Blog\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Modules\Blog\app\Models\BlogComment;
use Yajra\DataTables\Facades\DataTables;

class BlogCommentController extends Controller
{
    use RedirectHelperTrait;

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('blog.comment.view');

        $query = BlogComment::with('post')->latest();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('comment_excerpt', function ($comment) {
                    return e(\Illuminate\Support\Str::limit($comment->comment, 30, '...'));
                })
                ->addColumn('post_title', function ($comment) {
                    if (!$comment->post) {
                        return '';
                    }

                    return '<a href="' . route('website.blog', $comment->post->slug) . '" target="_blank">' . e($comment->post->title) . '</a>';
                })
                ->addColumn('author_name', function ($comment) {
                    $html = e($comment->name);

                    if ($comment->is_admin) {
                        $html .= ' <small class="badge badge-info py-1">' . __('Admin') . '</small>';
                    }

                    return $html;
                })
                ->addColumn('status_toggle', function ($comment) {
                    if (!checkAdminHasPermission('blog.comment.update')) {
                        return '';
                    }

                    $checked = $comment->status ? 'checked' : '';

                    return '<input id="status_toggle_' . $comment->id . '" data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="changeStatus(' . $comment->id . ')" ' . $checked . '>';
                })
                ->addColumn('action', function ($comment) {
                    if (!checkAdminHasPermission('blog.comment.view') && !checkAdminHasPermission('blog.comment.delete') && !checkAdminHasPermission('blog.comment.replay')) {
                        return '';
                    }

                    $html = '';

                    if (checkAdminHasPermission('blog.comment.view')) {
                        $html .= '<a class="btn btn-success btn-sm" href="' . route('admin.blog-comment.show', $comment->post?->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
                    }

                    if (checkAdminHasPermission('blog.comment.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.blog-comment.destroy', $comment->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a> ';
                    }

                    if (checkAdminHasPermission('blog.comment.replay')) {
                        $html .= '<a class="post-reply btn btn-info btn-sm" data-id="' . $comment->id . '" data-bs-toggle="modal" data-bs-target="#post-reply" href="javascript:;" title="Reply"><i class="fas fa-reply"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['post_title', 'author_name', 'status_toggle', 'action'])
                ->make(true);
        }

        return view('blog::Comment.index');
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('blog.comment.view');
        $comments = BlogComment::withNested()->where(['blog_id' => $id, 'parent_id' => 0])->latest()->paginate(10);

        return view('blog::Comment.show', compact('comments'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('blog.comment.delete');
        BlogComment::findOrFail($id)?->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.blog-comment.index');
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('blog.comment.update');
        $blogCategory = BlogComment::find($id);
        if ($blogCategory) {
            $status = $blogCategory->status == 1 ? 0 : 1;
            $blogCategory->update(['status' => $status]);

            $notification = __('Updated Successfully');

            return response()->json([
                'success' => true,
                'message' => $notification,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Failed!'),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function reply(Request $request)
    {
        checkAdminHasPermissionAndThrowException('blog.comment.replay');

        $comment = BlogComment::find($request->comment_id);

        $blog_id           = $comment->blog_id;
        $comment->status   = 1;
        $data              = request()->all();
        $data['is_admin']  = 1;
        $data['status']    = 1;
        $data['parent_id'] = $request->comment_id;
        $data['blog_id']   = $blog_id;
        $data['comment']   = $request->reply;
        $data['name']      = auth('admin')->user()->name;
        $data['email']     = auth('admin')->user()->email;
        $data['image']     = auth('admin')->user()->image;

        BlogComment::create($data);

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.blog-comment.show', ['blog_comment' => $blog_id], [
            'message'    => 'Reply Successfully!',
            'alert-type' => 'success',
        ]);
    }
}
