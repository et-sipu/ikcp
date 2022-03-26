<?php

namespace App\Repositories\Contracts;

use App\Models\DocAudit;

/**
 * Interface DocAuditlRepository.
 */
interface DocAuditRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return DocAudit
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param DocAudit $doc_audit
     * @param array       $input
     *
     * @return mixed
     */
    public function update(DocAudit $doc_audit, array $input);

    /**
     * @param DocAudit $doc_audit
     *
     * @return mixed
     */
    public function destroy(DocAudit $doc_audit);
}
