<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testالأصناف extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetالأصناف()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الأصناف')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Test الأصناف'],
            ]);

        $أصنافController = new الأصنافController($this->pdo);
        $result = $أصنافController->getالأصناف($request, $response);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testPostالأصناف()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New الأصناف']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO الأصناف (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'New الأصناف');

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $أصنافController = new الأصنافController($this->pdo);
        $result = $أصنافController->postالأصناف($request, $response);

        $this->assertTrue($result);
    }

    public function testPutالأصناف()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['id' => 1, 'name' => 'Updated الأصناف']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE الأصناف SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Updated الأصناف');

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $أصنافController = new الأصنافController($this->pdo);
        $result = $أصنافController->putالأصناف($request, $response);

        $this->assertTrue($result);
    }

    public function testDeleteالأصناف()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM الأصناف WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $أصنافController = new الأصنافController($this->pdo);
        $result = $أصنافController->deleteالأصناف($request, $response);

        $this->assertTrue($result);
    }
}