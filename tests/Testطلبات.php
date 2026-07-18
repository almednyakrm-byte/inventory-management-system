<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testطلبات extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetطلبات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM طلبات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'طلبة 1']]);

        $result = $this->getطلبات($this->pdo);
        $this->assertEquals([['id' => 1, 'name' => 'طلبة 1']], $result);
    }

    public function testPostطلبات()
    {
        $data = ['name' => 'طلبة 2'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO طلبات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->postطلبات($this->pdo, $data);
        $this->assertTrue($result);
    }

    public function testPutطلبات()
    {
        $id = 1;
        $data = ['name' => 'طلبة 1 updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE طلبات SET name = :name WHERE id = :id')
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

        $result = $this->putطلبات($this->pdo, $id, $data);
        $this->assertTrue($result);
    }

    public function testDeletطلبات()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM طلبات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deletطلبات($this->pdo, $id);
        $this->assertTrue($result);
    }

    private function getطلبات(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT * FROM طلبات');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postطلبات(PDO $pdo, array $data)
    {
        $stmt = $pdo->prepare('INSERT INTO طلبات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    private function putطلبات(PDO $pdo, int $id, array $data)
    {
        $stmt = $pdo->prepare('UPDATE طلبات SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deletطلبات(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM طلبات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}