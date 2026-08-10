<?php

namespace App\Models;

use App\Facades\MailSender;
use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\KnowYourClient\app\Models\KycInformation;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderDetails;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductReview;
use Modules\Product\app\Models\ProductTos;
use Modules\Wallet\app\Models\WalletHistory;

class Vendor extends Model
{
    use GlobalActiveScopeTrait, HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'phone',
        'email',
        'shop_name',
        'open_at',
        'closed_at',
        'address',
        'seo_title',
        'seo_description',
        'status',
        'is_featured',
        'top_rated',
        'is_verified',
    ];

    /**
     * @var array
     */
    protected $appends = ['name'];

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        $this->loadMissing('user');

        return $this->user?->name;
    }

    /**
     * @return mixed
     */
    public function kyc()
    {
        return $this->belongsTo(KycInformation::class, 'id', 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'vendor_id', 'id');
    }

    /**
     * @return mixed
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderDetails::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function returnPolicies()
    {
        return $this->hasMany(ProductTos::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function withdrawRequests()
    {
        return $this->hasMany(WithdrawRequest::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function walletRequests()
    {
        return $this->hasMany(WalletHistory::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function reviews()
    {
        return $this->hasManyThrough(
            ProductReview::class,
            Product::class,
            'vendor_id',
            'product_id',
            'id',
            'id'
        );
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($vendor) {
            $vendor->verification_token = str()->random(40);
            $vendor->save();

            try {
                [$subject, $message] = MailSender::fetchEmailTemplate('shop_verification', ['shop_name' => $vendor->shop_name]);

                $link = [__('COMPLETE VERIFICATION') => route('website.verify.shop', ['token' => $vendor->verification_token])];

                if ($vendor->verification_token) {
                    MailSender::sendMail($vendor->email, $subject, $message, $link);
                }
            } catch (Exception $e) {
                logError('Verification email could not be sent', $e);

                $date    = formattedTime(now());
                $message = "Unable to send vendor {$vendor->shop_name}'s verification email to {$vendor->email} at {$date}. The error is {$e->getMessage()}";

                $route = route('admin.manage-seller.profile', $vendor->user_id);

                notifyAdmin('Verification email could not be sent', $message, 'danger', $route);
            }
        });
    }
}
