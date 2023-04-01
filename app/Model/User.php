<?php

namespace App\Model;

use Base\Db;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;
    public $id;
    public $name;
    public $created_date;
    public $password;
    public $email;


    public static function getByEmail(string $email)
    {
        $data = self::query()->where('email', '=', $email);
        var_dump($data);
        die;
        if (!$data) {
            return null;
        }

        $user = new self($data);
        $user->id = $data['id'];
        return $user;
    }

    public static function getByIds(array $userIds)
    {
        $idsString = implode(',', $userIds);
        return self::query()->where('id', 'IN', $idsString);
    }

    public function saveUser()
    {
        $this->save();
        $this->id = $this["id"];
    }

    public static function getById(int $id): ?self
    {
        return self:: query()->find($id)->first();
    }

    public static function getPasswordHash(string $password)
    {
        return sha1('.,f.akjsduf' . $password);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isAdmin()
    {
        return in_array($this->id, ADMIN_IDS);
    }
}