<?php

declare(strict_types=1);

namespace App\Entity;

use Jajo\JSONDB;
use ReflectionClass;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class JsonEntityBase
{
    private const DB_DIR = './db';
    protected array $attributes;

    protected SerializerInterface $serializer;

    protected string $db_name;

    protected JSONDB $db;
    public function __construct()
    {
        $this->db = new JSONDB(self::DB_DIR);
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->db_name = $this->getDbFileName();
    }

    public function load($id): self {
        try
        {
            $json_data = $this->db->select()
                ->from($this->getDbFileName())
                ->where(['id' => $id])
                ->get();
            $data = reset($json_data);

        }
        catch (\Exception) {
            return $this;
        }

        foreach ($data as $field => $value) {
            $this->$field = $value;
        }

        return $this;
    }

    public function save(): void {
        $data = $this->normalize();

        if ($this->hasId()) {
            $data['id'] = $this->getLastId()+1;
            $this->db->insert($this->db_name, $data);
        }
        else {
            $this->db->update($data)
                ->from($this->db_name)
                ->where(['id' => $this->getId()])
                ->trigger();
        }
    }

    public function hasId(): bool {
        return $this->getId() === null;
    }

    public function normalize(): array
    {
        return $this->serializer->normalize($this, 'array');
    }

    protected function getDbFileName(): string {
        $reflectionClass = new ReflectionClass($this);
        return $reflectionClass->getShortName() . '.json';
    }

    protected function getLastId() {
        try {
            $allIds = $this->db->select('id')
                ->from($this->getDbFileName())
                ->order_by( 'id', JSONDB::DESC )
                ->get();
        }
        catch (\Exception)
        {
            return 0;
        }

        return reset($allIds)['id'];
    }

    abstract public function getId(): ?int;

}
