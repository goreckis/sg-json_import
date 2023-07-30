<?php

declare(strict_types=1);

namespace App\Entity;

use Exception;
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

    public function save(bool $check_unique = TRUE): void {
        if (!$this->hasId()) {
            if ($check_unique && !$this->isUnique()) {
                return;
            }
            $this->setCreated(new \DateTime('now'));
            $data = $this->normalize();
            $data['id'] = $this->getLastId()+1;

            $this->db->insert($this->db_name, $data);
        }
        else {
            $data = $this->normalize();
            $this->db->update($data)
                ->from($this->db_name)
                ->where(['id' => $this->getId()])
                ->trigger();
        }
    }

    public function hasId(): bool {
        return $this->getId() !== null;
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

    protected function getUniqueColumns(): array {
        $columns = [];
        $reflectionClass = new ReflectionClass($this);

        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            if (!empty($property->getAttributes('App\Entity\JSON\Unique'))) {
                $columns[] = $property->getName();
            }
        }

        return $columns;
    }

    protected function isUnique(): bool {
        $values = [];
        foreach ($this->getUniqueColumns() as $column) {
            $values[$column] = $this->$column;
        }

        if (empty($values)) {
            return TRUE;
        }

        try {
            $duplicates = $this->db->select()
                ->from($this->getDbFileName())
                ->where($values)
                ->get();
        }
        catch (Exception) {}

        if (!empty($duplicates)) {
            throw new Exception('This entity already exist in database');
        }

        return TRUE;
    }

    abstract public function getId(): ?int;

}
