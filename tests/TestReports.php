<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ReportsController;
use App\Repository\ReportsRepository;
use App\Service\ReportsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestReports extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(ReportsRepository::class);
        $this->service = $this->createMock(ReportsService::class);
        $this->controller = new ReportsController($this->repository, $this->service);
    }

    public function testGetReports()
    {
        $this->repository->expects($this->once())
            ->method('getAllReports')
            ->willReturn([
                ['id' => 1, 'title' => 'Report 1'],
                ['id' => 2, 'title' => 'Report 2'],
            ]);

        $response = $this->controller->getReports();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetReportById()
    {
        $reportId = 1;
        $this->repository->expects($this->once())
            ->method('getReportById')
            ->with($reportId)
            ->willReturn(['id' => $reportId, 'title' => 'Report 1']);

        $response = $this->controller->getReport($reportId);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetReportByIdNotFound()
    {
        $reportId = 1;
        $this->repository->expects($this->once())
            ->method('getReportById')
            ->with($reportId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getReport($reportId);
    }

    public function testCreateReport()
    {
        $reportData = ['title' => 'New Report'];
        $this->service->expects($this->once())
            ->method('createReport')
            ->with($reportData)
            ->willReturn(['id' => 1, 'title' => 'New Report']);

        $request = new Request([], [], [], [], [], json_encode($reportData));
        $response = $this->controller->createReport($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateReport()
    {
        $reportId = 1;
        $reportData = ['title' => 'Updated Report'];
        $this->repository->expects($this->once())
            ->method('getReportById')
            ->with($reportId)
            ->willReturn(['id' => $reportId, 'title' => 'Report 1']);
        $this->service->expects($this->once())
            ->method('updateReport')
            ->with($reportId, $reportData)
            ->willReturn(['id' => $reportId, 'title' => 'Updated Report']);

        $request = new Request([], [], [], [], [], json_encode($reportData));
        $response = $this->controller->updateReport($reportId, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateReportNotFound()
    {
        $reportId = 1;
        $reportData = ['title' => 'Updated Report'];
        $this->repository->expects($this->once())
            ->method('getReportById')
            ->with($reportId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->updateReport($reportId, new Request([], [], [], [], [], json_encode($reportData)));
    }

    public function testDeleteReport()
    {
        $reportId = 1;
        $this->repository->expects($this->once())
            ->method('getReportById')
            ->with($reportId)
            ->willReturn(['id' => $reportId, 'title' => 'Report 1']);
        $this->service->expects($this->once())
            ->method('deleteReport')
            ->with($reportId);

        $response = $this->controller->deleteReport($reportId);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteReportNotFound()
    {
        $reportId = 1;
        $this->repository->expects($this->once())
            ->method('getReportById')
            ->with($reportId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->deleteReport($reportId);
    }
}