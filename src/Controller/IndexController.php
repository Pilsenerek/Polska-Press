<?php

namespace App\Controller;

use App\Entity\District;
use App\Service\DistrictService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @var DistrictService
     */
    private $districtService;

    /**
     * @param DistrictService $districtService
     */
    public function __construct(DistrictService $districtService) {
        $this->districtService = $districtService;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function index(Request $request): array {

        return $this->districtService->index($request);
    }

    /**
     * @Route("/add")
     * @Template
     */
    public function add(Request $request) {

        return $this->districtService->add($request);
    }

    /**
     * @Route("/{districtId}/edit", requirements={"districtId": "\d+"})
     * @ParamConverter("district", options={"id" = "districtId"})
     * @Template
     */
    public function edit(Request $request, District $district) {

        return $this->districtService->edit($request, $district);
    }

    /**
     * @Route("/{districtId}/delete", requirements={"districtId": "\d+"})
     * @ParamConverter("district", options={"id" = "districtId"})
     */
    public function delete(Request $request, District $district) {

        return $this->districtService->delete($request, $district);
    }

}
