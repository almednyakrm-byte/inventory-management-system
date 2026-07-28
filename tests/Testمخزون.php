<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمخزون extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetمخزون()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مخزون')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'مخزون 1']]);

        $result = $this->getمخزون();
        $this->assertEquals([['id' => 1, 'name' => 'مخزون 1']], $result);
    }

    public function testPostمخزون()
    {
        $data = ['name' => 'مخزون 2'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مخزون (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(2);

        $result = $this->postمخزون($data);
        $this->assertEquals(2, $result);
    }

    public function testPutمخزون()
    {
        $id = 1;
        $data = ['name' => 'مخزون 1 updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مخزون SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putمخزون($id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteمخزون()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مخزون WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteمخزون($id);
        $this->assertTrue($result);
    }

    private function getمخزون()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM مخزون');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postمخزون($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO مخزون (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    private function putمخزون($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE مخزون SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteمخزون($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM مخزون WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}