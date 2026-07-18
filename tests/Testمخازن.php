<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Mkhazen;
use App\Repositories\MkhazenRepository;
use Mockery;
use Mockery\MockInterface;
use PDO;

class TestMkhazen extends TestCase
{
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(MkhazenRepository::class);
    }

    public function testGetAll()
    {
        $pdo = Mockery::mock(PDO::class);
        $pdo->shouldReceive('query')->andReturnUsing(function ($sql) {
            return Mockery::mock(\PDOStatement::class);
        });
        $this->repository->shouldReceive('getAll')->andReturn([
            ['id' => 1, 'name' => 'مخزن 1'],
            ['id' => 2, 'name' => 'مخزن 2'],
        ]);
        $response = $this->repository->getAll($pdo);
        $this->assertEquals(2, count($response));
    }

    public function testGetById()
    {
        $pdo = Mockery::mock(PDO::class);
        $pdo->shouldReceive('query')->andReturnUsing(function ($sql) {
            return Mockery::mock(\PDOStatement::class);
        });
        $this->repository->shouldReceive('getById')->with(1)->andReturn(['id' => 1, 'name' => 'مخزن 1']);
        $response = $this->repository->getById($pdo, 1);
        $this->assertEquals(['id' => 1, 'name' => 'مخزن 1'], $response);
    }

    public function testCreate()
    {
        $pdo = Mockery::mock(PDO::class);
        $pdo->shouldReceive('prepare')->andReturnUsing(function ($sql) {
            return Mockery::mock(\PDOStatement::class);
        });
        $this->repository->shouldReceive('create')->with(['name' => 'مخزن 1'])->andReturn(1);
        $response = $this->repository->create($pdo, ['name' => 'مخزن 1']);
        $this->assertEquals(1, $response);
    }

    public function testUpdate()
    {
        $pdo = Mockery::mock(PDO::class);
        $pdo->shouldReceive('prepare')->andReturnUsing(function ($sql) {
            return Mockery::mock(\PDOStatement::class);
        });
        $this->repository->shouldReceive('update')->with(1, ['name' => 'مخزن 1'])->andReturn(true);
        $response = $this->repository->update($pdo, 1, ['name' => 'مخزن 1']);
        $this->assertTrue($response);
    }

    public function testDelete()
    {
        $pdo = Mockery::mock(PDO::class);
        $pdo->shouldReceive('prepare')->andReturnUsing(function ($sql) {
            return Mockery::mock(\PDOStatement::class);
        });
        $this->repository->shouldReceive('delete')->with(1)->andReturn(true);
        $response = $this->repository->delete($pdo, 1);
        $this->assertTrue($response);
    }
}



// MkhazenRepository.php
namespace App\Repositories;

use App\Models\Mkhazen;
use PDO;

class MkhazenRepository
{
    public function getAll(PDO $pdo)
    {
        $stmt = $pdo->query('SELECT * FROM مخازن');
        return $stmt->fetchAll();
    }

    public function getById(PDO $pdo, int $id)
    {
        $stmt = $pdo->query('SELECT * FROM مخازن WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->fetch();
    }

    public function create(PDO $pdo, array $data)
    {
        $stmt = $pdo->prepare('INSERT INTO مخازن (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function update(PDO $pdo, int $id, array $data)
    {
        $stmt = $pdo->prepare('UPDATE مخازن SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM مخازن WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}