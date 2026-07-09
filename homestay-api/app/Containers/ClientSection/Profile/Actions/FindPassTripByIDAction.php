<?php

namespace App\Containers\ClientSection\Profile\Actions;

use App\Containers\ClientSection\Profile\Tasks\FindPassTripByIDTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class FindPassTripByIDAction extends ParentAction
{
    public function __construct(private readonly FindPassTripByIDTask $findPassTripByIDTask)
    {
    }

    public function run(Request $request)
    {
        return $this->findPassTripByIDTask->run($request->id);
    }
}
