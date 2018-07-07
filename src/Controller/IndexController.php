<?php

namespace App\Controller;

use App\Entity\District;
use App\Form\DistrictForm;
use App\Form\SearchForm;
use App\Repository\DistrictRepository;
use App\Service\Grid;
use App\Service\GridService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    public function index(Request $request) : array
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
    
     /**
     * @Route("/add")
     * @Template
     */
    public function add(Request $request)
    {
        $district = new District();
        $form = $this->createForm(DistrictForm::class, $district);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($district);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'District has been added');
            
            return $this->redirect($request->get('returnUrl'));
        }    
        
        return ['districtForm' => $form->createView()];
    }
    
     /**
     * @Route("/{districtId}/edit", requirements={"districtId": "\d+"})
     * @ParamConverter("district", options={"id" = "districtId"})
     * @Template
     */
    public function edit(Request $request, District $district)
    {
        $form = $this->createForm(DistrictForm::class, $district);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($district);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'District has been saved');
            
            return $this->redirect($request->get('returnUrl'));
        }    
        
        return [
            'district' => $district,
            'districtForm' => $form->createView(),
        ];
    }
    
     /**
     * @Route("/{districtId}/delete", requirements={"districtId": "\d+"})
     * @ParamConverter("district", options={"id" = "districtId"})
     */
    public function delete(Request $request, District $district)
    {
        $name = $district->getName();
        $this->getDoctrine()->getManager()->remove($district);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'District '.$name.' has been removed');
            
        return $this->redirect($request->get('returnUrl'));
    }
}
