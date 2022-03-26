<?php

namespace App\Utils;

use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Exports\DataTableExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Builder;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class RequestSearchQuery
{
    /**
     * @var \Request
     */
    private $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    /**
     * RequestSearchQuery constructor.
     *
     * @param Request $request
     * @param Builder $query
     * @param array   $searchables
     */
    public function __construct(Request $request, Builder $query, $searchables = [])
    {
        $this->request = $request;
        $this->query = $query;

        $this->initializeQuery($searchables);
//        $this->searchables = $searchables;
    }

    /**
     * @param array $searchables
     */
    public function initializeQuery($searchables = [])
    {
        if ($column = $this->request->get('column')) {
            $this->query->orderBy($column, $this->request->get('direction') ?? 'asc');
        }

        if ($search = $this->request->get('search')) {
            $this->query->where(function (Builder $query) use ($searchables, $search) {
                foreach ($searchables as $searchableColumn) {
                    $matches = [];
                    if (preg_match('/^(`[^`]*`)(?:\-(`[^`]*`))*$/', $searchableColumn, $matches)) {
                        array_shift($matches);
                        if(count($matches) == 1){
                            $searchableColumn = str_replace('`', '', $searchableColumn);
                            $query->orWhere($searchableColumn, 'LIKE', "%{$search}%");
                        } else {
                            $matches = array_map(function($item){
                                return str_replace('`', '', $item);
                            },$matches);

                            $query->orWhere(DB::raw('concat('.implode('," ",', $matches).')'), 'LIKE', "%{$search}%");
                        }
                    } elseif (preg_match('/\-/', $searchableColumn) && !preg_match('/\./', $searchableColumn)) {
//                    } elseif (preg_match('/\-/', $searchableColumn)) {
                        $columns = explode('-', $searchableColumn);

                        $query->orWhere(DB::raw('concat('.implode('," ",', $columns).')'), 'LIKE', "%{$search}%");
                    } elseif (preg_match('/\./', $searchableColumn)) {
                        [$relation,$column] = explode('.', $searchableColumn);

                        $query->orWhereHas($relation, function ($query) use ($column, $search) {
                            $columns = explode('-', $column);
                            if (\count($columns) > 1) {
                                $query->Where(DB::raw('concat('.implode('," ",', $columns).')'), 'LIKE', "%{$search}%");
                            } else {
                                $query->where($column, 'LIKE', "%{$search}%");
                            }
                        });
                    } else {
                        $query->orWhere($searchableColumn, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        if ($otherSearch = $this->request->get('otherSearch')) {
            $otherSearch = json_decode($otherSearch);

            $this->query->where(function (Builder $query) use ($otherSearch) {
                foreach ($otherSearch as $key => $value) {
                    if (! $value) {
                        continue;
                    }

                    if (preg_match('/\./', $key)) {
                        [$relation,$column] = explode('.', $key);

                        $query->orWhereHas($relation, static
                        function ($query) use ($column, $value) {
                            $query->where($column, $value);
                        });
                    } else {
                        $query->where($key, $value);
                    }
                }
            });
        }
    }

    /**
     * @param TransformerAbstract $transformer
     *
     * @return array
     */
    public function result(TransformerAbstract $transformer)
    {
        $paginator = $this->query->paginate($this->request->get('perPage'));

        return Fractal::create()->collection($paginator->items())->transformWith($transformer)
            ->serializeWith(new ArraySerializer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();
    }

    /**
     * @param $columns
     * @param $headings
     * @param $fileName
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     *
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($fileName, TransformerAbstract $transformer, $columns = [], $headings = [])
    {
        $currentDate = date('dmY-His');

        $data = Fractal::create()->collection($this->query->get())->transformWith($transformer)
            ->toArray();

        return Excel::download(
            new DataTableExport($headings, $this->query, $columns, $data, \count($data)),
            "$fileName-export-$currentDate.xlsx"
        );
    }
}
