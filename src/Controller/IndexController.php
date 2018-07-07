<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Repository\DistrictRepository;
use App\Service\Grid;
use App\Service\GridService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    
    /**
     * @var DistrictRepository
     */
    private $districtRepository;
    
    /**
     *
     * @var GridService
     */
    private $gridService;
    
    public function __construct(DistrictRepository $districtRepository, GridService $gridService) {
        $this->districtRepository = $districtRepository;
        $this->gridService = $gridService;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function index(Request $request)
    {
        $searchForm = $this->createForm(SearchForm::class);
        $searchForm->handleRequest($request);
        
        $queryBuilder = $this->districtRepository->findAllQb($request->get('col'), $request->get('search'));
        $sortParams = [
            'name' => 'a.name',
            'city' => 'b.name',
            'id' => 'a.id',
            'population' => 'a.population',
            'area' => 'a.area',
        ];
        $filterParams = [
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
}
