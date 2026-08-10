<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Traits\GTMPayload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WishlistController extends Controller
{
    use GTMPayload;

    /**
     * @return mixed
     */
    public function wishlist()
    {
        return redirect()->action([UserController::class, 'wishlist']);
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function wishlistStore(Request $request)
    {
        $user = auth()->user();

        $request->validate(
            [
                'product_id' => [
                    'required',
                    'exists:products,id',
                    Rule::unique('wishlists')->where(fn($query) => $query->where('user_id', $user->id)),
                ],
            ],
            [
                'product_id.required' => __('Please select a product'),
                'product_id.exists'   => __('Product not found'),
                'product_id.unique'   => __('Product already added to wishlist'),
            ]
        );

        $wishlist = Wishlist::create([
            'product_id' => $request->product_id,
            'user_id'    => $user->id,
        ]);

        if ($wishlist) {
            return response()->json([
                'status'   => 'success',
                'message'  => __('Product added to wishlist successfully'),
                'wishlist' => $user->wishlist->count() ?? 0,
                'gtm'      => $this->getGTMPayloadForWishlist($wishlist->product),
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => __('Something went wrong'),
        ]);
    }

    /**
     * @param Request $request
     */
    public function wishlistRemove($slug)
    {
        $user = auth()->user();

        $wishlist = Wishlist::whereHas('product', function ($query) {
            $query->where('slug', request()->slug);
        })->where('user_id', $user->id)->first();

        if (!$wishlist) {
            return to_route('website.user.wishlist')->with([
                'alert-type' => 'error',
                'message'    => __('Product not found'),
            ]);
        }

        $payload = $this->getGTMPayloadForWishlist($wishlist->product, 'remove_from_wishlist');

        $wishlist->delete();

        pushToGTM($payload);

        return to_route('website.user.wishlist')->with([
            'alert-type' => 'success',
            'message'    => __('Product removed from wishlist successfully'),
        ]);
    }
}
