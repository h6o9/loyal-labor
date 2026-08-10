<?php

namespace Modules\Faq\app\Models;

use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Faq\app\Enums\FaqGroupEnum;

class Faq extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['status', 'group'];

    /**
     * @return mixed
     */
    public function getGroupEnumAttribute(): ?FaqGroupEnum
    {
        return FaqGroupEnum::tryFrom($this->group);
    }

    /**
     * @return mixed
     */
    public function getQuestionAttribute(): ?string
    {
        return $this->translation->question;
    }

    /**
     * @return mixed
     */
    public function getAnswerAttribute(): ?string
    {
        return $this->translation->answer;
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(FaqTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param  $code
     * @return mixed
     */
    public function getTranslation($code): ?FaqTranslation
    {
        return $this->hasOne(FaqTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(FaqTranslation::class, 'faq_id');
    }
}
