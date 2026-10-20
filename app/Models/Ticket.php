<?php

namespace App\Models;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'priority',
        'user_id',
        'admin_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => TicketCategory::class,
            'status' => TicketStatus::class,
            'priority' => TicketPriority::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Superadmins see every ticket, admins see tickets assigned to them, employees see their own.
     *
     * @param  Builder<Ticket>  $query
     */
    #[Scope]
    protected function visibleTo(Builder $query, User $user): void
    {
        match ($user->role) {
            UserRole::Superadmin => null,
            UserRole::Admin => $query->whereBelongsTo($user, 'admin'),
            UserRole::User => $query->whereBelongsTo($user, 'user'),
        };
    }

    /**
     * Mirrors the visibleTo() scope for a single, already loaded ticket.
     */
    public function isVisibleTo(User $user): bool
    {
        return match ($user->role) {
            UserRole::Superadmin => true,
            UserRole::Admin => $this->admin()->is($user),
            UserRole::User => $this->user()->is($user),
        };
    }
}
