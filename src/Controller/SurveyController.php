<?php

namespace App\Controller;

use App\Service\Survey\Survey;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Twig\Environment;

#[Route('/survey', 'api.survey', stateless: true)]
class SurveyController
{
    public function __construct(
        private readonly Survey $surveyService,
        private readonly FormFactoryInterface $formFactory,
        private readonly Environment $renderer
    ) {}

    #[Route('/{id}', '.getById', methods: [Request::METHOD_GET])]
    public function getSurvey(string $id): Response
    {
        $form = $this->buildSurveyForm($id);

        return new Response($this->renderer->render(
            'base.html.twig',
            ['form' => $form->createView()]
        ), Response::HTTP_OK, ['Content-Type' => 'text/html']);
    }

    #[Route('/{id}', '.fillById', methods: [Request::METHOD_POST])]
    public function fillSurvey(string $id, Request $request): Response
    {
        $form = $this->buildSurveyForm($id);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return new Response($this->renderer->render(
            'base.html.twig',
            ['form' => $form->createView()]
        ), Response::HTTP_OK, ['Content-Type' => 'text/html']);
    }

    private function buildSurveyForm(string $surveyId): FormInterface
    {
        $survey = $this->surveyService->getSurvey($surveyId);

        $formBuilder = $this->formFactory->createBuilder();

        $formBuilder->add('answerId', HiddenType::class, ['data' => Uuid::v4()]);
        foreach ($survey->questions as $question) {
            $formBuilder->add($question->questionId, ChoiceType::class, [
                'label' => $question->questionTitle,
                'choices' => array_flip($question->variants),
                'required' => true,
                'expanded' => true,
                'multiple' => true
            ]);
        }
        $formBuilder->add('submit', SubmitType::class);
        return $formBuilder->getForm();
    }
}