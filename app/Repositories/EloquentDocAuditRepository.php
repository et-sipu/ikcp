<?php

namespace App\Repositories;

use Exception;
use App\Models\DocAudit;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\DocAuditRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentDocAuditRepository.
 */
class EloquentDocAuditRepository extends EloquentBaseRepository implements DocAuditRepository
{
    /**
     * EloquentDocAuditRepository constructor.
     *
     * @param DocAudit $doc_audit
     */
    public function __construct(DocAudit $doc_audit)
    {
        parent::__construct($doc_audit);
    }

//    /**
//     * @param $name
//     *
//     * @return DocAudit
//     */
//    public function find($name)
//    {
//        /** @var DocAudit $doc_audit */
//        return false;//$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\DocAudit
     */
    public function store(array $input)
    {
        /** @var DocAudit $doc_audit */
        $doc_audit = $this->make(array_except($input, []));

//        if ($this->find($doc_audit->name)) {
//            throw new GeneralException(__('exceptions.backend.doc_audits.already_exist'));
//        }

        DB::transaction(function () use ( $doc_audit, $input) {
            if (!$doc_audit->save()) {
                throw new GeneralException(__('exceptions.backend.doc_audits.create'));
            }


            if (isset($input['audit_file'])) {
                $doc_audit->addMedia($input['audit_file'])->toMediaCollection('audit_file');
            }

            return true;
        });

        return $doc_audit;
    }

    /**
     * @param DocAudit $doc_audit
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\DocAudit
     */
    public function update(DocAudit $doc_audit, array $input)
    {
//        if (($existingDocAudit = $this->find($doc_audit->name))
//          && $existingDocAudit->id !== $doc_audit->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.doc_audits.already_exist'));
//        }

        DB::transaction(function () use ( $doc_audit, $input) {
            if (!$doc_audit->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.doc_audits.update'));
            }

            if (isset($input['audit_file'])) {
                if ($doc_audit->getMedia('audit_file')->first()) {
                    $doc_audit->deleteMedia($doc_audit->getMedia('audit_file')->first());
                }

                $doc_audit->addMedia($input['audit_file'])->toMediaCollection('audit_file');
            }

            return true;
        });

        return $doc_audit;
    }

    /**
     * @param DocAudit $doc_audit
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(DocAudit $doc_audit)
    {
        if (! $doc_audit->delete()) {
            throw new GeneralException(__('exceptions.backend.doc_audits.delete'));
        }

        return true;
    }
}
