<?php

namespace App\Http\Services\AboutCompany;

use App\Http\Requests\AboutCompany\AboutCompanyUpdateRequest;
use App\Http\Services\BaseService;
use App\Models\AboutCompany;

class AboutCompanyService extends BaseService implements AboutCompanyServiceInterface
{
    protected AboutCompanyRepositoryInterface $aboutCompanyRepository;

    public function __construct(AboutCompanyRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->aboutCompanyRepository = $repository;
    }

    public function getAboutCompanyData(): array
    {
        $aboutCompany = $this->aboutCompanyRepository->getFirst();

        if (!$aboutCompany) {
            return $this->sendResponse(true, 'About Company data not found', (object) []);
        }

        return $this->sendResponse(true, 'About Company data retrieved successfully', $aboutCompany);
    }

    public function updateAboutCompany(AboutCompanyUpdateRequest $request): array
    {
        $aboutCompany = $this->aboutCompanyRepository->getFirst();

        $data = [
            'banner_image' => $request->banner_image,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'our_story' => $request->our_story,
            'story_image' => $request->story_image,
            'mission' => $request->mission,
            'vision' => $request->vision,
            'core_values' => $request->core_values ? json_encode($request->core_values) : null,
            'company_stats' => $request->company_stats ? json_encode($request->company_stats) : null,
            'why_choose' => $request->why_choose ? json_encode($request->why_choose) : null,
            'updated_by' => auth()->id() ?? null,
        ];

        if ($aboutCompany) {
            $this->aboutCompanyRepository->update($aboutCompany->id, $data);
            $message = 'About Company updated successfully';
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $aboutCompany = $this->aboutCompanyRepository->create($data);
            $message = 'About Company created successfully';
        }

        return $this->sendResponse(true, $message, $aboutCompany->fresh());
    }
}
