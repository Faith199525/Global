<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'body',
        'files'
    ];

    protected $with = ['author', 'replies'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function (Builder $builder) {
            $builder->withCount('replies');
        });

        if (auth()->guest()) {
            return;
        }
        
        foreach (static::getActivityToRecord() as $event) {

            static::$event(function ($model) use ($event) {

                $model->recordActivity($event);
    
            });
        }

        // static::deleting(function ($thread) { // delete likes if thread is deleted

        //     $thread->likes()->delete();

        // });

    }


    public function author () {
       return $this->belongsTo(User::class, 'author_id');
    }

    public function path() {
        return '/threads/'. $this->category->name . '/' . $this->id;
    }

    public function replies () {
        return $this->hasMany(Reply::class);
    }

    public function category () {
        return $this->belongsTo(Category::class);
    }
     
     public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function addReply ($reply) {
        $this->replies()->create($reply);
    }

    public function updateReply ($reply) {
        $this->replies()->update($reply);
    }

    public function likes()
    {
        return $this->morphToMany(Like::class, 'likeable');
    }

    public function threadLikesCount () {
        
        return Like::where('likeable_type', 'App\Models\Thread')
            ->where('likeable_id', $this->id)
            ->count();
        
    }

    public function isLikedBy()
    { 
        return Like::where('likeable_type', 'App\Models\Thread')
            ->where('likeable_id', $this->id)
            ->where('user_id', Auth()->id() )
            ->count();
    }

    protected function recordActivity($event)
    {
        $this->activities()->create([
            'user_id'=> auth()->id(),
            'type' => $this->getActivityType($event),
            'subject_id' => $this->id,
            'subject_type' => get_class($this)
        ]);
    }

    protected function getActivityType($event) {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return $event .'_'.$type ;
    }

    protected static function getActivityToRecord() {
        return ['created', 'deleted', 'updated'];
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function lock() {
        
        return $this->locked = true;
    }

    public function unlock() {
        
        return $this->locked = false;
    }

    public function archive() {
        
        return $this->archived = true;
    }

    public function unarchive() {
        
        return $this->archived = true;
    }

    public function scopeWhereDateBetween($query,$fieldName,$fromDate,$todate)
    { // to retrive only threads bewteen a given time range
        return $query->whereDate($fieldName,'>=',$fromDate)->whereDate($fieldName,'<=',$todate);
    }
}
