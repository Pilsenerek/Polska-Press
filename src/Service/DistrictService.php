<?php

namespace App\Service;

use App\Entity\District;
use App\Form\DistrictForm;
use App\Form\SearchForm;
use App\Repository\DistrictRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(
            FormFactoryInterface $formFactory,
            GridService $gridService,
            DistrictRepository $districtRepository,
            EntityManagerInterface $entityManager,
            SessionInterface $session
    ) {
        $this->formFactory = $formFactory;
        $this->gridService = $gridService;
        $this->districtRepository = $districtRepository;
        $this->entityManager = $entityManager;
        $this->session = $session;
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
            'controller_name' => 'IndexController',
            'searchForm' => $searchForm->createView(),
            'districts' => $this->gridService->getPaginate(),
            'sort' => $this->gridService->getSort(),
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
