<?php

namespace App\Repositories\Contracts;

use App\Models\DocTemplate;

/**
 * Interface DocTemplatelRepository.
 */
interface DocTemplateRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return DocTemplate
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param DocTemplate $doc_template
     * @param array       $input
     *
     * @return mixed
     */
    public function update(DocTemplate $doc_template, array $input);

    /**
     * @param DocTemplate $doc_template
     *
     * @return mixed
     */
    public function destroy(DocTemplate $doc_template);
}
