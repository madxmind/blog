<?php

namespace App\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractHandler implements HandlerInterface
{
    private FormFactoryInterface $formFactory;
    protected FormInterface $form;

    /**
     * @return string
     */
    abstract protected function getFormType(): string;

    /**
     * @param  mixed $data
     * @return void
     */
    abstract protected function process($data): void;

    /**
     * @required
     * @param  FormFactoryInterface $formFactory
     * @return void
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request, $data, array $options = []): bool
    {
        $this->form = $this->formFactory->create($this->getFormType(), $data, $options)->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->process($data);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function createView(): FormView
    {
        return $this->form->createView();
    }
}
