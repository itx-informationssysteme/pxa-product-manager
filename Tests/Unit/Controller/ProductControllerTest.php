<?php

namespace Pixelant\PxaProductManager\Tests\Unit\Controller;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaProductManager\Controller\ProductController;
use Pixelant\PxaProductManager\Domain\Model\Attribute;
use Pixelant\PxaProductManager\Domain\Model\AttributeSet;
use Pixelant\PxaProductManager\Domain\Model\Category;
use Pixelant\PxaProductManager\Domain\Model\DTO\Demand;
use Pixelant\PxaProductManager\Domain\Model\OrderConfiguration;
use Pixelant\PxaProductManager\Domain\Model\OrderFormField;
use Pixelant\PxaProductManager\Domain\Model\Product;
use Pixelant\PxaProductManager\Domain\Repository\CategoryRepository;
use Pixelant\PxaProductManager\Domain\Repository\ProductRepository;
use Pixelant\PxaProductManager\Service\Link\LinkBuilderService;
use Pixelant\PxaProductManager\Validation\Validator\RequiredValidator;
use Pixelant\PxaProductManager\Validation\ValidatorResolver;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class ProductControllerTest
 *
 * @package Pixelant\PxaProductManager\Tests
 */
class ProductControllerTest extends UnitTestCase
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function setUp()
    {
        $this->categoryRepository = $this->prophesize(CategoryRepository::class);
        $this->productRepository = $this->prophesize(ProductRepository::class);
    }

    public function tearDown()
    {
        unset($this->categoryRepository, $this->productRepository);
    }

    /**
     * @test
     */
    public function listActionFindsSubCategoriesFindsDemandProductsBuildNavigationTree()
    {
        $demand = new Demand();
        $category = 1;
        $categoryObject = new Category();
        $categoryObject->_setProperty('uid', $category);
        $expectedCategories = new ObjectStorage();

        $settings = [
            'showCategoriesWithProducts' => true,
            'showNavigationListView' => true
        ];

        $_GET = [
            'tx_pxaproductmanager_pi1' => [
                LinkBuilderService::CATEGORY_ARGUMENT_START_WITH . '0' => $category
            ]
        ];

        $fixture = $this->getAccessibleMock(ProductController::class, [
                                                                        'createDemandFromSettings',
                                                                        'getNavigationTree',
                                                                        'determinateCategory',
                                                                        'getOrderingsForCategories'
                                                                    ]);

        $this->categoryRepository->findByParent($category, ['title' => QueryInterface::ORDER_DESCENDING])->willReturn($expectedCategories);

        $this->productRepository->findDemanded($demand)->willReturn($this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock());

        $fixture->_set('settings', $settings);
        $fixture->_set('categoryRepository', $this->categoryRepository->reveal());
        $fixture->_set('productRepository', $this->productRepository->reveal());
        $fixture->_set('view', $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock());

        $fixture->expects($this->once())->method('determinateCategory')->willReturn($categoryObject);
        $fixture->expects($this->once())->method('createDemandFromSettings')->willReturn($demand);
        $fixture->expects($this->once())->method('getNavigationTree')->willReturn([]);
        $fixture->expects($this->once())->method('getOrderingsForCategories')->willReturn(['title' => QueryInterface::ORDER_DESCENDING]);

        $this->categoryRepository->findByParent($category, ['title' => QueryInterface::ORDER_DESCENDING])->shouldBeCalled();

        $this->productRepository->findDemanded($demand)->shouldBeCalled();

        $fixture->listAction();
    }

    /**
     * @test
     */
    public function determinateCategoryWillCallErrorIfCouldNotDeterminate()
    {
        $mockedCategoryRepository = $this->createMock(CategoryRepository::class);
        $mockedController = $this->getAccessibleMock(ProductController::class, ['addFlashMessage']);

        $mockedController->_set('categoryRepository', $mockedCategoryRepository);

        $mockedController->expects($this->once())->method('addFlashMessage');

        $mockedController->_call('determinateCategory');
    }

    /**
     * @test
     */
    public function determinateCategoryFromParameterWillDeterminateObject()
    {
        $category = new Category();
        $category->_setProperty('uid', 1);

        $mockedCategoryRepository = $this->createPartialMock(CategoryRepository::class, ['findByUid']);
        $mockedController = $this->getAccessibleMock(ProductController::class, ['addFlashMessage']);

        $mockedController->_set('categoryRepository', $mockedCategoryRepository);

        $mockedCategoryRepository->expects($this->once())->method('findByUid')->with($category->getUid())->willReturn($category);

        $mockedController->expects($this->never())->method('addFlashMessage');

        $this->assertSame($category, $mockedController->_call('determinateCategory', 1));
    }

    /**
     * @test
     */
    public function determinateCategoryFromSettingsWillDeterminateObject()
    {
        $category = new Category();
        $category->_setProperty('uid', 1);

        $settings = [
            'category' => 1
        ];

        $mockedCategoryRepository = $this->createPartialMock(CategoryRepository::class, ['findByUid']);
        $mockedController = $this->getAccessibleMock(ProductController::class, ['addFlashMessage']);

        $mockedController->_set('categoryRepository', $mockedCategoryRepository);
        $mockedController->_set('settings', $settings);

        $mockedCategoryRepository->expects($this->once())->method('findByUid')->with($category->getUid())->willReturn($category);

        $mockedController->expects($this->never())->method('addFlashMessage');

        $this->assertSame($category, $mockedController->_call('determinateCategory'));
    }

    /**
     * @test
     */
    public function noSettingsReturnEmptyResulrForCategoriesOrdering()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);

        $this->assertEmpty($mockedController->_call('getOrderingsForCategories'));
    }

    /**
     * @test
     */
    public function categoriesOrderingReturnedFromSettings()
    {
        $settings = [
            'orderCategoriesBy' => 'title',
            'orderCategoriesDirection' => QueryInterface::ORDER_DESCENDING
        ];

        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);

        $mockedController->_set('settings', $settings);

        $this->assertEquals([
                                $settings['orderCategoriesBy'] => $settings['orderCategoriesDirection']
                            ], $mockedController->_call('getOrderingsForCategories'));
    }

    /**
     * @test
     */
    public function generateAttributesDiffDataForProductsSkipNotInCompareAttributes()
    {
        $attributesCountAll = 3;
        $attributeSet = new AttributeSet();
        for ($i = 0; $i < $attributesCountAll; $i++) {
            $attribute = new Attribute();
            $attribute->_setProperty('uid', $i + 1);
            $attributeSet->addAttribute($attribute);

            // Show in compare only last
            if (($i + 1) === $attributesCountAll) {
                $attribute->setShowInCompare(true);
            }
        }

        $mockedController = $this->getAccessibleMock(ProductController::class, ['getDiffValuesForProductsSingleAttribute']);

        $mockedController->expects($this->atLeastOnce())->method('getDiffValuesForProductsSingleAttribute');

        $this->assertCount(1, $mockedController->_call('generateAttributesDiffDataForProducts', [], $attributeSet));
    }

    /**
     * @test
     * @dataProvider generateDiffDataForSingleAttibuteWillCreateDiffArrayDataProvider
     */
    public function generateDiffDataForSingleAttibuteWillCreateDiffArray($attribute, $attributesProduct1, $attributesProduct2, $diffData)
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);

        $product1 = $this->createPartialMock(Product::class, ['getAttributes']);
        $product2 = $this->createPartialMock(Product::class, ['getAttributes']);

        $product1->expects($this->once())->method('getAttributes')->willReturn($attributesProduct1);

        $product2->expects($this->once())->method('getAttributes')->willReturn($attributesProduct2);

        $this->assertEquals($diffData, $mockedController->_call('getDiffValuesForProductsSingleAttribute', [
            $product1,
            $product2
        ], $attribute));
    }

    public function generateDiffDataForSingleAttibuteWillCreateDiffArrayDataProvider()
    {
        // Data for no difference
        $attributeCaseNoDiff = new Attribute();
        $attributeCaseNoDiff->_setProperty('uid', 2);
        $attributeCaseNoDiff->setName('attributeCaseNoDiff');

        $productAttributesCaseNoDiff1 = $this->getAttributesStorage('123', 2);
        $productAttributesCaseNoDiff2 = $this->getAttributesStorage('123', 2);

        // Data for with difference
        $attributeCaseWithDiff = new Attribute();
        $attributeCaseWithDiff->_setProperty('uid', 3);
        $attributeCaseWithDiff->setName('attributeCaseWithDiff');

        $productAttributesCaseWithDiff1 = $this->getAttributesStorage('123', 3);
        $productAttributesCaseWithDiff2 = $this->getAttributesStorage('321', 3);

        // Data with same options
        $attributeCaseNoDiffOptions = new Attribute();
        $attributeCaseNoDiffOptions->_setProperty('uid', 4);
        $attributeCaseNoDiffOptions->setName('attributeCaseNoDiffOptions');

        $productAttributesNoDiffOptions1 = $this->getAttributesStorage(['Option 1', 'Option 2'], 4, true);
        $productAttributesNoDiffOptions2 = $this->getAttributesStorage(['Option 1', 'Option 2'], 4, true);

        // Data with different options
        $attributeCaseDiffOptions = new Attribute();
        $attributeCaseDiffOptions->_setProperty('uid', 5);
        $attributeCaseDiffOptions->setName('attributeCaseDiffOptions');

        $productAttributesDiffOptions1 = $this->getAttributesStorage(['Option 1', 'Option 2'], 5, true);
        $productAttributesDiffOptions2 = $this->getAttributesStorage(['Option diff', 'Option diff'], 5, true);

        $productAttributesCaseNoDiff1Array = $productAttributesCaseNoDiff1->toArray();
        $productAttributesCaseNoDiff2Array = $productAttributesCaseNoDiff2->toArray();

        $productAttributesCaseWithDiff1Array = $productAttributesCaseWithDiff1->toArray();
        $productAttributesCaseWithDiff2Array = $productAttributesCaseWithDiff2->toArray();

        $productAttributesNoDiffOptions1Array = $productAttributesNoDiffOptions1->toArray();
        $productAttributesNoDiffOptions2Array = $productAttributesNoDiffOptions2->toArray();

        $productAttributesDiffOptions1Array = $productAttributesDiffOptions1->toArray();
        $productAttributesDiffOptions2Array = $productAttributesDiffOptions2->toArray();

        return [
            'attributes_with_same_values_has_no_diff' => [
                $attributeCaseNoDiff,
                $productAttributesCaseNoDiff1,
                $productAttributesCaseNoDiff2,
                [
                    'label' => $attributeCaseNoDiff->getName(),
                    'attributesList' => [
                        end($productAttributesCaseNoDiff1Array),
                        end($productAttributesCaseNoDiff2Array)
                    ],
                    'isDifferent' => false
                ]
            ],
            'attributes_with_different_values_has_diff' => [
                $attributeCaseWithDiff,
                $productAttributesCaseWithDiff1,
                $productAttributesCaseWithDiff2,
                [
                    'label' => $attributeCaseWithDiff->getName(),
                    'attributesList' => [
                        end($productAttributesCaseWithDiff1Array),
                        end($productAttributesCaseWithDiff2Array)
                    ],
                    'isDifferent' => true
                ]
            ],
            'attributes_with_same_options_no_diff' => [
                $attributeCaseNoDiffOptions,
                $productAttributesNoDiffOptions1,
                $productAttributesNoDiffOptions2,
                [
                    'label' => $attributeCaseNoDiffOptions->getName(),
                    'attributesList' => [
                        end($productAttributesNoDiffOptions1Array),
                        end($productAttributesNoDiffOptions2Array)
                    ],
                    'isDifferent' => false
                ]
            ],
            'attributes_with_diff_options' => [
                $attributeCaseDiffOptions,
                $productAttributesDiffOptions1,
                $productAttributesDiffOptions2,
                [
                    'label' => $attributeCaseDiffOptions->getName(),
                    'attributesList' => [
                        end($productAttributesDiffOptions1Array),
                        end($productAttributesDiffOptions2Array)
                    ],
                    'isDifferent' => true
                ]
            ]
        ];
    }

    protected function getAttributesStorage($value, $amount, $isOption = false)
    {
        $objectStorage = new ObjectStorage();

        for ($i = 0; $i < $amount; $i++) {
            $attribute = new Attribute();
            $attribute->_setProperty('uid', $i + 1);
            // Set value only for last attribute
            $attribute->setValue(($amount === ($i + 1)) ? $value : '');
            if (true === $isOption) {
                $attribute->setType(Attribute::ATTRIBUTE_TYPE_DROPDOWN);
            }
            $objectStorage->attach($attribute);
        }

        return $objectStorage;
    }

    /**
     * @test
     */
    public function createDemandFromSettingsReturnDemand()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy'], [], '', false);
        $mockedSignalSlotDispatcher = $this->createMock(Dispatcher::class);
        $this->inject($mockedController, 'signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $this->assertInstanceOf(Demand::class, $mockedController->_call('createDemandFromSettings', []));
    }

    /**
     * @test
     */
    public function createDemandWithClassThatIsNotInstanceOfDemandThrowException()
    {
        $class = 'stdClass';
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy'], [], '', false);
        $mockedSignalSlotDispatcher = $this->createMock(Dispatcher::class);
        $this->inject($mockedController, 'signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $this->expectException(\UnexpectedValueException::class);
        $mockedController->_call('createDemandFromSettings', [], $class);
    }

    /**
     * @test
     */
    public function handleNoProductFoundErrorCallNotFoundActionIfEnable()
    {
        $settings = ['enableMessageInsteadOfPage404' => 1];

        $mockedController = $this->getAccessibleMock(ProductController::class, ['forward']);

        $mockedController->_set('settings', $settings);
        $mockedController->expects($this->once())->method('forward')->with('notFound');

        $mockedController->_call('handleNoProductFoundError');
    }

    /**
     * @test
     */
    public function handleNoProductFoundErrorCallPageNotFound()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);
        $tsfe = $this->createPartialMock(TypoScriptFrontendController::class, ['pageNotFoundAndExit']);
        $GLOBALS['TSFE'] = $tsfe;

        $GLOBALS['TSFE']->expects($this->once())->method('pageNotFoundAndExit');

        $mockedController->_call('handleNoProductFoundError');

        unset($GLOBALS['TSFE']);
    }

    /**
     * @test
     */
    public function validateOrderFieldsReturnFalseIfNotValid()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['translate', 'getValidatorResolver']);
        $mockedValidator = $this->createMock(RequiredValidator::class);
        $mockedValidatorResolver = $this->createPartialMock(ValidatorResolver::class, ['createValidator']);
        $mockedValidatorResolver->expects($this->atLeastOnce())->method('createValidator')->willReturn($mockedValidator);

        $mockedController->expects($this->once())->method('getValidatorResolver')->willReturn($mockedValidatorResolver);

        $orderConfiguration = new OrderConfiguration();

        $field = new OrderFormField();
        $field->_setProperty('uid', 1);
        $field->setValidationRules('required');

        $orderConfiguration->addFormField($field);

        $values = [
            1 => '  '
        ];

        $this->assertFalse($mockedController->_call('validateOrderFields', $orderConfiguration, $values));
    }

    /**
     * @test
     */
    public function validateOrderFieldsReturnTrueIfValid()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['translate', 'getValidatorResolver']);
        $mockedValidator = $this->createPartialMock(RequiredValidator::class, ['getErrorMessage']);

        $mockedValidatorResolver = $this->createPartialMock(ValidatorResolver::class, ['createValidator']);
        $mockedValidatorResolver->expects($this->atLeastOnce())->method('createValidator')->willReturn($mockedValidator);

        $mockedController->expects($this->once())->method('getValidatorResolver')->willReturn($mockedValidatorResolver);

        $orderConfiguration = new OrderConfiguration();

        $field = new OrderFormField();
        $field->_setProperty('uid', 1);
        $field->setValidationRules('required');

        $orderConfiguration->addFormField($field);

        $values = [
            1 => 'Value'
        ];

        $this->assertTrue($mockedController->_call('validateOrderFields', $orderConfiguration, $values));
    }

    /**
     * @test
     */
    public function loginRequiredAndNonLoggedInUserDoesNotAllowOrderForm()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['isUserLoggedIn'], [], '', false);
        $mockedController->expects($this->once())->method('isUserLoggedIn')->willReturn(false);

        $mockedController->_set('settings', ['orderFormRequireLogin' => 1]);

        $this->assertFalse($mockedController->_call('isOrderFormAllowed'));
    }

    /**
     * @test
     */
    public function loginRequiredAndLoggedInUserAllowOrderForm()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['isUserLoggedIn'], [], '', false);
        $mockedController->expects($this->once())->method('isUserLoggedIn')->willReturn(true);

        $mockedController->_set('settings', ['orderFormRequireLogin' => 1]);

        $this->assertTrue($mockedController->_call('isOrderFormAllowed'));
    }

    /**
     * @test
     */
    public function loginNotRequiredAllowOrderForm()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);

        $mockedController->_set('settings', ['orderFormRequireLogin' => 0]);

        $this->assertTrue($mockedController->_call('isOrderFormAllowed'));
    }

    /**
     * @test
     */
    public function getOrderFormFieldsForSerializationReturnArrayWithOrderFieldsData()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);

        $orderConfiguration = new OrderConfiguration();

        $formField = new OrderFormField();
        $formField->setName('test');
        $formField->setLabel('Label');
        $formField->setValue('value');

        $formFieldClone = clone $formField;
        $formFieldClone->setValue('value 2');
        $formFieldClone->setName('test22');

        $orderConfiguration->addFormField($formField);
        $orderConfiguration->addFormField($formFieldClone);

        $expect = [
            'test' => [
                'value' => $formField->getValueAsText(),
                'type' => $formField->getType(),
                'label' => $formField->getLabel()
            ],
            'test22' => [
                'value' => $formFieldClone->getValueAsText(),
                'type' => $formFieldClone->getType(),
                'label' => $formFieldClone->getLabel()
            ],
        ];

        $this->assertEquals($expect, $mockedController->_call('getOrderFormFieldsForSerialization', $orderConfiguration));
    }

    /**
     * @test
     */
    public function getOrderProductsQuantityForSerializationReturnArrayWithValidProductsQuantityData()
    {
        $mockedController = $this->getAccessibleMock(ProductController::class, ['dummy']);

        $orderProducts = [
            12 => 5,
            1 => 0,
            '0' => 12,
            33 => '0',
            0 => 0,
            1 => 1
        ];

        $expect = [
            12 => 5,
            1 => 1
        ];

        $this->assertEquals($expect, $mockedController->_call('getOrderProductsQuantityForSerialization', $orderProducts));
    }

    /**
     * @test
     */
    public function getProductAdditionalButtonsIsAlteringButtonUsingSignalSlots()
    {
        $expected = [
            [
                'name' => 'Sell me',
                'link' => 'http://example.sell.com',
                'classes' => '',
                'order' => 20
            ],
            [
                'name' => 'Buy me',
                'link' => 'http://example.com',
                'classes' => '',
                'order' => 120
            ]
        ];

        $signalSlotDispatcherMock = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);

        $buttons = [];
        $product = $this->getAccessibleMock(Product::class, ['dummy']);

        $signalSlotDispatcherMock->expects($this->once())->method('dispatch')->with(ProductController::class, 'BeforeProcessingAdditionalButtons', [
                $product,
                $buttons
            ])->will($this->returnCallback(function($class, $name, $params) use ($expected) {
                $params[1] = [
                    [
                        'name' => 'Sell me',
                        'link' => 'http://example.sell.com',
                        'classes' => [],
                        'order' => 20
                    ],
                    [
                        'name' => 'Buy me',
                        'link' => 'http://example.com',
                        'classes' => [],
                        'order' => 120
                    ]
                ];
            }));

        $subject = $this->getAccessibleMock(ProductController::class, null);
        $subject->_set('signalSlotDispatcher', $signalSlotDispatcherMock);

        $result = $subject->_call('getProductAdditionalButtons', $product, $buttons);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function getAdditionalButtonsSortsTheButtonsAccordingToOrderField()
    {
        $expected = [
            [
                'name' => 'Sell me',
                'link' => 'http://example.sell.com',
                'classes' => '',
                'order' => 20
            ],
            [
                'name' => 'Buy me',
                'link' => 'http://example.com',
                'classes' => '',
                'order' => 120
            ]
        ];

        $signalSlotDispatcherMock = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);

        $buttons = [];
        $product = $this->getAccessibleMock(Product::class, ['dummy']);

        $signalSlotDispatcherMock->expects($this->once())->method('dispatch')->with(ProductController::class, 'BeforeProcessingAdditionalButtons', [
                $product,
                $buttons
            ])->will($this->returnCallback(function($class, $name, $params) use ($expected) {
                $params[1] = [
                    [
                        'name' => 'Buy me',
                        'link' => 'http://example.com',
                        'classes' => [],
                        'order' => 120
                    ],
                    [
                        'name' => 'Sell me',
                        'link' => 'http://example.sell.com',
                        'classes' => [],
                        'order' => 20
                    ]
                ];
            }));

        $subject = $this->getAccessibleMock(ProductController::class, null);
        $subject->_set('signalSlotDispatcher', $signalSlotDispatcherMock);

        $result = $subject->_call('getProductAdditionalButtons', $product, $buttons);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function getAdditionalButtonsConvertsClassesListToAClassString()
    {
        $expected = [
            [
                'name' => 'Sell me',
                'link' => 'http://example.sell.com',
                'classes' => 'testclass dummy2',
                'order' => 20
            ],
            [
                'name' => 'Buy me',
                'link' => 'http://example.com',
                'classes' => 'testclass',
                'order' => 120
            ]
        ];

        $signalSlotDispatcherMock = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);

        $buttons = [];

        $product = $this->getAccessibleMock(Product::class, ['dummy']);

        $signalSlotDispatcherMock->expects($this->once())->method('dispatch')->with(ProductController::class, 'BeforeProcessingAdditionalButtons', [
                $product,
                $buttons
            ])->will($this->returnCallback(function($class, $name, $params) use ($expected) {
                $params[1] = [
                    [
                        'name' => 'Sell me',
                        'link' => 'http://example.sell.com',
                        'classes' => ['testclass', 'dummy2'],
                        'order' => 20
                    ],
                    [
                        'name' => 'Buy me',
                        'link' => 'http://example.com',
                        'classes' => ['testclass'],
                        'order' => 120
                    ]
                ];
            }));

        $subject = $this->getAccessibleMock(ProductController::class, null);
        $subject->_set('signalSlotDispatcher', $signalSlotDispatcherMock);

        $result = $subject->_call('getProductAdditionalButtons', $product, $buttons);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function getPreviewProductWithoutArgumentReturnNull()
    {
        $request = $this->createMock(Request::class);

        $subject = $this->getAccessibleMock(ProductController::class, null, [], '', false);
        $subject->_set('request', $request);

        $this->assertNull($subject->_call('getPreviewProduct'));
    }

    /**
     * @test
     */
    public function getPreviewProductWithPreviewArgumentAndHiddenDisabledLookForEnabledProduct()
    {
        $productUid = 3;
        $product = new Product();

        $request = $this->createMock(Request::class);
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())->method('findByUid')->with($productUid, true)->willReturn($product);

        $request->expects($this->once())->method('hasArgument')->with('product_preview')->willReturn(true);

        $request->expects($this->once())->method('getArgument')->with('product_preview')->willReturn($productUid);

        $subject = $this->getAccessibleMock(ProductController::class, ['allowHiddenRecords'], [], '', false);
        $subject->_set('request', $request);
        $subject->_set('productRepository', $productRepository);

        $subject->expects($this->never())->method('allowHiddenRecords');

        $this->assertSame($product, $subject->_call('getPreviewProduct'));
    }

    /**
     * @test
     */
    public function getPreviewProductWithPreviewArgumentAndHiddenEnabledLookForHiddenProductAndAllowHiddenRecords()
    {
        $productUid = 4;
        $product = new Product();

        $request = $this->createMock(Request::class);
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())->method('findByUid')->with($productUid, false)->willReturn($product);

        $request->expects($this->once())->method('hasArgument')->with('product_preview')->willReturn(true);

        $request->expects($this->once())->method('getArgument')->with('product_preview')->willReturn($productUid);

        $settings = [
            'showHiddenRecords' => 1
        ];
        $subject = $this->getAccessibleMock(ProductController::class, ['allowHiddenRecords'], [], '', false);
        $subject->_set('request', $request);
        $subject->_set('productRepository', $productRepository);
        $subject->_set('settings', $settings);

        $subject->expects($this->once())->method('allowHiddenRecords');

        $this->assertSame($product, $subject->_call('getPreviewProduct'));
    }
}
