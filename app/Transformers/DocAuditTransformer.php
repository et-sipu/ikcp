<?php
namespace App\Transformers;

use App\Models\DocAudit;
use League\Fractal\TransformerAbstract;

class DocAuditTransformer extends TransformerAbstract
{

    public function transform(DocAudit $doc_audit)
    {
        return [
            'id' => $doc_audit->id,
			'audit_date' => $doc_audit->audit_date,
			'nc' => $doc_audit->nc,
			'obs' => $doc_audit->obs,
            'audit_file_url' => $doc_audit->audit_file_url,
            'audit_file_name' => $doc_audit->audit_file_name,
            'can_edit' => $doc_audit->can_edit,
            'can_delete' => $doc_audit->can_delete,
        ];
    }
}