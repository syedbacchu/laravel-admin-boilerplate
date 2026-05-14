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
            return $this->sendResponse(true, 'Contact submitted successfully', $contact, 201);
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

            return $this->sendResponse(true, 'Contact detail retrieved successfully', $contact);
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

            // Send email
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

            if (!$emailSent) {
                return $this->sendResponse(false, 'Failed to send email', [], 500);
            }

            // Update contact record
            $updateData = [
                'status' => 'replied',
                'reply_message' => $data['reply_message'],
                'replied_at' => now(),
                'replied_by' => auth()->id() ?? null,
            ];

            $this->contactRepository->update($id, $updateData);

            return $this->sendResponse(true, 'Reply sent successfully', $contact->fresh());
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Failed to send reply', [], 500, $e->getMessage());
        }
    }
}
