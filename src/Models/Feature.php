<?php

declare(strict_types=1);

namespace AFZidan\Subscriptions\Models;

use Carbon\Carbon;
use Spatie\Sluggable\SlugOptions;
use  Rinvex\Support\Traits\HasSlug;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use AFZidan\Subscriptions\Services\Period;
use  Rinvex\Support\Traits\HasTranslations;
use  Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * AFZidan\Subscriptions\Models\Feature.
 *
 * @property int                 $id
 * @property int                 $plan_id
 * @property string              $slug
 * @property array               $title
 * @property array               $description
 * @property string              $value
 * @property int                 $resettable_period
 * @property string              $resettable_interval
 * @property int                 $sort_order
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \AFZidan\Subscriptions\Models\Plan                                                             $plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\AFZidan\Subscriptions\Models\PlanSubscriptionUsage[] $usage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature byPlanId($planId)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereResettableInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereResettablePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AFZidan\Subscriptions\Models\Feature whereValue($value)
 * @mixin \Eloquent
 */
class Feature extends Model implements Sortable
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
        'sort_order',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
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
        $this->setTable(config('afzidan.subscriptions.tables.features'));
        $this->mergeRules([
            'slug' => 'required|alpha_dash|max:150|unique:'.config('afzidan.subscriptions.tables.features').',slug',
            'name' => 'required|string|strip_tags|max:150',
            'description' => 'nullable|string|max:32768',
            'sort_order' => 'nullable|integer|max:100000',
        ]);

        parent::__construct($attributes);
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($plan_feature) {
            $plan_feature->usage()->delete();
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
     * The plan feature may have many subscription usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage(): HasMany
    {
        return $this->hasMany(config('afzidan.subscriptions.models.plan_subscription_usage'), 'feature_id', 'id');
    }

    /**
     * Get feature's reset date.
     *
     * @param string $dateFrom
     *
     * @return \Carbon\Carbon
     */
    public function getResetDate(Carbon $dateFrom): Carbon
    {
        $period = new Period($this->resettable_interval, $this->resettable_period, $dateFrom ?? now());

        return $period->getEndDate();
    }

    /**
     * The feature may belong to many plans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plans(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            config('afzidan.subscriptions.models.plan'),
            config('afzidan.subscriptions.tables.feature_plan'),
            'feature_id',
            'plan_id'
        )
            ->withPivot(
                'value',
                'resettable_period',
                'resettable_interval',
            );
    }
}
