<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Scopes\DepartmentHeadScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Filament\Models\Contracts\HasAvatar;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'military_number',
        'department_id',
        'rank_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['Admin', 'Department Head']);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function behaviorNotes()
    {
        return $this->hasMany(BehaviorNote::class);
    }

    public function positiveBehavior()
    {
        return $this->hasMany(BehaviorNote::class)->where('is_positive', true);
    }

    public function negativeBehavior()
    {
        return $this->hasMany(BehaviorNote::class)->where('is_positive', false);
    }

    public function generalNotes()
    {
        return $this->hasMany(GeneralNote::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->latest();
    }

    public function assignedQuizzes()
    {
        return $this->hasMany(Quiz::class)->where('is_opened', false);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ( auth()->user()->id == 1){
            return '/avatar/C.svg';
        }

        return '/avatar/black.svg';
    }

    public function assginQuiz(int $lvl, int $count, int $duration){
        
        $questions = Question::where('level', $lvl)->where('department_id', $this->department_id)->get();
        $questions = $questions->random(min($questions->count(), $count))->shuffle()->pluck('id')->toArray();

        $this->quizzes()->save(
            new Quiz([
                'level' => $lvl,
                'duration' => $duration,
                'questions' => $questions,
            ])
        );
    }

}
