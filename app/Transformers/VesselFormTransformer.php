<?php
namespace App\Transformers;

use App\Models\VesselForm;
use League\Fractal\TransformerAbstract;

class VesselFormTransformer extends TransformerAbstract
{

    public function transform(VesselForm $vessel_form)
    {
        return [
            'id' => $vessel_form->id,
			'doc_template_id' => $vessel_form->doc_template_id,
			'vessel_id' => $vessel_form->vessel_id,
			'vessel_name' => $vessel_form->vessel_name,
			//'template_name' => $vessel_form->template_name,
			//'template_type' => $vessel_form->doc_template->template_type,
			//'doc_template' => ['id' => $vessel_form->doc_template_id, 'name' => '('.$vessel_form->doc_template->code.') '.$vessel_form->doc_template->title],
            'form_file_name' => $vessel_form->form_file_name,
            'form_url' => $vessel_form->form_url,
			'created_at' => $vessel_form->created_at->format('Y-m-d'),
            'can_edit' => $vessel_form->can_edit,
            'can_delete' => $vessel_form->can_delete,
        ];
    }
}