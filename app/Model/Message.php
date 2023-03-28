<?php

namespace App\Model;

use Base\Db;

class Message
{
    private $id;
    private $text;
    private $createdDate;
    private $authorId;
    private $author;
    private $image;

    public function __construct(array $data)
    {
        $this->text = $data['text'];
        $this->createdDate = $data['created_date'];
        $this->authorId = $data['author_id'];
        $this->image = $data['image'] ?? '';
    }

    public static function deleteMessage(int $messageId)
    {
        $db = Db::getInstance();
        $query = "DELETE FROM posts WHERE id = $messageId";
        return $db->exec($query, __METHOD__);
    }

    public function save()
    {
        $db = Db::getInstance();
        $res = $db->exec(
            'INSERT INTO posts (
                    text, 
                    created_date,
                    author_id,
                    image
                    ) VALUES (
                    :text, 
                    :created_date,
                    :author_id,
                    :image
                )',
            __FILE__,
            [
                ':text' => $this->text,
                ':created_date' => $this->createdDate,
                ':author_id' => $this->authorId,
                ':image' => $this->image,
            ]
        );

        return $res;
    }

    public static function getList(int $limit = 10, int $offset = 0): array
    {
        $db = Db::getInstance();
        $data = $db->fetchAll(
            "SELECT * fROM posts LIMIT $limit OFFSET $offset",
            __METHOD__
        );
        if (!$data) {
            return [];
        }

        $messages = [];
        foreach ($data as $elem) {
            $message = new self($elem);
            $message->id = $elem['id'];
            $messages[] = $message;
        }

        return $messages;
    }

    public static function getUserMessages(int $userId, int $limit): array
    {
        $db = Db::getInstance();
        $data = $db->fetchAll(
            "SELECT * FROM posts WHERE author_id = $userId ORDER BY created_date DESC LIMIT $limit",
            __METHOD__
        );
        if (!$data) {
            return [];
        }

        $messages = [];
        foreach ($data as $elem) {
            $message = new self($elem);
            $message->id = $elem['id'];
            $messages[] = $message;
        }

        return $messages;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function loadFile(string $file)
    {
        if (file_exists($file)) {
            $this->image = $this->genFileName();
            move_uploaded_file($file, getcwd() . '/images/' . $this->image);
        }
    }

    private function genFileName()
    {
        return sha1(microtime(1) . mt_rand(1, 100000000)) . '.jpg';
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getData()
    {
        return [
            'id' => $this->id,
            'author_id' => $this->authorId,
            'text' => $this->text,
            'created_date' => $this->createdDate,
            'image' => $this->image
        ];
    }
}
