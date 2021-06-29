<?php


namespace App\Api\Action\Appointment;


use App\Entity\User;
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

    public function __invoke(Request $request, string $id, User $user): JsonResponse
    {
        $this->deleteAppointmentService->delete($id, $user->getId());

        return new JsonResponse('Appointment deleted successfully', JsonResponse::HTTP_NO_CONTENT);
    }
}