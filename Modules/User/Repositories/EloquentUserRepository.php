<?php

namespace Modules\User\Repositories;

use App\Models\User;
use \Illuminate\Database\Eloquent\Collection;
use Modules\Core\Logic\Abstract\AbstractRepository;

class EloquentUserRepository extends AbstractRepository implements UserRepositoryInterface
{
    CONST default_limit = 25;

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function all(): Collection
    {
        return User::all();
    }

    public function get($limit = self::default_limit): Collection
    {
        return User::limit($limit)->get();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create(array $data): Collection
    {
        return User::create($data);
    }

    public function update($id, array $data): Collection|bool
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return false;
    }

    public function delete($id): bool
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }
}
