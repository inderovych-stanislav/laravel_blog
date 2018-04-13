<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();
        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save;
    }

    public function generatePassword($password)
    {
        if ($password != null) {
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function remove()
    {
        Storage::delete('/uploads/admin/avatar/' . $this->avatar);
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if ($image == null) {
            return;
        }
        if ($this->avatar != null) {
            Storage::delete('/uploads/admin/avatar/' . $this->avatar);
        }

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('/uploads/admin/avatar/', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function getImage()
    {
        if ($this->avatar == null) {
            return '/img/admin/no-avatar.png';
        }
        return '/uploads/admin/avatar/' . $this->avatar;
    }
}
