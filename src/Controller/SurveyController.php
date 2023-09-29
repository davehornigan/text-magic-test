<?php

namespace App\Controller;

use App\Service\Survey\Survey;
use App\Service\Survey\SurveyProcessor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
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
    public function fillSurvey(string $id, Request $request, SurveyProcessor $surveyProcessor): Response
    {
        $form = $this->buildSurveyForm($id);

        $form->handleRequest($request);
        $responseContext = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $answerId = null;
            if (array_key_exists('answerId', $formData)) {
                $answerId = $formData['answerId'];
                unset($formData['answerId']);
            }
            $surveyProcessor->fillSurvey($id, $answerId, $formData);

            $result = $surveyProcessor->getResult($id, $answerId);

            $responseContext['results'] = $result;
        }

        if (!array_key_exists('results', $responseContext)) {
            $responseContext['form'] = $form->createView();
        }

        return new Response($this->renderer->render(
            'base.html.twig',
            $responseContext
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
                'multiple' => true,
                'constraints' => [new NotBlank()]
            ]);
        }
        $formBuilder->add('submit', SubmitType::class);
        return $formBuilder->getForm();
    }
}