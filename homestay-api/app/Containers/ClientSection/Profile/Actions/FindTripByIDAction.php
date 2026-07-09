<?php

namespace App\Containers\ClientSection\Profile\Actions;

use App\Containers\ClientSection\Profile\Tasks\FindTripByIDTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class FindTripByIDAction extends ParentAction
{
    public function __construct(private readonly FindTripByIDTask $findTripByIDTask)
    {
    }

    public function run(Request $request)
    {
        return $this->findTripByIDTask->run($request->id);
    }
}
