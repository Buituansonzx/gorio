<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Tasks\ImportHostsTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportHostsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
final class ImportHostsAction extends ParentAction
{
    public function run(ImportHostsRequest $request)
    {
        return app(ImportHostsTask::class)->run($request->file('files'));
    }
}
