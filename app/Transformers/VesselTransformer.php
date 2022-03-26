<?php
/**
 * Created by PhpStorm.
 * User: ahmadof
 * Date: 04/07/2018
 * Time: 9:07.
 */

namespace App\Transformers;

use App\Models\Vessel;
use League\Fractal\TransformerAbstract;

class VesselTransformer extends TransformerAbstract
{
    private $limited;

    /**
     * VesselTransformer constructor.
     *
     * @param $limited
     */
    public function __construct($limited = false)
    {
        $this->limited = $limited;
    }

    public function transform(Vessel $vessel)
    {
        if ($this->limited) {
            return [
                'id'                => $vessel->id,
                'name'              => $vessel->name,
            ];
        }

        $certificates = [];
        foreach ($vessel->documents as $certificate) {
            array_push($certificates, [
                'id'        => $certificate->id,
                'type'      => $certificate->document_type,
                'number'    => $certificate->document_no,
                'remarks'   => $certificate->remarks,
                'expiry'    => $certificate->document_expiry_date,
                'url'       => isset($vessel->certificates_paths[$certificate->document_type]) ? $vessel->certificates_paths[$certificate->document_type] : null,
                'file'      => null,
            ]);
        }

        foreach ($vessel->getMedia('vessel_attachments') as $media) {
            $port_clearance = [];
            $port_clearance['id'] = $media->id;
            $port_clearance['file'] = null;
            $port_clearance['departure_port'] = $media->getCustomProperty('departure_port');
            $port_clearance['departure_date'] = $media->getCustomProperty('departure_date');
            $port_clearance['url'] = config('app.url').$media->getUrl();
            array_push($port_clearances, $port_clearance);
        }

        $port_clearances = [];
        foreach ($vessel->getMedia('port_clearances') as $media) {
            $port_clearance = [];
            $port_clearance['id'] = $media->id;
            $port_clearance['file'] = null;
            $port_clearance['departure_port'] = $media->getCustomProperty('departure_port');
            $port_clearance['departure_date'] = $media->getCustomProperty('departure_date');
            $port_clearance['url'] = config('app.url').$media->getUrl();
            array_push($port_clearances, $port_clearance);
        }
        $certificates_summaries = [];
        foreach ($vessel->getMedia('certificates_summaries') as $media) {
            $certificates_summary = [];
            $certificates_summary['id'] = $media->id;
            $certificates_summary['file'] = null;
            $certificates_summary['quarter_date'] = $media->getCustomProperty('quarter_date');
            $certificates_summary['url'] = config('app.url').$media->getUrl();
            array_push($certificates_summaries, $certificates_summary);
        }

        return [
            'id'                            => $vessel->id,
            'name'                          => $vessel->name,
            'code'                          => $vessel->code,
            'imo_number'                    => $vessel->imo_number,
            'call_sign'                     => $vessel->call_sign,
            'official_number'               => $vessel->official_number,
            'port_of_registry'              => [
                'id'   => $vessel->port_of_registry->id,
                'name' => $vessel->port_of_registry->name,
            ],
            'pilot' => $vessel->pilot ? [
                'id'   => $vessel->pilot->id,
                'name' => $vessel->pilot->name,
            ] : [],
            'pilot_name'                       => $vessel->pilot ? $vessel->pilot->name : '',
            'port_of_registry_name'            => $vessel->port_of_registry->name,
            'flag'                             => $vessel->flag,
            'stamp_path'                       => $vessel->stamp_path,
            'vessel_specs_path'                => $vessel->vessel_specs_path,
            'created_at'                       => (string) $vessel->created_at,
            'updated_at'                       => (string) $vessel->updated_at,
            'certificates'                     => $certificates,
            'GA_Plans'                         => $vessel->ga_plans,
            'port_clearances'                  => $port_clearances,
            'certificates_summaries'           => $certificates_summaries,
            'can_edit'                         => $vessel->can_edit,
            'can_delete'                       => $vessel->can_delete,
        ];
    }
}
