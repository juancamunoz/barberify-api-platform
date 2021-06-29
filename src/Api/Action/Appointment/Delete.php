<?php


namespace App\Api\Action\Appointment;


use App\Service\Appointment\DeleteAppointmentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Delete
{
    private DeleteAppointmentService $deleteAppointmentService;

    public function __construct(DeleteAppointmentService $deleteAppointmentService)
    {
        $this->deleteAppointmentService = $deleteAppointmentService;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->deleteAppointmentService->delete($id);

        return new JsonResponse('Appointment deleted successfully', JsonResponse::HTTP_NO_CONTENT);
    }
}