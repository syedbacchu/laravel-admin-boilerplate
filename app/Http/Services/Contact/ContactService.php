<?php

namespace App\Http\Services\Contact;

use App\Http\Services\BaseService;
use App\Http\Services\Mail\MailManager;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;

class ContactService extends BaseService implements ContactServiceInterface
{
    protected ContactRepositoryInterface $contactRepository;
    protected MailManager $mailManager;

    public function __construct(ContactRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->contactRepository = $repository;
        $this->mailManager = new MailManager();
    }

    public function getDataTableData($request): array
    {
        $data = $this->contactRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function submitContact(array $data): array
    {
        try {
            $contact = $this->contactRepository->create($data);
            // Prevent recursion by hiding relationships and converting to array
            $contactData = $contact->makeHidden(['repliedBy'])->toArray();
            return $this->sendResponse(true, 'Contact submitted successfully', $contactData, 201);
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Failed to submit contact', [], 500, $e->getMessage());
        }
    }

    public function getContactList(array $filters = []): array
    {
        try {
            $query = Contact::query()->latest();

            if (isset($filters['status']) && in_array($filters['status'], ['pending', 'replied'])) {
                $query->where('status', $filters['status']);
            }

            $perPage = $filters['per_page'] ?? 15;
            $contacts = $query->paginate($perPage);

            return $this->sendResponse(true, 'Contact list retrieved successfully', $contacts);
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Failed to retrieve contacts', [], 500, $e->getMessage());
        }
    }

    public function getContactDetail(int $id): array
    {
        try {
            $contact = $this->contactRepository->find($id);
            if (!$contact) {
                return $this->sendResponse(false, 'Contact not found', [], 404);
            }

            // Prevent recursion by hiding relationships and converting to array
            $contactData = $contact->makeHidden(['repliedBy'])->toArray();
            return $this->sendResponse(true, 'Contact detail retrieved successfully', $contactData);
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Failed to retrieve contact detail', [], 500, $e->getMessage());
        }
    }

    public function replyToContact(int $id, array $data): array
    {
        try {
            $contact = $this->contactRepository->find($id);
            if (!$contact) {
                return $this->sendResponse(false, 'Contact not found', [], 404);
            }

            // Try to send email if mail is configured
            $emailSent = false;
            $mailConfigured = $this->mailManager->isConfigured();

            if ($mailConfigured) {
                try {
                    $mailService = $this->mailManager->make();
                    $emailSent = $mailService->send(
                        'emails.contact-reply',
                        [
                            'contact' => $contact,
                            'reply_message' => $data['reply_message'],
                        ],
                        $contact->email,
                        $contact->name,
                        'Re: ' . $contact->subject
                    );
                } catch (\Exception $e) {
                    // Log email error but don't fail the reply
                    \Log::warning('Failed to send contact reply email: ' . $e->getMessage());
                }
            } else {
                \Log::info('Mail not configured - skipping email send for contact reply');
            }

            // Update contact record regardless of email status
            $updateData = [
                'status' => 'replied',
                'reply_message' => $data['reply_message'],
                'replied_at' => now(),
                'replied_by' => auth()->id() ?? null,
            ];

            $this->contactRepository->update($id, $updateData);

            // Prevent recursion by hiding relationships and converting to array
            $updatedContact = $contact->fresh()->makeHidden(['repliedBy'])->toArray();

            // Provide appropriate message based on email status
            if (!$mailConfigured) {
                $message = 'Reply saved (email not configured - please configure email settings)';
            } elseif ($emailSent) {
                $message = 'Reply sent successfully';
            } else {
                $message = 'Reply saved (email could not be sent - check mail settings)';
            }

            return $this->sendResponse(true, $message, $updatedContact);
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Failed to send reply', [], 500, $e->getMessage());
        }
    }
}
