<?php
// app/Services/AttendanceSyncService.php

namespace App\Services;

use App\Contracts\AttendableInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AttendanceSyncService
{
    protected $client;
    protected $baseUrl;
    protected $timeout;

    public function __construct()
    {
        file_put_contents(
            storage_path('logs/php-env.log'),
            PHP_BINARY . PHP_EOL . phpversion() . PHP_EOL,
            FILE_APPEND
        );
        $this->baseUrl = Config::get('services.attendance.base_url', 'attendance2.localhost.com');
        $this->timeout = Config::get('services.attendance.timeout', 30);

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'verify' => Config::get('services.attendance.verify_ssl', false),
        ]);
    }

    /**
     * Sync any attendable entity to attendance service
     * @throws \Exception
     */

    /**
     * Sync any attendable entity to attendance service
     * Returns the decoded response data on success.
     * @throws \Exception
     * @return array
     */
    public function sync(AttendableInterface $entity, string $action = 'create')
    {
        Log::info('8888', [
            'entity_type' => $entity->getAttendanceEntityType(),
            'entity_id' => $entity->uid ?? 'unknown',
            'action' => $action
        ]);

        try {
            $payload = $entity->getAttendancePayload();
            $payload['action'] = $action;

            // Add emp_id based on entity type
            $entityType = $payload['entity_type'] ?? null;

            if ($entityType === 'student') {
                // For students, use student_unique_id as emp_id
                $payload['emp_id'] = $entity->student_unique_id ?? null;
            } elseif (in_array($entityType, ['teacher', 'staff'])) {
                // For teachers and staff, use emp_id
                $payload['emp_id'] = $entity->emp_id ?? null;
            }

            // Remove null/empty values
            $payload = array_filter($payload, function ($value) {
                return $value !== null && $value !== '';
            });

            // Build target URI. Some attendance endpoints expect identifier in the path
            $action = $payload['action'] ?? 'create';
            $uri = 'api/employee_manage/' . $action;

            // For update/delete the remote controller often expects an identifier in the route.
            // Append emp_id (or student_unique_id) when available.
            // if (in_array($action, ['update', 'delete'])) {
            //     if (!empty($payload['emp_id'])) {
            //         $uri .= '/' . $payload['emp_id'];
            //     } elseif (!empty($payload['student_unique_id'])) {
            //         $uri .= '/' . $payload['student_unique_id'];
            //     }
            // }

            Log::info('3333', [
                'uri' => $uri,
                'payload' => $payload
            ]);

            $response = $this->client->post($uri, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            ]);

            $statusCode = $response->getStatusCode();

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);

            // Log the raw response for debugging 👈
            // Log::info('Attendance service response', [
            //     'status_code' => $statusCode,
            //     'body' => $responseData ?? $responseBody,
            //     'entity' => $entity->uid ?? null,
            //     'action' => $action,
            // ]);

            // Accept any 2xx status as success (200, 201, etc.)
            if ($statusCode < 200 || $statusCode >= 300) {
                throw new \Exception("Attendance service returned status: {$statusCode}. Response: " . (is_string($responseBody) ? $responseBody : json_encode($responseBody)));
            }

            // Check if the response explicitly indicates failure
            if (isset($responseData['status']) && $responseData['status'] === false) {
                throw new \Exception('Attendance service returned error: ' . ($responseData['message'] ?? 'Unknown error'));
            }

            // Log success and return parsed response data
            $this->logSuccess($entity, $action, $statusCode, is_array($responseData) ? $responseData : []);
            return is_array($responseData) ? $responseData : ['raw' => $responseBody];
        } catch (RequestException $e) {
            $errorMessage = $this->formatRequestException($e);
            $this->logError($entity, $action, $errorMessage);
            throw new \Exception("Attendance service request failed: {$errorMessage}");
        } catch (\Exception $e) {
            $this->logError($entity, $action, $e->getMessage());
            throw new \Exception('Attendance sync failed: ' . $e->getMessage());
        }
    }

    public function syncMultiple(array $entities, string $action = 'create'): array
    {
        $results = [];
        $errors = [];

        foreach ($entities as $index => $entity) {
            try {
                if ($entity instanceof AttendableInterface) {
                    $success = $this->sync($entity, $action);
                    $results[] = [
                        'entity' => $entity,
                        'success' => $success,
                        'entity_type' => $entity->getAttendanceEntityType()
                    ];
                } else {
                    $error = "Entity at index {$index} does not implement AttendableInterface";
                    $errors[] = $error;
                    Log::warning($error);
                }
            } catch (\Exception $e) {
                $error = "Failed to sync entity at index {$index}: " . $e->getMessage();
                $errors[] = $error;
                $results[] = [
                    'entity' => $entity,
                    'success' => false,
                    'error' => $e->getMessage(),
                    'entity_type' => $entity instanceof AttendableInterface ? $entity->getAttendanceEntityType() : 'unknown'
                ];
            }
        }

        // If there are any errors, throw an exception with all error messages
        if (!empty($errors)) {
            throw new \Exception('Batch sync completed with errors: ' . implode('; ', $errors));
        }

        return $results;
    }

    /**
     * Delete teacher from attendance service using emp_id
     */
    public function deleteTeacher(string $empId): bool
    {
        return $this->deleteEmployee($empId, 'teacher');
    }

    /**
     * Delete staff from attendance service using emp_id
     */
    public function deleteStaff(string $empId): bool
    {
        return $this->deleteEmployee($empId, 'staff');
    }

    /**
     * Delete student from attendance service using student_unique_id
     */
    public function deleteStudent(string $studentUniqueId): bool
    {
        try {
            $payload = [
                'emp_id' => $studentUniqueId,  // Using student_unique_id as emp_id in attendance service
                'entity_type' => 'student',
                'action' => 'delete'
            ];

            Log::info('Deleting student from attendance service', [
                'student_unique_id' => $studentUniqueId,
                'payload' => $payload
            ]);

            $uri = 'api/employee_manage/delete/' . $studentUniqueId;
            $response = $this->client->post($uri, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            ]);

            return $this->handleDeleteResponse($response, 'student', $studentUniqueId);
        } catch (RequestException $e) {
            $errorMessage = $this->formatRequestException($e);
            Log::error('Failed to delete student from attendance service', [
                'student_unique_id' => $studentUniqueId,
                'error' => $errorMessage
            ]);
            throw new \Exception("Attendance service delete request failed: {$errorMessage}");
        } catch (\Exception $e) {
            Log::error('Failed to delete student from attendance service', [
                'student_unique_id' => $studentUniqueId,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Student delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Generic method to delete employee (teacher/staff) from attendance service
     */
    protected function deleteEmployee(string $empId, string $entityType): bool
    {
        try {
            $payload = [
                'emp_id' => $empId,
                'entity_type' => $entityType,
                'action' => 'delete'
            ];

            Log::info('Deleting employee from attendance service', [
                'emp_id' => $empId,
                'entity_type' => $entityType,
                'payload' => $payload
            ]);

            $uri = 'api/employee_manage/delete/' . $empId;
            $response = $this->client->post($uri, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            ]);

            return $this->handleDeleteResponse($response, $entityType, $empId);
        } catch (RequestException $e) {
            $errorMessage = $this->formatRequestException($e);
            Log::error('Failed to delete employee from attendance service', [
                'emp_id' => $empId,
                'entity_type' => $entityType,
                'error' => $errorMessage
            ]);
            throw new \Exception("Attendance service delete request failed: {$errorMessage}");
        } catch (\Exception $e) {
            Log::error('Failed to delete employee from attendance service', [
                'emp_id' => $empId,
                'entity_type' => $entityType,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Employee delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle delete response from attendance service
     */
    protected function handleDeleteResponse($response, string $entityType, string $identifier): bool
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new \Exception("Attendance service returned status: {$statusCode}. Response: " . $response->getBody());
        }

        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        // Check if the response indicates success
        if (isset($responseData['status']) && $responseData['status'] === false) {
            throw new \Exception('Attendance service returned error: ' . ($responseData['message'] ?? 'Unknown error'));
        }

        Log::info('Successfully deleted from attendance service', [
            'entity_type' => $entityType,
            'identifier' => $identifier,
            'status_code' => $statusCode,
            'response' => $responseData
        ]);

        return true;
    }

    /**
     * Format Guzzle request exception for better error messages
     */
    protected function formatRequestException(RequestException $e): string
    {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $responseBody = $e->getResponse()->getBody()->getContents();

            try {
                $responseData = json_decode($responseBody, true);
                if (isset($responseData['message'])) {
                    return "Status: {$statusCode}, Message: " . $responseData['message'];
                }
            } catch (\Exception $jsonException) {
                // If JSON parsing fails, return raw response
            }

            return "Status: {$statusCode}, Response: " . substr($responseBody, 0, 200);
        }

        return $e->getMessage();
    }

    /**
     * Check if attendance service is available
     */
    public function isAvailable(): bool
    {
        try {
            $response = $this->client->get('/health', ['timeout' => 5]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::warning('Attendance service health check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if attendance service is enabled in config
     */
    public function isEnabled(): bool
    {
        return Config::get('services.attendance.enabled', false);
    }

    protected function logSuccess(AttendableInterface $entity, string $action, int $statusCode, array $responseData = []): void
    {
        Log::info('Entity successfully synced to attendance service', [
            'entity_type' => $entity->getAttendanceEntityType(),
            'entity_id' => $entity->uid ?? 'unknown',
            'action' => $action,
            'status_code' => $statusCode,
            'response' => $responseData,
            'payload' => $entity->getAttendancePayload()
        ]);
    }

    protected function logError(AttendableInterface $entity, string $action, string $errorMessage): void
    {
        Log::error('Failed to sync entity to attendance service', [
            'entity_type' => $entity->getAttendanceEntityType(),
            'entity_id' => $entity->uid ?? 'unknown',
            'action' => $action,
            'error' => $errorMessage,
            'payload' => $entity->getAttendancePayload()
        ]);
    }
}
