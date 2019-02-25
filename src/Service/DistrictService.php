<?php

namespace App\Service;

use App\Entity\District;
use App\Form\DistrictForm;
use App\Form\SearchForm;
use App\Repository\DistrictRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DistrictService {
    
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var GridService
     */
    private $gridService;
    
    /**
     * @var GridElasticaService
     */
    private $gridElasticaService;

    /**
     * @var DistrictRepository
     */
    private $districtRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var TransformedFinder
     */
    private $transformedFinder = null;

    public function __construct(
            FormFactoryInterface $formFactory,
            GridService $gridService,
            GridElasticaService $gridElasticaService,
            DistrictRepository $districtRepository,
            EntityManagerInterface $entityManager,
            SessionInterface $session,
            TransformedFinder $transformedFinder = null
    ) {
        $this->formFactory = $formFactory;
        $this->gridService = $gridService;
        $this->gridElasticaService = $gridElasticaService;
        $this->districtRepository = $districtRepository;
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->transformedFinder = $transformedFinder;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array {
        $searchForm = $this->formFactory->create(SearchForm::class);
        $searchForm->handleRequest($request);
        $queryBuilder = $this->districtRepository->findAllQb($request->get('col'), $request->get('search'));
        $sortParams = [
            'name' => 'a.name',
            'city' => 'b.name',
            'id' => 'a.id',
            'population' => 'a.population',
            'area' => 'a.area',
        ];
        $this->gridService->setQueryBuilder($queryBuilder);
        $this->gridService->setSortParams($sortParams);
        $this->gridService->setSortKeepParams(['col', 'search']);

        return [
            'searchForm' => $searchForm->createView(),
            'districts' => $this->gridService->getPaginate(),
            'sort' => $this->gridService->getSort(),
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function indexEs(Request $request): array {
        $searchForm = $this->formFactory->create(SearchForm::class);
        $searchForm->handleRequest($request);
        $queryBuilder = $this->districtRepository->findAllQb($request->get('col'), $request->get('search'));
        $column = $request->get('col');
        if (!empty($column)) {
            $columns = explode('.', $column);
            $column = array_pop($columns);
        }

        $queryEs = $this->districtRepository->findAllEs($column, $request->get('search'));

        $sortParams = [
            'name' => 'name',
            'city' => 'city',
            'id' => 'id',
            'population' => 'population',
            'area' => 'area',
        ];

        //@todo it shoudln't be necessary
        //$this->gridService->setQueryBuilder($queryBuilder);

        $this->gridElasticaService->setQueryEs($queryEs);
        $this->gridElasticaService->setSortParams($sortParams);
        $this->gridElasticaService->setSortKeepParams(['col', 'search']);
        
        return [
            'searchForm' => $searchForm->createView(),
            'districts' => $this->gridElasticaService->getPaginateEs(),
            'sort' => $this->gridElasticaService->getSort(),
        ];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request) {
        $district = new District();
        $form = $this->formFactory->create(DistrictForm::class, $district);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($district);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add('success', 'District has been added');

            return new RedirectResponse($request->get('returnUrl'));
        }

        return ['districtForm' => $form->createView()];
    }

    /**
     * @param Request $request
     * @param District $district
     * @return mixed
     */
    public function edit(Request $request, District $district) {
        $form = $this->formFactory->create(DistrictForm::class, $district);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($district);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add('success', 'District has been saved');

            return new RedirectResponse($request->get('returnUrl'));
        }

        return [
            'district' => $district,
            'districtForm' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param District $district
     * @return RedirectResponse
     */
    public function delete(Request $request, District $district): RedirectResponse {
        $name = $district->getName();
        $this->entityManager->remove($district);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'District ' . $name . ' has been removed');

        return new RedirectResponse($request->get('returnUrl'));
    }

}
