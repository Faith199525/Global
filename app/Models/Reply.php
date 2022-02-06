<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'author_id',
        'thread_id'
    ];

    protected $with = ['author'];

    protected static function boot() 
    {
        parent::boot();

        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivityToRecord() as $event) {

            static::$event(function ($model) use ($event) {

                $model->recordActivity($event);
    
            });
        }
        
    }

    public function author () {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likes()
    {
        return $this->morphToMany(Like::class, 'likeable');
    }

    public function replyLikesCount () {
        
        return Like::where('likeable_type', 'App\Models\Reply')
            ->where('likeable_id', $this->id)
            ->count();
        
    }
    public function isLikedBy()
    { 
        return Like::where('likeable_type', 'App\Models\Reply')
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

    protected static function getActivityToRecord() {
        return ['created', 'deleted', 'updated'];
    }

    protected function getActivityType($event) {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return $event .'_'.$type ;
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
    
        return $this->thread->path().'/replies/'.$this->id;
    }
}
