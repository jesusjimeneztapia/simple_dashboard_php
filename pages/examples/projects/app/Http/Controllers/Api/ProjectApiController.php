<?php
namespace Projects\App\Http\Controllers\Api;

use Projects\App\Models\Project;
use Projects\Core\Response;

class ProjectApiController
{
  public function getAll() {
    $projects = Project::all();
    return Response::json(["projects" => $projects])->send();
  }

  public function store($body) {
    $project = new Project($body);
    $project->save();
    return Response::json(["project" => $project->getAttributes()])->send();
  }

  public function update($projectId, $body) {
    $project = Project::findById($projectId);
    $project->update($body);
    return Response::json(["project" => $project->getAttributes()])->send();
  }

  public function destroy($projectId) {
    $project = Project::findById($projectId);
    $project->delete();
    return Response::text("")->status(204)->send();
  }
}