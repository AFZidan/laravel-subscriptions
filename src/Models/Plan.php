<?php

declare(strict_types=1);

namespace AFZidan\Subscriptions\Models;

use Spatie\Sluggable\SlugOptions;
use  Rinvex\Support\Traits\HasSlug;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use  Rinvex\Support\Traits\HasTranslations;
use  Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * AFZidan\Subscriptions\Models\Plan.
 *
 * @property int                 $id
 * @property string              $slug
 * @property array               $name
 * @property array               $description
 * @property bool                $is_active
 * @property float               $price
 * @property float               $signup_fee
 * @property string              $currency
 * @property int                 $trial_period
 * @property string              $trial_interval
 * @property int                 $invoice_period
 * @property string              $invoice_interval
 * @property int                 $grace_period
 * @property string              $grace_interval
 * @property int                 $prorate_day
 * @property int                 $prorate_period
 * @property int                 $prorate_extend_due
 * @property int                 $active_subscribers_limit
 * @property int                 $sort_order
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\AFZidan\Subscriptions\Models\Feature[]      $features
 * @property-read \Illuminate\Database\Eloquent\Collection|\AFZidan\Subscriptions\Models\PlanSubscription[] $subscriptions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereActiveSubscribersLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereGraceInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereGracePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereInvoiceInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereInvoicePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereProrateDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereProrateExtendDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereProratePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereSignupFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereTrialInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereTrialPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Plan extends Model implements Sortable
{
    use HasSlug;
    use SoftDeletes;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'published',
        'price',
        'annual_discount',
        'signup_fee',
        'currency',
        'trial_period',
        'trial_interval',
        'invoice_period',
        'invoice_interval',
        'grace_period',
        'grace_interval',
        'prorate_day',
        'prorate_period',
        'prorate_extend_due',
        'active_subscribers_limit',
        'sort_order',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
        'is_active' => 'boolean',
        'published' => 'boolean',
        'price' => 'float',
        'annual_discount' => 'float',
        'signup_fee' => 'float',
        'currency' => 'string',
        'trial_period' => 'integer',
        'trial_interval' => 'string',
        'invoice_period' => 'integer',
        'invoice_interval' => 'string',
        'grace_period' => 'integer',
        'grace_interval' => 'string',
        'prorate_day' => 'integer',
        'prorate_period' => 'integer',
        'prorate_extend_due' => 'integer',
        'active_subscribers_limit' => 'integer',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The sortable settings.
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'sort_order',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('afzidan.subscriptions.tables.plans'));
        $this->mergeRules([
            'slug' => 'required|alpha_dash|max:150|unique:'.config('afzidan.subscriptions.tables.plans').',slug',
            'name' => 'required|string|strip_tags|max:150',
            'description' => 'nullable|string|max:32768',
            'is_active' => 'sometimes|boolean',
            'published' => 'sometimes|boolean',
            'price' => 'required|numeric',
            'annual_discount' => 'nullable|numeric',
            'signup_fee' => 'required|numeric',
            'currency' => 'required|alpha|size:3',
            'trial_period' => 'sometimes|integer|max:100000',
            'trial_interval' => 'sometimes|in:hour,day,week,month',
            'invoice_period' => 'sometimes|integer|max:100000',
            'invoice_interval' => 'sometimes|in:hour,day,week,month',
            'grace_period' => 'sometimes|integer|max:100000',
            'grace_interval' => 'sometimes|in:hour,day,week,month',
            'sort_order' => 'nullable|integer|max:100000',
            'prorate_day' => 'nullable|integer|max:150',
            'prorate_period' => 'nullable|integer|max:150',
            'prorate_extend_due' => 'nullable|integer|max:150',
            'active_subscribers_limit' => 'nullable|integer|max:100000',
        ]);

        parent::__construct($attributes);
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($plan) {
            $plan->features()->delete();
            $plan->planSubscriptions()->delete();
        });
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * The plan may belong to many features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            config('afzidan.subscriptions.models.feature'),
            config('afzidan.subscriptions.tables.feature_plan'),
            'plan_id',
            'feature_id'
        )
            ->withPivot(
                'value',
                'resettable_period',
                'resettable_interval',
            );
    }

    /**
     * The plan may have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(config('afzidan.subscriptions.models.plan_subscription'), 'plan_id', 'id');
    }

    /**
     * Check if plan is free.
     *
     * @return bool
     */
    public function isFree(): bool
    {
        return (float) $this->price <= 0.00;
    }

    /**
     * Check if plan is Published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return (boolean) $this->published;
    }

    /**
     * Scope a query to only include published plans.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published',true);
    }

    /**
     * Check if plan has trial.
     *
     * @return bool
     */
    public function hasTrial(): bool
    {
        return $this->trial_period && $this->trial_interval;
    }

    /**
     * Check if plan has grace.
     *
     * @return bool
     */
    public function hasGrace(): bool
    {
        return $this->grace_period && $this->grace_interval;
    }

    /**
     * Get plan feature by the given slug.
     *
     * @param string $featureSlug
     *
     * @return \AFZidan\Subscriptions\Models\Feature|null
     */
    public function getFeatureBySlug(string $featureSlug): ?Feature
    {
        return $this->features()->where('slug', $featureSlug)->first();
    }

    /**
     * Activate the plan.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate the plan.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }
}
