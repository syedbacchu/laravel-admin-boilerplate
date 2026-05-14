<?php

namespace App\Providers;

use App\Http\Services\Audit\AuditRepository;
use App\Http\Services\Audit\AuditRepositoryInterface;
use App\Http\Services\Audit\AuditService;
use App\Http\Services\Audit\AuditServiceInterface;
use App\Http\Services\CustomField\CustomFieldRepository;
use App\Http\Services\CustomField\CustomFieldRepositoryInterface;
use App\Http\Services\CustomField\CustomFieldService;
use App\Http\Services\CustomField\CustomFieldServiceInterface;
use App\Http\Services\Role\RoleRepository;
use App\Http\Services\Role\RoleRepositoryInterface;
use App\Http\Services\Role\RoleService;
use App\Http\Services\Role\RoleServiceInterface;
use App\Http\Services\Slider\SliderRepository;
use App\Http\Services\Slider\SliderRepositoryInterface;
use App\Http\Services\Slider\SliderService;
use App\Http\Services\Slider\SliderServiceInterface;
use App\Http\Services\PostCategory\PostCategoryRepository;
use App\Http\Services\PostCategory\PostCategoryRepositoryInterface;
use App\Http\Services\PostCategory\PostCategoryService;
use App\Http\Services\PostCategory\PostCategoryServiceInterface;
use App\Http\Services\Tag\TagRepository;
use App\Http\Services\Tag\TagRepositoryInterface;
use App\Http\Services\Tag\TagService;
use App\Http\Services\Tag\TagServiceInterface;
use App\Http\Services\Post\PostRepository;
use App\Http\Services\Post\PostRepositoryInterface;
use App\Http\Services\Post\PostService;
use App\Http\Services\Post\PostServiceInterface;
use App\Http\Services\PostComment\PostCommentRepository;
use App\Http\Services\PostComment\PostCommentRepositoryInterface;
use App\Http\Services\PostComment\PostCommentService;
use App\Http\Services\PostComment\PostCommentServiceInterface;
use App\Http\Services\FaqCategory\FaqCategoryRepository;
use App\Http\Services\FaqCategory\FaqCategoryRepositoryInterface;
use App\Http\Services\FaqCategory\FaqCategoryService;
use App\Http\Services\FaqCategory\FaqCategoryServiceInterface;
use App\Http\Services\Faq\FaqRepository;
use App\Http\Services\Faq\FaqRepositoryInterface;
use App\Http\Services\Faq\FaqService;
use App\Http\Services\Faq\FaqServiceInterface;
use App\Http\Services\ServiceCategory\ServiceCategoryRepository;
use App\Http\Services\ServiceCategory\ServiceCategoryRepositoryInterface;
use App\Http\Services\ServiceCategory\ServiceCategoryService;
use App\Http\Services\ServiceCategory\ServiceCategoryServiceInterface;
use App\Http\Services\Service\ServiceRepository;
use App\Http\Services\Service\ServiceRepositoryInterface;
use App\Http\Services\Service\ServiceService;
use App\Http\Services\Service\ServiceServiceInterface;
use App\Http\Services\FeatureCategory\FeatureCategoryRepository;
use App\Http\Services\FeatureCategory\FeatureCategoryRepositoryInterface;
use App\Http\Services\FeatureCategory\FeatureCategoryService;
use App\Http\Services\FeatureCategory\FeatureCategoryServiceInterface;
use App\Http\Services\Feature\FeatureRepository;
use App\Http\Services\Feature\FeatureRepositoryInterface;
use App\Http\Services\Feature\FeatureService;
use App\Http\Services\Feature\FeatureServiceInterface;
use App\Http\Services\ProjectCategory\ProjectCategoryRepository;
use App\Http\Services\ProjectCategory\ProjectCategoryRepositoryInterface;
use App\Http\Services\ProjectCategory\ProjectCategoryService;
use App\Http\Services\ProjectCategory\ProjectCategoryServiceInterface;
use App\Http\Services\Project\ProjectRepository;
use App\Http\Services\Project\ProjectRepositoryInterface;
use App\Http\Services\Project\ProjectService;
use App\Http\Services\Project\ProjectServiceInterface;
use App\Http\Services\User\UserRepository;
use App\Http\Services\User\UserRepositoryInterface;
use App\Http\Services\User\UserService;
use App\Http\Services\User\UserServiceInterface;

use App\Http\Services\Testimonial\TestimonialRepository;
use App\Http\Services\Testimonial\TestimonialRepositoryInterface;
use App\Http\Services\Testimonial\TestimonialService;
use App\Http\Services\Testimonial\TestimonialServiceInterface;

use App\Http\Services\Stat\StatRepository;
use App\Http\Services\Stat\StatRepositoryInterface;
use App\Http\Services\Stat\StatService;
use App\Http\Services\Stat\StatServiceInterface;

use App\Http\Services\Team\TeamRepository;
use App\Http\Services\Team\TeamRepositoryInterface;
use App\Http\Services\Team\TeamService;
use App\Http\Services\Team\TeamServiceInterface;

use App\Http\Services\Attribute\AttributeRepository;
use App\Http\Services\Attribute\AttributeRepositoryInterface;
use App\Http\Services\Attribute\AttributeService;
use App\Http\Services\Attribute\AttributeServiceInterface;

use App\Http\Services\AttributeValue\AttributeValueRepository;
use App\Http\Services\AttributeValue\AttributeValueRepositoryInterface;
use App\Http\Services\AttributeValue\AttributeValueService;
use App\Http\Services\AttributeValue\AttributeValueServiceInterface;

use App\Http\Services\ProductsCategory\ProductsCategoryRepository;
use App\Http\Services\ProductsCategory\ProductsCategoryRepositoryInterface;
use App\Http\Services\ProductsCategory\ProductsCategoryService;
use App\Http\Services\ProductsCategory\ProductsCategoryServiceInterface;

use App\Http\Services\Products\ProductsRepository;
use App\Http\Services\Products\ProductsRepositoryInterface;
use App\Http\Services\Products\ProductsService;
use App\Http\Services\Products\ProductsServiceInterface;
use App\Http\Services\ProductFeature\ProductFeatureRepository;
use App\Http\Services\ProductFeature\ProductFeatureRepositoryInterface;
use App\Http\Services\ProductFeature\ProductFeatureService;
use App\Http\Services\ProductFeature\ProductFeatureServiceInterface;

use App\Http\Services\AboutCompany\AboutCompanyService;
use App\Http\Services\AboutCompany\AboutCompanyServiceInterface;
use App\Http\Services\AboutCompany\AboutCompanyRepository;
use App\Http\Services\AboutCompany\AboutCompanyRepositoryInterface;

use App\Http\Services\CollectLead\CollectLeadRepository;
use App\Http\Services\CollectLead\CollectLeadRepositoryInterface;
use App\Http\Services\CollectLead\CollectLeadService;
use App\Http\Services\CollectLead\CollectLeadServiceInterface;

use App\Http\Services\Contact\ContactRepository;
use App\Http\Services\Contact\ContactRepositoryInterface;
use App\Http\Services\Contact\ContactService;
use App\Http\Services\Contact\ContactServiceInterface;

use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(SliderServiceInterface::class, SliderService::class);

        $this->app->bind(AuditRepositoryInterface::class, AuditRepository::class);
        $this->app->bind(AuditServiceInterface::class, AuditService::class);

        $this->app->bind(CustomFieldRepositoryInterface::class, CustomFieldRepository::class);
        $this->app->bind(CustomFieldServiceInterface::class, CustomFieldService::class);

        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        $this->app->bind(FaqCategoryRepositoryInterface::class, FaqCategoryRepository::class);
        $this->app->bind(FaqCategoryServiceInterface::class, FaqCategoryService::class);

        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(FaqServiceInterface::class, FaqService::class);

        $this->app->bind(PostCategoryRepositoryInterface::class, PostCategoryRepository::class);
        $this->app->bind(PostCategoryServiceInterface::class, PostCategoryService::class);

        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(TagServiceInterface::class, TagService::class);

        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);

        $this->app->bind(PostCommentRepositoryInterface::class, PostCommentRepository::class);
        $this->app->bind(PostCommentServiceInterface::class, PostCommentService::class);

        $this->app->bind(ServiceCategoryRepositoryInterface::class, ServiceCategoryRepository::class);
        $this->app->bind(ServiceCategoryServiceInterface::class, ServiceCategoryService::class);

        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ServiceServiceInterface::class, ServiceService::class);

        $this->app->bind(FeatureCategoryRepositoryInterface::class, FeatureCategoryRepository::class);
        $this->app->bind(FeatureCategoryServiceInterface::class, FeatureCategoryService::class);

        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(FeatureServiceInterface::class, FeatureService::class);

        $this->app->bind(ProjectCategoryRepositoryInterface::class, ProjectCategoryRepository::class);
        $this->app->bind(ProjectCategoryServiceInterface::class, ProjectCategoryService::class);

        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);

        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);
        $this->app->bind(TestimonialServiceInterface::class, TestimonialService::class);

        $this->app->bind(StatRepositoryInterface::class, StatRepository::class);
        $this->app->bind(StatServiceInterface::class, StatService::class);

        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(TeamServiceInterface::class, TeamService::class);

        $this->app->bind(AttributeRepositoryInterface::class, AttributeRepository::class);
        $this->app->bind(AttributeServiceInterface::class, AttributeService::class);

        $this->app->bind(AttributeValueRepositoryInterface::class, AttributeValueRepository::class);
        $this->app->bind(AttributeValueServiceInterface::class, AttributeValueService::class);

        $this->app->bind(ProductsCategoryRepositoryInterface::class, ProductsCategoryRepository::class);
        $this->app->bind(ProductsCategoryServiceInterface::class, ProductsCategoryService::class);

        $this->app->bind(AboutCompanyRepositoryInterface::class, AboutCompanyRepository::class);
        $this->app->bind(AboutCompanyServiceInterface::class, AboutCompanyService::class);

        $this->app->bind(ProductsRepositoryInterface::class, ProductsRepository::class);
        $this->app->bind(ProductsServiceInterface::class, ProductsService::class);

        $this->app->bind(ProductFeatureRepositoryInterface::class, ProductFeatureRepository::class);
        $this->app->bind(ProductFeatureServiceInterface::class, ProductFeatureService::class);

        $this->app->bind(CollectLeadRepositoryInterface::class, CollectLeadRepository::class);
        $this->app->bind(CollectLeadServiceInterface::class, CollectLeadService::class);

        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
