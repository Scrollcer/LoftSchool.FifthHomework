<?php

namespace App\Model;

use Base\Db;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    private $id;
    private $name;
    private $createdDate;
    private $password;
    private $email;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->password = $data['password'];;
        $this->createdDate = $data['created_date'];
        $this->email = $data['email'];
    }


    public static function getByEmail(string $email)
    {
//        $db = Db::getInstance();
//        $data = $db->fetchOne(
//            "SELECT * fROM users WHERE email = :email",
//            __METHOD__,
//            [':email' => $email]
//        );
//        if (!$data) {
//            return null;
//        }

//        $user = new self($data);
//        $user->id = $data['id'];
//        return $user;

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
//        $db = Db::getInstance();
//        $idsString = implode(',', $userIds);
//        $data = $db->fetchAll(
//            "SELECT * fROM users WHERE id IN($idsString)",
//            __METHOD__
//        );
//        if (!$data) {
//            return [];
//        }
//
//        $users = [];
//        foreach ($data as $elem) {
//            $user = new self($elem);
//            $user->id = $elem['id'];
//            $users[$user->id] = $user;
//        }
//
//        return $users;
        $idsString = implode(',', $userIds);
        return self::query()->where('id', 'IN', $idsString);
    }

    public function saveUser()
    {
        $db = Db::getInstance();
        $res = $db->exec(
            'INSERT INTO users (
                    name,
                    password,
                    created_date,
                    email
                    ) VALUES (
                    :name,
                    :password,
                    :created_date,
                    :email
                )',
            __FILE__,
            [
                ':name' => $this->name,
                ':password' => self::getPasswordHash($this->password),
                ':created_date' => $this->createdDate,
                ':email' => $this->email,
            ]
        );

        $this->id = $db->lastInsertId();

        return $res;
    }

    public static function getById(int $id): ?self
    {
//        $db = Db::getInstance();
//        $data = $db->fetchOne("SELECT * fROM users WHERE id = :id", __METHOD__, [':id' => $id]);
//        if (!$data) {
//            return null;
//        }
//
//        $user = new self($data);
//        $user->id = $id;
//        return $user;
        return self:: query()->find($id)->first();
    }


    public static function getList(int $limit = 10, int $offset = 0): array
    {
//        $db = Db::getInstance();
//        $data = $db->fetchAll(
//            "SELECT * fROM users LIMIT $limit OFFSET $offset",
//            __METHOD__
//        );
//        if (!$data) {
//            return [];
//        }
//
//        $users = [];
//        foreach ($data as $elem) {
//            $user = new self($elem);
//            $user->id = $elem['id'];
//            $users[] = $user;
//        }
//
//        return $users;
        return self:: query()->limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
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