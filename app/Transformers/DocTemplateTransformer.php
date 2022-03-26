<?php
namespace App\Transformers;

use App\Models\DocTemplate;
use League\Fractal\TransformerAbstract;

class DocTemplateTransformer extends TransformerAbstract
{

    public function transform(DocTemplate $doc_template)
    {
        return [
            'id' => $doc_template->id,
			'template_type' => $doc_template->template_type,
			'code' => $doc_template->code,
			'title' => $doc_template->title,
			'template_url' => $doc_template->template_url,
			'template_file_name' => $doc_template->template_file_name,
            'can_edit' => $doc_template->can_edit,
            'can_delete' => $doc_template->can_delete,
        ];
    }
}