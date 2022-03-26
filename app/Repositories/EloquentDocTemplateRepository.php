<?php

namespace App\Repositories;

use Exception;
use App\Models\DocTemplate;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\DocTemplateRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentDocTemplateRepository.
 */
class EloquentDocTemplateRepository extends EloquentBaseRepository implements DocTemplateRepository
{
    /**
     * EloquentDocTemplateRepository constructor.
     *
     * @param DocTemplate $doc_template
     */
    public function __construct(DocTemplate $doc_template)
    {
        parent::__construct($doc_template);
    }

//    /**
//     * @param $name
//     *
//     * @return DocTemplate
//     */
//    public function find($name)
//    {
//        /** @var DocTemplate $doc_template */
//        return false;//$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\DocTemplate
     */
    public function store(array $input)
    {
        /** @var DocTemplate $doc_template */
        $doc_template = $this->make(array_except($input, []));

//        if ($this->find($doc_template->name)) {
//            throw new GeneralException(__('exceptions.backend.doc_templates.already_exist'));
//        }

        DB::transaction(function () use ( $doc_template, $input) {
            if (!$doc_template->save()) {
                throw new GeneralException(__('exceptions.backend.doc_templates.create'));
            }

            if (isset($input['template'])) {
                $doc_template->addMedia($input['template'])->toMediaCollection('template');
            }

            return true;
        });

        return $doc_template;
    }

    /**
     * @param DocTemplate $doc_template
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\DocTemplate
     */
    public function update(DocTemplate $doc_template, array $input)
    {
//        if (($existingDocTemplate = $this->find($doc_template->name))
//          && $existingDocTemplate->id !== $doc_template->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.doc_templates.already_exist'));
//        }

        DB::transaction(function () use ( $doc_template, $input) {
            if (!$doc_template->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.doc_templates.update'));
            }

            if (isset($input['template'])) {
                if ($doc_template->getMedia('template')->first()) {
                    $doc_template->deleteMedia($doc_template->getMedia('template')->first());
                }

                $doc_template->addMedia($input['template'])->toMediaCollection('template');
            }

            return true;
        });

        return $doc_template;
    }

    /**
     * @param DocTemplate $doc_template
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(DocTemplate $doc_template)
    {
        if (! $doc_template->delete()) {
            throw new GeneralException(__('exceptions.backend.doc_templates.delete'));
        }

        return true;
    }
}
